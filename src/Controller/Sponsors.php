<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Sponsors.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class Sponsors extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return array(
      '#theme' => 'hcbeerfest_sponsors',
    );
  }

}
