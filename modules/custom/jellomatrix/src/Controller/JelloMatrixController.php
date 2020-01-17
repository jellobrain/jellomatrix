<?php
/**
 * @file
 * Contains \Drupal\jellomatrix\Controller\JelloMatrixController.
 */
namespace Drupal\jellomatrix\Controller;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Controller\ControllerBase;

class JelloMatrixController extends ControllerBase {
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Welcome to the JelloMatrix Admin'),
    );
  }
}
