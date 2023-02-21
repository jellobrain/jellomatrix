<?php
/**
 * @file
 * Contains \Drupal\jellomatrix\Form\JelloMatrixResultForm.
 */

namespace Drupal\jellomatrix\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use Drupal\jellomatrix\Services\Query\JellomatrixGetColors;
//use Drupal\jellomatrix\Services\Query\JellomatrixHarmonics;
//use Drupal\jellomatrix\Services\Query\JellomatrixIncrementsDerivative;
//use Drupal\jellomatrix\Services\Query\JellomatrixIncrementsPrimeDerivative;
//use Drupal\jellomatrix\Services\Query\JellomatrixIncrementsOriginal;
//use Drupal\jellomatrix\Services\Query\JellomatrixPrimeMatrix;
//use Drupal\jellomatrix\Services\Query\JellomatrixResponseMatrix;
//use Drupal\jellomatrix\Services\Query\JellomatrixSplicedMatrix;
//use Drupal\jellomatrix\Services\Query\JellomatrixWaveDetection;
//use Drupal\jellomatrix\Services\Query\JellomatrixWavePreparation;
//use Drupal\jellomatrix\Services\Query\JellomatrixGenerateSoundFiles;
//use Drupal\jellomatrix\Services\Query\JellomatrixCircleGrids;
//use Drupal\jellomatrix\Services\Query\JellomatrixPrimes;
//use Drupal\jellomatrix\Services\Display\GridPrimeMatrix;
//use Drupal\jellomatrix\Services\Display\GridSplicedMatrix;
//use Drupal\jellomatrix\Services\Display\GridSplicedDerivativeEvenOdd;
//use Drupal\jellomatrix\Services\Display\GridSplicedDerivativeHarmonics;
//use Drupal\jellomatrix\Services\Display\GridSplicedDerivativePrimes;
//use Drupal\jellomatrix\Services\Display\GridSplicedDerivatives;
//use Drupal\jellomatrix\Services\Display\GridSplicedEvenOdd;
//use Drupal\jellomatrix\Services\Display\GridSplicedHarmonics;
//use Drupal\jellomatrix\Services\Display\GridSplicedPrimes;
//use Drupal\jellomatrix\Services\Display\GridSplicedScalePatterns;
//use Drupal\jellomatrix\Services\Display\GridSplicedWaveForms;

class JelloMatrixResultForm extends FormBase {
  
  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixGetColors
   */
  protected $get_colors;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixHarmonics
   */
  protected $harmonics;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixIncrementsDerivative
   */
  protected $increments_derivative;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixIncrementsPrimeDerivative
   */
  protected $increments_prime_derivative;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixIncrementsOriginal
   */
  protected $increments_original;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixPrimeMatrix
   */
  protected $prime_matrix;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixResponseMatrix
   */
  protected $response_matrix;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixSplicedMatrix
   */
  protected $spliced_matrix;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixWaveDetection
   */
  protected $wave_detection;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixWavePreparation
   */
  protected $wave_preparation;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixGenerateSoundFiles
   */
  protected $sound_files;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixCircleGrids
   */
  protected $circle_grids;
  
  /**
   * @var \Drupal\jellomatrix\Services\Query\JellomatrixPrimes
   */
  protected $primes;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridPrimeMatrix
   */
  protected $grid_prime_matrix;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedMatrix
   */
  protected $grid_spliced_matrix;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedDerivativeEvenOdd
   */
  protected $grid_spliced_derivative_even_odd;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedDerivativeHarmonics
   */
  protected $grid_spliced_derivative_harmonics;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedDerivativePrimes
   */
  protected $grid_spliced_derivative_primes;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedDerivatives
   */
  protected $grid_spliced_derivatives;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedEvenOdd
   */
  protected $grid_spliced_even_odd;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedHarmonics
   */
  protected $grid_spliced_harmonics;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedPrimes
   */
  protected $grid_spliced_primes;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedScalePatterns
   */
  protected $grid_spliced_scale_patterns;
  
