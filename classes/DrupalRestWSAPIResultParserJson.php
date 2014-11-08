<?php

/**
 * @file
 *  Json parser for Drupal RestWS API results.
 */
namespace DrupalRestWSAPI\classes;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface;

class DrupalRestWSAPIResultParserJson implements DrupalRestWSAPIResultParserInterface {

	/** (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIResultParserInterface::parse()
   */
  public function parse($data) {
    return json_decode($data);
  }
}
