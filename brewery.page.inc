<?php

/**
 * @file
 * Contains brewery.page.inc.
 *
 * Page callback for Brewery entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Brewery templates.
 *
 * Default template: brewery.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_brewery(array &$variables) {
  // Fetch Brewery Entity Object.
  $brewery = $variables['elements']['#brewery'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
