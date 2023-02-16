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
 *   id = "jellomatrix_spliced_matrix_scale_pattern_block",
 *   admin_label = @Translation("Jellomatrix Spliced Matrix Scale Pattern Block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JellomatrixSplicedMatrixScalePatternBlock extends BlockBase {

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
  public function defaultConfiguration($scale_increments, $scaled, $primes, $tone, $interval) {
    // Place block output here //
    $intro = '';
    $output = '';
    $append = '';
    
    if (isset($scaled)) {
      $output .= '<div class="begintext"><p><h3>Scale Pattern:</h3></p>';
      //$output .= '<p>Whether you look at each row individually, or look at each diagonal row (in forward or backward \'slash\' ';
      //$output .= 'directions) you will notice that the order of numbers is consistent on every row (or each direction of diagonal rows) ';
      //$output .= 'and that only the starting number differs from row to row.  I refer to this as a \'scale\'.  If the scale were ';
      //$output .= 'to be played in a circle consisting of the numbers of the first \'tone\' value, the shape formed would be the ';
      //$output .= 'same regardless of which number you start with.';
      //$output .= '</p>';
      //$output .= '<p><img src="/sites/default/files/h_circle.png?t='. time().'" />';
      //$output .= '&nbsp;<img src="/sites/default/files/f_circle.png?t='. time().'" />';
      //$output .= '&nbsp;<img src="/sites/default/files/b_circle.png?t='. time().'" /></p><div class="endtext"><br></div>';
      //$output .= '<div class="begintext"><p><h3>Experimental Pattern:</h3></p><p><img src="/sites/default/files/circle_grid.png?t='. time().'" /></p><div class="endtext"><br></div>';
      //$output .= '<p><strong>' . $scaled . '...</strong></p><div class="endtext"><br></div>';
      $intro .= '<p><strong>This tool is meant as a proof of concept and not as a complete set of waveforms that are possible (although I am working on it!).</strong></p><div class="endtext"><br></div>';
      $intro .= '<p><strong>RED</strong> = Start of wave.</p>';
      $output .= '<p><strong>EVEN Waves</strong></p>';
      if (isset($scale_increments)) {
        foreach ($scale_increments as $i=>$increment) {
          $explode = explode(':', $increment);
          $t = $explode[0];
          $jump = $explode[1];
          $direction = $explode[2];
          $scale_direction = $explode[3];
          $color = $explode[4];
          if ($jump %2 == 0) {
            $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
          }
          if ($jump %2 != 0) {
            $odd_waves = 1;
          }
        }
      }
      if (isset($odd_waves)){
        $output .= '<p><strong>ODD Waves</strong></p>';
      }
      unset($odd_waves);
      if (isset($scale_increments)) {
        foreach ($scale_increments as $i=>$increment) {
          $explode = explode(':', $increment);
          $t = $explode[0];
          $jump = $explode[1];
          $direction = $explode[2];
          $scale_direction = $explode[3];
          $color = $explode[4];
          if ($jump %2 != 0) {
            $output .= '<p><strong>Starting ' . $t . ':</strong> scale direction = ' . $scale_direction . ', rhythm = ' . $jump . ', initial vertical = ' . $direction . ', color = ' . $color . '.</p>';
          }
        }
      }
    }
    $output .= '<p></p><br></div>';
    
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
