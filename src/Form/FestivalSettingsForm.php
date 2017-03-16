<?php

namespace Drupal\hcbeerfest_core\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FestivalSettingsForm.
 *
 * @package Drupal\hcbeerfest_core\Form
 *
 * @ingroup hcbeerfest_core
 */
class FestivalSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'Festival_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($festival = $form_state->getValue('active_festival')) {
      \Drupal::state()->set('hcbeerfest_core_festival', $festival);
    }

    if ($registration_brewery = $form_state->getValue('registration_brewery')) {
      \Drupal::state()->set('hcbeerfest_core_registration_brewery', $registration_brewery);
    }
  }

  /**
   * Defines the settings form for Festival entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $festival_query = \Drupal::entityQuery('festival');
    $festival_result = $festival_query->execute();
    $entity_storage = \Drupal::entityManager()->getStorage('festival');

    $festivals = array(0 => 'None');
    foreach ($festival_result as $festival) {
      $entity = $entity_storage->load($festival);
      $festivals[$entity->id()] = $entity->getSettingsPageOption();
    }

    $form['core'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Core settings'),
    );

    $form['core']['active_festival'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select the active year'),
      '#options' => $festivals,
      '#default_value' => \Drupal::state()->get('hcbeerfest_core_festival') ?: 0,
    );

    $form['core']['registration'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Registration settings'),
    );

    $form['core']['registration']['registration_brewery'] = array(
      '#type' => 'select',
      '#title' => $this->t('Is brewery registration active?'),
      '#options' => array(
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ),
      '#default_value' => \Drupal::state()->get('hcbeerfest_core_registration_brewery') ?: 0,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
  }

}
