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

    // Lookup email by pincode (replace with DB config)
    $email_map = [
      '110001' => 'delhi@example.com',
      '560001' => 'bangalore@example.com',
    ];
    $to = $email_map[$pincode] ?? 'default@example.com';

    $mailManager = \Drupal::service('plugin.manager.mail');
    $params = [
      'subject' => 'New Distributor Submission',
      'message' => "Name: {$values['name']}\nEmail: {$values['email']}\nMessage: {$values['message']}",
    ];
    $mailManager->mail('custom_portal', 'distributor_form', $to, \Drupal::currentUser()->getPreferredLangcode(), $params);

    $this->messenger()->addMessage($this->t('Form submitted. Mail sent to @mail', ['@mail' => $to]));
  }
}
