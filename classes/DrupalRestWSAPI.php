<?php

/**
 * @file
 *
 *  Base class for the DrupalRestWSAPI classes.
 */

namespace DrupalRestWSAPI\classes;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface;

class DrupalRestWSAPI implements DrupalRestWSAPIInterface {

  /**
   * @var \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface
   * A query builder object.
   */
  protected $query_builder;

  /**
   * @var \DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface
   * A parser object.
   */
  protected $parser;

  /**
   * Class constructor.
   */
  public function __construct(DrupalRestWSAPIUrlBuilderInterface $query_builder, DrupalRestWSAPIResultParserInterface $parser) {
    $this->query_builder = $query_builder;
    $this->parser = $parser;
  }

  /**
   * Make a request and returns a response.
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface::request()
   */
  public function request() {
    // The basic flow: build the url, make the request, parse and return the
    // output.
    $url = $this->query_builder->getUrl();

    // @todo: does this make sense to be in a separate class? Anyway, this shoudl
    // be somehow refactored because it is hard to replace or overwrite it.
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    // @todo: very important: check the curl errors here.

    return $this->parser->parse($response);
  }

  /**
   * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface::getEntity()
   */
  public function getEntity($entity_type, $entity_id) {
    $this->query_builder->initializeEntityPath($entity_type, $entity_id);
    return $this->request();
  }

  /**
   * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIInterface::getComments()
   */
  public function getEntities($entity_type, $params = array()) {
    $this->query_builder->initializeEntitiesPath($entity_type, $params);
    return $this->request();
  }
}
