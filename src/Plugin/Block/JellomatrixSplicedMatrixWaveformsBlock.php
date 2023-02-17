<?php

namespace Drupal\jellomatrix\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Example: configurable text string' block.
 *
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "jellomatrix_spliced_matrix_waveforms_block",
 *   admin_label = @Translation("Jellomatrix Spliced Matrix Waveforms Block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JellomatrixSplicedMatrixWaveformsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * This method sets the block default configuration. This configuration
   * determines the block's behavior when a block is initially placed in a
   * region. Default values for the block configuration form should be added to
   * the configuration array. System default configurations are assembled in
   * BlockBase::__construct() e.g. cache setting and block title visibility.
   *
   * @see \Drupal\block\BlockBase::__construct()
   */
  public function defaultConfiguration($spliced_matrix, $spliced_matrix_reversed, $tone, $interval, $boolean, $scaled) {
    $output .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';
    if (!empty($spliced_matrix_reversed)) {
      foreach ($spliced_matrix_reversed as $spliced_row_reversed) {
        foreach ($spliced_row_reversed as $item) {
          if (isset($item['phase_color']) && !isset($item['wave_limit'])) {
            $reversed = 'reversed';
          }
        }
      }
    }
    foreach ($spliced_matrix as $spliced_row) {
      foreach ($spliced_row as $item) {
        if (isset($item['phase_color']) && !isset($item['wave_limit'])) {
          $forward = 'forward';
        }
      }
    }

    if (isset($reversed) && isset($forward)) {
      $direction = 'Bi-directional';
      $output .= '<p><br/></p><h2>Prime Series of Matrix is Bi-directional</h2>';
    }

    if (!isset($reversed) && isset($forward)) {
      $direction = 'Forward Only';
      $output .= '<p><br/></p><h2>Prime Series of Matrix is Forward Only</h2>';
    }

    if (isset($reversed) && !isset($forward)) {
      $direction = 'Reversed Only';
      $spliced_matrix = $spliced_matrix_reversed;
      $output .= '<p><br/></p><h2>Prime Series of Matrix is Reversed Only</h2>';
    }

    if ($boolean == 'yes') {
      // And then we create the spliced matrix grid using wave indicators for coloring
      // (we could use the rows at this point as well)...
      $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM POLE SHIFT: Highlighting the adjacent equal values.</h3><table class="table begingrid" cols="' . $tone * 2 . '" rows="' . $interval . '">';
      for ($i = 1; $i <= $interval; $i++) {
        $output .= '<tr>';
        $count = 1;
        foreach ($spliced_matrix as $spliced_row) {
          foreach ($spliced_row as $item) {
            if (!empty($item['row']) && $item['row'] == $i) {
              $prime = jellomatrix_primes($tone);
              if (($item['column']) % 2 == 0) {
                $item['color'] = 'green-text';
              }
              if (($item['column']) % 2 != 0) {
                $item['color'] = 'red-text';
              }
              if (isset($item['pole_shift'])) {
                if ($item['pole_shift'] == '1') {
                  $item['background'] = 'yellow-background';
                  $item['opacity'] = 1;
                }
                if ($item['pole_shift'] == '2') {
                  $item['background'] = 'torquoise-background';
                  $item['opacity'] = 1;
                }
              }

              $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid ' . $item['background'] . '" >' . $item['tone'] . '</td>';
              $count++;
            }
          }
        }
        $output .= '</tr>';
      }
      $output .= '</table><div class="begintext endtable"></div><hr class="hr"><br></div>';
    }
    //dpm($scaled);
    $output .= '<p><br/></p>' . $scaled . '<p><br/></p>';


    // And then we create the spliced matrix grid using wave indicators for coloring
    // (we could use the rows at this point as well)...
    $output_even = '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM SCALES: The Waveform Scales: EVEN Rhythms</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
    for ($i = 1; $i <= $interval; $i++) {
      $output_even .= '<tr>';
      $count = 1;
      foreach ($spliced_matrix as $spliced_row) {
        foreach ($spliced_row as $item) {
          if (!empty($item['row']) && $item['row'] == $i) {
            $prime = jellomatrix_primes($tone);
            if (($item['column'])%2 == 0) {
              $item['color'] = 'green-text';
            }
            if (($item['column'])%2 != 0) {
              $item['color'] = 'red-text';
            }
            if (isset($item['wave_limit'])) {
              if ($item['pole_shift'] == '2') {
                $item['background'] = 'yellow-background-light';
                $item['opacity'] = 1;
              }
              if ($item['pole_shift'] == '1') {
                $item['background'] = 'torquoise-background-light';
                $item['opacity'] = 1;
              }
            }
            $even = 0;
            if (isset($item['rhythm'])) {
              foreach ($item['rhythm'] as $rhythm) {
                if ($rhythm%2 == 0) {
                  $even = 1;
                  $evengrid = 1;
                }
              }
            }
            if (isset($item['yellow']) && isset($even) && $even == 1) {
              $item['background'] = $item['yellow'];
              $item['opacity'] = 1;
              $item['br'] = 'border-radius';
            }


            $output_even .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y tdgrid ' . $item['background'];
            if (isset($item['br'])) {
              $output_even .= ' ' . $item['br'];
            }
            $output_even .= '" ';
            $output_even .= '><span style="background: ' . $item['background'] . ' !important;">';
            $output_even .= $item['tone'] . '</span></td>';

            $count++;

          }
        }
      }
      $output_even .= '</tr>';
    }
    $output_even .= '</table><div class="begintext endtable"></div><p></p><hr class="hr"></div>';

    if (isset($evengrid)) {
      $output .= $output_even;
      unset($evengrid);
    }

    // And then we create the spliced matric grid using wave indicators for coloring
    // (we could use the rows at this point as well)...
    $output_odd = '<div class="begintext endtable"></div><div class="begingrid"><h3>WAVE FORM SCALES: The Waveform Scales: ODD Rhythms</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
    for ($i = 1; $i <= $interval; $i++) {
      $output_odd .= '<tr>';
      $count = 1;
      foreach ($spliced_matrix as $spliced_row) {
        foreach ($spliced_row as $item) {
          if (!empty($item['row']) && $item['row'] == $i) {
            $prime = jellomatrix_primes($tone);
            if (($item['column'])%2 == 0) {
              $item['color'] = 'green-text';
            }
            if (($item['column'])%2 != 0) {
              $item['color'] = 'red-text';
            }
            if (isset($item['wave_limit'])) {
              if ($item['pole_shift'] == '2') {
                $item['background'] = 'yellow-background-light';
                $item['opacity'] = 1;
              }
              if ($item['pole_shift'] == '1') {
                $item['background'] = 'torquoise-background-light';
                $item['opacity'] = 1;
              }
            }
            $odd = 0;
            if (isset($item['rhythm'])) {
              foreach ($item['rhythm'] as $rhythm) {
                if ($rhythm%2 != 0) {
                  $odd = 1;
                  $oddgrid = 1;
                }
              }
            }
            if (isset($item['yellow']) && isset($odd) && $odd != 0) {
              $item['background'] = $item['yellow'];
              $item['opacity'] = 1;
              $item['br'] = 'border-radius';
            }
            $output_odd .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y tdgrid ' . $item['background'];
            if (isset($item['br'])) {
              $output_odd .= ' ' . $item['br'];
            }
            $output_odd .= '" ';
            $output_odd .= '><span>';
            $output_odd .= $item['tone'] . '</span></td>';

            $count++;
          }
        }
      }
      $output_odd .= '</tr>';
    }
    $output_odd .= '</table><div class="begintext endtable"></div><p></p><hr class="hr">';

    if (isset($oddgrid)) {
      $output .= $output_odd;
      unset($oddgrid);
    }
    // TODO: https://api.drupal.org/api/drupal/core%21modules%21views%21src%21Plugin%21views%21area%21Text.php/function/Text%3A%3Arender/8.7.x
    // return render($output);
    $outputt = [];
    $outputt = [
      '#type' => 'processed_text',
      '#text' => $output,
      '#format' => 'full_html',
    ];
    //return render($outputt);
    return $output;
    
    return [
      'jellomatrix_intro_string' => $intro,
      'jellomatrix_append_string' => $append,
      'jellomatrix_block_meat' => $output,
    ];
  }

  /**
   * {@inheritdoc}
   *
   * This method defines form elements for custom block configuration. Standard
   * block configuration fields are added by BlockBase::buildConfigurationForm()
   * (block title and title visibility) and BlockFormController::form() (block
   * visibility settings).
   *
   * @see \Drupal\block\BlockBase::buildConfigurationForm()
   * @see \Drupal\block\BlockFormController::form()
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['jellomatrix_intro_string_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Block contents'),
      '#description' => $this->t('This text will appear in the block.'),
      '#default_value' => $this->configuration['jellomatrix_intro_string'] . '<br>' . $this->configuration['jellomatrix_block_meat'],
    ];
    $form['jellomatrix_append_string_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Block contents'),
      '#description' => $this->t('This text will appear in the block.'),
      '#default_value' => $this->configuration['jellomatrix_append_string'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['jellomatrix_intro_string']
      = $form_state->getValue('jellomatrix_intro_string_text');
    $this->configuration['jellomatrix_append_string']
      = $form_state->getValue('jellomatrix_append_string_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build($scale) {
    return [
      '#markup' => $this->configuration['jellomatrix_intro_string'] . '<br>' . $this->configuration['jellomatrix_block_meat'] . '<br>' . $this->configuration['jellomatrix_append_string'],
    ];
  }


}
