<?php
/**
 * Interface Felix_Arntz\WP_Plugin_Contracts\Deactivatable
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

/**
 * Interface for a WordPress plugin that can be deactivated.
 *
 * @since 1.0.0
 */
interface Deactivatable {

	/**
	 * Runs the deactivation routine.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide Optional. Whether the deactivation routine should run for the entire network.
	 */
	public function activate( bool $network_wide = false );
}
