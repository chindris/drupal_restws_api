<?php

/**
 * Interface for building URLs.
 */

namespace DrupalRestWSAPI\interfaces;

interface DrupalRestWSAPIUrlBuilderInterface {

  /**
   * Returns the final URL.
   */
  public function getUrl();

  /**
   * Initializes the internal path for requesting a single entity.
   *
   * @param string $entity_type
   *  The machine name of the entity type.
   *
   * @param int $entity_id
   *  The value of the entity id.
   */
  public function initializeEntityPath($entity_type, $entity_id);

  /**
   * Initializes the interal path for requesting a list of entities.
   *
   * @param string $entity_type
   *  The machine name of the entity type.
   *
   * @param array $params
   *  An array with query parameters.
   */
  public function initializeEntitiesPath($entity_path, $params);
}
