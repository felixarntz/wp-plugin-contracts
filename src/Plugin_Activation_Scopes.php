<?php
/**
 * Class Felix_Arntz\WP_Plugin_Contracts\Plugin_Activation_Scopes
 *
 * @package Felix_Arntz\WP_Plugin_Contracts
 * @license GNU General Public License, version 2
 * @link    https://github.com/felixarntz/wp-plugin-contracts
 */

namespace Felix_Arntz\WP_Plugin_Contracts;

use FelixArntz\Contracts\Enum;
use FelixArntz\Contracts\EnumTrait;

/**
 * Enum class for plugin activation scopes.
 *
 * @since 1.0.0
 */
class Plugin_Activation_Scopes {
	use EnumTrait;

	const SITE    = 1;
	const NETWORK = 2;
	const SETUP   = 4;
}
