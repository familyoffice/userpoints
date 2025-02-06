<?php

namespace Drupal\userpoints\Event;

use Drupal\userpoints\Entity\UserPointsInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Defines the User Points service.
 */
class UserPointsEvent extends Event {

  public UserPointsInterface $points;
  public int $quantity;
  public string $log;

  
}
