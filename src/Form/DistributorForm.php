<?php

namespace Drupal\custom_portal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DistributorForm extends FormBase
{

  public function getFormId()
  {
    return 'custom_portal_distributor_form';
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
    $form['pincode'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pincode'),
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

    $form['#attached']['library'][] = 'custom_portal/form_styles';
    $form['#theme'] = 'custom_portal_distributor_form';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $pincode = $values['pincode'];

    $config = $this->config('custom_portal.pincode_mapping');
    $email_map = $config->get('pincode_mappings') ?: [];
    $to = $email_map[$pincode] ?? 'default@example.com';

    $module = 'custom_portal';
    $key = 'distributor_form';
    $langcode = $this->currentUser()->getPreferredLangcode();

    // Params only contain the dynamic data, not the whole message.
    $params = [
      'name' => $values['name'],
      'email' => $values['email'],
      'message' => $values['message'],
    ];

    $result = $this->mailManager->mail($module, $key, $to, $langcode, $params);

    if ($result['result'] !== true) {
      $this->messenger()->addError($this->t('There was a problem sending your message.'));
    } else {
      $this->messenger()->addMessage($this->t('Form submitted. Mail sent to @mail', ['@mail' => $to]));
    }
  }
}
