<?php

namespace Drupal\custom_portal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class HcpForm extends FormBase
{

  public function getFormId()
  {
    return 'custom_portal_hcp_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['organization'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Organization'),
      '#required' => TRUE,
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    // Attach your CSS library and theme here for styling:
    $form['#attached']['library'][] = 'custom_portal/form_styles';
    $form['#theme'] = 'custom_portal_hcp_form';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    // Implement your mail or processing logic here as needed.

    $this->messenger()->addMessage($this->t('HCP form submitted successfully.'));
  }
}
