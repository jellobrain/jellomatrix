<?php
/**
 * @file
 * Contains \Drupal\jellomatrix\Form\JelloMatrixOffsetResultForm.
 */

namespace Drupal\jellomatrix\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\jellomatrix\JellomatrixGetColors;
use Drupal\jellomatrix\JellomatrixHarmonics;
use Drupal\jellomatrix\JellomatrixIncrementsDerivative;
use Drupal\jellomatrix\JellomatrixIncrementsPrimeDerivative;
use Drupal\jellomatrix\JellomatrixIncrementsOriginal;
use Drupal\jellomatrix\JellomatrixPrimeOffsetMatrix;
use Drupal\jellomatrix\JellomatrixResponseOffsetMatrix;
use Drupal\jellomatrix\JellomatrixSplicedOffsetMatrix;
use Drupal\jellomatrix\JellomatrixWaveDetection;
use Drupal\jellomatrix\JellomatrixWavePreparation;
use Drupal\jellomatrix\JellomatrixGenerateSoundFiles;
use Drupal\jellomatrix\JellomatrixCircleGrids;

/**
 * Contribute form.
 */
class JelloMatrixOffsetResultForm extends FormBase {
  
  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixGetColors
   */
  protected $get_colors;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixHarmonics
   */
  protected $harmonics;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixIncrementsDerivative
   */
  protected $increments_derivative;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixIncrementsPrimeDerivative
   */
  protected $increments_prime_derivative;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixIncrementsOriginal
   */
  protected $increments_original;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixPrimeOffsetMatrix
   */
  protected $prime_offset_matrix;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixResponseOffsetMatrix
   */
  protected $response_offset_matrix;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixSplicedOffsetMatrix
   */
  protected $spliced_offset_matrix;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixWaveDetection
   */
  protected $wave_detection;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixWavePreparation
   */
  protected $wave_preparation;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixGenerateSoundFiles
   */
  protected $sound_files;
  
