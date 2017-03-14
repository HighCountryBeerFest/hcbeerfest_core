<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Brewery entities.
 *
 * @ingroup hcbeerfest_core
 */
interface BreweryInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Brewery name.
   *
   * @return string
   *   Name of the Brewery.
   */
  public function getName();

  /**
   * Sets the Brewery name.
   *
   * @param string $name
   *   The Brewery name.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BreweryInterface
   *   The called Brewery entity.
   */
  public function setName($name);

  /**
   * Gets the Brewery creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Brewery.
   */
  public function getCreatedTime();

  /**
   * Sets the Brewery creation timestamp.
   *
   * @param int $timestamp
   *   The Brewery creation timestamp.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BreweryInterface
   *   The called Brewery entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Brewery published status indicator.
   *
   * Unpublished Brewery are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Brewery is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Brewery.
   *
   * @param bool $published
   *   TRUE to set this Brewery to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BreweryInterface
   *   The called Brewery entity.
   */
  public function setPublished($published);

  /**
   * Gets the Brewery revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Brewery revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BreweryInterface
   *   The called Brewery entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Brewery revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Brewery revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BreweryInterface
   *   The called Brewery entity.
   */
  public function setRevisionUserId($uid);

}
