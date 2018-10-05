<?php
/**
 * Interface Felix_Arntz\WP_Plugin_Contracts\Plugin
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

use Felix_Arntz\WP_Plugin_Contracts\Exception\Plugin_Loading_Exception;
use FelixArntz\Contracts\Registerable;

/**
 * Interface for a WordPress plugin.
 *
 * @since 1.0.0
 */
interface Plugin extends Registerable {

	/**
	 * Retrieves the main instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return self Plugin main instance.
	 *
	 * @throws Plugin_Loading_Exception Thrown when the plugin could not be loaded.
	 */
	public static function instance() : self;

	/**
	 * Loads the plugin main instance and initializes it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 */
	public static function load( string $main_file ) : bool;
}
