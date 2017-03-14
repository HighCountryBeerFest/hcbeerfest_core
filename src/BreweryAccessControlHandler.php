<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Brewery entity.
 *
 * @see \Drupal\hcbeerfest_core\Entity\Brewery.
 */
class BreweryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hcbeerfest_core\Entity\BreweryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished brewery entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published brewery entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit brewery entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete brewery entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add brewery entities');
  }

}
