<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\hcbeerfest_core\Entity\FestivalInterface;

/**
 * Defines the storage handler class for Festival entities.
 *
 * This extends the base storage class, adding required special handling for
 * Festival entities.
 *
 * @ingroup hcbeerfest_core
 */
class FestivalStorage extends SqlContentEntityStorage implements FestivalStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(FestivalInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {festival_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {festival_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(FestivalInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {festival_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('festival_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
