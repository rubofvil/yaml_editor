<?php

namespace Drupal\yaml_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "yaml_editor_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("yaml_editor")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
