<?php
/**
 * Class Felix_Arntz\WP_Plugin_Contracts\Exception\Plugin_Loading_Exception
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts\Exception;

use Psr\Container\ContainerExceptionInterface;
use Exception;

/**
 * Exception thrown when loading a plugin fails.
 *
 * @since 1.0.0
 */
class Plugin_Loading_Exception extends Exception implements ContainerExceptionInterface {

}
