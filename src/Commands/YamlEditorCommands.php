<?php

namespace Drupal\yaml_editor\Commands;

use Drush\Commands\DrushCommands;
use Symfony\Component\Finder\Finder;
use Drupal\Core\Serialization\Yaml;


/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class YamlEditorCommands extends DrushCommands {

  /**
   * Command description here.
   *
   * @param $arg1
   *  Path to find yaml.
   * @param $arg2
   *  key to seach in webform.
   * @param $arg3
   * Value to replace.
   * @param $arg4
   * key to seach in elements.
   * @param $arg5
   * Path to replace directories
   * @param array $options
   *   An associative array of options whose values come from cli, aliases, config, etc.
   * @option option-name
   *   Description
   * @usage yaml_editor-replaceElementsFields foo
   *   Usage description
   *
   * @command yaml_editor:replaceElementsFields
   * @aliases foo
   */
  public function replaceElementsFields($arg1, $arg2, $arg3, $arg4, $arg5 ,$options = ['option-name' => 'default']) {
    // Verify if path exists
    if (!file_exists($arg1)) {
      $this->logger()->error(dt('Path does not exist.'));
      return;
    }

    if (!file_exists($arg5)) {
      $this->logger()->error(dt('Path to replace does not exist.'));
      return;
    }

    $finder = new Finder();
    $files = $finder->files()->in($arg1);
    foreach ($files as $file) {

      if ($file->getExtension() !== 'yml' || \preg_match('/^webform\.webform\./', $file->getFilename()) === 0) {
        continue;
      }
      $yamlData = Yaml::decode(file_get_contents($file->getRealPath()));
      foreach ($yamlData as $key => $value) {
        if ($key == "elements") {
            // The value is another yaml
            $elementYaml = Yaml::decode($value);
            self::searchMultiArray($arg4, $elementYaml, $arg2, $arg3);
            $yamlData[$key] = Yaml::encode($elementYaml);
        }
      }
      $newFile = $arg5 . "/" . $file->getFilename();
      file_put_contents($newFile , Yaml::encode($yamlData));
      $this->logger()->success(dt('Yamls ' . $newFile . ' replaced.'));
    }

    $this->logger()->success(dt('Yamls replaced.'));
  }

  private static function searchMultiArray($needle, &$haystack, $keyWebform, $valueWebform) {
    foreach ($haystack as $key => &$value) {
      if ($key === $needle) {
        if (empty($valueWebform) || $valueWebform == '-') { // Empty values in bash are passed as '-' so we need to check for that
          unset($value[$keyWebform]);
        }
        else {
          $value[$keyWebform] = $valueWebform;
        }
        return $value;

      } elseif (is_array($value)) {
        $result = self::searchMultiArray($needle, $value, $keyWebform, $valueWebform);
        if ($result !== false) {
          return $result;
        }
      }
    }
    return false;
  }

}
