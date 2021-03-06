<?php

namespace DrupalRestWSAPI\classes\factories;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface;

/**
 * @file
 *
 *  Factory class to created DrupalRestWSAPI objects.
 *  @todo: We need to change this class so that is able to create more than one
 *  type of objects.
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
    $this->map['rest_ws_class']['default'] = '\DrupalRestWSAPI\classes\DrupalRestWSAPI';
    $this->map['url_builder']['default'] = '\DrupalRestWSAPI\classes\DrupalRestWSAPIUrlBuilder';
    // The default parser class is JSON.
    $this->map['result_parser']['default'] = '\DrupalRestWSAPI\classes\DrupalRestWSAPIResultParserJson';
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
   * @param string $type
   *  The type of the object. Available values: 'rest_ws_class', 'url_builder'
   *  and 'result_parser'.
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
  public function register($type, $id, $class_name) {
    $this->map[$type][$id] = $class_name;
  }

  /**
   * Returns a the id to be used for a specific class type to be instantiated.
   *
   * @param string $type
   *  The type of the class. Available values: 'rest_ws_class', 'url_builder'
   *  and 'result_parser'.
   *
   * @param string $id
   *  A unique id for the class.
   *
   * @throws \Exception
   */
  public function getRestWSClassIdForType($type, $id = '') {
    if (empty($this->map[$type])) {
      throw new  \Exception('There are no classes mapped for the ' . $type . ' type.');
    }
    $class_id = $id;
    if (empty($class_id)) {
      end($this->map[$type]);
      $class_id = key($this->map[$type]);
    }

    // Check to see if we can actually instantiate the class.
    if (empty($this->map[$type][$class_id])) {
      // @todo: we should implement here an own Exception type..
      throw new  \Exception('There is no class mapped for ' . $class_id . ' of type ' . $type . '.');
    }
    if (!class_exists($this->map[$type][$class_id])) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map[$class_id] . ' could not be found.');
    }
    return $class_id;
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
  public function getRestWSAPiObject(DrupalRestWSAPIUrlBuilderInterface $url_builder, DrupalRestWSAPIResultParserInterface $result_parser, $id = '', $force_create = FALSE) {
    static $objects;
    $class_id = $this->getRestWSClassIdForType('rest_ws_class', $id);
    // If we want to create a new one, we do it now, but we do not overwrite the
    // cache, unless the cache is empty.
    if (!empty($force_create)) {
      $instance = new $this->map['rest_ws_class'][$class_id]($url_builder, $result_parser);
      // Store the new instance in the cache, but only if the cache is empty.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = $instance;
      }
    }
    else {
      // If we are here we try to find an object in the cache and return it.
      // Otherwise we create on and store it in the cache.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = new $this->map['rest_ws_class'][$class_id]($url_builder, $result_parser);
      }
      $instance = $objects[$class_id];
    }

    // As a last check, we want to make sure that the object implements our
    // interface.
    if (!($instance instanceof DrupalRestWSAPIInterface)) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map['rest_ws_class'][$class_id] . ' is not valid because it does not implement the \DrupalRestWSAPI\interfaces\DrupalRestWSInterface interface.');
    }

    // If we are here, we can finally return the instance.
    return $instance;
  }

  /**
   * @todo: THIS HAS TO BE CHANGED SOMEHOW, IT IS DUPLICATED CODE WITH THE CODE
   * ABOVE.
   */
  public function getRestWSUrlBuilder($id = '', $params = array(), $force_create = FALSE) {
    static $objects;
    $class_id = $this->getRestWSClassIdForType('url_builder', $id);
    // If we want to create a new one, we do it now, but we do not overwrite the
    // cache, unless the cache is empty.
    if (!empty($force_create)) {
      $instance = new $this->map['url_builder'][$class_id]($params);
      // Store the new instance in the cache, but only if the cache is empty.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = $instance;
      }
    }
    else {
      // If we are here we try to find an object in the cache and return it.
      // Otherwise we create on and store it in the cache.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = new $this->map['url_builder'][$class_id]($params);
      }
      $instance = $objects[$class_id];
    }

    // As a last check, we want to make sure that the object implements our
    // interface.
    if (!($instance instanceof DrupalRestWSAPIUrlBuilderInterface)) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map['url_builder'][$class_id] . ' is not valid because it does not implement the \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface interface.');
    }

    // If we are here, we can finally return the instance.
    return $instance;
  }

  /**
   * @todo: THIS HAS TO BE CHANGED SOMEHOW, IT IS DUPLICATED CODE WITH THE CODE
   * ABOVE.
   */
  public function getRestWSResultParser($id = '', $force_create = FALSE) {
    static $objects;
    $class_id = $this->getRestWSClassIdForType('result_parser', $id);
    // If we want to create a new one, we do it now, but we do not overwrite the
    // cache, unless the cache is empty.
    if (!empty($force_create)) {
      $instance = new $this->map['result_parser'][$class_id]();
      // Store the new instance in the cache, but only if the cache is empty.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = $instance;
      }
    }
    else {
      // If we are here we try to find an object in the cache and return it.
      // Otherwise we create on and store it in the cache.
      if (empty($objects[$class_id])) {
        $objects[$class_id] = new $this->map['result_parser'][$class_id]();
      }
      $instance = $objects[$class_id];
    }

    // As a last check, we want to make sure that the object implements our
    // interface.
    if (!($instance instanceof DrupalRestWSAPIResultParserInterface)) {
      // @todo: we should implement here an own ClassNotFoundException.
      throw new  \Exception('The class ' . $this->map['result_parser'][$class_id] . ' is not valid because it does not implement the \DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface interface.');
    }

    // If we are here, we can finally return the instance.
    return $instance;
  }
}
