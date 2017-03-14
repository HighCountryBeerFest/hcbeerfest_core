<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\hcbeerfest_core\Entity\VendorInterface;

/**
 * Defines the storage handler class for Vendor entities.
 *
 * This extends the base storage class, adding required special handling for
 * Vendor entities.
 *
 * @ingroup hcbeerfest_core
 */
class VendorStorage extends SqlContentEntityStorage implements VendorStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(VendorInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {vendor_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {vendor_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(VendorInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {vendor_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('vendor_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
