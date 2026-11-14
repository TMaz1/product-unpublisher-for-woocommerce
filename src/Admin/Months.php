<?php
/**
 * Localised month names.
 *
 * @package ProductUnpublisher
 */

namespace ProductUnpublisher\Admin;

use ProductUnpublisher\Plugin;

defined( 'ABSPATH' ) || exit;

final class Months {

	/**
	 * Month names keyed by two-digit number ("01" => "January").
	 *
	 * @return array<string,string>
	 */
	public static function names(): array {
		return array(
			'01' => __( 'January', Plugin::TEXT_DOMAIN ),
			'02' => __( 'February', Plugin::TEXT_DOMAIN ),
			'03' => __( 'March', Plugin::TEXT_DOMAIN ),
			'04' => __( 'April', Plugin::TEXT_DOMAIN ),
			'05' => __( 'May', Plugin::TEXT_DOMAIN ),
			'06' => __( 'June', Plugin::TEXT_DOMAIN ),
			'07' => __( 'July', Plugin::TEXT_DOMAIN ),
			'08' => __( 'August', Plugin::TEXT_DOMAIN ),
			'09' => __( 'September', Plugin::TEXT_DOMAIN ),
			'10' => __( 'October', Plugin::TEXT_DOMAIN ),
			'11' => __( 'November', Plugin::TEXT_DOMAIN ),
			'12' => __( 'December', Plugin::TEXT_DOMAIN ),
		);
	}

	/**
	 * Weekday names, Sunday first.
	 *
	 * @return string[]
	 */
	public static function weekdays(): array {
		return array(
			__( 'Sunday', Plugin::TEXT_DOMAIN ),
			__( 'Monday', Plugin::TEXT_DOMAIN ),
			__( 'Tuesday', Plugin::TEXT_DOMAIN ),
			__( 'Wednesday', Plugin::TEXT_DOMAIN ),
			__( 'Thursday', Plugin::TEXT_DOMAIN ),
			__( 'Friday', Plugin::TEXT_DOMAIN ),
			__( 'Saturday', Plugin::TEXT_DOMAIN ),
		);
	}
}
