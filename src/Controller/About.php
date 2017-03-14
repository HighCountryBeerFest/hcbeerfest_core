<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class About.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class About extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return array(
      '#theme' => 'hcbeerfest_about',
    );
  }

}
