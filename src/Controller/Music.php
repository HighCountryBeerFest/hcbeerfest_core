<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MusicPage.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class Music extends ControllerBase {

  /**
   * Index.
   *
   * @return array
   *   Return a render array of the page content.
   */
  public function index() {
    $bands = array();

    $bands_query = \Drupal::entityQuery('band');
    $bands_result = $bands_query->execute();
    $entity_storage = \Drupal::entityManager()->getStorage('band');

    foreach ($bands_result as $band) {
      $bands[] = array(
        'title' => $entity_storage->load($band)->getWebsiteLink(),
        'description' => $entity_storage->load($band)->getDescription(),
      );
    }

    return [
      '#theme' => 'hcbeerfest_music',
      '#bands' => $bands,
    ];
  }
}
