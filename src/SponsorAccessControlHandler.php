<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Sponsor entity.
 *
 * @see \Drupal\hcbeerfest_core\Entity\Sponsor.
 */
class SponsorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hcbeerfest_core\Entity\SponsorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished sponsor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published sponsor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit sponsor entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete sponsor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add sponsor entities');
  }

}
