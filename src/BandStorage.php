<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\hcbeerfest_core\Entity\BandInterface;

/**
 * Defines the storage handler class for Band entities.
 *
 * This extends the base storage class, adding required special handling for
 * Band entities.
 *
 * @ingroup hcbeerfest_core
 */
class BandStorage extends SqlContentEntityStorage implements BandStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BandInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {band_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {band_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BandInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {band_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('band_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
