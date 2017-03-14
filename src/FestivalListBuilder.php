<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Festival entities.
 *
 * @ingroup hcbeerfest_core
 */
class FestivalListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['is_public'] = $this->t('Is public?');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\hcbeerfest_core\Entity\Festival */
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.festival.edit_form', array(
          'festival' => $entity->id(),
        )
      )
    );
    $row['is_public'] = ($entity->isPublic()) ? 'Yes': 'No';
    return $row + parent::buildRow($entity);
  }

}
