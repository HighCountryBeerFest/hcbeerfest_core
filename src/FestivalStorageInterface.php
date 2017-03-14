<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface FestivalStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Festival revision IDs for a specific Festival.
   *
   * @param \Drupal\hcbeerfest_core\Entity\FestivalInterface $entity
   *   The Festival entity.
   *
   * @return int[]
   *   Festival revision IDs (in ascending order).
   */
  public function revisionIds(FestivalInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Festival author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Festival revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\hcbeerfest_core\Entity\FestivalInterface $entity
   *   The Festival entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FestivalInterface $entity);

  /**
   * Unsets the language for all Festival with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
