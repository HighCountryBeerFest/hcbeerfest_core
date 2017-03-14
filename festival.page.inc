<?php

/**
 * @file
 * Contains festival.page.inc.
 *
 * Page callback for Festival entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Festival templates.
 *
 * Default template: festival.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_festival(array &$variables) {
  // Fetch Festival Entity Object.
  $festival = $variables['elements']['#festival'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
