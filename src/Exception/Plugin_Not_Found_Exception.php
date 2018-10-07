<?php
/**
 * Class Felix_Arntz\WP_Plugin_Contracts\Exception\Plugin_Not_Found_Exception
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * Exception thrown when a plugin cannot be found.
 *
 * @since 1.0.0
 */
class Plugin_Not_Found_Exception extends Exception implements NotFoundExceptionInterface {

}
