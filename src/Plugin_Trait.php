<?php
/**
 * Trait Felix_Arntz\WP_Plugin_Contracts\Plugin_Trait
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

/**
 * Trait for a WordPress plugin.
 *
 * @since 1.0.0
 */
trait Plugin_Trait {

	/**
	 * Absolute path to the plugin main file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $main_file;

	/**
	 * Internal storage of bitwise flags for the activation scopes covered.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $activation_scopes = 0;

	/**
	 * Gets the plugin basename, which consists of the plugin directory name and main file name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin basename.
	 */
	public function basename() : string {
		return plugin_basename( $this->main_file );
	}

	/**
	 * Gets the absolute path for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Absolute path.
	 */
	public function path( string $relative_path = '/' ) : string {
		return plugin_dir_path( $this->main_file ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Gets the full URL for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Full URL.
	 */
	public function url( string $relative_path = '/' ) : string {
		return plugin_dir_url( $this->main_file ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Checks whether the plugin is active for a given scope.
	 *
	 * @since 1.0.0
	 *
	 * @param int $activation_scope Activation scope to check. See {@see Plugin_Activation_Scopes} for the available
	 *                              scopes.
	 * @return bool True if the plugin is active for the given scope, false otherwise.
	 */
	public function is_active_in_scope( int $activation_scope ) : bool {
		if ( empty( $this->activation_scopes ) ) {
			$this->activation_scopes = $this->detect_activation_scopes();
		}

		return ( $this->activation_scopes & $activation_scope ) === $activation_scope;
	}

	/**
	 * Detects the scopes for which the plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @return int Bitwise flags for the activation scopes covered.
	 */
	protected function detect_activation_scopes() : int {
		$scopes = Plugin_Activation_Scopes::SITE;

		if ( ! is_multisite() ) {
			return $scopes;
		}

		if ( 0 === strpos( wp_normalize_path( $this->main_file ), wp_normalize_path( WPMU_PLUGIN_DIR ) ) ) {
			$scopes |= Plugin_Activation_Scopes::NETWORK;
			$scopes |= Plugin_Activation_Scopes::SETUP;

			return $scopes;
		}

		$network_active_plugins = wp_get_active_network_plugins();
		if ( in_array( WP_PLUGIN_DIR . '/' . $this->basename(), $network_active_plugins, true ) ) {
			$scopes |= Plugin_Activation_Scopes::NETWORK;
		}

		return $scopes;
	}

	/**
	 * Sets the plugin main file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 */
	protected function set_main_file( string $main_file ) {
		$this->main_file = $main_file;
	}

	/**
	 * Retrieves the main instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin Plugin main instance.
	 */
	public static function instance() : self {
		return Plugin_Container::instance()->get( get_called_class() );
	}

	/**
	 * Loads the plugin main instance and initializes it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 */
	public static function load( string $main_file ) : bool {
		return Plugin_Container::instance()->load( $main_file, get_called_class() );
	}
}
