<?php

namespace Drupal\yaml_editor\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for yaml_editor routes.
 */
class YamlEditorController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
