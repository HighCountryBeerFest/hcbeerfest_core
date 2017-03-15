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

    $release_party = TRUE;

    return array(
      '#theme' => 'hcbeerfest_home',
      '#release_party' => $release_party,
    );
  }
}
