<?php
/**
 * Unpublishing controls inside the native Bulk Edit panel.
 *
 * @var array<string,string>  $parts  Default date field values.
 * @var array<string,string>  $months Month names.
 *
 * @package ProductUnpublisher
 */

defined( 'ABSPATH' ) || exit;

use ProductUnpublisher\Admin\BulkAction;
use ProductUnpublisher\Plugin;

?>
<div class="inline-edit-group wpu-bulk-edit">
	<fieldset>
		<legend class="wpu-bulk-legend"><?php esc_html_e( 'Unpublishing schedule', 'product-unpublisher-for-woocommerce' ); ?></legend>

		<label>
			<input type="radio" name="wpu_bulk_action" value="<?php echo esc_attr( BulkAction::NONE ); ?>" checked="checked" />
			<span><?php esc_html_e( 'No change to existing schedules', 'product-unpublisher-for-woocommerce' ); ?></span>
		</label>

		<label>
			<input type="radio" name="wpu_bulk_action" value="<?php echo esc_attr( BulkAction::SCHEDULE ); ?>" />
			<span><?php esc_html_e( 'Schedule unpublishing', 'product-unpublisher-for-woocommerce' ); ?></span>
		</label>

		<label>
			<input type="radio" name="wpu_bulk_action" value="<?php echo esc_attr( BulkAction::REMOVE ); ?>" />
			<span><?php esc_html_e( 'Remove scheduled unpublishing', 'product-unpublisher-for-woocommerce' ); ?></span>
		</label>

		<label>
			<input type="radio" name="wpu_bulk_action" value="<?php echo esc_attr( BulkAction::UNPUBLISH_NOW ); ?>" />
			<span><?php esc_html_e( 'Unpublish published products now', 'product-unpublisher-for-woocommerce' ); ?></span>
		</label>
	</fieldset>

	<div id="wpu-bulk-date-fields" class="wpu-bulk-date-fields" hidden>
		<p><strong><?php esc_html_e( 'Unpublish on:', 'product-unpublisher-for-woocommerce' ); ?></strong></p>

		<?php
		\ProductUnpublisher\Admin\View::render(
			'date-fields',
			array(
				'prefix' => 'wpu_bulk_',
				'parts'  => $parts,
				'months' => $months,
			)
		);
		?>

		<p id="wpu-bulk-error" class="wpu-error" role="alert" hidden></p>
		<p class="description">
			<?php esc_html_e( 'Applied to selected published products. Existing schedules are replaced.', 'product-unpublisher-for-woocommerce' ); ?>
		</p>
	</div>
</div>
