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

    // Generates the ticket_info array.
    $ticket_info = array();
    foreach(TICKET_TYPES as $type) {
      $ticket_info[$type] = array(
        'on_sale' => \Drupal::state()->get('hcbeerfest_core_tickets_on_sale_' . $type) ?: 0,
        'link' => \Drupal::state()->get('hcbeerfest_core_tickets_link_' . $type) ?: 0,
        'price' => \Drupal::state()->get('hcbeerfest_core_tickets_price_' . $type) ?: 0,
      );
    }

    return array(
      '#theme' => 'hcbeerfest_home',
      '#release_party' => \Drupal::state()->get('hcbeerfest_core_tickets_release_party') ?: 0,
      '#week_of_message' => \Drupal::state()->get('hcbeerfest_core_tickets_release_party') ?: 0,
      '#tickets_on_sale' => \Drupal::state()->get('hcbeerfest_core_tickets_on_sale') ?: 0,
      '#ticket_info' => $ticket_info,
    );
  }
}
