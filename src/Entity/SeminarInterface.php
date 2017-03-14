<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Seminar entities.
 *
 * @ingroup hcbeerfest_core
 */
interface SeminarInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Seminar name.
   *
   * @return string
   *   Name of the Seminar.
   */
  public function getName();

  /**
   * Sets the Seminar name.
   *
   * @param string $name
   *   The Seminar name.
   *
   * @return \Drupal\hcbeerfest_core\Entity\SeminarInterface
   *   The called Seminar entity.
   */
  public function setName($name);

  /**
   * Gets the Seminar creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Seminar.
   */
  public function getCreatedTime();

  /**
   * Sets the Seminar creation timestamp.
   *
   * @param int $timestamp
   *   The Seminar creation timestamp.
   *
   * @return \Drupal\hcbeerfest_core\Entity\SeminarInterface
   *   The called Seminar entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Seminar published status indicator.
   *
   * Unpublished Seminar are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Seminar is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Seminar.
   *
   * @param bool $published
   *   TRUE to set this Seminar to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hcbeerfest_core\Entity\SeminarInterface
   *   The called Seminar entity.
   */
  public function setPublished($published);

  /**
   * Gets the Seminar revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Seminar revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\hcbeerfest_core\Entity\SeminarInterface
   *   The called Seminar entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Seminar revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Seminar revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\hcbeerfest_core\Entity\SeminarInterface
   *   The called Seminar entity.
   */
  public function setRevisionUserId($uid);

}
