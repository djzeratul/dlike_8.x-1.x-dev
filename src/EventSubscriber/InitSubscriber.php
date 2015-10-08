<?php /**
 * @file
 * Contains \Drupal\dlike\EventSubscriber\InitSubscriber.
 */

namespace Drupal\dlike\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 0]];
  }

  public function onEvent() {
    return TRUE;
  }

}
