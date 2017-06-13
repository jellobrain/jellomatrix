<?php

namespace Drupal\jellomatrix\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @file
 * Contains \Drupal\jellomatrix\Form\JelloMatrixSidebar.
 */

class JelloMatrixSidebar extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jellomatrix_offset_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $current_path = \Drupal::service('path.current')->getPath();
    $result = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    $explode = explode('/', $result);
    $interval = $explode[3];
    $tone = $explode[2];

    $offsetrange = range(0, $tone-2);
    if ($explode[4] == 'offset' && isset($explode[5])) {
      $offset = $explode[5];
    } else {
      $offset = 0;
    }


    $preamble = '<p>' . $this->t('This tool takes two numbers and creates a matrix grid with them, and then performs all sorts of calculations including harmonics and derivative value shifts between numbers in their numerical contexts (topologies).') . '</p><p>' . $this->t('In addition and perhaps more specifically, this tool evaluates matrices spliced with inverse (upside down) copies of themselves, and looks for waveforms in the resulting numerical topologies with the following characteristics:') . '</p>';
    $list = '<ol><li>' . $this->t('Bands of numbers in the spliced matrix with equal values adjacent to one another...') . '</li><li>' . $this->t('which connect in predictable sine wave forms with one another...') . '</li><li>' . $this->t('following the order of a scale which is determined by the top row of values in the unspliced and native "seed" matrix...') . '</li><li>' . $this->t('rhythms that are even numbered change polarity at the crests of the waveforms,') . '</li><li>' . $this->t('and harmonically cycle between zero and infinity.') . '</li></ol>';
    $preform = '<p>' . $this->t('Aspects of that set of characteristics will appear even if the full pattern is not present in unison.') . '</p><p>' . $this->t('In addition, the patterns seem to continue to contain these inherent characheristics even when the two polar grids are spliced in a way that they are offset.') . '</p>';
    $postform = '<p>' . $this->t('Following the grid drawings will lead you through the story of how they are created, and enterring a value in the form to offset the grids will generate an offset grid.') . '</p>';

    $form['preamble'] = array (
      '#type' => 'markup',
      '#title' => t('Orientation'),
      '#markup' => $preamble . $list . $preform,
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
      '#default_value' => $offset,
    );
    if ($tone && is_numeric($tone)) {
      $form['tone'] = array(
        '#type' => 'hidden',
        '#value' => $tone,
      );
    }
    if ($interval && is_numeric($interval)) {
      $form['interval'] = array(
        '#type' => 'hidden',
        '#value' => $interval,
      );
    }
    $form['post'] = array (
      '#type' => 'markup',
      '#markup' => $postform,
      '#attached' => array(
        'library' => array(
          'jellomatrix/jellomatrix',
        ),
      ),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('offset', $form_state->getValue('offset'));
    $this->setConfigurationValue('interval', $form_state->getValue('offset'));
    $this->setConfigurationValue('tone', $form_state->getValue('offset'));
    $offset = $form_state->getValue('offset');
    $interval = $form_state->getValue('interval');
    $tone = $form_state->getValue('tone');
    $uri = 'jellomatrix/' . $tone . '/' . $interval . '/offset/' . $offset;
    $url = Url::fromUri('internal:/' . $uri);
    $form_state->setRedirectUrl($url);
  }
}
