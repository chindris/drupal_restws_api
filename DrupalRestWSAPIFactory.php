<?php

namespace DrupalRestWSAPI;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface;

/**
 * @file
 *
 *  Factory class to created DrupalRestWSAPI objects.
 */

class DrupalRestWSAPIFactory {

  protected $map;

  /**
   * Private constructor, this is a singleton.
   */
  private function __construct() {
    // Register the default DrupalRestWSAPI class, which is the Drupal class.
    // @todo: think of a better way of registering the default class. It would
    // be nice to have this somehow configurable and independent of the factory
    // class which should suffer as fewer changes as possible in the future.
    $this->map['default'] = '\DrupalRestWSAPI\classes\drupalorg\DrupalOrgRestWSAPI';
  }

  /**
   * Returns an instance of the factory class, because the constuctor is
   * private.
   *
   * @return \DrupalRestWSAPI\DrupalRestWSAPIFactory
   */
  static function getInstance() {
    static $instance;
    if (!isset($instance)) {
      $instance = new DrupalRestWSAPIFactory();
    }
    return $instance;
  }

  /**
   * Registers a DrupalRestWSAPI class.
   *
   * @param string $id
   *  A unique id for the class.
   *
   *  If another class with the same id already exists in the map array, this
   *  will be replaced by the new one, specified in $class_name.
   *
   * @param string $class_name
   *  The full class name.
   */
  public function register($id, $class_name) {
    $this->map[$id] = $class_name;
  }

  /**
   * Returns an instance of a concrete DrupalRestWSAPI object.
   *
   * When the map contains more registered classed, and we do not specify a
   * concrete class, then we the last regsitered class will be used.
   *
   * The method can throw an Exception if there is no class for the specified
   * id, or if the class is not found.
   *
   * @param string $id
   *  The id of the class used to instantiate the object. If empty, the last
   *  registered id in the map will be used.
   *
   * @param array $params
   *  An array with parameters to be sent to the constructor, if needed. When
   *  the object is already created, it is not needed the second time when
   *  called, for the same $id, unless the $force_create is set to TRUE.
   *
   * @param bool $force_create
   *  boolean flag indicating if we should always instantiate the class. By
   *  default, we will keep a static cache of already instatiated object per id
   *  and we will return the cached one if possible.
   *
   * @return \DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface
   *  A concrete DrupalRestWSAPI object.
   */
  public function getRestWSAPiObject($id = '', $params = array(), $force_create = FALSE) {
    static $objects;
    $class_id = $id;
    if (empty($class_id)) {
      end($this->map);
      $class_id = key($this->map);
    }

    // Check to see if we can actually instantiate the class.
    if (empty($this->map[$class_id])) {
      // @todo: we should implement here an own Exception type..
      throw new  \Exception('There is no class mapped for ' . $class_id . '.');
    }
    if (!class_exists($this->map[$class_id])) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map[$class_id] . ' could not be found.');
    }

    // If we want to create a new one, we do it now, but we do not overwrite the
    // cache, unless the cache is empty.
    if (!empty($force_create)) {
      $instance = new $this->map[$class_id]($params);
      // Store the new instance in the cache, but only if the cache is empty.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = $instance;
      }
    }
    else {
      // If we are here we try to find an object in the cache and return it.
      // Otherwise we create on and store it in the cache.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = new $this->map[$class_id]($params);
      }
      $instance = $objects[$class_id];
    }

    // As a last check, we want to make sure that the object implements our
    // interface.
    if (!($instance instanceof DrupalRestWSAPIInterface)) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map[$class_id] . ' is not valid because it does not implement the \DrupalRestWSAPI\interfaces\DrupalRestWSInterface interface.');
    }

    // If we are here, we can finally return the instance.
    return $instance;
  }
}
