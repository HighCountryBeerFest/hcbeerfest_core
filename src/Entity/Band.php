<?php

namespace Drupal\hcbeerfest_core\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Defines the Band entity.
 *
 * @ingroup hcbeerfest_core
 *
 * @ContentEntityType(
 *   id = "band",
 *   label = @Translation("Band"),
 *   handlers = {
 *     "storage" = "Drupal\hcbeerfest_core\BandStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\hcbeerfest_core\BandListBuilder",
 *     "views_data" = "Drupal\hcbeerfest_core\Entity\BandViewsData",
 *     "translation" = "Drupal\hcbeerfest_core\BandTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\hcbeerfest_core\Form\BandForm",
 *       "add" = "Drupal\hcbeerfest_core\Form\BandForm",
 *       "edit" = "Drupal\hcbeerfest_core\Form\BandForm",
 *       "delete" = "Drupal\hcbeerfest_core\Form\BandDeleteForm",
 *     },
 *     "access" = "Drupal\hcbeerfest_core\BandAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\hcbeerfest_core\BandHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "band",
 *   data_table = "band_field_data",
 *   revision_table = "band_revision",
 *   revision_data_table = "band_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer band entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/band/{band}",
 *     "add-form" = "/admin/structure/band/add",
 *     "edit-form" = "/admin/structure/band/{band}/edit",
 *     "delete-form" = "/admin/structure/band/{band}/delete",
 *     "version-history" = "/admin/structure/band/{band}/revisions",
 *     "revision" = "/admin/structure/band/{band}/revisions/{band_revision}/view",
 *     "revision_revert" = "/admin/structure/band/{band}/revisions/{band_revision}/revert",
 *     "translation_revert" = "/admin/structure/band/{band}/revisions/{band_revision}/revert/{langcode}",
 *     "revision_delete" = "/admin/structure/band/{band}/revisions/{band_revision}/delete",
 *     "collection" = "/admin/structure/band",
 *   },
 *   field_ui_base_route = "band.settings"
 * )
 */
class Band extends RevisionableContentEntityBase implements BandInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the band owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionUser() {
    return $this->get('revision_uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionUserId($uid) {
    $this->set('revision_uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFestivals() {
    $festivals = [];
    foreach ($this->get('festival') as $festival) {
      $festivals[] = $festival->entity;
    }
    return $festivals;
  }

  /**
   * {@inheritdoc}
   */
  public function getWebsiteLink() {
    return Link::fromTextAndUrl(
      $this->get('name')->value,
      Url::fromUri($this->get('uri')->value));
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    // TODO: Make this return a formatted description
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Band entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', FALSE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Band entity.'))
      ->setRevisionable(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['festival'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Festival'))
      ->setDescription(t('The festival this band is performing at.'))
      ->setSetting('target_type', 'festival')
      ->setCardinality(-1)
      ->setTargetEntityTypeId('festival')
      ->setDisplayOptions('form', array(
        'type'     => 'entity_reference_autocomplete',
        'weight'   => 5,
        'settings' => array(
          'match_operator'    => 'CONTAINS',
          'size'              => '60',
          'autocomplete_type' => 'tags',
          'placeholder'       => '',
          ),
        ))
      ->setDisplayConfigurable('form', TRUE);

      $fields['uri'] = BaseFieldDefinition::create('uri')
        ->setLabel(t('Website'))
        ->setDescription(t('A website for this band, example: http://bandname.example.com/'))
        ->setDisplayOptions('view', array(
          'label' => 'hidden',
          'type' => 'uri_link',
          'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
          'type'   => 'uri',
          'weight' => 6
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

      $fields['description'] = BaseFieldDefinition::create('text_long')
        ->setLabel(t('Description'))
        ->setDescription(t('A description of this band.'))
        ->setDisplayOptions('view', array(
          'label' => 'hidden',
          'type' => 'text_default',
          'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
          'type'   => 'text_textarea',
          'weight' => 6
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('Upload an image for this band.'))
      ->setSettings(array(
        'file_directory' => 'images/bands',
        'alt_field_required' => FALSE,
        'file_extensions' => 'jpg jpeg png',
        'min_resolution' => '400x400',
        'max_resolution' => '1024x768',
      ))
      ->setDisplayOptions('form', array(
        'label' => 'hidden',
        'type' => 'image_image',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Band is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_timestamp'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Revision timestamp'))
      ->setDescription(t('The time that the current revision was created.'))
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Revision user ID'))
      ->setDescription(t('The user ID of the author of the current revision.'))
      ->setSetting('target_type', 'user')
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
