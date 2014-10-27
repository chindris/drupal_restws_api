<?php

namespace DrupalRestWSAPI\interfaces;

interface DrupalRestWSAPIResultParserInterface {

  /**
   * Parses the $data string and returns the result in a format we can easly
   * handle (for example array or object).
   *
   * @param string $data
   */
  public function parse($data);
}