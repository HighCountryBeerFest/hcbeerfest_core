<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface BandStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Band revision IDs for a specific Band.
   *
   * @param \Drupal\hcbeerfest_core\Entity\BandInterface $entity
   *   The Band entity.
   *
   * @return int[]
   *   Band revision IDs (in ascending order).
   */
  public function revisionIds(BandInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Band author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Band revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\hcbeerfest_core\Entity\BandInterface $entity
   *   The Band entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BandInterface $entity);

  /**
   * Unsets the language for all Band with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
