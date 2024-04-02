# Readme

## Drupal module to replace configuration elements in webforms

- The purpose of the module is essentially to utilize Drupal\Core\Serialization\Yaml for reading and replacing YAML nested within other YAML in a webform, and replacing configurations

```
drush yaml_editor:replaceElementsFields "/var/www/html/web/sites/default/files/sync/" "#options" "-" "civicrm_1_membership_1_membership_custom_91" "/var/www/html/web/sites/default/files/sync/"
```
