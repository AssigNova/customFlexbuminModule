<?php

namespace Drupal\custom_portal\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PincodeMappingForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'custom_portal_pincode_mapping_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['custom_portal.pincode_mapping'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('custom_portal.pincode_mapping');
    $pincode_mappings = $config->get('pincode_mappings') ?: [];

    // Create a container for the table of existing mappings.
    $form['pincode_mappings_table'] = [
      '#type' => 'details',
      '#title' => $this->t('Existing Pincode Mappings'),
      '#open' => true,
    ];

    // Build a table to display the existing mappings.
    $form['pincode_mappings_table']['table'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Pincode'),
        $this->t('Email'),
        $this->t('Operations'), // New column for the delete button
      ],
      '#rows' => [],
    ];
    foreach ($pincode_mappings as $pincode => $email) {
      $form['pincode_mappings_table']['table']['#rows'][] = [
        $pincode,
        $email,
        [
          '#type' => 'submit',
          '#value' => $this->t('Delete'),
          '#name' => 'delete_' . $pincode,
          '#submit' => ['::deleteMappingSubmit'], // New submit handler
        ],
      ];
    }

    // Create fields for adding a new mapping.
    $form['pincode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pincode'),
      '#description' => $this->t('Enter the pincode to map.'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter the email address for this pincode.'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->config('custom_portal.pincode_mapping');
    $pincode_mappings = $config->get('pincode_mappings') ?: [];

    $pincode = $form_state->getValue('pincode');
    $email = $form_state->getValue('email');

    $pincode_mappings[$pincode] = $email;

    $config->set('pincode_mappings', $pincode_mappings)->save();

    $this->messenger()->addMessage($this->t('Pincode mapping added successfully.'));
  }

  /**
   * Custom submit handler for deleting a mapping.
   */
  public function deleteMappingSubmit(array &$form, FormStateInterface $form_state)
  {
    // Get the name of the button that was clicked.
    $triggering_element = $form_state->getTriggeringElement();
    $name = $triggering_element['#name'];

    // Extract the pincode from the button name (e.g., "delete_110001").
    $pincode = str_replace('delete_', '', $name);

    $config = $this->config('custom_portal.pincode_mapping');
    $pincode_mappings = $config->get('pincode_mappings') ?: [];

    // Check if the pincode exists before trying to delete it.
    if (isset($pincode_mappings[$pincode])) {
      unset($pincode_mappings[$pincode]);
      $config->set('pincode_mappings', $pincode_mappings)->save();
      $this->messenger()->addStatus($this->t('Pincode mapping for @pincode has been deleted.', ['@pincode' => $pincode]));
    }

    // The form will rebuild after this handler runs.
    $form_state->setRebuild();
  }
}
