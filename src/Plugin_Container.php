<?php
/**
 * Class Felix_Arntz\WP_Plugin_Contracts\Plugin_Container
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

use Felix_Arntz\WP_Plugin_Contracts\Exception\Plugin_Loading_Exception;
use Felix_Arntz\WP_Plugin_Contracts\Exception\Plugin_Not_Found_Exception;
use Felix_Arntz\WP_Admin_Notices\Admin_Notice_Factory;
use Felix_Arntz\WP_Admin_Notices\Admin_Notice_Types;
use Psr\Container\ContainerInterface as Container;

/**
 * Container class managing WordPress plugins.
 *
 * @since 1.0.0
 */
class Plugin_Container implements Container {

	/**
	 * Plugin instances, keyed by their basename.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $plugins = [];

	/**
	 * Plugin exception instances, keyed by the plugin basename.
	 *
	 * This is for plugins where loading them caused an exception to be thrown.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $plugin_exceptions = [];

	/**
	 * Map of plugin classes and plugin basenames.
	 *
	 * This internal storage allows retrieving plugin instances via their class name.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $class_basename_map = [];

	/**
	 * Main plugin container instance.
	 *
	 * @since 1.0.0
	 * @var Plugin_Container|null
	 */
	private static $instance = null;

	/**
	 * Finds a plugin by its basename and returns its instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $basename Plugin basename, or class name.
	 * @return Plugin The plugin instance.
	 *
	 * @throws Plugin_Not_Found_Exception Thrown if the plugin was not found.
	 * @throws Plugin_Loading_Exception   Thrown if an error occurred while loading the plugin.
	 */
	public function get( $basename ) {
		// Check if $basename is actually a class name.
		if ( isset( $this->class_basename_map[ $basename ] ) ) {
			$basename = $this->class_basename_map[ $basename ];
		}

		if ( isset( $this->plugins[ $basename ] ) ) {
			return $this->plugins[ $basename ];
		}

		if ( isset( $this->plugin_exceptions[ $basename ] ) ) {
			throw new Plugin_Loading_Exception( $this->plugin_exceptions[ $basename ]->getMessage() );
		}

		throw new Plugin_Not_Found_Exception( 'Plugin has not been found.' );
	}

	/**
	 * Determines whether a plugin of a given basename is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $basename Plugin basename, or class name.
	 * @return bool True if the plugin is available, false otherwise.
	 */
	public function has( $basename ) {
		// Check if $basename is actually a class name.
		if ( isset( $this->class_basename_map[ $basename ] ) ) {
			$basename = $this->class_basename_map[ $basename ];
		}

		return isset( $this->plugins[ $basename ] );
	}

	/**
	 * Loads the instance for a given plugin and initializes it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file  Absolute path to the plugin main file.
	 * @param string $class_name Plugin main class name.
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 */
	public function load( string $main_file, string $class_name ) {
		$basename = plugin_basename( $main_file );

		if ( isset( $this->plugins[ $basename ] ) ) {
			return true;
		}

		if ( isset( $this->plugin_exceptions[ $basename ] ) ) {
			return false;
		}

		try {
			$this->plugins[ $basename ]              = $this->load_plugin_instance( $main_file, $class_name );
			$this->class_basename_map[ $class_name ] = $this->plugins[ $basename ]->basename();
		} catch ( Plugin_Loading_Exception $e ) {
			$this->plugin_exceptions[ $basename ]    = $e;
			$this->class_basename_map[ $class_name ] = $basename;

			( new Admin_Notice_Factory() )
				->create_notice( $e->getMessage(), Admin_Notice_Types::ERROR )
				->register();

			return false;
		}

		$this->class_basename_map[ $class_name ] = $this->plugins[ $basename ]->basename();
		$this->register_plugin_instance( $main_file, $this->plugins[ $basename ] );

		return true;
	}

	/**
	 * Loads the instance for a given plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file  Absolute path to the plugin main file.
	 * @param string $class_name Plugin main class name.
	 * @return Plugin Plugin instance.
	 *
	 * @throws Plugin_Loading_Exception Thrown if loading the plugin failed.
	 */
	protected function load_plugin_instance( string $main_file, string $class_name ) : Plugin {
		if ( ! class_exists( $class_name ) ) {
			throw new Plugin_Loading_Exception(
				sprintf(
					'Plugin class %s does not exist.',
					$class_name
				)
			);
		}

		if ( ! in_array( Plugin::class, class_implements( $class_name ), true ) ) {
			throw new Plugin_Loading_Exception(
				sprintf(
					'Plugin class %1$s does not implement the required %2$s interface.',
					$class_name,
					Plugin::class
				)
			);
		}

		if ( isset( $this->class_basename_map[ $class_name ] ) ) {
			throw new Plugin_Loading_Exception(
				sprintf(
					'Plugin class %s cannot be used for multiple plugins.',
					$class_name
				)
			);
		}

		return new $class_name( $main_file );
	}

	/**
	 * Registers a plugin instance with WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 * @param Plugin $instance  Plugin instance.
	 */
	protected function register_plugin_instance( string $main_file, Plugin $instance ) {
		if ( $instance instanceof Installable ) {
			register_activation_hook( $main_file, [ $instance, 'install' ] );
		}
		if ( $instance instanceof Activatable ) {
			register_activation_hook( $main_file, [ $instance, 'activate' ] );
		}
		if ( $instance instanceof Deactivatable ) {
			register_deactivation_hook( $main_file, [ $instance, 'deactivate' ] );
		}
		if ( $instance instanceof Uninstallable ) {
			register_uninstall_hook( $main_file, [ $instance, 'uninstall' ] );
		}

		$instance->register();
	}

	/**
	 * Retrieves the main instance of the plugin container.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin_Container Plugin container main instance.
	 */
	public static function instance() : self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
