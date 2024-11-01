<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'WPCS_Content_Scheduler_Settings' ) ) {
    class WPCS_Content_Scheduler_Settings
    {
        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'menu_pages' ) );
            add_action( 'admin_init', array( $this, 'save' ) );
            add_action( 'admin_init', array( $this, 'downgrade' ), 0 );
            // 0 priority ensures settings downgraded before any other actions
        }
        
        public function menu_pages()
        {
            add_submenu_page(
                'wpcs-content-scheduler',
                __( 'Content Scheduler - Settings', 'wpcs-content-scheduler' ),
                __( 'Settings', 'wpcs-content-scheduler' ),
                apply_filters( 'wpcs_content_scheduler_capability', WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT ),
                'wpcs-content-scheduler-settings',
                array( $this, 'page' )
            );
        }
        
        public function page()
        {
            $settings = WPCS_Content_Scheduler_Settings::get();
            // Contents of these arrays should match the downgrade function arrays
            $post_types = WPCS_Content_Scheduler_Settings::core_post_types();
            $post_statuses = WPCS_Content_Scheduler_Settings::core_post_statuses();
            $taxonomies = WPCS_Content_Scheduler_Settings::core_taxonomies();
            $user_roles = get_editable_roles();
            ksort( $post_types );
            ksort( $post_statuses );
            ksort( $taxonomies );
            ksort( $user_roles );
            ?>

			<div class="wrap">
				<h1 class="wp-heading-inline"><?php 
            esc_html_e( 'Content Scheduler - Settings', 'wpcs-content-scheduler' );
            ?></h1>
				<div id="wpcs-content-scheduler-settings">
					<div id="wpcs-content-scheduler-fields">
						<form method="post">
							<h2><?php 
            esc_html_e( 'General', 'wpcs-content-scheduler' );
            ?></h2>
							<p><?php 
            esc_html_e( 'These settings control the data available within the dashboard.', 'wpcs-content-scheduler' );
            ?></p>
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-post-types"><?php 
            esc_html_e( 'Post types', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<select id="wpcs-content-scheduler-settings-post-types" name="wpcs_content_scheduler_settings[post_types][]" class="wpcs-content-scheduler-select2" multiple>
												<?php 
            foreach ( $post_types as $post_type ) {
                $post_type_object = get_post_type_object( $post_type );
                $selected = false;
                if ( isset( $settings['post_types'] ) ) {
                    $selected = ( in_array( $post_type, $settings['post_types'] ) ? true : false );
                }
                ?>
													<option value="<?php 
                echo  esc_html( $post_type ) ;
                ?>"<?php 
                echo  ( true == $selected ? ' selected' : '' ) ;
                ?>><?php 
                echo  esc_html( $post_type_object->label ) ;
                ?> <?php 
                esc_html_e( '(', 'wpcs-content-scheduler' );
                esc_html_e( 'ID:', 'wpcs-content-scheduler' );
                ?> <?php 
                echo  esc_html( $post_type ) ;
                esc_html_e( ')', 'wpcs-content-scheduler' );
                ?></option>
												<?php 
            }
            ?>
											</select>
											<p class="description">
												<?php 
            esc_html_e( 'Posts of these post types will be displayed and are filterable in the dashboard and for selection when adding new content.', 'wpcs-content-scheduler' );
            ?>
												<?php 
            ?>
													<br>
													<strong><?php 
            echo  sprintf( wp_kses_post( __( 'To use custom post types (such as ones registered by other plugins) <a href="%s">upgrade to premium</a>.', 'wpcs-content-scheduler' ) ), admin_url( 'admin.php?page=wpcs-content-scheduler-pricing' ) ) ;
            ?></strong>
												<?php 
            ?>
											</p>
										</td>
									</tr>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-post-statuses"><?php 
            esc_html_e( 'Post statuses', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<select id="wpcs-content-scheduler-settings-post-statuses" name="wpcs_content_scheduler_settings[post_statuses][]" class="wpcs-content-scheduler-select2" multiple>
												<?php 
            foreach ( $post_statuses as $post_status ) {
                $post_status_object = get_post_status_object( $post_status );
                $selected = false;
                if ( isset( $settings['post_statuses'] ) ) {
                    $selected = ( in_array( $post_status, $settings['post_statuses'] ) ? true : false );
                }
                ?>
													<option value="<?php 
                echo  esc_html( $post_status ) ;
                ?>"<?php 
                echo  ( true == $selected ? ' selected' : '' ) ;
                ?>><?php 
                echo  esc_html( $post_status_object->label ) ;
                ?> <?php 
                esc_html_e( '(', 'wpcs-content-scheduler' );
                esc_html_e( 'ID:', 'wpcs-content-scheduler' );
                ?> <?php 
                echo  esc_html( $post_status ) ;
                esc_html_e( ')', 'wpcs-content-scheduler' );
                ?></option>
												<?php 
            }
            ?>
											</select>
											<p class="description">
												<?php 
            esc_html_e( 'Posts with these post statuses where matched to the selected post types will be displayed and are filterable in the dashboard.', 'wpcs-content-scheduler' );
            ?>
												<?php 
            ?>
													<br>
													<strong><?php 
            echo  sprintf( wp_kses_post( __( 'To use custom post statuses (such as ones registered by other plugins) <a href="%s">upgrade to premium</a>.', 'wpcs-content-scheduler' ) ), admin_url( 'admin.php?page=wpcs-content-scheduler-pricing' ) ) ;
            ?></strong>
												<?php 
            ?>
											</p>
										</td>
									</tr>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-taxonomies"><?php 
            esc_html_e( 'Taxonomies', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<select id="wpcs-content-scheduler-settings-taxonomies" name="wpcs_content_scheduler_settings[taxonomies][]" class="wpcs-content-scheduler-select2" multiple>
												<?php 
            foreach ( $taxonomies as $taxonomy ) {
                $taxonomy_object = get_taxonomy( $taxonomy );
                $selected = false;
                if ( isset( $settings['taxonomies'] ) ) {
                    $selected = ( in_array( $taxonomy, $settings['taxonomies'] ) ? true : false );
                }
                ?>
													<option value="<?php 
                echo  esc_html( $taxonomy ) ;
                ?>"<?php 
                echo  ( true == $selected ? ' selected' : '' ) ;
                ?>><?php 
                echo  esc_html( $taxonomy_object->label ) ;
                ?> <?php 
                esc_html_e( '(', 'wpcs-content-scheduler' );
                esc_html_e( 'ID:', 'wpcs-content-scheduler' );
                ?> <?php 
                echo  esc_html( $taxonomy ) ;
                esc_html_e( ',', 'wpcs-content-scheduler' );
                ?> <?php 
                esc_html_e( 'post types:', 'wpcs-content-scheduler' );
                ?> <?php 
                echo  implode( ', ', $taxonomy_object->object_type ) ;
                esc_html_e( ')', 'wpcs-content-scheduler' );
                ?></option>
												<?php 
            }
            ?>
											</select>
											<p class="description">
												<?php 
            esc_html_e( 'The terms from these taxonomies will be available to filter the dashboard.', 'wpcs-content-scheduler' );
            ?>
												<?php 
            ?>
													<br>
													<strong><?php 
            echo  sprintf( wp_kses_post( __( 'To use custom taxonomies (such as ones registered by other plugins) <a href="%s">upgrade to premium</a>.', 'wpcs-content-scheduler' ) ), admin_url( 'admin.php?page=wpcs-content-scheduler-pricing' ) ) ;
            ?></strong>
												<?php 
            ?>
											</p>
										</td>
									</tr>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-user-roles"><?php 
            esc_html_e( 'User roles', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<select id="wpcs-content-scheduler-settings-user-roles" name="wpcs_content_scheduler_settings[user_roles][]" class="wpcs-content-scheduler-select2" multiple>
												<?php 
            foreach ( $user_roles as $user_role_id => $user_role ) {
                $selected = false;
                if ( isset( $settings['user_roles'] ) ) {
                    $selected = ( in_array( $user_role_id, $settings['user_roles'] ) ? true : false );
                }
                ?>
													<option value="<?php 
                echo  esc_html( $user_role_id ) ;
                ?>"<?php 
                echo  ( true == $selected ? ' selected' : '' ) ;
                ?>><?php 
                echo  esc_html( $user_role['name'] ) ;
                ?> <?php 
                esc_html_e( '(', 'wpcs-content-scheduler' );
                esc_html_e( 'ID:', 'wpcs-content-scheduler' );
                ?> <?php 
                echo  esc_html( $user_role_id ) ;
                esc_html_e( ')', 'wpcs-content-scheduler' );
                ?></option>
												<?php 
            }
            ?>
											</select>
											<p class="description">
												<?php 
            esc_html_e( 'The users assigned to these roles will be available to filter the dashboard. You can only assign roles that your user role can edit.', 'wpcs-content-scheduler' );
            ?>
											</p>
										</td>
									</tr>
								</tbody>
							</table>
							<h2><?php 
            esc_html_e( 'Colors', 'wpcs-content-scheduler' );
            ?></h2>
							<p><?php 
            esc_html_e( 'If you have just added additional post statuses they will appear here after save. If a color is not selected a default color will be used.', 'wpcs-content-scheduler' );
            ?></p>
							<?php 
            
            if ( !empty($settings['post_statuses']) ) {
                ?>
								<table class="form-table">
									<tbody>
										<?php 
                foreach ( $settings['post_statuses'] as $post_status ) {
                    $post_status_object = get_post_status_object( $post_status );
                    ?>
											<tr>
												<th>
													<label><?php 
                    echo  esc_html( $post_status_object->label ) ;
                    ?></label>
												</th>
												<td>
													<input type="text" name="wpcs_content_scheduler_settings[colors][<?php 
                    echo  esc_html( $post_status ) ;
                    ?>]" value="<?php 
                    echo  ( isset( $settings['colors'][$post_status] ) ? esc_html( $settings['colors'][$post_status] ) : '' ) ;
                    ?>" class="wpcs-content-scheduler-color-picker">
												</td>
											</tr>
										<?php 
                }
                ?>
									</tbody>
								</table>
							<?php 
            } else {
                ?>
								<p><strong><?php 
                esc_html_e( 'You can not assign colors as no post statuses are saved - select post statuses and save first.', 'wpcs-content-scheduler' );
                ?></strong></p>
							<?php 
            }
            
            ?>
							<h2><?php 
            esc_html_e( 'Notes', 'wpcs-content-scheduler' );
            ?></h2>
							<p><?php 
            esc_html_e( 'Configure the notes functionality.', 'wpcs-content-scheduler' );
            ?></p>
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<?php 
            esc_html_e( 'Notes', 'wpcs-content-scheduler' );
            ?>
										</th>
										<td>
											<input type="checkbox" id="wpcs-content-scheduler-settings-notes" name="wpcs_content_scheduler_settings[notes]" value="yes"<?php 
            echo  ( isset( $settings['notes'] ) && $settings['notes'] == 'yes' ? ' checked' : '' ) ;
            ?>>
											<label for="wpcs-content-scheduler-settings-notes"><?php 
            esc_html_e( 'Enable notes', 'wpcs-content-scheduler' );
            ?></label>
											<p class="description">
												<?php 
            esc_html_e( 'Allows you to save notes on posts via the "Content Scheduler Notes" meta box (depending on post types selected).', 'wpcs-content-scheduler' );
            ?>
												<?php 
            ?>
													<br>
													<strong><?php 
            echo  sprintf( wp_kses_post( __( 'To see notes when hovering over posts in the dashboard calendar <a href="%s">upgrade to premium</a>.', 'wpcs-content-scheduler' ) ), admin_url( 'admin.php?page=wpcs-content-scheduler-pricing' ) ) ;
            ?></strong>
												<?php 
            ?>
													
											</p>
										</td>
									</tr>
								</tbody>
							</table>
							<h2><?php 
            esc_html_e( 'Popup', 'wpcs-content-scheduler' );
            ?></h2>
							<p><?php 
            esc_html_e( 'Configure the appearance of the add/edit post popup.', 'wpcs-content-scheduler' );
            ?></p>
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-popup-width"><?php 
            esc_html_e( 'Width (px)', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<input type="number" id="wpcs-content-scheduler-settings-popup-width" name="wpcs_content_scheduler_settings[popup][width]" value="<?php 
            echo  ( isset( $settings['popup']['width'] ) ? esc_html( $settings['popup']['width'] ) : '' ) ;
            ?>" min="500" step="1" required>
										</td>
									</tr>
									<tr>
										<th>
											<label for="wpcs-content-scheduler-settings-popup-height"><?php 
            esc_html_e( 'Height (px)', 'wpcs-content-scheduler' );
            ?></label>
										</th>
										<td>
											<input type="number" id="wpcs-content-scheduler-settings-popup-height" name="wpcs_content_scheduler_settings[popup][height]" value="<?php 
            echo  ( isset( $settings['popup']['width'] ) ? esc_html( $settings['popup']['height'] ) : '' ) ;
            ?>" min="300" step="1" required>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit">
								<?php 
            wp_nonce_field( 'wpcs_content_scheduler_settings_save', 'wpcs_content_scheduler_settings_save_nonce' );
            ?>
								<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php 
            esc_html_e( 'Save Changes', 'wpcs-content-scheduler' );
            ?>">
							</p>
						</form>
					</div>
					<div id="wpcs-content-scheduler-sidebar">
						<h2><?php 
            esc_html_e( 'Hooks', 'wpcs-content-scheduler' );
            ?></h2>
						<p><?php 
            esc_html_e( 'These hooks allow advanced control over Content Scheduler.', 'wpcs-content-scheduler' );
            ?></p>
						<table class="widefat fixed">
							<thead>
								<tr>
									<th><?php 
            esc_html_e( 'Filter', 'wpcs-content-scheduler' );
            ?></th>
									<th><?php 
            esc_html_e( 'Description', 'wpcs-content-scheduler' );
            ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>wpcs_content_scheduler_capability( $capability )</td>
									<td><?php 
            echo  sprintf( esc_html__( 'If a user has %1$s then allows access to the Content Scheduler, default is %2$s', 'wpcs-content-scheduler' ), '$capability', WPCS_CONTENT_SCHEDULER_CAPABILITY_DEFAULT ) ;
            ?></td>
								</tr>
							</tbody>
						</table>
						<p class="description"><?php 
            echo  sprintf( wp_kses_post( __( 'For further details on how to use these filter hooks see the <a href="%s" target="_blank">WordPress documentation</a>.', 'wpcs-content-scheduler' ) ), 'https://developer.wordpress.org/reference/functions/add_filter/' ) ;
            ?></p>
					</div>
				</div>
			</div>

		<?php 
        }
        
        public function save()
        {
            if ( isset( $_POST['wpcs_content_scheduler_settings_save_nonce'] ) ) {
                
                if ( wp_verify_nonce( sanitize_key( $_POST['wpcs_content_scheduler_settings_save_nonce'] ), 'wpcs_content_scheduler_settings_save' ) ) {
                    $settings = $_POST['wpcs_content_scheduler_settings'];
                    // Gets sanitized later
                    
                    if ( isset( $settings ) ) {
                        
                        if ( !empty($settings) ) {
                            // Populate empty checkboxes with no values ensures the setting data exists and set to no
                            if ( !isset( $settings['notes'] ) ) {
                                $settings['notes'] = 'no';
                            }
                            // Sanitize settings
                            foreach ( $settings as $setting_section_key => $setting_section ) {
                                
                                if ( 'colors' == $setting_section_key ) {
                                    // Sanitizes differently to other settings (sanitize_hex_color)
                                    if ( !empty($setting_section) ) {
                                        foreach ( $setting_section as $setting_key => $setting_value ) {
                                            // Colors is an array for iterate
                                            $settings[$setting_section_key][$setting_key] = sanitize_hex_color( $setting_value );
                                        }
                                    }
                                } else {
                                    
                                    if ( is_array( $setting_section ) ) {
                                        // Is array so iterate
                                        if ( !empty($setting_section) ) {
                                            foreach ( $setting_section as $setting_key => $setting_value ) {
                                                $settings[$setting_section_key][$setting_key] = sanitize_text_field( $setting_value );
                                            }
                                        }
                                    } else {
                                        // Some settings aren't arrays, so no need to loop through
                                        if ( isset( $settings[$setting_section_key] ) ) {
                                            $settings[$setting_section_key] = sanitize_text_field( $settings[$setting_section_key] );
                                        }
                                    }
                                
                                }
                            
                            }
                        }
                        
                        ksort( $settings );
                        update_option( 'wpcs_content_scheduler_settings', $settings );
                    }
                
                }
            
            }
        }
        
        public function downgrade()
        {
            $settings = WPCS_Content_Scheduler_Settings::get();
            $settings_downgraded = false;
            if ( isset( $settings['post_types'] ) ) {
                if ( !empty($settings['post_types']) ) {
                    foreach ( $settings['post_types'] as $post_type_key => $post_type_value ) {
                        
                        if ( !in_array( $post_type_value, WPCS_Content_Scheduler_Settings::core_post_types() ) ) {
                            unset( $settings['post_types'][$post_type_key] );
                            $settings_downgraded = true;
                        }
                    
                    }
                }
            }
            if ( isset( $settings['post_statuses'] ) ) {
                if ( !empty($settings['post_statuses']) ) {
                    foreach ( $settings['post_statuses'] as $post_status_key => $post_status_value ) {
                        
                        if ( !in_array( $post_status_value, WPCS_Content_Scheduler_Settings::core_post_statuses() ) ) {
                            unset( $settings['post_statuses'][$post_status_key] );
                            $settings_downgraded = true;
                        }
                    
                    }
                }
            }
            if ( isset( $settings['taxonomies'] ) ) {
                if ( !empty($settings['taxonomies']) ) {
                    foreach ( $settings['taxonomies'] as $taxonomy_key => $taxonomy_value ) {
                        
                        if ( !in_array( $taxonomy_value, WPCS_Content_Scheduler_Settings::core_taxonomies() ) ) {
                            unset( $settings['taxonomies'][$taxonomy_key] );
                            $settings_downgraded = true;
                        }
                    
                    }
                }
            }
            if ( true == $settings_downgraded ) {
                update_option( 'wpcs_content_scheduler_settings', $settings );
            }
        }
        
        public static function get()
        {
            $settings = get_option( 'wpcs_content_scheduler_settings' );
            if ( false == $settings ) {
                $settings = array();
            }
            return $settings;
        }
        
        public static function core_post_types()
        {
            return array(
                'post' => 'post',
                'page' => 'page',
            );
        }
        
        public static function core_post_statuses()
        {
            return array(
                'draft'   => 'draft',
                'future'  => 'future',
                'pending' => 'pending',
                'private' => 'private',
                'publish' => 'publish',
                'trash'   => 'trash',
            );
        }
        
        public static function core_taxonomies()
        {
            return array(
                'category' => 'category',
                'post_tag' => 'post_tag',
            );
        }
    
    }
}