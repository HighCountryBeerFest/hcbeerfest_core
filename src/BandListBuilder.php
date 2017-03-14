<?php

namespace Drupal\hcbeerfest_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Band entities.
 *
 * @ingroup hcbeerfest_core
 */
class BandListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['year'] = $this->t('Year(s)');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\hcbeerfest_core\Entity\Band */
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.band.edit_form', array(
          'band' => $entity->id(),
        )
      )
    );
    $years = [];
    foreach ($entity->getFestivals() as $festival) {
      $years[] = $festival->getName();
    }
    $row['year'] = implode(', ', $years);
    return $row + parent::buildRow($entity);
  }

}
