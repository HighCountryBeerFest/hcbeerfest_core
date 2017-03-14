<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MusicPage.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class MusicPage extends ControllerBase {

  /**
   * Index.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }

}
