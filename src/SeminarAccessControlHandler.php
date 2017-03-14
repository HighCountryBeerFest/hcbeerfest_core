<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Seminar entity.
 *
 * @see \Drupal\hcbeerfest_core\Entity\Seminar.
 */
class SeminarAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hcbeerfest_core\Entity\SeminarInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished seminar entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published seminar entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit seminar entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete seminar entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add seminar entities');
  }

}
