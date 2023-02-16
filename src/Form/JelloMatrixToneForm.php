<?php
/**
 * @file
 * Contains \Drupal\custom\jellomatrix\Form\JelloMatrixToneForm.
 */

namespace Drupal\jellomatrix\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
//use Drupal\Core\Session\AccountInterface;
//use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Contribute form.
 */
class JelloMatrixToneForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_tone_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $tones = range(1,99);

    $form['description'] = array(
      '#type' => 'markup',
      '#title' => t('Orientation'),
      '#markup' => '<div class="begintext" ><p>' . t('This is the first part of a process of creating a grid or matrix composed of two base numbers that make up the counting system.  This first of these numbers I am calling a tone, which sets up the base numbering system.') . '</p></div>',
      '#attached' => array(
        'library' => array(
          'jellomatrix/jellomatrix',
        ),
      ),
    );
    $form['tone'] = array(
      '#type' => 'select',
      '#title' => t('Tone'),
      '#options' => $tones,
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
    if (!is_numeric($form_state->getValue('tone'))) {
      $form_state->setErrorByName('tone', $this->t("The tone is not valid."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $tone = $form_state->getValue('tone')+1;
    $uri = 'jellomatrix/' .$tone;
    $url = Url::fromUri('internal:/' . $uri);
    $form_state->setRedirectUrl($url);
  }
}
