<?php

namespace Drupal\mailreader\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Contribute form.
 */
class PassConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mailreader_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mailreader.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Email Id'),
      '#required' => TRUE,
      '#default_value' => $this->config('mailreader.settings')->get('email'),
    );

    $form['password'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('password'),
      '#required' => TRUE,
      '#default_value' => $this->config('mailreader.settings')->get('password'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mailreader.settings')
      ->set('email', $form_state->getValue('email'))
      ->set('password', $form_state->getValue('password'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
