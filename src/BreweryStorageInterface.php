<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\hcbeerfest_core\Entity\BreweryInterface;

/**
 * Defines the storage handler class for Brewery entities.
 *
 * This extends the base storage class, adding required special handling for
 * Brewery entities.
 *
 * @ingroup hcbeerfest_core
 */
interface BreweryStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Brewery revision IDs for a specific Brewery.
   *
   * @param \Drupal\hcbeerfest_core\Entity\BreweryInterface $entity
   *   The Brewery entity.
   *
   * @return int[]
   *   Brewery revision IDs (in ascending order).
   */
  public function revisionIds(BreweryInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Brewery author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Brewery revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\hcbeerfest_core\Entity\BreweryInterface $entity
   *   The Brewery entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BreweryInterface $entity);

  /**
   * Unsets the language for all Brewery with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
