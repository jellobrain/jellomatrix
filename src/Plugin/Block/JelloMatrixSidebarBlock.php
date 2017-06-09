<?php

namespace Drupal\jellomatrix\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides blocks for the JelloMatrix.
 *
 * @Block(
 *   id = "jellomatrix_sidebar_block",
 *   admin_label = @Translation("JelloMatrix Sidebar block"),
 * )
 */

class JelloMatrixSidebarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    
    $preamble = '<p>' . $this->t('This tool takes two numbers and creates a matrix grid with them, and then performs all sorts of calculations including harmonics and derivative value shifts between numbers in their numerical contexts (topologies).') . '</p><p>' . $this->t('In addition and perhaps more specifically, this tool evaluates matrices spliced with inverse (upside down) copies of themselves, and looks for waveforms in the resulting numerical topologies with the following characteristics:') . '</p>';
    $list = '<ol><li>' . $this->t('Bands of numbers in the spliced matrix with equal values adjacent to one another...') . '</li><li>' . $this->t('which connect in predictable sine wave forms with one another...') . '</li><li>' . $this->t('following the order of a scale which is determined by the top row of values in the unspliced and native "seed" matrix...') . '</li><li>' . $this->t('rhythms that are even numbered change polarity at the crests of the waveforms, rhythms that are odd numbered change polarity at every step...') . '</li><li>' . $this->t('and harmonically cycling between zero and infinity.') . '</li></ol>';
    $preform = '<p>' . $this->t('Aspects of that set of characteristics will appear even if the full pattern is not present in unison.') . '</p><p>' . $this->t('In addition, the patterns seem to continue to contain these inherent characheristics even when the two polar grids are spliced in a way that they are offset.') . '</p>';
    $postform = '<p>' . $this->t('Following the grid drawings will lead you through the story of how they are created, and enterring a value in the form to offset the grids will generate an offset grid.') . '</p>';

    return array(
      '#markup' => $preamble . $list . $preform . $postform,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    return $account->hasPermission('access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    if (arg(3) && is_numeric(arg(3))) {
      $offsetrange = range(0,arg(3)-2);
      $interval = arg(3);
    }
    elseif (isset($config['interval']) && is_numeric($config['interval'])) {
      $offsetrange = range(0,$config['interval']-2);
      $interval = $config['interval'];
    }
    if (arg(2) && is_numeric(arg(2))) {
      $tone = arg(2);
    }
    elseif (isset($config['tone']) && is_numeric($config['tone'])) {
      $tone = config['tone'];
    }

    if (arg(5) && is_numeric(arg(5))) {
      $offset = arg(5);
    }
    elseif (isset($config['offset']) && is_numeric($config['offset'])) {
      $offset = $config['offset'];
    }
    else {
      $offset = 0;
    }

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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
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
