<?php
/**
 * @file
 * Contains \Drupal\jellomatrix\Form\JelloMatrixResultForm.
 */
namespace Drupal\jellomatrix\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class JelloMatrixResultForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_offset_result_form';
  }

   /**
    * {@inheritdoc}
    */
  public function buildForm(array $form, FormStateInterface $form_state, $tone = NULL, $interval = NULL) {
    $offsetrange = range(0,$interval-2);

    $form['description'] = array(
      '#type' => 'markup',
      '#title' => t('Orientation'),
      '#markup' => '<p>' . t('If this looks like the beginning of a new math, that is because it is.  It\'s actually a very old math reborn.') . '</p><p><strong>' . t('Welcome.') . '</strong> ' . t('Contact me directly at') . ' <a href="mailto:ana@jellobrain.com">ana at jellobrain dot com</a> ' . t('if you\'d like to talk about it.') . '</p><div class="endtext"><br></div><div class="begintext" ><p>' . t('This is where we see that even if the grids are offset vertically from one another, they still have an opportunity to be scale active.').'</p></div>',
      '#attached' => array(
        'library' => array(
          'jellomatrix/jellomatrix',
        ),
      ),
    );
    $form['offset'] = array(
      '#type' => 'select',
      '#title' => t('Offset'),
      '#options' => $offsetrange,
      '#default_value' => 0,
    );
    $form['tone'] = array(
      '#type' => 'hidden',
      '#value' => $tone,
    );
    $form['interval'] = array(
      '#type' => 'hidden',
      '#value' => $interval,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    //This function does not know how to deal yet with tones that are larger than intervals.
    if ($tone > $interval) {
      $i = $interval;
      $t = $tone;
      $interval = $t;
      $tone = $i;
      unset($i);
      unset($t);
    }

    //Find the values of the arrays
    $prime_matrix = jellomatrix_prime_basetone($tone, $interval);
    $response_matrix = jellomatrix_response_basetone($tone, $interval);
    $spliced_matrix = jellomatrix_spliced_basetone($prime_matrix, $response_matrix, $tone, $interval);

    extract(jellomatrix_wave_detection($prime_matrix, $tone, $interval, $spliced_matrix));

    //TEST
    $fwd = 0;
    $rev = 0;
    foreach($spliced_matrix as $row) {
      foreach ($row as $item) {
        if (isset($item['yellow'])) {
          $fwd++;
        }
      }
    }

    if (isset($spliced_matrix_reversed)) {
      foreach($spliced_matrix_reversed as $row) {
        foreach ($row as $item) {
          if (isset($item['yellow'])) {
            $rev++;
          }
        }
      }
    }

    if (isset($scaled)) {
      jellomatrix_circle_detection($tone, $interval, 100);

    }


    //now we get the harmonics
    $harmonics = jellomatrix_harmonics();

    $primes = jellomatrix_primes($tone);

    $increments = jellomatrix_increments_derivative($spliced_matrix, $tone);

    $increment_original = jellomatrix_increments_original($spliced_matrix, $tone);

    $increments_prime = jellomatrix_increments_prime_derivative($prime_matrix, $tone);

  	//Now we create the first original matrix grid
    $output = '';

    $output .= jellomatrix_output_basegrid($scale_increments, $prime_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_basic($spliced_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_primes($spliced_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_evenodd($spliced_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_waveforms($spliced_matrix, $spliced_matrix_reversed, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_scalepattern($scale_increments, $scaled, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_harmonics($increment_original, $harmonics, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_derivative_harmonics($increment_original, $harmonics, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_derivatives($increments, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_derivative_oddeven($increments_prime, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_derivative_primes($increments_prime, $primes, $tone, $interval);
    //TODO
    $output .= '</div><hr><br><hr></div>';


    $form['output'] = array(
      '#type' => 'markup',
      '#markup' => $output,
    );
    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate video URL.
    if (!is_numeric($form_state->getValue('offset'))) {
      $form_state->setErrorByName('offset', $this->t("The offset is not valid."));
    }
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $offset = $form_state->getValue('offset');
    $tone = $form_state->getValue('tone');
    $interval = $form_state->getValue('interval');
    $uri = 'jellomatrix/' . $tone . '/' . $interval . '/offset/' . $offset;
    $url = Url::fromUri('internal:/' . $uri);
    $form_state->setRedirectUrl($url);
  }
}
