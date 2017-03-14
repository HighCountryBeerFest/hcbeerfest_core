<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\hcbeerfest_core\Entity\SeminarInterface;

/**
 * Defines the storage handler class for Seminar entities.
 *
 * This extends the base storage class, adding required special handling for
 * Seminar entities.
 *
 * @ingroup hcbeerfest_core
 */
interface SeminarStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Seminar revision IDs for a specific Seminar.
   *
   * @param \Drupal\hcbeerfest_core\Entity\SeminarInterface $entity
   *   The Seminar entity.
   *
   * @return int[]
   *   Seminar revision IDs (in ascending order).
   */
  public function revisionIds(SeminarInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Seminar author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Seminar revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\hcbeerfest_core\Entity\SeminarInterface $entity
   *   The Seminar entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SeminarInterface $entity);

  /**
   * Unsets the language for all Seminar with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
