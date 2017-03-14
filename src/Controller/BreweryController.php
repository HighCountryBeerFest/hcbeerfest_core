<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\hcbeerfest_core\Entity\BreweryInterface;

/**
 * Class BreweryController.
 *
 *  Returns responses for Brewery routes.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class BreweryController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Brewery  revision.
   *
   * @param int $brewery_revision
   *   The Brewery  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($brewery_revision) {
    $brewery = $this->entityManager()->getStorage('brewery')->loadRevision($brewery_revision);
    $view_builder = $this->entityManager()->getViewBuilder('brewery');

    return $view_builder->view($brewery);
  }

  /**
   * Page title callback for a Brewery  revision.
   *
   * @param int $brewery_revision
   *   The Brewery  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($brewery_revision) {
    $brewery = $this->entityManager()->getStorage('brewery')->loadRevision($brewery_revision);
    return $this->t('Revision of %title from %date', array('%title' => $brewery->label(), '%date' => format_date($brewery->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Brewery .
   *
   * @param \Drupal\hcbeerfest_core\Entity\BreweryInterface $brewery
   *   A Brewery  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BreweryInterface $brewery) {
    $account = $this->currentUser();
    $langcode = $brewery->language()->getId();
    $langname = $brewery->language()->getName();
    $languages = $brewery->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $brewery_storage = $this->entityManager()->getStorage('brewery');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $brewery->label()]) : $this->t('Revisions for %title', ['%title' => $brewery->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all brewery revisions") || $account->hasPermission('administer brewery entities')));
    $delete_permission = (($account->hasPermission("delete all brewery revisions") || $account->hasPermission('administer brewery entities')));

    $rows = array();

    $vids = $brewery_storage->revisionIds($brewery);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\hcbeerfest_core\BreweryInterface $revision */
      $revision = $brewery_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $brewery->getRevisionId()) {
          $link = $this->l($date, new Url('entity.brewery.revision', ['brewery' => $brewery->id(), 'brewery_revision' => $vid]));
        }
        else {
          $link = $brewery->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.brewery.translation_revert', ['brewery' => $brewery->id(), 'brewery_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.brewery.revision_revert', ['brewery' => $brewery->id(), 'brewery_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.brewery.revision_delete', ['brewery' => $brewery->id(), 'brewery_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['brewery_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