  /**
   * @var \Drupal\jellomatrix\Services\Display\GridSplicedWaveForms
   */
  protected $grid_spliced_wave_forms;


  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function __construct(AccountInterface $account, $get_colors, $harmonics, $increments_derivative, $increments_prime_derivative, $increments_original, $prime_matrix, $response_matrix, $spliced_matrix, $wave_detection, $wave_preparation, $sound_files, $circle_grids, $primes, $grid_prime_matrix, $grid_spliced_matrix, $grid_spliced_derivative_even_odd, $grid_spliced_derivative_harmonics, $grid_spliced_derivative_primes, $grid_spliced_derivatives, $grid_spliced_even_odd, $grid_spliced_harmonics, $grid_spliced_primes, $grid_spliced_scale_patterns, $grid_spliced_wave_forms) {
    $this->account = $account;
    $this->get_colors = $get_colors;
    $this->harmonics = $harmonics;
    $this->increments_derivative = $increments_derivative;
    $this->increments_prime_derivative = $increments_prime_derivative;
    $this->increments_original = $increments_original;
    $this->prime_matrix = $prime_matrix;
    $this->response_matrix = $response_matrix;
    $this->spliced_matrix = $spliced_matrix;
    $this->wave_detection = $wave_detection;
    $this->wave_preparation = $wave_preparation;
    $this->sound_files = $sound_files;
    $this->circle_grids = $circle_grids;
    $this->primes = $primes;
    $this->grid_prime_matrix = $grid_prime_matrix;
    $this->grid_spliced_matrix = $grid_spliced_matrix;
    $this->grid_spliced_derivative_even_odd = $grid_spliced_derivative_even_odd;
    $this->grid_spliced_derivative_harmonics = $grid_spliced_derivative_harmonics;
    $this->grid_spliced_derivative_primes = $grid_spliced_derivative_primes;
    $this->grid_spliced_derivatives = $grid_spliced_derivatives;
    $this->grid_spliced_even_odd = $grid_spliced_even_odd;
    $this->grid_spliced_harmonics = $grid_spliced_harmonics;
    $this->grid_spliced_primes = $grid_spliced_primes;
    $this->grid_spliced_scale_patterns = $grid_spliced_scale_patterns;
    $this->grid_spliced_wave_forms = $grid_spliced_wave_forms;
    
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Load the service required to construct this class.
    $account = $container->get('current_user');
    $get_colors = $container->get('jellomatrix.jellomatrix_get_colors');
    $harmonics = $container->get('jellomatrix.jellomatrix_harmonics');
    $increments_derivative = $container->get('jellomatrix.jellomatrix_increments_derivative');
    $increments_prime_derivative = $container->get('jellomatrix.jellomatrix_increments_prime_derivative');
    $increments_original = $container->get('jellomatrix.jellomatrix_increments_original');
    $prime_matrix = $container->get('jellomatrix.jellomatrix_prime_matrix');
    $response_matrix = $container->get('jellomatrix.jellomatrix_response_matrix');
    $spliced_matrix = $container->get('jellomatrix.jellomatrix_spliced_matrix');
    $wave_detection = $container->get('jellomatrix.jellomatrix_wave_detection');
    $wave_preparation = $container->get('jellomatrix.jellomatrix_wave_preparation');
    $sound_files = $container->get('jellomatrix.jellomatrix_generate_sound_files');
    $circle_grids = $container->get('jellomatrix.jellomatrix_circle_grids');
    $primes = $container->get('jellomatrix.jellomatrix_primes');
    $grid_prime_matrix = $container->get('jellomatrix.grid_prime_matrix');
    $grid_spliced_matrix = $container->get('jellomatrix.grid_spliced_matrix');
    $grid_spliced_derivative_even_odd = $container->get('jellomatrix.grid_spliced_derivative_even_odd');
    $grid_spliced_derivative_harmonics = $container->get('jellomatrix.grid_spliced_derivative_harmonics');
    $grid_spliced_derivative_primes = $container->get('jellomatrix.grid_spliced_derivative_primes');
    $grid_spliced_derivatives = $container->get('jellomatrix.grid_spliced_derivatives');
    $grid_spliced_even_odd = $container->get('jellomatrix.grid_spliced_even_odd');
    $grid_spliced_harmonics = $container->get('jellomatrix.grid_spliced_harmonics');
    $grid_spliced_primes = $container->get('jellomatrix.grid_spliced_primes');
    $grid_spliced_scale_patterns = $container->get('jellomatrix.grid_spliced_scale_patterns');
    $grid_spliced_wave_forms = $container->get('jellomatrix.grid_spliced_wave_forms');
    
    return new static(
        $account, $get_colors, $harmonics, $increments_derivative, $increments_prime_derivative, $increments_original, $prime_matrix, $response_matrix, $spliced_matrix, $wave_detection, $wave_preparation, $sound_files, $circle_grids, $primes, $grid_prime_matrix, $grid_spliced_matrix, $grid_spliced_derivative_even_odd, $grid_spliced_derivative_harmonics, $grid_spliced_derivative_primes, $grid_spliced_derivatives, $grid_spliced_even_odd, $grid_spliced_harmonics, $grid_spliced_primes, $grid_spliced_scale_patterns, $grid_spliced_wave_forms
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_result_form';
  }

   /**
    * {@inheritdoc}
    */
  public function buildForm(array $form, FormStateInterface $form_state, $tone = NULL, $interval = NULL, $frequency = NULL, $print = NULL) {
    $frequency = \Drupal::request()->query->get('frequency');
    $offsetrange = range(0,$interval-2);
    if(!isset($frequency)) {
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
        '#default_value' => 0,
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
      $print_options = ['none', 'singles', 'pairings', 'complete', 'rife11harmonic', 'combine rife11harmonic', 'rife12harmonic', 'combine rife12harmonic'];
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

    // This function does not know how to deal yet with tones that are larger than intervals.
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
    # BOOKMARK: injection
    $prime_matrix = $this->prime_matrix->getPrimeMatrix($tone, $interval);
    $response_matrix = $this->response_matrix->getResponseMatrix($tone, $interval);
    $spliced_matrix = $this->spliced_matrix->getSplicedMatrix($prime_matrix, $response_matrix, $tone, $interval);

    extract($this->wave_preparation->getWavePreparation($prime_matrix, $tone, $interval, $spliced_matrix));


    if (!empty($h_increment)) {
      $circle_detection = $this->circle_grids->circleDetection($h_increment, $tone, 100, $direction = 'h');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }
    else {
      $h_increment = .1;
      $circle_detection = $this->circle_grids->circleDetection($h_increment, $tone, 100, $direction = 'h');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }

    if (!empty($f_increment)) {
      $circle_detection = $this->circle_grids->circleDetection($f_increment, $tone, 100, $direction = 'f');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }
    else {
      $f_increment = .1;
      $circle_detection = $this->circle_grids->circleDetection($f_increment, $tone, 100, $direction = 'f');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }

    if (!empty($b_increment)) {
      $circle_detection = $this->circle_grids->circleDetection($b_increment, $tone, 100, $direction = 'b');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }
    else {
      $b_increment = .1;
      $circle_detection = $this->circle_grids->circleDetection($b_increment, $tone, 100, $direction = 'b');
      $circle_grids = $this->circle_grids->circleGrid($tone, $interval, 100);
    }



    // Now we get the harmonics.
    $harmonics = $this->harmonics->getHarmonics($frequency);

    $primes = $this->primes->getPrimes($tone);


    
    $increments = $this->increments_derivative->getIncrementsDerivative($spliced_matrix, $tone);

    $increment_original = $this->increments_original->getIncrementsOriginal($spliced_matrix, $tone);

    $increments_prime = $this->increments_prime_derivative->getIncrementsPrimeDerivative($prime_matrix, $tone);
  
    $spliced_matrix_saved = $spliced_matrix;
    $spliced_matrix_reversed_saved = $spliced_matrix_reversed;

  	// Now we create the first original matrix grid.
    $output = '<hr><h4>Control Set:</h4><p><a class="button btn-primary btn" href="/jellomatrix/' . $tone . '/' . $interval . '/doubleflip/">Doublflip it.</a></p>';
    $output .= $this->grid_prime_matrix->getGridPrimeMatrix($increments, $prime_matrix, $primes, $tone, $interval, $scaled, $scales);
    $output .= $this->grid_spliced_matrix->getGridSplicedMatrix($spliced_matrix, $primes, $tone, $interval);
    $output .= $this->grid_spliced_primes->getGridSplicedPrimes($spliced_matrix, $primes, $tone, $interval);
    $output .= $this->grid_spliced_even_odd->getGridSplicedEvenOdd($spliced_matrix, $tone, $interval);
    
  
    unset($spliced_matrix);
    unset($spliced_matrix_reversed);
    $spliced_matrix = $spliced_matrix_saved;
    $spliced_matrix_reversed = $spliced_matrix_reversed_saved;
  
    $output .= '<h2>HORIZONTAL SCALED WAVES</h2>';
    $dir = 'h';
    unset($scale);
    $scale = $scales['h'];
    
    if (!empty($spliced_matrix)) {
      extract($this->wave_detection->getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale));
    }
    $boolean = 'yes';
    if (isset($hscaled)) {
      $output .= $this->grid_spliced_wave_forms->getGridSplicedWaveForms($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $boolean, $scaled);
    }
    
    if (!empty($scale_increments) && isset($scaled)) {
      $output .= $this->grid_spliced_scale_patterns->getGridSplicedScalePatterns($scale_increments, $scaled, $primes, $tone, $interval);
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
    if (!empty($this->wave_detection->getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale))) {
      extract($this->wave_detection->getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale));
    }

   
    if (isset($fscaled)) {
      $output .= $this->grid_spliced_wave_forms->getGridSplicedWaveForms($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $boolean = 'no', $fscaled);
    }

    if (!empty($scale_increments) && isset($fscaled)) {
      $output .= $this->grid_spliced_scale_patterns->getGridSplicedScalePatterns($scale_increments, $fscaled, $primes, $tone, $interval);
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

    if (!empty($this->wave_detection->getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale/*, scales*/))) {
      extract($this->wave_detection->getWaveDetection($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $scale/*, scales*/));
    }
    
    if (isset($bscaled)) {
      $output .= $this->grid_spliced_wave_forms->getGridSplicedWaveForms($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $boolean = 'no', $bscaled);
    }
    
    if (!empty($scale_increments) && isset($bscaled)) {
      $output .= $this->grid_spliced_scale_patterns->getGridSplicedScalePatterns($scale_increments, $bscaled, $primes, $tone, $interval);
    }
    if (isset($wavelength_calculation)) {
      $output .= $wavelength_calculation;
    }
  
    $print = \Drupal::request()->query->get('print');
    $output .= $this->grid_spliced_harmonics->getGridSplicedHarmonics($increment_original, $harmonics, $tone, $interval, $frequency, $print);
    $output .= $this->grid_spliced_derivative_harmonics->getGridSplicedDerivativeHarmonics($increment_original, $harmonics, $primes, $tone, $interval, $frequency, $print);
    //$output .= $this->grid_spliced_derivativess->getGridSplicedDerivatives($increments, $primes, $tone, $interval, $harmonics, $frequency, $print);
    //$output .= $this->grid_spliced_derivative_even_odd->getGridSplicedDerivativeEvenOdd($increments_prime, $primes, $tone, $interval, $harmonics, $frequency, $print);
    //$output .= $this->grid_spliced_derivative_primes->getGridSplicedDerivativePrimes($increments_prime, $primes, $tone, $interval, $harmonics, $frequency, $print);
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
