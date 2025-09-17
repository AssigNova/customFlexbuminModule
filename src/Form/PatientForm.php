<?php

namespace Drupal\custom_portal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class PatientForm extends FormBase
{

  public function getFormId()
  {
    return 'custom_portal_patient_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['pincode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your Pincode'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find Nearest Distributors'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $pincode = $form_state->getValue('pincode');

    // TODO: Replace with DB query or custom table lookup
    $distributors = $this->getDistributorsByPincode($pincode);

    $this->messenger()->addMessage($this->t('Nearest distributors for pincode @pincode:', ['@pincode' => $pincode]));
    foreach ($distributors as $dist) {
      $this->messenger()->addMessage($dist);
    }
  }

  private function getDistributorsByPincode($pincode)
  {
    // Replace with actual DB lookup
    $mock_data = [
      '110001' => ['Distributor A', 'Distributor B'],
      '560001' => ['Distributor C'],
    ];
    return $mock_data[$pincode] ?? ['No distributors found'];
  }
}
