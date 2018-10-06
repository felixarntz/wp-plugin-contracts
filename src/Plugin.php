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
	 * Gets the plugin basename, which consists of the plugin directory name and main file name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin basename.
	 */
	public function basename() : string;

	/**
	 * Gets the absolute path for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Absolute path.
	 */
	public function path( string $relative_path = '/' ) : string;

	/**
	 * Gets the full URL for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Full URL.
	 */
	public function url( string $relative_path = '/' ) : string;

	/**
	 * Checks whether the plugin is active for a given scope.
	 *
	 * @since 1.0.0
	 *
	 * @param int $activation_scope Activation scope to check. See {@see Plugin_Activation_Scopes} for the available
	 *                              scopes.
	 * @return bool True if the plugin is active for the given scope, false otherwise.
	 */
	public function is_active_in_scope( int $activation_scope ) : bool;

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
