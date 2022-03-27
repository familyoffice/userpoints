<?php

namespace Drupal\Tests\userpoints\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * @coversDefaultClass \Drupal\userpoints\Service\UserPointsService
 * @group userpoints
 */
class UserpointsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable';

  /**
   * Test users.
   *
   * @var AccountInterface[]
   */
  private $users;

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = [
    'user',
    'node',
    'userpoints',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Tests the VBO bulk form with simple test action.
   */
  public function testUserpointsBasicOperations() {
    $assertSession = $this->assertSession();

    // Settings first.
    $admin_user = $this->drupalCreateUser(['administer userpoints']);
    $this->drupalLogin($admin_user);
    $this->drupalGet('admin/structure/userpoints/types');
    $assertSession->pageTextContains('There are no user points type entities yet.');
    $assertSession->statusCodeEquals(200);

    $this->drupalGet('admin/structure/userpoints/types/add');
    $assertSession->statusCodeEquals(200);

    $this->submitForm([
      'label' => 'Default',
      // We need this as there's no js running in this test.
      'id' => 'default',
      'initial_value' => 1,
    ], 'Save');
    $assertSession->pageTextContains('Created the Default User points type.');

    $this->drupalGet('admin/structure/userpoints/settings');
    $this->submitForm([
      'userpoints_ui_bundles[user][user]' => TRUE,
    ], 'Save configuration');
    $assertSession->pageTextContains('The configuration options have been saved.');

    // Now let's try point manager actions.
    $standard_user = $this->drupalCreateUser(['view own default points']);
    $manager_user = $this->drupalCreateUser(['manage default points']);
    $this->drupalLogin($manager_user);
    $this->drupalGet('user/' . $standard_user->id() . '/userpoints');
    $assertSession->elementExists('css', 'input[name="quantity"]');
    $assertSession->elementExists('css', 'input[name="log"]');
    $assertSession->elementExists('css', 'input[type="submit"]');

    // This is basically all we can check using UI here as the userpoints form
    // is an AJAX form.

    $this->drupalLogin($standard_user);
    $this->drupalGet('user/' . $standard_user->id() . '/userpoints');
    $assertSession->elementNotExists('css', 'input[name="quantity"]');
    $assertSession->elementNotExists('css', 'input[name="log"]');
    $assertSession->elementNotExists('css', 'input[type="submit"]');
    $assertSession->elementExists('css', 'table');
    $assertSession->elementTextContains('css', 'table th:nth-child(1)', 'Quantity');
    $assertSession->elementTextContains('css', 'table th:nth-child(2)', 'Log');
    $assertSession->elementTextContains('css', 'table th:nth-child(3)', 'Changed by user ID');
    $assertSession->elementTextContains('css', 'table th:nth-child(4)', 'Created');

    $assertSession->elementTextContains('css', 'table tbody tr:nth-child(1) td:nth-child(1)', '1');
    $assertSession->elementTextContains('css', 'table tbody tr:nth-child(1) td:nth-child(2)', 'Initial value.');
  }

}
