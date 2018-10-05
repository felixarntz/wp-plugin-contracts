<?php
/**
 * Trait Felix_Arntz\WP_Plugin_Contracts\Multisite_Aware_Trait
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

/**
 * Trait for a class that is aware of WordPress Multisite.
 *
 * @since 1.0.0
 */
trait Multisite_Aware_Trait {

	/**
	 * Executes a callback for all sites in the current network.
	 *
	 * If not a multisite, the callback is executed once for the only site.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $callback Callback to execute.
	 */
	protected function for_network( callable $callback ) {
		$this->for_sites( $this->get_network_site_ids(), $callback );
	}

	/**
	 * Executes a callback for all sites in the entire setup.
	 *
	 * If not a multisite, the callback is executed once for the only site.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $callback Callback to execute.
	 */
	protected function for_setup( callable $callback ) {
		$this->for_sites( $this->get_setup_site_ids(), $callback );
	}

	/**
	 * Executes a callback for one or more sites.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $site_ids List of site IDs for which to execute the callback.
	 * @param callable $callback Callback to execute.
	 */
	protected function for_sites( array $site_ids, callable $callback ) {
		if ( ! is_multisite() ) {
			$callback();
			return;
		}

		array_walk(
			$site_ids,
			function( int $site_id ) use ( $callback ) {
				switch_to_blog( $site_id );
				$callback();
				restore_current_blog();
			}
		);
	}

	/**
	 * Gets the list of all site IDs in the current network.
	 *
	 * If not a multisite, the ID of the only site is returned as sole element.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of site IDs.
	 */
	protected function get_network_site_ids() : array {
		global $blog_id;

		if ( ! is_multisite() ) {
			return [ (int) $blog_id ];
		}

		return get_sites(
			[
				'network_id' => get_current_network_id(),
				'fields'     => 'ids',
			]
		);
	}

	/**
	 * Gets the list of all site IDs in the entire setup.
	 *
	 * If not a multisite, the ID of the only site is returned as sole element.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of site IDs.
	 */
	protected function get_setup_site_ids() : array {
		global $blog_id;

		if ( ! is_multisite() ) {
			return [ (int) $blog_id ];
		}

		return get_sites( [ 'fields' => 'ids' ] );
	}
}
