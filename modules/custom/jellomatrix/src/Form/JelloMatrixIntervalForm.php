<?php
/**
 * @file
 * Contains \Drupal\jellomatrix\Form\JellomatrixIntervalForm.
 */

namespace Drupal\jellomatrix\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contribute form.
 */
class JellomatrixIntervalForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_interval_form';
  }

   /**
    * {@inheritdoc}
    */
  public function buildForm(array $form, FormStateInterface $form_state, $tone = NULL) {
    $intervals = range($tone,99);

    $form['description'] = array(
      '#type' => 'markup',
      '#title' => t('Orientation'),
      '#markup' => '<div class="begintext" ><p>' . t('This is the second part of the process of creating a grid or matrix composed of two base numbers that make up the counting system.  This second of these numbers I am calling an "interval" because it governs a central aspect of the "frequency" of these matrixes.').'</p></div>',
      '#attached' => array(
        'library' => array(
          'jellomatrix/jellomatrix',
        ),
      ),
    );
    $form['interval'] = array(
      '#type' => 'select',
      '#title' => t('Interval'),
      '#options' => $intervals,
    );
    $form['tone'] = array(
      '#type' => 'hidden',
      '#value' => $tone,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate video URL.
    if (!is_numeric($form_state->getValue('interval'))) {
      $form_state->setErrorByName('interval', $this->t("The interval is not valid."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $interval = $form_state->getValue('interval')+$form_state->getValue('tone');
    $tone = $form_state->getValue('tone');
    $uri = 'jellomatrix/' . $tone . '/' . $interval;
    $url = Url::fromUri('internal:/' . $uri);
    $form_state->setRedirectUrl($url);
  }
}
