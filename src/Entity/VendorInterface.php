<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Vendor entities.
 *
 * @ingroup hcbeerfest_core
 */
interface VendorInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Vendor name.
   *
   * @return string
   *   Name of the Vendor.
   */
  public function getName();

  /**
   * Sets the Vendor name.
   *
   * @param string $name
   *   The Vendor name.
   *
   * @return \Drupal\hcbeerfest_core\Entity\VendorInterface
   *   The called Vendor entity.
   */
  public function setName($name);

  /**
   * Gets the Vendor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Vendor.
   */
  public function getCreatedTime();

  /**
   * Sets the Vendor creation timestamp.
   *
   * @param int $timestamp
   *   The Vendor creation timestamp.
   *
   * @return \Drupal\hcbeerfest_core\Entity\VendorInterface
   *   The called Vendor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Vendor published status indicator.
   *
   * Unpublished Vendor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Vendor is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Vendor.
   *
   * @param bool $published
   *   TRUE to set this Vendor to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hcbeerfest_core\Entity\VendorInterface
   *   The called Vendor entity.
   */
  public function setPublished($published);

  /**
   * Gets the Vendor revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Vendor revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\hcbeerfest_core\Entity\VendorInterface
   *   The called Vendor entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Vendor revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Vendor revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\hcbeerfest_core\Entity\VendorInterface
   *   The called Vendor entity.
   */
  public function setRevisionUserId($uid);

}
