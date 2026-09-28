<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'FEDSCHATWhatsappLayout' ) ) {
	/**
	 * Class FEDSCHATWhatsappLayout
	 */
	class FEDSCHATWhatsappLayout {

		/**
		 * @var mixed
		 */
		public $settings;

		public function __construct() {
			add_action( 'wp_footer', array( $this, 'layout' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'fed_add_inline_css_at_head', array( $this, 'custom_css' ) );
			$this->settings = get_option( 'fed_social_chat_settings' );
		}

		/**
		 * @param string $css
		 */
		public function custom_css( $css ) {
			$fed_colors     = get_option( 'fed_admin_setting_upl_color' );
			$pbg_color      = fed_get_data( 'color.fed_upl_color_bg_color', $fed_colors, '#128C7E' );
			$pbg_font_color = fed_get_data( 'color.fed_upl_color_bg_font_color', $fed_colors, '#FFFFFF' );
			$sbg_color      = fed_get_data( 'color.fed_upl_color_sbg_color', $fed_colors, '#075E54' );
			$sbg_font_color = fed_get_data( 'color.fed_upl_color_sbg_font_color', $fed_colors, '#FFFFFF' );
			?>
			<style>
				:root {
					--fed-wa-primary: <?php echo esc_attr( $pbg_color ); ?>;
					--fed-wa-primary-text: <?php echo esc_attr( $pbg_font_color ); ?>;
					--fed-wa-dark: <?php echo esc_attr( $sbg_color ); ?>;
					--fed-wa-dark-text: <?php echo esc_attr( $sbg_font_color ); ?>;
					--fed-wa-green: #25D366;
				}
				.fed_wa_header_container {
					background: linear-gradient(135deg, var(--fed-wa-dark) 0%, var(--fed-wa-primary) 100%) !important;
					color: var(--fed-wa-primary-text) !important;
				}
				.fed_wa_footer_chat_container {
					background: var(--fed-wa-primary) !important;
					color: var(--fed-wa-primary-text) !important;
				}
				.fed_wa_close {
					background: var(--fed-wa-dark) !important;
				}
			</style>
			<?php
		}

		/**
		 * Enqueue Scripts & Styles
		 */
		public function scripts() {
			if ( $this->is_enable() ) {
				wp_enqueue_style(
					'fed_schat_style',
					plugins_url( '/assets/fed_schat_style.css', BC_FED_SCHAT_PLUGIN ),
					array(),
					BC_FED_SCHAT_PLUGIN_VERSION,
					'all'
				);
				wp_enqueue_style(
					'fed_admin_font_awesome',
					plugins_url( '/assets/frontend/css/fontawesome.css', BC_FED_PLUGIN ),
					array(),
					BC_FED_PLUGIN_VERSION,
					'all'
				);
				wp_enqueue_style(
					'fed_admin_font_awesome-shims',
					plugins_url( '/assets/frontend/css/fontawesome-shims.css', BC_FED_PLUGIN ),
					array(),
					BC_FED_PLUGIN_VERSION,
					'all'
				);
				wp_enqueue_script(
					'fed_schat_script',
					plugins_url( '/assets/fed_schat_script.js', BC_FED_SCHAT_PLUGIN ),
					array( 'jquery' ),
					BC_FED_SCHAT_PLUGIN_VERSION,
					true
				);
			}
		}

		/**
		 * @return bool
		 */
		public function is_enable() {
			$this->settings = get_option( 'fed_social_chat_settings' );
			$is_enable      = fed_get_data( 'whatsapp.settings.enable', $this->settings, false );
			$user_allowed   = fed_get_data( 'whatsapp.settings.users.allow', $this->settings, array() );

			if ( is_user_logged_in() ) {
				$user_can = fed_is_current_user_role( $user_allowed );
			} else {
				$user_can = array_key_exists( 'unregistered', $user_allowed );
			}

			return ( 'Enable' === $is_enable && $user_can );
		}

		public function layout() {
			if ( ! $this->is_enable() ) {
				return;
			}

			$users_raw = fed_get_data( 'whatsapp.users.details', $this->settings, false );
			$users     = array();

			if ( ! empty( $users_raw ) ) {
				if ( is_array( $users_raw ) ) {
					$users = $users_raw;
				} elseif ( is_string( $users_raw ) ) {
					$unserialized = @unserialize( $users_raw, array( 'allowed_classes' => false ) );
					if ( is_array( $unserialized ) ) {
						$users = $unserialized;
					}
				}
			}

			if ( empty( $users ) ) {
				return;
			}

			$announcement = fed_get_data(
				'whatsapp.layout.body.title',
				$this->settings,
				__( 'The team typically replies in a few minutes', 'frontend-dashboard-social-chat' )
			);
			$footer_title = fed_get_data(
				'whatsapp.layout.footer.title',
				$this->settings,
				''
			);
			$chat_prompt = fed_get_data(
				'whatsapp.layout.chat.title',
				$this->settings,
				__( 'How may I help you?', 'frontend-dashboard-social-chat' )
			);
			$header_title = fed_get_data(
				'whatsapp.layout.header.title',
				$this->settings,
				__( 'Start a Conversation', 'frontend-dashboard-social-chat' )
			);
			$header_sub_title = fed_get_data(
				'whatsapp.layout.header.sub_title',
				$this->settings,
				__( 'Click one of our team members below to chat on WhatsApp.', 'frontend-dashboard-social-chat' )
			);
			?>
			<div class="bc_fed fed_schat_widget_root" id="fed_wa_container">
				
				<!-- Popup Chat Box Window -->
				<div class="fed_wa_container fed_hide" id="fed_wa_box">
					<div class="fed_wa_header_container">
						<div class="fed_wa_header_top">
							<div class="fed_wa_header_brand">
								<div class="fed_wa_logo_badge">
									<i class="fab fa-whatsapp"></i>
								</div>
								<div>
									<h4 class="fed_wa_header_title"><?php echo esc_html( $header_title ); ?></h4>
									<p class="fed_wa_header_sub_title"><?php echo esc_html( $header_sub_title ); ?></p>
								</div>
							</div>
							<button type="button" class="fed_wa_box_close" id="fed_wa_box_close" aria-label="Close">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>

					<div class="fed_wa_body_container">
						<?php if ( ! empty( $announcement ) ) : ?>
							<div class="fed_wa_announcement_pill">
								<i class="fas fa-bolt"></i> <?php echo esc_html( $announcement ); ?>
							</div>
						<?php endif; ?>

						<div class="fed_wa_agents_list">
							<?php
							foreach ( $users as $index => $user ) {
								$name   = fed_get_data( 'name', $user, '' );
								$number = preg_replace( '/[^0-9]/', '', fed_get_data( 'number', $user, '' ) );
								$status = fed_get_data( 'status', $user, 'active' );
								$role   = fed_get_data( 'role', $user, '' );
								$is_online = ( 'active' === $status );
								$url    = $is_online ? 'https://wa.me/' . $number : '#';
								?>
								<a class="fed_wa_agent_card <?php echo $is_online ? 'online' : 'offline'; ?>" <?php echo $is_online ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> href="<?php echo esc_url( $url ); ?>">
									<div class="fed_wa_agent_avatar_wrap">
										<div class="fed_wa_agent_avatar">
											<i class="fas fa-headset"></i>
										</div>
										<span class="fed_wa_status_indicator <?php echo $is_online ? 'online' : 'offline'; ?>"></span>
									</div>
									<div class="fed_wa_agent_info">
										<div class="fed_wa_agent_name"><?php echo esc_html( $name ); ?></div>
										<?php if ( ! empty( $role ) ) : ?>
											<div class="fed_wa_agent_role"><?php echo esc_html( $role ); ?></div>
										<?php endif; ?>
										<div class="fed_wa_agent_status_text <?php echo $is_online ? 'online' : 'offline'; ?>">
											<?php echo $is_online ? esc_html__( 'Online - Instant Reply', 'frontend-dashboard-social-chat' ) : esc_html__( 'Offline', 'frontend-dashboard-social-chat' ); ?>
										</div>
									</div>
									<div class="fed_wa_agent_action">
										<div class="fed_wa_chat_icon_btn">
											<i class="fab fa-whatsapp"></i>
										</div>
									</div>
								</a>
							<?php } ?>
						</div>
					</div>

					<?php if ( ! empty( $footer_title ) ) : ?>
						<div class="fed_wa_footer_notice">
							<i class="fas fa-info-circle"></i> <?php echo esc_html( $footer_title ); ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Floating Action Trigger (Bottom Button) -->
				<div class="fed_wa_footer_container">
					<div class="fed_wa_floating_trigger" id="fed_wa_trigger">
						<?php if ( ! empty( $chat_prompt ) ) : ?>
							<div class="fed_wa_prompt_bubble" id="fed_wa_prompt_bubble">
								<span><?php echo esc_html( $chat_prompt ); ?></span>
								<div class="fed_wa_prompt_arrow"></div>
							</div>
						<?php endif; ?>
						<button type="button" class="fed_wa_floating_btn" aria-label="Open WhatsApp Chat">
							<i class="fab fa-whatsapp"></i>
							<span class="fed_wa_btn_pulse"></span>
						</button>
					</div>
				</div>

			</div>
			<?php
		}
	}

	new FEDSCHATWhatsappLayout();
}

