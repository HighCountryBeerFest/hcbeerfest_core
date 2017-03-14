<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\hcbeerfest_core\Entity\FestivalInterface;

/**
 * Class FestivalController.
 *
 *  Returns responses for Festival routes.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class FestivalController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Festival  revision.
   *
   * @param int $festival_revision
   *   The Festival  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($festival_revision) {
    $festival = $this->entityManager()->getStorage('festival')->loadRevision($festival_revision);
    $view_builder = $this->entityManager()->getViewBuilder('festival');

    return $view_builder->view($festival);
  }

  /**
   * Page title callback for a Festival  revision.
   *
   * @param int $festival_revision
   *   The Festival  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($festival_revision) {
    $festival = $this->entityManager()->getStorage('festival')->loadRevision($festival_revision);
    return $this->t('Revision of %title from %date', array('%title' => $festival->label(), '%date' => format_date($festival->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Festival .
   *
   * @param \Drupal\hcbeerfest_core\Entity\FestivalInterface $festival
   *   A Festival  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FestivalInterface $festival) {
    $account = $this->currentUser();
    $langcode = $festival->language()->getId();
    $langname = $festival->language()->getName();
    $languages = $festival->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $festival_storage = $this->entityManager()->getStorage('festival');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $festival->label()]) : $this->t('Revisions for %title', ['%title' => $festival->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all festival revisions") || $account->hasPermission('administer festival entities')));
    $delete_permission = (($account->hasPermission("delete all festival revisions") || $account->hasPermission('administer festival entities')));

    $rows = array();

    $vids = $festival_storage->revisionIds($festival);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\hcbeerfest_core\FestivalInterface $revision */
      $revision = $festival_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $festival->getRevisionId()) {
          $link = $this->l($date, new Url('entity.festival.revision', ['festival' => $festival->id(), 'festival_revision' => $vid]));
        }
        else {
          $link = $festival->link($date);
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
              Url::fromRoute('entity.festival.translation_revert', ['festival' => $festival->id(), 'festival_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.festival.revision_revert', ['festival' => $festival->id(), 'festival_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.festival.revision_delete', ['festival' => $festival->id(), 'festival_revision' => $vid]),
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

    $build['festival_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
