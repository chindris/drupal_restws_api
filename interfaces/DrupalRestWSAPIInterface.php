<?php

namespace DrupalRestWSAPI\interfaces;

/**
 * @file
 *
 * @todo: provide a description.
 */

interface DrupalRestWSAPIInterface {

  /**
   * Makes a request to the service URL.
   */
  public function request();

  /**
   * Makes a request to get a specific entity.
   *
   * @param string $entity_type
   *  The machine name of the entity type.
   *
   * @param int $entity_id
   *  The value of the entity id.
   */
  public function getEntity($entity_type, $entity_id);

  /**
   * Returns a list with the entities.
   *
   * @param string $entity_type
   *  The machine name of the entity type.
   *
   * @param array $params
   *  An array with parameters to filter the comments.
   */
  public function getEntities($entity_type, $params);
}
