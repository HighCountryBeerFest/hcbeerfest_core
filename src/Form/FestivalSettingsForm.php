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
    // Sets the current year
    \Drupal::state()->set('hcbeerfest_core_festival', $form_state->getValue('active_festival'));

    // Sets the 'Week of' message
    \Drupal::state()->set('hcbeerfest_core_week_of_message', $form_state->getValue('week_of_message'));

    // Handles the registration types
    foreach (REGISTRATION_TYPES as $type) {
      \Drupal::state()->set('hcbeerfest_core_registration_' . $type, $form_state->getValue('registration_' . $type));
    }

    // Sets ticket release party message
    \Drupal::state()->set('hcbeerfest_core_tickets_release_party', $form_state->getValue('ticket_release_party_message'));

    // Sets tickets on sale.
    \Drupal::state()->set('hcbeerfest_core_tickets_on_sale', $form_state->getValue('tickets_on_sale'));

    foreach (TICKET_TYPES as $type) {
      foreach (TICKET_OPTIONS as $option) {
        \Drupal::state()->set('hcbeerfest_core_' . $option . $type, $form_state->getValue($option . $type));
      }
    }

    drupal_set_message($this->t('Updated festival configuration'));
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

    $form['core']['week_of_message'] = array(
      '#type' => 'select',
      '#title' => $this->t('Dispaly the "week of" message?'),
      '#options' => array(
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ),
      '#default_value' => \Drupal::state()->get('hcbeerfest_core_week_of_message') ?: 0,
    );

    $form['registration'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Registration settings'),
    );

    foreach (REGISTRATION_TYPES as $type) {
      $form['registration']['registration_' . $type] = array(
        '#type' => 'select',
        '#title' => $this->t('Is @type registration active?', array('@type' => $type)),
        '#options' => array(
          0 => $this->t('No'),
          1 => $this->t('Yes'),
        ),
        '#default_value' => \Drupal::state()->get('hcbeerfest_core_registration_' . $type) ?: 0,
      );
    }

    $form['tickets'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Tickets settings'),
    );

    $form['tickets']['ticket_release_party_message'] = array(
      '#type' => 'select',
      '#title' => $this->t('Should the ticket release party message show up?'),
      '#options' => array(
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ),
      '#default_value' => \Drupal::state()->get('hcbeerfest_core_tickets_release_party') ?: 0,
    );

    $form['tickets']['tickets_on_sale'] = array(
      '#type' => 'select',
      '#title' => $this->t('Are tickets on sale?'),
      '#options' => array(
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ),
      '#default_value' => \Drupal::state()->get('hcbeerfest_core_tickets_on_sale') ?: 0,
    );

    foreach (TICKET_TYPES as $type) {
      $form['tickets'][$type] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('@type tickets', array('@type' => $type)),
      );

      $form['tickets'][$type]['tickets_on_sale_' . $type] = array(
        '#type' => 'select',
        '#title' => $this->t('Are @type tickets on sale?', array('@type' => $type)),
        '#options' => array(
          0 => $this->t('No'),
          1 => $this->t('Yes'),
        ),
        '#default_value' => \Drupal::state()->get('hcbeerfest_core_tickets_on_sale_' . $type) ?: 0,
      );

      $form['tickets'][$type]['tickets_link_' . $type] = array(
        '#type' => 'textfield',
        '#title' => $this->t('@type ticket link', array('@type' => $type)),
        '#default_value' => \Drupal::state()->get('hcbeerfest_core_tickets_link_' . $type),
      );

      $form['tickets'][$type]['tickets_price_' . $type] = array(
        '#type' => 'textfield',
        '#title' => $this->t('@type ticket price', array('@type' => $type)),
        '#default_value' => \Drupal::state()->get('hcbeerfest_core_tickets_price_' . $type),
      );

    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
  }

}
