<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\hcbeerfest_core\Entity\BandInterface;

/**
 * Class BandController.
 *
 *  Returns responses for Band routes.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class BandController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Band  revision.
   *
   * @param int $band_revision
   *   The Band  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($band_revision) {
    $band = $this->entityManager()->getStorage('band')->loadRevision($band_revision);
    $view_builder = $this->entityManager()->getViewBuilder('band');

    return $view_builder->view($band);
  }

  /**
   * Page title callback for a Band  revision.
   *
   * @param int $band_revision
   *   The Band  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($band_revision) {
    $band = $this->entityManager()->getStorage('band')->loadRevision($band_revision);
    return $this->t('Revision of %title from %date', array('%title' => $band->label(), '%date' => format_date($band->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Band .
   *
   * @param \Drupal\hcbeerfest_core\Entity\BandInterface $band
   *   A Band  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BandInterface $band) {
    $account = $this->currentUser();
    $langcode = $band->language()->getId();
    $langname = $band->language()->getName();
    $languages = $band->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $band_storage = $this->entityManager()->getStorage('band');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $band->label()]) : $this->t('Revisions for %title', ['%title' => $band->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all band revisions") || $account->hasPermission('administer band entities')));
    $delete_permission = (($account->hasPermission("delete all band revisions") || $account->hasPermission('administer band entities')));

    $rows = array();

    $vids = $band_storage->revisionIds($band);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\hcbeerfest_core\BandInterface $revision */
      $revision = $band_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $band->getRevisionId()) {
          $link = $this->l($date, new Url('entity.band.revision', ['band' => $band->id(), 'band_revision' => $vid]));
        }
        else {
          $link = $band->link($date);
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
              Url::fromRoute('entity.band.translation_revert', ['band' => $band->id(), 'band_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.band.revision_revert', ['band' => $band->id(), 'band_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.band.revision_delete', ['band' => $band->id(), 'band_revision' => $vid]),
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

    $build['band_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
