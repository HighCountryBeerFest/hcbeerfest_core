<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\hcbeerfest_core\Entity\SeminarInterface;

/**
 * Class SeminarController.
 *
 *  Returns responses for Seminar routes.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class SeminarController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Seminar  revision.
   *
   * @param int $seminar_revision
   *   The Seminar  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($seminar_revision) {
    $seminar = $this->entityManager()->getStorage('seminar')->loadRevision($seminar_revision);
    $view_builder = $this->entityManager()->getViewBuilder('seminar');

    return $view_builder->view($seminar);
  }

  /**
   * Page title callback for a Seminar  revision.
   *
   * @param int $seminar_revision
   *   The Seminar  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($seminar_revision) {
    $seminar = $this->entityManager()->getStorage('seminar')->loadRevision($seminar_revision);
    return $this->t('Revision of %title from %date', array('%title' => $seminar->label(), '%date' => format_date($seminar->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Seminar .
   *
   * @param \Drupal\hcbeerfest_core\Entity\SeminarInterface $seminar
   *   A Seminar  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SeminarInterface $seminar) {
    $account = $this->currentUser();
    $langcode = $seminar->language()->getId();
    $langname = $seminar->language()->getName();
    $languages = $seminar->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $seminar_storage = $this->entityManager()->getStorage('seminar');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $seminar->label()]) : $this->t('Revisions for %title', ['%title' => $seminar->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all seminar revisions") || $account->hasPermission('administer seminar entities')));
    $delete_permission = (($account->hasPermission("delete all seminar revisions") || $account->hasPermission('administer seminar entities')));

    $rows = array();

    $vids = $seminar_storage->revisionIds($seminar);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\hcbeerfest_core\SeminarInterface $revision */
      $revision = $seminar_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $seminar->getRevisionId()) {
          $link = $this->l($date, new Url('entity.seminar.revision', ['seminar' => $seminar->id(), 'seminar_revision' => $vid]));
        }
        else {
          $link = $seminar->link($date);
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
              Url::fromRoute('entity.seminar.translation_revert', ['seminar' => $seminar->id(), 'seminar_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.seminar.revision_revert', ['seminar' => $seminar->id(), 'seminar_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.seminar.revision_delete', ['seminar' => $seminar->id(), 'seminar_revision' => $vid]),
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

    $build['seminar_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
