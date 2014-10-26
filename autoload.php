<?php

/**
 * @file
 *  Defines and registers an autoloader for the library.
 */

function drupal_restws_api_loader($class_name) {
  // We remove the 'DrupalRestWSAPI\' prefix from the class name and replace the
  // backslashes with the directory separators.
  $class_path = str_replace('\\', DIRECTORY_SEPARATOR, substr_replace($class_name, '', 0, 16));
  include_once $class_path . '.php';
}

spl_autoload_register('drupal_restws_api_loader');
