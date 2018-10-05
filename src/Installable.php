<?php
/**
 * Interface Felix_Arntz\WP_Plugin_Contracts\Installable
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

/**
 * Interface for a WordPress plugin that can be installed.
 *
 * @since 1.0.0
 */
interface Installable {

	/**
	 * Runs the installation routine.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide Optional. Whether the installation routine should run for the entire network.
	 */
	public function install( bool $network_wide = false );
}