  /**
   * @var \Drupal\jellomatrix\JellomatrixCircleGrids
   */
  protected $circle_grids;



  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function __construct(AccountInterface $account, $get_colors, $harmonics, $increments_derivative, $increments_prime_derivative, $increments_original, $prime_offset_matrix, $response_offset_matrix, $spliced_offset_matrix, $wave_detection, $wave_preparation, $sound_files, $circle_grids) {
    $this->account = $account;
    $this->get_colors = $get_colors;
    $this->harmonics = $harmonics;
    $this->increments_derivative = $increments_derivative;
    $this->increments_prime_derivative = $increments_prime_derivative;
    $this->increments_original = $increments_original;
    $this->prime_offset_matrix = $prime_offset_matrix;
    $this->response_offset_matrix = $response_offset_matrix;
    $this->spliced_offset_matrix = $spliced_offset_matrix;
    $this->wave_detection = $wave_detection;
    $this->wave_preparation = $wave_preparation;
    $this->sound_files = $sound_files;
    $this->circle_grids = $circle_grids;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    // Load the service required to construct this class.
    $account = $container->get('current_user');
    $get_colors = $container->get('jellomatrix.jellomatrix_get_colors');
    $harmonics = $container->get('jellomatrix.jellomatrix_harmonics');
    $increments_derivative = $container->get('jellomatrix.jellomatrix_increments_derivative');
    $increments_prime_derivative = $container->get('jellomatrix.jellomatrix_increments_prime_derivative');
    $increments_original = $container->get('jellomatrix.jellomatrix_increments_original');
    $prime_offset_matrix = $container->get('jellomatrix.jellomatrix_prime_offest_matrix');
    $response_offset_matrix = $container->get('jellomatrix.jellomatrix_response_offset_matrix');
    $spliced_offset_matrix = $container->get('jellomatrix.jellomatrix_spliced_offset_matrix');
    $wave_detection = $container->get('jellomatrix.jellomatrix_wave_detection');
    $wave_preparation = $container->get('jellomatrix.jellomatrix_wave_preparation');
    $sound_files = $container->get('jellomatrix.jellomatrix_generate_sound_files');
    $circle_grids = $container->get('jellomatrix.jellomatrix_circle_grids');
    return new static(
        $account, $get_colors, $harmonics, $increments_derivative, $increments_prime_derivative, $increments_original, $prime_offset_matrix, $response_offset_matrix, $spliced_offset_matrix, $wave_detection, $wave_preparation, $sound_files, $circle_grids
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_offset_result_form';
  }

   /**
    * {@inheritdoc}
    */
  public function buildForm(array $form, FormStateInterface $form_state, $tone = NULL, $interval = NULL, $offset = 0, $frequency = NULL, $print = NULL) {
    $frequency = \Drupal::request()->query->get('frequency');
    $offsetrange = range(0,$interval-2);
    if (!isset($frequency)) {
      $frequency = 264;
    }
    $print = \Drupal::request()->query->get('print');
    if(!isset($print)) {
      $print = 'none';
    }
    $form['description'] = array(
      '#type' => 'markup',
      '#title' => t('Orientation'),
      '#markup' => '<p>' . t('If this looks like the beginning of a new math, that is because it is.  It\'s actually a very old math reborn.') . '</p><p><strong>' . t('Welcome.') . '</strong> ' . t('Contact me directly at') . ' <a href="mailto:ana@jellobrain.com">ana at jellobrain dot com</a> ' . t('if you\'d like to talk about it.') . '</p><p>' . t('This tool takes two numbers and creates a matrix grid with them, and then performs all sorts of calculations including harmonics and derivative value shifts between numbers in their numerical contexts (topologies).') . '</p><p>' . t('In addition and perhaps more specifically, this tool evaluates matrices spliced with inverse (upside down) copies of themselves, and looks for waveforms in the resulting numerical topologies with the following characteristics:') . '</p><ol><li>' . t('Bands of numbers in the spliced matrix with equal values adjacent to one another...') . '</li><li>' . t('which connect in predictable sine wave forms with one another...') . '</li><li>' . t('following the order of a scale which is determined by the top row of values in the unspliced and native "seed" matrix...') . '</li><li>' . t('rhythms that are even numbered change polarity at the crests of the waveforms, while odd rhythms change polarity at each shift in position.') . '</li><li>' . t('and harmonically cycle between zero and infinity.') . '</li></ol><p>' . t('Aspects of that set of characteristics will appear even if the full pattern is not present in unison.') . '</p><p>' . t('In addition, the patterns seem to continue to contain these inherent characheristics even when the two polar grids are spliced in a way that they are offset.') . '</p><p>' . t('Following the grid drawings will lead you through the story of how they are created, and enterring a value in the form to offset the grids will generate an offset grid.') . '</p>',
      '#attached' => array(
        'library' => array(
          'jellomatrix/jellomatrix',
        ),
      ),
    );
    $form['offset'] = array(
      '#type' => 'select',
      '#title' => t('Offset'),
      '#description' => t('This is where we see that even if the grids are offset vertically from one another, they still have an opportunity to be scale active and seem to function like Moire patterns in that sense.'),
      '#options' => $offsetrange,
      '#default_value' => $offset,
    );
    $form['frequency'] = array(
        '#title' => t('Base frequency.'),
        '#description' => t('This is where we modify the base frequency of the middle C value in the Lambdoma/Frequency charts.'),
        '#default_value' => $frequency,
        '#type' => 'textfield',
        '#attributes' => array(
            ' type' => 'number', // insert space before attribute name :)
        ),
        '#maxlength' => 11,
    );
    $user = \Drupal::currentUser();
    $roles = $user->getRoles();
    if (in_array('administrator', $roles)) {
      $print_options = ['none', 'singles', 'pairings', 'complete', 'rife', 'combine rife'];
      $form['print'] = array(
          '#title' => t('Do you want to reload and create audio files?  Which?'),
          '#description' => t(''),
          '#default_value' => $print,
          '#type' => 'select',
          '#options' => $print_options,
      );
    }
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
  
    unset($prime_matrix);
    unset($response_matrix);
    unset($spliced_matrix);
    // Find the values of the arrays.
    $prime_matrix = jellomatrix_prime_offset($tone, $interval, $offset);
    $response_matrix = jellomatrix_response_offset($tone, $interval, $offset);
    $spliced_matrix = jellomatrix_spliced_offset($prime_matrix, $response_matrix, $tone, $interval, $offset);
  
    extract(jellomatrix_wave_preparation($prime_matrix, $tone, $interval, $spliced_matrix));


    if (!empty($h_increment)) {
      jellomatrix_circle_detection($h_increment, $tone, $interval, 100, $direction = 'h');
      jellomatrix_circle_grid($tone, $interval, 200);
    }
    else {
      $h_increment = .1;
      jellomatrix_circle_detection($h_increment, $tone, $interval, 100, $direction = 'h');
      jellomatrix_circle_grid($tone, $interval, 200);
    }

    if (!empty($f_increment)) {
      jellomatrix_circle_detection($f_increment, $tone, $interval, 100, $direction = 'f');
    }
    else {
      $f_increment = .1;
      jellomatrix_circle_detection($f_increment, $tone, $interval, 100, $direction = 'f');
    }

    if (!empty($b_increment)) {
      jellomatrix_circle_detection($b_increment, $tone, $interval, 100, $direction = 'b');
    }
    else {
      $b_increment = .1;
      jellomatrix_circle_detection($b_increment, $tone, $interval, 100, $direction = 'b');
    }

    // Now we get the harmonics.
    $harmonics = jellomatrix_harmonics($frequency);
  
    $primes = jellomatrix_primes($tone);
  
    $increments = jellomatrix_increments_derivative($spliced_matrix, $tone);
  
    $increment_original = jellomatrix_increments_original($spliced_matrix, $tone);
  
    $increments_prime = jellomatrix_increments_prime_derivative($prime_matrix, $tone);
  
    $spliced_matrix_saved = $spliced_matrix;
    $spliced_matrix_reversed_saved = $spliced_matrix_reversed;
  
    // Now we create the first original matrix grid.
    $output = '';
    
    $output .= jellomatrix_output_basegrid($increments, $prime_matrix, $primes, $tone, $interval, $scaled, $scales);
    $output .= jellomatrix_output_splicegrid_basic($spliced_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_primes($spliced_matrix, $primes, $tone, $interval);
    $output .= jellomatrix_output_splicegrid_evenodd($spliced_matrix, $primes, $tone, $interval);
  
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    $spliced_matrix = $spliced_matrix_saved;
    $spliced_matrix_reversed = $spliced_matrix_reversed_saved;
  
    $output .= '<h2>HORIZONTAL SCALED WAVES</h2>';
    $dir = 'h';
    unset($scale);
    $scale = $scales['h'];
    
    if (!empty($spliced_matrix)) {
      extract(jellomatrix_wave_detection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale, $dir/*, $scales*/));
    }
    if (isset($hscaled)) {
      $output .= jellomatrix_output_splicegrid_waveforms($spliced_matrix, $spliced_matrix_reversed, $primes, $tone, $interval, $boolean = 'yes', $hscaled);
    }
    
    if (!empty($scale_increments) && isset($scaled)) {
      $output .= jellomatrix_output_splicegrid_scalepattern($scale_increments, $scaled, $primes, $tone, $interval);
    }
    if (isset($wavelength_calculation)) {
      $output .= $wavelength_calculation;
    }
  
    unset($scale_increments);
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    $spliced_matrix = $spliced_matrix_saved;
    $spliced_matrix_reversed = $spliced_matrix_reversed_saved;
  
    $output .= '<div class="begintext"><p><br></p><hr><h2>FORWARD BACKSLASH SCALED WAVES</h2></div>';
    $dir = 'f';
    unset($scale);
    $scale = $scales['f'];
    
    if (!empty(jellomatrix_wave_detection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale, $dir))) {
      extract(jellomatrix_wave_detection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale, $dir));
    }
    
