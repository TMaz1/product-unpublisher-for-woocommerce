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
			'01' => __( 'January', 'product-unpublisher-for-woocommerce' ),
			'02' => __( 'February', 'product-unpublisher-for-woocommerce' ),
			'03' => __( 'March', 'product-unpublisher-for-woocommerce' ),
			'04' => __( 'April', 'product-unpublisher-for-woocommerce' ),
			'05' => __( 'May', 'product-unpublisher-for-woocommerce' ),
			'06' => __( 'June', 'product-unpublisher-for-woocommerce' ),
			'07' => __( 'July', 'product-unpublisher-for-woocommerce' ),
			'08' => __( 'August', 'product-unpublisher-for-woocommerce' ),
			'09' => __( 'September', 'product-unpublisher-for-woocommerce' ),
			'10' => __( 'October', 'product-unpublisher-for-woocommerce' ),
			'11' => __( 'November', 'product-unpublisher-for-woocommerce' ),
			'12' => __( 'December', 'product-unpublisher-for-woocommerce' ),
		);
	}

	/**
	 * Weekday names, Sunday first.
	 *
	 * @return string[]
	 */
	public static function weekdays(): array {
		return array(
			__( 'Sunday', 'product-unpublisher-for-woocommerce' ),
			__( 'Monday', 'product-unpublisher-for-woocommerce' ),
			__( 'Tuesday', 'product-unpublisher-for-woocommerce' ),
			__( 'Wednesday', 'product-unpublisher-for-woocommerce' ),
			__( 'Thursday', 'product-unpublisher-for-woocommerce' ),
			__( 'Friday', 'product-unpublisher-for-woocommerce' ),
			__( 'Saturday', 'product-unpublisher-for-woocommerce' ),
		);
	}
}
