<?php

namespace Drupal\jellomatrix\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
/**
 * Provides a 'jellomatrix' block.
 *
 * @Block(
 *   id = "jellomatrix_sidebar_block",
 *   admin_label = @Translation("JelloMatrix block"),
 *   category = @Translation("JelloMatrix block")
 * )
 */
class JelloMatrixSidebarBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\jellomatrix\Form\JelloMatrixSidebar');
    return $form;
   }
}
