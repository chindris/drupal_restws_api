<?php

/**
 * Base class for query builder.
 *
 */
namespace DrupalRestWSAPI\classes;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface;

class DrupalRestWSAPIUrlBuilder implements DrupalRestWSAPIUrlBuilderInterface {

  /**
   * An array with parameters of the URL:
   *  - scheme
   *  - host
   *  - port
   *  - path
   *  - path_prefix
   *  - query
   */
  protected $params;

  /**
   * Constructor for the URL builder class.
   */
  public function __construct($params = array()) {
    $params += array(
      'scheme' => 'http',
      'query' => array(),
    );
    $this->params = $params;
  }

	/**
	 * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface::getUrl()
   */
  public function getUrl() {
    $url_parts = $this->buildUrlParts();
    // @todo: check if the url should be encoded in any way.
    return implode('', $url_parts);
  }

  /**
   * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface::initializeEntityPath()
   */
  public function initializeEntityPath($entity_type, $entity_id) {
    // @todo: maybe we should make the '.json' part configurable.
    $this->params['path'] = $entity_type . '/'  . $entity_id . '.json';
  }

  /**
   * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface::initializeCommentsPath()
   */
  function initializeEntitiesPath($entity_path, $params = array()) {
    $this->params['path'] = $entity_path. '.json';
    $this->params['query'] = $params;
  }

  /**
   * Builds an array with the url parts for the current object.
   */
  protected function buildUrlParts() {
    $url_parts['scheme'] = $this->params['scheme'] . '://';
    $url_parts['host'] = $this->params['host'];
    if (!empty($this->params['port'])) {
      $url_parts['port'] = ':' . $this->params['port'] . '/';
    }
    else {
      $url_parts['host'] .= '/';
    }

    if (!empty($this->params['path_prefix'])) {
      $url_parts['path_prefix'] = $this->params['path_prefix'] . '/';
    }

    if (!empty($this->params['path'])) {
      $url_parts['path'] = $this->params['path'];
    }

    if (!empty($this->params['query'])) {
      $url_parts['query'] = '?' . http_build_query($this->params['query']);
    }
    return $url_parts;
  }

}
