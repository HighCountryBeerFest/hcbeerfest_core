<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Band entity.
 *
 * @see \Drupal\hcbeerfest_core\Entity\Band.
 */
class BandAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hcbeerfest_core\Entity\BandInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished band entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published band entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit band entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete band entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add band entities');
  }

}
