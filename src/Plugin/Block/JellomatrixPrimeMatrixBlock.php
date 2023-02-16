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
 *   id = "jellomatrix_prime_matrix_block",
 *   admin_label = @Translation("Jellomatrix Prime Matrix Block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JellomatrixPrimeMatrixBlock extends BlockBase {

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
  public function defaultConfiguration($scale_increments, $prime_matrix, $primes, $tone, $interval, $scaled, $scales) {
    // Place block output here //
    $intro = '';
    $output = '';
    $append = '';
    
    $intro .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';

    $output .= '<div class="begintext">';

    if (isset($scale_increments)) {
      $output .= '<div class="floatright"><h3>You have scales!</h3></div>';
    }
    else {
      $output .= '<div class="floatright"><h3>Not scale active. Try again!</h3></div>';
    }
    $output .= '</div><hr class="hr"><br></div>';
    $output .= '<div class="begingrid"><h3>The Original Matrix</h3><div class="endtext"><br></div>';
    $output .= '<table class="table begingrid" cols="' . $tone . '" rows="' . $interval . '">';
    $totalcount = $tone*$interval;
    foreach($prime_matrix as $prime_row) {
      $output .= '<tr>';
      $count = 0;
      foreach($prime_row as $item) {
        if ($item['tone']%2 != 0) {
          $color = 'white';
        }
        if ($item['tone']%2 == 0) {
          $color = 'subhighlight';
        }
        foreach ($primes as $prime) {
          if ($item['tone'] == $prime) {
            $color = 'white';
              if ($item['tone']%2 == 0) {
                $color = 'highlight';
              }
          }
        }

        /*dpm($scales);*/
        if ($item['tone'] == $scales['h'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' blue-text">' . $item['tone'] . '</td>';
        }
        elseif ($item['tone'] == $scales['f'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' groen-text">' . $item['tone'] . '</td>';
        }
        elseif ($item['tone'] == $scales['b'][$count]) {
          $output .= '<td class="tdgrid ' . $color . ' salmon-text">' . $item['tone'] . '</td>';
        }
        else {
          $output .= '<td class="tdgrid ' . $color . ' red-text">' . $item['tone'] . '</td>';
        }
        $count++;
      }
      $output .= '</tr>';
    }
    $output .= '</table></div><p><br/></p><div class="endtext"><br></div>';

    $append .= '<div class="begintext"><p><h3>Scale Pattern:</h3></p>';
    $append .= '<p>Whether you look at each row individually, or look at each diagonal row (in forward or backward \'slash\' ';
    $append .= 'directions) you will notice that the order of numbers is consistent on every row (or each direction of diagonal rows) ';
    $append .= 'and that only the starting number differs from row to row.  I refer to this as a \'scale\'.  If the scale were ';
    $append .= 'to be played in a circle consisting of the numbers of the first \'tone\' value, the shape formed would be the ';
    $append .= 'same regardless of which number you start with.';
    $append .= '</p>';
    $append .= '<p><img src="/sites/default/files/h_circle.png?t='. time().'" />';
    $append .= '&nbsp;<img src="/sites/default/files/f_circle.png?t='. time().'" />';
    $append .= '&nbsp;<img src="/sites/default/files/b_circle.png?t='. time().'" /></p><div class="endtext"><br></div></div>';
    $append .= '<p><strong>' . $scaled . '...</strong></p><hr class="hr"><p><br/></p><div class="endtext"><br></div>';
    $append .= '<div class="begintext"><p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Jellomatrix</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://www.jellobrain.com" property="cc:attributionName" rel="cc:attributionURL">Ana Willem</a> is licensed since 2007 under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://www.jellobrain.com" rel="dct:source">https://www.jellobrain.com</a>.</p></div><hr class="hr">';

    
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
      '#default_value' => $this->configuration['jellomatrix_intro_string'],
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
