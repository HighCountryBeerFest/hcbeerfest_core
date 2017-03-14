<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Festival entities.
 *
 * @ingroup hcbeerfest_core
 */
interface FestivalInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Festival name.
   *
   * @return string
   *   Name of the Festival.
   */
  public function getName();

  /**
   * Sets the Festival name.
   *
   * @param string $name
   *   The Festival name.
   *
   * @return \Drupal\hcbeerfest_core\Entity\FestivalInterface
   *   The called Festival entity.
   */
  public function setName($name);

  /**
   * Gets the Festival creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Festival.
   */
  public function getCreatedTime();

  /**
   * Sets the Festival creation timestamp.
   *
   * @param int $timestamp
   *   The Festival creation timestamp.
   *
   * @return \Drupal\hcbeerfest_core\Entity\FestivalInterface
   *   The called Festival entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Festival published status indicator.
   *
   * Unpublished Festival are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Festival is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Festival.
   *
   * @param bool $published
   *   TRUE to set this Festival to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hcbeerfest_core\Entity\FestivalInterface
   *   The called Festival entity.
   */
  public function setPublished($published);

  /**
   * Gets the Festival revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Festival revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\hcbeerfest_core\Entity\FestivalInterface
   *   The called Festival entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Festival revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Festival revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\hcbeerfest_core\Entity\FestivalInterface
   *   The called Festival entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Is the festival public?
   *
   * @return boolean
   *   If the festival is public.
   */
  public function isPublic();

}
