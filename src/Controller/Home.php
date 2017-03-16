<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Home.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class Home extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {

    return array(
      '#theme' => 'hcbeerfest_home',
      '#release_party' => \Drupal::state()->get('hcbeerfest_core_tickets_release_party') ?: 0,
    );
  }
}
