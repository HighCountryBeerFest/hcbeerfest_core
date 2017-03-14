<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class Breweries.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class Breweries extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return array(
      '#theme' => 'hcbeerfest_breweries',
      '#breweries' => array('foo', 'bar'),
    );
  }
}
