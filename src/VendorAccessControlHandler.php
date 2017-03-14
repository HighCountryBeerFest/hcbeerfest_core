<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Vendor entity.
 *
 * @see \Drupal\hcbeerfest_core\Entity\Vendor.
 */
class VendorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hcbeerfest_core\Entity\VendorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished vendor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published vendor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit vendor entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete vendor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add vendor entities');
  }

}
