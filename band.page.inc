<?php

/**
 * @file
 * Contains band.page.inc.
 *
 * Page callback for Band entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Band templates.
 *
 * Default template: band.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_band(array &$variables) {
  // Fetch Band Entity Object.
  $band = $variables['elements']['#band'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
