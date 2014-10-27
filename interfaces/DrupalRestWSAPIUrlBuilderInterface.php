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
}
