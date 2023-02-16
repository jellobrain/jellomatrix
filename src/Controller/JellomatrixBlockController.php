<?php

namespace Drupal\jellomatrix\Controller;

use Drupal\jellomatrix\Utility\DescriptionTemplateTrait;

/**
 * Controller routines for block example routes.
 */
class JellomatrixBlockController {
  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'jellomatrix';
  }

}
