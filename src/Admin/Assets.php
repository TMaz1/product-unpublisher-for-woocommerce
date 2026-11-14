<?php
/**
 * Loads admin scripts and styles on the product editor and product list screens.
 *
 * @package ProductUnpublisher
 */

namespace ProductUnpublisher\Admin;

use ProductUnpublisher\Plugin;
use ProductUnpublisher\Config;
use ProductUnpublisher\Time\Clock;

defined( 'ABSPATH' ) || exit;

final class Assets {

	public function __construct( private Clock $clock ) {}

	public function register(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * @param string $hook Current admin page hook suffix.
	 */
	public function enqueue( $hook ): void {
		$screen = get_current_screen();

		if ( ! $screen instanceof \WP_Screen || Config::POST_TYPE !== $screen->post_type ) {
			return;
		}

		$is_editor = in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && 'post' === $screen->base;
		$is_list   = 'edit.php' === $hook && 'edit' === $screen->base;

		if ( ! $is_editor && ! $is_list ) {
			return;
		}

		wp_register_script(
			Plugin::SCRIPT_DATE_UTILS,
			WPU_URL . 'assets/js/date-utils.js',
			array(),
			WPU_VERSION,
			true
		);

		if ( $is_editor ) {
			wp_enqueue_style( Plugin::STYLE_EDITOR, WPU_URL . 'assets/css/unpublisher.css', array(), WPU_VERSION );
			wp_enqueue_script(
				Plugin::STYLE_EDITOR,
				WPU_URL . 'assets/js/unpublisher.js',
				array( 'jquery', Plugin::SCRIPT_DATE_UTILS ),
				WPU_VERSION,
				true
			);
			wp_localize_script( Plugin::STYLE_EDITOR, Plugin::JS_ADMIN_GLOBAL, $this->editor_data() );
			return;
		}

		wp_enqueue_style( Plugin::SCRIPT_BULK_EDIT , WPU_URL . 'assets/css/bulk-edit.css', array(), WPU_VERSION );
		wp_enqueue_script(
			Plugin::SCRIPT_BULK_EDIT,
			WPU_URL . 'assets/js/bulk-edit.js',
			array( 'jquery', Plugin::SCRIPT_DATE_UTILS, 'inline-edit-post' ),
			WPU_VERSION,
			true
		);
		wp_localize_script( Plugin::SCRIPT_BULK_EDIT, Plugin::JS_BULK_GLOBAL, $this->bulk_data() );
	}

	/**
	 * Data shared by both scripts for date validation and formatting.
	 *
	 * @return array<string,mixed>
	 */
	private function common_data(): array {
		$now = ( new \DateTimeImmutable( '@' . $this->clock->now() ) )->setTimezone( $this->clock->timezone() );

		return array(
			'monthNames' => array_values( Months::names() ),
			'weekdays'   => Months::weekdays(),
			'dateFormat' => get_option( 'date_format' ),
			'timeFormat' => get_option( 'time_format' ),
			'atLabel'    => __( 'at', Plugin::TEXT_DOMAIN ),
			'now'        => array_map( 'intval', explode( ',', $now->format( 'Y,n,j,G,i' ) ) ),
			'invalid'    => __( 'Please enter a valid date and time.', Plugin::TEXT_DOMAIN ),
			'past'       => __( 'Choose a date and time in the future.', Plugin::TEXT_DOMAIN ),
		);
	}

	/**
	 * @return array<string,mixed>
	 */
	private function editor_data(): array {
		return array_merge(
			$this->common_data(),
			array(
				'before'         => __( 'Unpublishing must be later than scheduled publication.', Plugin::TEXT_DOMAIN ),
				'confirmRemove'  => __( 'Remove the unpublishing schedule? The product will stay published. The change is applied when you click Update.', Plugin::TEXT_DOMAIN ),
				'unpublishLabel' => __( 'Unpublish on:', Plugin::TEXT_DOMAIN ),
				'removalPending' => __( 'Schedule will be removed when you update.', Plugin::TEXT_DOMAIN ),
				'notScheduled'   => __( 'Not scheduled', Plugin::TEXT_DOMAIN ),
			)
		);
	}

	/**
	 * @return array<string,mixed>
	 */
	private function bulk_data(): array {
		return array_merge(
			$this->common_data(),
			array(
				/* translators: %d: number of products. */
				'confirmNowOne'      => __( 'Unpublish %d selected published product now? It will immediately become a Draft, will no longer be visible in your store, and any scheduled unpublishing for it will be removed.', Plugin::TEXT_DOMAIN ),
				/* translators: %d: number of products. */
				'confirmNowMany'     => __( 'Unpublish %d selected published products now? They will immediately become Drafts, will no longer be visible in your store, and any scheduled unpublishing for them will be removed.', Plugin::TEXT_DOMAIN ),
				/* translators: %d: number of products that are not published. */
				'confirmNowSkipped'  => __( '%d selected product is not published and will be left unchanged.', Plugin::TEXT_DOMAIN ),
				/* translators: %d: number of products that are not published. */
				'confirmNowSkippedMany' => __( '%d selected products are not published and will be left unchanged.', Plugin::TEXT_DOMAIN ),
				'nothingToUnpublish' => __( 'None of the selected products are published, so there is nothing to unpublish.', Plugin::TEXT_DOMAIN ),
				/* translators: %d: number of products. */
				'confirmRemoveOne'   => __( 'Remove the scheduled unpublishing from %d selected product? This deletes its scheduled date, its cron event and any retry information. The product stays published.', Plugin::TEXT_DOMAIN ),
				/* translators: %d: number of products. */
				'confirmRemoveMany'  => __( 'Remove the scheduled unpublishing from %d selected products? This deletes their scheduled dates, their cron events and any retry information. The products stay published.', Plugin::TEXT_DOMAIN ),
			)
		);
	}
}
