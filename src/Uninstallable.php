<?php
/**
 * Interface Felix_Arntz\WP_Plugin_Contracts\Uninstallable
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

/**
 * Interface for a WordPress plugin that can be uninstalled.
 *
 * @since 1.0.0
 */
interface Uninstallable {

	/**
	 * Runs the uninstallation routine.
	 *
	 * @since 1.0.0
	 */
	public function uninstall();
}
