<?php

namespace Drupal\userpoints\Event;

use Drupal\userpoints\Entity\UserPointsInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Defines the User Points service.
 */
class UserPointsEvent  implements Event {

  public UserPointsInterface $points;
  public int $quantity;
  public string $log;

  /**
   * Constructs a new UserPointsEvent.
   *
   * @param \Drupal\userpoints\Entity\UserPointsInterface $points
   *   The UserPoints entity.
   * @param int $quantity
   *   The quantity of points added or transferred.
   * @param string $log
   *   The log message associated with the points change.
   */
  public function __construct(UserPointsInterface $points, int &$quantity, string &$log) {
    $this->points = $points;
    $this->quantity = &$quantity;
    $this->log = &$log;
  }

}
