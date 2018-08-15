<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.dariah.eu
 * @since      1.0.0
 *
 * @package    Nerd_Wp
 * @subpackage Nerd_Wp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<h2 class="nav-tab-wrapper">NERD Settings</h2>

    <form method="post" name="nerd_options" action="options.php">

		<?php
		//Grab all options
		$options = get_option( $this->plugin_name );
		$url_nerd_instance = $options['url_nerd_instance'];
		if( array_key_exists( 'category_weight', $options ) ) {
			$category_weight = $options['category_weight'];
        }
        if( array_key_exists( 'entity_weight', $options ) ) {
	        $entity_weight = $options['entity_weight'];
        }

		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		?>

        <table class="form-table">
            <tbody>
                <!-- URL of the NERD instance -->
                <tr>
                    <th scope="row">
                        <label for="<?php echo $this->plugin_name;?>-url-nerd-instance"><?php _e('URL of NERD instance', $this->plugin_name);?></label>
                    </th>
                    <td>
                        <input name="<?php echo $this->plugin_name;?>[url_nerd_instance]" id="<?php echo $this->plugin_name;?>-url-nerd-instance" value="<?php echo $url_nerd_instance;?>" class="regular-text" type="text">
                    </td>
                </tr>
                <!-- Weight of global categories of the NERD instance -->
                <tr>
                    <th scope="row">
                        <label for="<?php echo $this->plugin_name;?>-category-weight"><?php _e('Weight of the global categories (if a category weight this or more, then it is used as a tag)', $this->plugin_name);?></label>
                    </th>
                    <td>
                        <input name="<?php echo $this->plugin_name;?>[category_weight]" id="<?php echo $this->plugin_name;?>-category-weight" value="<?php echo $category_weight;?>" class="regular-text" type="text">
                    </td>
                </tr>
                <!-- Weight of entities of the NERD instance -->
                <tr>
                    <th scope="row">
                        <label for="<?php echo $this->plugin_name;?>-entity-weight"><?php _e('Weight of the entities (if an entity weight this or more, then it is used as a tag - we use nerd_selection_score)', $this->plugin_name);?></label>
                    </th>
                    <td>
                        <input name="<?php echo $this->plugin_name;?>[entity_weight]" id="<?php echo $this->plugin_name;?>-entity-weight" value="<?php echo $entity_weight;?>" class="regular-text" type="text">
                    </td>
                </tr>
            </tbody>
        </table>

		<?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
	</form>

</div>
