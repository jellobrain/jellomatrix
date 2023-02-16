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
 *   id = "jellomatrix_spliced_matrix_even_odd_block",
 *   admin_label = @Translation("Jellomatrix Spliced Matrix Even Odd Block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JellomatrixSplicedMatrixEvenOddBlock extends BlockBase {

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
  public function defaultConfiguration($spliced_matrix, $tone, $interval) {
    // Place block output here //
    $intro = '';
    $output = '';
    $append = '';

    // And then we create the spliced matrix grid.
    $output .= '<div class="begintext endtable"></div><div class="begingrid"><h3>HIGHLIGHTING EVEN+ODD: The Spliced Matrix</h3><table class="table begingrid" cols="' . $tone*2 . '" rows="' . $interval . '">';
    for ($i = 1; $i <= $interval; $i++) {
      $output .= '<tr>';
      $count = 1;
      foreach ($spliced_matrix as $spliced_row) {
        foreach ($spliced_row as $item) {
          if ($item['row'] == $i) {
            $prime = jellomatrix_primes($tone);
            if (($item['column'])%2 == 0) {
              $item['color'] = 'green-text';
            }
            if (($item['column'])%2 != 0) {
              $item['color'] = 'red-text';
            }
            if (($item['tone'])%2 == 0) {
              $item['background'] = 'white';
              $item['opacity'] = '.' . $item['tone'];
            }
            if (($item['tone'])%2 != 0) {
              $item['background'] = 'highlight';
              $item['opacity'] = '.' . $item['tone'];
            }
            $output .= '<td class="' . $item['column'] . 'x-' . $item['row'] . 'y ' . $item['color'] . ' tdgrid  ' . $item['background'] . '">' . $item['tone'] . '</td>';
            $count++;
          }
        }
      }
      $output .= '</tr>';
    }
    $output .= '</table>';
    $append .= '<div class="hr begintext"><p>Interstingly enough, the sections which seem to hold information about the vortec/ies they reflect seem to fall most often in the middle of the sine waves created by what appear to be very different "environments" or "gradients" between higher frequency oscillations of even and odd numbers (you might need to squint your eyes to see them), They are the waves defined by the more or less frequent oscillatory patterns taken as a whole.  More about this in the "Rows" calculations in the "Increments" section below.</p><hr class="hr"></div></div>';

    
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
