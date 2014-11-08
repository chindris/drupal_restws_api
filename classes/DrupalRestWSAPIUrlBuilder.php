<?php

/**
 * Base class for query builder.
 *
 */
namespace DrupalRestWSAPI\classes;

use DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface;

class DrupalRestWSAPIUrlBuilder implements DrupalRestWSAPIUrlBuilderInterface {

  /**
   * @var string
   */
  protected $scheme;

  /**
   * @var string
   */
  protected $host;

  /**
   * @var string
   */
  protected $port;

  /**
   * @var string
   */
  protected $path;

  /**
   * @var array
   */
  protected $query;

  /**
   * Constructor for the URL builder class.
   */
  public function __construct($host, $path, $query= array(), $scheme = 'http', $port = NULL) {
    $this->host = $host;
    $this->path = $path;
    $this->query = $query;
    $this->scheme = $scheme;
    $this->port = $port;
  }

	/**
	 * (non-PHPdoc)
   * @see \DrupalRestWSAPI\interfaces\DrupalRestWSAPIUrlBuilderInterface::getUrl()
   */
  public function getUrl() {
    $url_parts[] = $this->scheme . '://';
    $url_parts[] = $this->host;
    if (!empty($this->port)) {
      $url_parts[] = ':' . $this->port . '/';
    }
    $url_parts[] = $this->path;
    if (!empty($this->query)) {
      $url_parts[] = '?' . http_build_query($this->query);
    }
    // @todo: check if the url should be encoded in any way.
    return implode('', $url_parts);
  }

}
