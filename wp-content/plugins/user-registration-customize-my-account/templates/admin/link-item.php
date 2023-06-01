<?php
/**
 * MY ACCOUNT LINK FIELDS
 *
 * @package User Registration Customize My Account
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$editor_args = array(
	'wpautop'       => false, // use wpautop ?
	'media_buttons' => true, // show insert/upload button(s).
	'textarea_name' => $id . '_' . $link . '[content]', // set the textarea name to something different, square brackets [] can be used here.
	'textarea_rows' => 7, // rows="..." .
	'tabindex'      => '',
	'editor_css'    => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
	'editor_class'  => '', // add extra class(es) to the editor textarea.
	'teeny'         => false, // output the minimal editor config used in Press This.
	'dfw'           => false, // replace the default fullscreen with DFW (needs specific DOM elements and css).
	'tinymce'       => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array().
	'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array().
);

?>

<div class="urcma-endpoint-content" id="<?php echo $link; ?>">
	<div class="urcma-endpoint-header">
		<h3><?php echo $options['label']; ?></h3>
		<div class="urcma-endpoint-header-option">
		<?php
		if ( '1' == $options['active'] ) {
			$label = esc_html__( 'Enabled', 'user-registration-customize-my-account' );
			$class = 'enabled';
		} else {
			$label = esc_html__( 'Disabled', 'user-registration-customize-my-account' );
			$class = '';
		}
		?>
			<div class="user-registration-switch">
				<input id="<?php echo $id . '_' . $link; ?>" type="checkbox" class="user-registration-switch__control hide-show-check <?php echo $class; ?>" name="<?php echo $id . '_' .$link; ?>[active]" id="<?php echo $id . '_' .$link; ?>_active" value="<?php echo $link; ?>" <?php checked( $options['active'] ); ?>>
				<label for="<?php echo $id . '_' . $link; ?>"><?php echo $label; ?></label>
			</div>

			<?php if ( ! urcma_is_default_item( $link ) && ! urcma_is_plugin_item( $link ) ) : ?>
					<button type="button" class="button button-secondary button-medium button-danger remove-trigger" data-endpoint="<?php echo $link; ?>"><?php _e( 'Remove', 'user-registration-customize-my-account' ); ?></button>
			<?php endif; ?>
		</div>
	</div>
	<div class="urcma-endpoint-options" style="display: none;">
		<table class="options-table form-table">
			<tbody>
			<?php
			if ( 'dashboard' !== $link ) {
				$disabled = '';
			} else {
				$disabled = 'disabled';
			}
			?>

				<tr>
					<th>
						<label class="ur-label" for="<?php echo $id . '_' . $link; ?>_url"><?php echo __( 'Link URL', 'user-registration-customize-my-account' ); ?></label>
						<span class="user-registration-help-tip" data-tip="<?php esc_attr_e( 'URL of the link to redirect to when the link tab is clicked', 'user-registration-customize-my-account' ); ?>"></span>
					</th>
					<td>
						<input class="regular-text urcma_url_input" type="text" name="<?php echo $id . '_' . $link; ?>[url]" id="<?php echo $id . '_' . $link; ?>_url" value="<?php echo $options['url']; ?> " <?php echo $disabled; ?>>
					</td>
				</tr>

			<tr>
				<th>
					<label class="ur-label" for="<?php echo $id . '_' . $link; ?>_label"><?php echo __( 'Link label', 'user-registration-customize-my-account' ); ?></label>
						<span class="user-registration-help-tip" data-tip="
						<?php
						esc_attr_e(
							'Menu item for this link in "My Account".',
							'user-registration-customize-my-account'
						)
						?>
							"></span>
				</th>
				<td>
					<input class="regular-text urcma_label_input" type="text" name="<?php echo $id . '_' . $link; ?>[label]" id="<?php echo $id . '_' . $link; ?>_label" value="<?php echo $options['label']; ?>">
				</td>
			</tr>

			<tr>
				<th>
					<label class="ur-label" for="<?php echo $id . '_' . $link; ?>_icon"><?php echo __( 'Link icon', 'user-registration-customize-my-account' ); ?></label>
					<span class="user-registration-help-tip" data-tip="<?php esc_attr_e( 'Link icon for "My Account" menu option', 'user-registration-customize-my-account' ); ?>"></span>
				</th>
				<td>
					<select name="<?php echo $id . '_' . $link; ?>[icon]" id="<?php echo $id . '_' . $link; ?>_icon" class="icon-select">
						<option value=""><?php _e( 'No icon', 'user-registration-customize-my-account' ); ?></option>
						<?php foreach ( $icon_list as $icon => $label ) : ?>
							<option value="<?php echo $label; ?>" <?php selected( $options['icon'], $label ); ?>><?php echo $label; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>

			<tr>
				<th>
					<label class="ur-label" for="<?php echo $id . '_' . $link; ?>_usr_roles">
						<?php
						echo __(
							'Restrict to user roles',
							'user-registration-customize-my-account'
						);
						?>
					</label>
					<span class="user-registration-help-tip" data-tip="
						<?php
						esc_attr_e(
							'Restrict link visibility to the following user role(s).',
							'user-registration-customize-my-account'
						)
						?>
							" ></span>
				</th>
				<td>
					<select name="<?php echo $id . '_' . $link; ?>[usr_roles][]" id="<?php echo $id . '_' . $link; ?>_usr_roles" multiple="multiple">
						<?php
						foreach ( $usr_roles as $role => $role_name ) :
							! isset( $options['usr_roles'] ) && $options['usr_roles'] = array();
							?>
							<option value="<?php echo $role; ?>" <?php selected( in_array( $role, (array) $options['usr_roles'] ), true ); ?>><?php echo $role_name; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>

			<tr>
				<th>
					<label class="ur-label" for="<?php echo $id . '_' . $link; ?>_target_blank"><?php echo __( 'Open in new tab', 'user-registration-customize-my-account' ); ?></label>
					<span class="user-registration-help-tip" data-tip="<?php esc_attr_e( 'Open in new tab when link is clicked', 'user-registration-customize-my-account' ); ?>"></span>
				</th>
				<td>
					<input id="<?php echo $id . '_' . $link; ?>_target_blank" type="checkbox" class="target-blank-select" name="<?php echo $id . '_' . $link; ?>[target_blank]" <?php checked( ( isset(  $options['target_blank'] ) && 1 == $options['target_blank'] ) ? true : false );?>>
				</td>
			</tr>

			</tbody>
		</table>
	</div>

</div>
