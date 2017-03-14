<?php

/**
 * @file
 * Contains seminar.page.inc.
 *
 * Page callback for Seminar entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Seminar templates.
 *
 * Default template: seminar.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seminar(array &$variables) {
  // Fetch Seminar Entity Object.
  $seminar = $variables['elements']['#seminar'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
