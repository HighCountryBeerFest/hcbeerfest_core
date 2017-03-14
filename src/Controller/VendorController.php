<?php

namespace Drupal\hcbeerfest_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\hcbeerfest_core\Entity\VendorInterface;

/**
 * Class VendorController.
 *
 *  Returns responses for Vendor routes.
 *
 * @package Drupal\hcbeerfest_core\Controller
 */
class VendorController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Vendor  revision.
   *
   * @param int $vendor_revision
   *   The Vendor  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($vendor_revision) {
    $vendor = $this->entityManager()->getStorage('vendor')->loadRevision($vendor_revision);
    $view_builder = $this->entityManager()->getViewBuilder('vendor');

    return $view_builder->view($vendor);
  }

  /**
   * Page title callback for a Vendor  revision.
   *
   * @param int $vendor_revision
   *   The Vendor  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($vendor_revision) {
    $vendor = $this->entityManager()->getStorage('vendor')->loadRevision($vendor_revision);
    return $this->t('Revision of %title from %date', array('%title' => $vendor->label(), '%date' => format_date($vendor->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Vendor .
   *
   * @param \Drupal\hcbeerfest_core\Entity\VendorInterface $vendor
   *   A Vendor  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(VendorInterface $vendor) {
    $account = $this->currentUser();
    $langcode = $vendor->language()->getId();
    $langname = $vendor->language()->getName();
    $languages = $vendor->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $vendor_storage = $this->entityManager()->getStorage('vendor');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $vendor->label()]) : $this->t('Revisions for %title', ['%title' => $vendor->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all vendor revisions") || $account->hasPermission('administer vendor entities')));
    $delete_permission = (($account->hasPermission("delete all vendor revisions") || $account->hasPermission('administer vendor entities')));

    $rows = array();

    $vids = $vendor_storage->revisionIds($vendor);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\hcbeerfest_core\VendorInterface $revision */
      $revision = $vendor_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $vendor->getRevisionId()) {
          $link = $this->l($date, new Url('entity.vendor.revision', ['vendor' => $vendor->id(), 'vendor_revision' => $vid]));
        }
        else {
          $link = $vendor->link($date);
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
              Url::fromRoute('entity.vendor.translation_revert', ['vendor' => $vendor->id(), 'vendor_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.vendor.revision_revert', ['vendor' => $vendor->id(), 'vendor_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.vendor.revision_delete', ['vendor' => $vendor->id(), 'vendor_revision' => $vid]),
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

    $build['vendor_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
