<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'FEDSCHATMenu' ) ) {
	/**
	 * Class FEDSCHATMenu
	 */
	class FEDSCHATMenu {

		public function __construct() {
			add_filter( 'fed_add_main_sub_menu', array( $this, 'menu' ) );
			add_filter( 'fed_plugin_versions', array( $this, 'plugin_versions' ) );
		}

		/**
		 * @param $menu
		 *
		 * @return mixed
		 */
		public function menu( $menu ) {
			$menu['social_chat'] = array(
				'page_title' => __( 'Social Chat', BC_FED_SCHAT_PLUGIN_SLUG ),
				'menu_title' => __( 'Social Chat', BC_FED_SCHAT_PLUGIN_SLUG ),
				'capability' => 'manage_options',
				'callback'   => array( $this, 'main_menu' ),
				'position'   => 30,
			);

			return $menu;
		}

		public function main_menu() {
			$menus = apply_filters(
				'fed_social_chat_menu',
				array(
					'whatsapp' => array(
						'icon'    => 'fab fa-whatsapp',
						'name'    => __( 'WhatsApp', 'frontend-dashboard-social-chat' ),
						'submenu' => array(
							'FEDSCHATWhatsapp@settings' => array(
								'icon' => 'fas fa-sliders-h',
								'name' => __( 'General Settings', 'frontend-dashboard-social-chat' ),
								'menu' => array( 'FEDSCHATWhatsapp@settings' ),
							),
							'FEDSCHATWhatsapp@users'    => array(
								'icon' => 'fas fa-user-friends',
								'name' => __( 'Support Agents', 'frontend-dashboard-social-chat' ),
								'menu' => array( 'FEDSCHATWhatsapp@users' ),
							),
							'FEDSCHATWhatsapp@layout'   => array(
								'icon' => 'fas fa-palette',
								'name' => __( 'Chat Layout', 'frontend-dashboard-social-chat' ),
								'menu' => array( 'FEDSCHATWhatsapp@layout' ),
							),
						),
					),
				)
			);
			?>
			<div class="wrap fed-admin-wrap fed_schat_admin_root" style="width: calc(100% - 20px); max-width: 100%; margin: 20px 20px 40px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif; box-sizing: border-box;">
				
				<!-- Hero Header Banner -->
				<div class="fed_schat_admin_banner" style="background: linear-gradient(135deg, #075E54 0%, #128C7E 50%, #25D366 100%); border-radius: 14px; padding: 26px 32px; color: #ffffff; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(18, 140, 126, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
					<div style="display: flex; align-items: center; gap: 18px;">
						<div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #ffffff; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
							<i class="fab fa-whatsapp"></i>
						</div>
						<div>
							<h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; line-height: 1.2; letter-spacing: -0.02em;">
								<?php esc_html_e( 'Social Chat Support', 'frontend-dashboard-social-chat' ); ?>
							</h1>
							<p style="color: rgba(255, 255, 255, 0.9); margin: 6px 0 0 0; font-size: 13.5px; font-weight: 400;">
								<?php esc_html_e( 'Configure WhatsApp floating chat widget, support agents, and frontend layout.', 'frontend-dashboard-social-chat' ); ?>
							</p>
						</div>
					</div>
					<div style="display: flex; gap: 10px; align-items: center;">
						<span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.35); color: #ffffff; font-weight: 600; font-size: 12px; padding: 6px 14px; border-radius: 9999px;">
							<i class="fas fa-check-circle"></i> v<?php echo esc_html( defined( 'BC_FED_SCHAT_PLUGIN_VERSION' ) ? BC_FED_SCHAT_PLUGIN_VERSION : '3.0.0' ); ?>
						</span>
					</div>
				</div>

				<?php if ( ! empty( $menus ) ) : ?>
					<!-- Navigation Header Tabs (if multiple channels) -->
					<?php if ( count( $menus ) > 1 ) : ?>
						<div class="fed_schat_nav_tabs" style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
							<?php $this->header_menu( $menus ); ?>
						</div>
					<?php endif; ?>

					<!-- Main Content Grid -->
					<div class="fed_schat_admin_content">
						<?php $this->body_content( $menus ); ?>
					</div>
				<?php else : ?>
					<div style="background: #fee2e2; border: 1px solid #f87171; border-radius: 10px; padding: 16px 20px; color: #991b1b; font-weight: 500;">
						<?php esc_html_e( 'No chat menu configurations found.', 'frontend-dashboard-social-chat' ); ?>
					</div>
				<?php endif; ?>

			</div>
			<?php
		}

		/**
		 * @param array $menus
		 */
		public function header_menu( $menus ) {
			$current_menu = fed_get_data( 'menu', $_GET, 'whatsapp' );
			foreach ( $menus as $index => $item ) {
				$is_active = ( $current_menu === $index );
				$url       = fed_menu_page_url( 'social_chat', array( 'menu' => esc_attr( $index ) ) );
				$bg        = $is_active ? '#128c7e' : '#ffffff';
				$color     = $is_active ? '#ffffff' : '#475569';
				$border    = $is_active ? '#128c7e' : '#e2e8f0';
				?>
				<a href="<?php echo esc_url( $url ); ?>" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13.5px; text-decoration: none; background: <?php echo esc_attr( $bg ); ?>; color: <?php echo esc_attr( $color ); ?>; border: 1px solid <?php echo esc_attr( $border ); ?>; transition: all 0.2s ease;">
					<i class="<?php echo esc_attr( fed_get_data( 'icon', $item, 'fas fa-comment' ) ); ?>"></i>
					<span><?php echo esc_html( fed_get_data( 'name', $item ) ); ?></span>
				</a>
				<?php
			}
		}

		/**
		 * @param array $menus
		 */
		public function body_content( $menus ) {
			$_menu    = fed_get_data( 'menu', $_GET, null );
			$_submenu = fed_get_data( 'submenu', $_GET, null );
			$menu     = ! empty( $_menu ) ? sanitize_text_field( $_menu ) : fed_get_first_key_in_array( $menus );
			$submenu  = ! empty( $_submenu ) ? sanitize_text_field( $_submenu ) : false;

			if ( $menu && isset( $menus[ $menu ] ) ) {
				if ( isset( $menus[ $menu ]['submenu'] ) && is_array( $menus[ $menu ]['submenu'] ) && count( $menus[ $menu ]['submenu'] ) ) {
					if ( ! $submenu || ! isset( $menus[ $menu ]['submenu'][ $submenu ] ) ) {
						$submenu = fed_get_first_key_in_array( $menus[ $menu ]['submenu'] );
					}
					?>
					<div style="display: grid; grid-template-columns: 260px 1fr; gap: 24px; align-items: start;">
						
						<!-- Left Sidebar Submenu Navigation -->
						<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
							<div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; padding: 8px 12px 12px 12px; border-bottom: 1px solid #f1f5f9; margin-bottom: 8px;">
								<i class="fas fa-cogs" style="margin-right: 6px;"></i> <?php echo esc_html( $menus[ $menu ]['name'] ); ?> <?php esc_html_e( 'Menu', 'frontend-dashboard-social-chat' ); ?>
							</div>
							<div style="display: flex; flex-direction: column; gap: 4px;">
								<?php
								foreach ( $menus[ $menu ]['submenu'] as $index => $sub_menu ) {
									$is_active = ( $submenu === $index || ( is_array( $sub_menu['menu'] ) && in_array( $submenu, $sub_menu['menu'], true ) ) );
									$sub_url   = fed_menu_page_url(
										'social_chat',
										array(
											'menu'    => $menu,
											'submenu' => $index,
										)
									);
									$active_bg     = $is_active ? 'background: #f0fdf4; color: #15803d; border-color: #bbf7d0;' : 'background: transparent; color: #475569; border-color: transparent;';
									$active_weight = $is_active ? 'font-weight: 700;' : 'font-weight: 500;';
									$active_icon   = $is_active ? 'color: #16a34a;' : 'color: #94a3b8;';
									?>
									<a href="<?php echo esc_url( $sub_url ); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 11px 14px; border-radius: 8px; font-size: 13.5px; text-decoration: none; border: 1px solid transparent; transition: all 0.15s ease; <?php echo $active_bg; ?> <?php echo $active_weight; ?>">
										<span style="display: flex; align-items: center; gap: 10px;">
											<i class="<?php echo esc_attr( $sub_menu['icon'] ); ?>" style="<?php echo $active_icon; ?> font-size: 14px; width: 18px; text-align: center;"></i>
											<?php echo esc_html( $sub_menu['name'] ); ?>
										</span>
										<?php if ( $is_active ) : ?>
											<i class="fas fa-chevron-right" style="font-size: 11px; color: #16a34a;"></i>
										<?php endif; ?>
									</a>
								<?php } ?>
							</div>
						</div>

						<!-- Right Content Card -->
						<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
							<div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; background: #fafafa; display: flex; align-items: center; justify-content: space-between;">
								<div style="display: flex; align-items: center; gap: 10px;">
									<div style="width: 32px; height: 32px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 14px;">
										<i class="<?php echo esc_attr( $menus[ $menu ]['submenu'][ $submenu ]['icon'] ); ?>"></i>
									</div>
									<h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">
										<?php echo esc_html( $menus[ $menu ]['submenu'][ $submenu ]['name'] ); ?>
									</h2>
								</div>
							</div>
							<div style="padding: 24px;">
								<?php
								if ( is_string( $submenu ) ) {
									fed_execute_method_by_string( $submenu, $_GET );
								}
								?>
							</div>
						</div>

					</div>
					<?php
				} else {
					if ( isset( $menus[ $menu ]['submenu'] ) && is_string( $menus[ $menu ]['submenu'] ) ) {
						?>
						<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); padding: 24px;">
							<h2 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
								<i class="<?php echo esc_attr( $menus[ $menu ]['icon'] ); ?>"></i>
								<?php echo esc_html( $menus[ $menu ]['name'] ); ?>
							</h2>
							<?php fed_execute_method_by_string( $menus[ $menu ]['submenu'], $_GET ); ?>
						</div>
						<?php
					} else {
						?>
						<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 32px; text-align: center; color: #64748b;">
							<i class="fas fa-tools" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
							<div style="font-size: 16px; font-weight: 600; color: #334155;"><?php esc_html_e( 'Feature Under Construction', 'frontend-dashboard-social-chat' ); ?></div>
						</div>
						<?php
					}
				}
			} else {
				?>
				<div style="background: #fee2e2; border: 1px solid #f87171; border-radius: 10px; padding: 18px 22px; color: #991b1b; font-weight: 500;">
					<strong><i class="fas fa-exclamation-triangle"></i> <?php esc_html_e( 'Invalid Menu Selection | Error FEDSCHAT|Admin|Settings|FEDSCHATMenu@body_content', 'frontend-dashboard-social-chat' ); ?></strong>
				</div>
				<?php
			}
		}


		/**
		 * @param $version
		 *
		 * @return array
		 */
		public function plugin_versions( $version ) {
			return array_merge(
				$version, array(
					'social_chat' => sprintf( __( 'Social Chat (%s)', BC_FED_SCHAT_PLUGIN_SLUG ), BC_FED_SCHAT_PLUGIN_VERSION ),
				)
			);
		}
	}

	new FEDSCHATMenu();
}