    if (isset($fscaled)) {
      $output .= jellomatrix_output_splicegrid_waveforms($spliced_matrix, $spliced_matrix_reversed, $primes, $tone, $interval, $boolean = 'no', $fscaled);
    }

    if (!empty($scale_increments) && isset($fscaled)) {
      $output .= jellomatrix_output_splicegrid_scalepattern($scale_increments, $fscaled, $primes, $tone, $interval);
    }
    if (isset($wavelength_calculation)) {
      $output .= $wavelength_calculation;
    }
  
    unset($scale_increments);
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    $spliced_matrix = $spliced_matrix_saved;
    $spliced_matrix_reversed = $spliced_matrix_reversed_saved;
  
    $output .= '<div class="begintext"><p><br></p><hr><h2>BACKWARD BACKSLASH SCALED WAVES</h2></div>';
    $dir = 'b';
    unset($scale);
    $scale = $scales['b'];
    
    if (!empty(jellomatrix_wave_detection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale, $dir))) {
      extract(jellomatrix_wave_detection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale, $dir));
    }
    
    if (isset($bscaled)) {
      $output .= jellomatrix_output_splicegrid_waveforms($spliced_matrix, $spliced_matrix_reversed, $primes, $tone, $interval, $boolean = 'no', $bscaled);
    }
    if (!empty($scale_increments) && isset($bscaled)) {
      $output .= jellomatrix_output_splicegrid_scalepattern($scale_increments, $bscaled, $primes, $tone, $interval);
    }
    if (isset($wavelength_calculation)) {
      $output .= $wavelength_calculation;
    }
  
    $output .= jellomatrix_output_splicegrid_harmonics($increment_original, $harmonics, $primes, $tone, $interval, $frequency, $print);
    $output .= jellomatrix_output_splicegrid_derivative_harmonics($increment_original, $harmonics, $primes, $tone, $interval, $frequency, $print);
    //$output .= jellomatrix_output_splicegrid_derivatives($increments, $primes, $tone, $interval, $harmonics, $frequency, $print);
    //$output .= jellomatrix_output_splicegrid_derivative_oddeven($increments_prime, $primes, $tone, $interval, $harmonics, $frequency, $print);
    //$output .= jellomatrix_output_splicegrid_derivative_primes($increments_prime, $primes, $tone, $interval, $harmonics, $frequency, $print);
    $output .= '</div>';
  
  
    $form['output'] = array(
      '#type' => 'markup',
      '#markup' => $output,
    );
    unset($scale_increments);
    unset($scale);
    unset($scaled);
    unset($bscaled);
    unset($fscaled);
    unset($hscaled);
    unset($h_increment);
    unset($f_increment);
    unset($b_increment);
    unset($h_scale_sum_ratios);
    unset($f_scale_sum_ratios);
    unset($b_scale_sum_ratios);
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    unset($wavelength_calculation);
    unset($increment_original);
    unset($increments);
    unset($increments_prime);
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
    $frequency = $form_state->getValue('frequency');
    $print = $form_state->getValue('print');
    if ($offset != 0) {
      $uri = 'jellomatrix/' . $tone . '/' . $interval . '/offset/' . $offset . '?frequency=' . $frequency;
      if (isset($print)) {
        $uri .= '&print=' . $print;
      }
      $url = Url::fromUri('internal:/' . $uri);
      $form_state->setRedirectUrl($url);
      return $frequency;
    }
    else {
      $uri = 'jellomatrix/' . $tone . '/' . $interval . '?frequency=' . $frequency;
      if (isset($print)) {
        $uri .= '&print=' . $print;
      }
      $url = Url::fromUri('internal:/' . $uri);
      $form_state->setRedirectUrl($url);
      return $frequency;
    }
  }
}
