<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Band entities.
 *
 * @ingroup hcbeerfest_core
 */
interface BandInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Band name.
   *
   * @return string
   *   Name of the Band.
   */
  public function getName();

  /**
   * Sets the Band name.
   *
   * @param string $name
   *   The Band name.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BandInterface
   *   The called Band entity.
   */
  public function setName($name);

  /**
   * Gets the Band creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Band.
   */
  public function getCreatedTime();

  /**
   * Sets the Band creation timestamp.
   *
   * @param int $timestamp
   *   The Band creation timestamp.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BandInterface
   *   The called Band entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Band published status indicator.
   *
   * Unpublished Band are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Band is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Band.
   *
   * @param bool $published
   *   TRUE to set this Band to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BandInterface
   *   The called Band entity.
   */
  public function setPublished($published);

  /**
   * Gets the Band revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Band revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BandInterface
   *   The called Band entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Band revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Band revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\hcbeerfest_core\Entity\BandInterface
   *   The called Band entity.
   */
  public function setRevisionUserId($uid);

}
