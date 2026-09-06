<?php
if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('FEDSCHATWhatsapp')) {
    /**
     * Class FEDSCHATWhatsapp
     */
    class FEDSCHATWhatsapp
    {
        /**
         * @var void
         */
        private $settings;

        public function __construct()
        {
            $this->settings = get_option('fed_social_chat_settings');
        }

        public function authorize()
        {
            if ( ! fed_is_admin()) {
                wp_die(__('Sorry! You are not allowed to do this action | Error: FEDSCHAT|Admin|Settings|FEDSCHATWhatsapp@authorize',
                    BC_FED_SCHAT_PLUGIN_SLUG));
            }

        }

        public function settings()
        {
            $this->authorize();
            $enable        = fed_get_data( 'whatsapp.settings.enable', $this->settings, 'Disable' );
            $allowed_roles = fed_get_data( 'whatsapp.settings.users.allow', $this->settings, array() );
            if ( ! is_array( $allowed_roles ) ) {
                $allowed_roles = array();
            }

            global $wp_roles;
            $roles = isset( $wp_roles ) ? $wp_roles->get_names() : array();
            $all_roles = array_merge(
                array( 'unregistered' => __( 'Unregistered (Guests / Visitors)', 'frontend-dashboard-social-chat' ) ),
                $roles
            );

            echo fed_loader();
            ?>
            <div class="bc_fed fed_whatsapp_settings_manager" style="font-family: inherit;">
                <form class="fed_ajax" method="post" action="<?php echo esc_url( fed_get_ajax_form_action( 'fed_ajax_request' ) . '&fed_action_hook=FEDSCHATWhatsapp@settings_update' ); ?>">
                    <?php fed_wp_nonce_field( 'fed_nonce', 'fed_nonce' ); ?>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        
                        <!-- Enable / Disable Card -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                            <label style="display: block; font-size: 14.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                                <i class="fas fa-power-off" style="color: #128c7e; margin-right: 8px;"></i> <?php esc_html_e( 'WhatsApp Widget Status', 'frontend-dashboard-social-chat' ); ?>
                            </label>
                            <p style="margin: 0 0 16px 0; font-size: 13px; color: #64748b;">
                                <?php esc_html_e( 'Enable or disable the floating WhatsApp customer support widget on your frontend site.', 'frontend-dashboard-social-chat' ); ?>
                            </p>

                            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                <label style="flex: 1; min-width: 180px; cursor: pointer; position: relative;">
                                    <input type="radio" name="settings[enable]" value="Enable" <?php checked( $enable, 'Enable' ); ?> style="position: absolute; opacity: 0;" class="fed_schat_radio_trigger" />
                                    <div class="fed_schat_status_card <?php echo ( 'Enable' === $enable ) ? 'active' : ''; ?>" style="border: 2px solid <?php echo ( 'Enable' === $enable ) ? '#22c55e' : '#e2e8f0'; ?>; background: <?php echo ( 'Enable' === $enable ) ? '#f0fdf4' : '#ffffff'; ?>; border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; transition: all 0.2s ease;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo ( 'Enable' === $enable ) ? '#dcfce7' : '#f1f5f9'; ?>; color: <?php echo ( 'Enable' === $enable ) ? '#16a34a' : '#94a3b8'; ?>; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; font-size: 14px; color: <?php echo ( 'Enable' === $enable ) ? '#15803d' : '#1e293b'; ?>;"><?php esc_html_e( 'Enabled', 'frontend-dashboard-social-chat' ); ?></div>
                                            <div style="font-size: 12px; color: #64748b;"><?php esc_html_e( 'Display widget to visitors', 'frontend-dashboard-social-chat' ); ?></div>
                                        </div>
                                    </div>
                                </label>

                                <label style="flex: 1; min-width: 180px; cursor: pointer; position: relative;">
                                    <input type="radio" name="settings[enable]" value="Disable" <?php checked( $enable, 'Disable' ); ?> style="position: absolute; opacity: 0;" class="fed_schat_radio_trigger" />
                                    <div class="fed_schat_status_card <?php echo ( 'Disable' === $enable ) ? 'active' : ''; ?>" style="border: 2px solid <?php echo ( 'Disable' === $enable ) ? '#ef4444' : '#e2e8f0'; ?>; background: <?php echo ( 'Disable' === $enable ) ? '#fef2f2' : '#ffffff'; ?>; border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; transition: all 0.2s ease;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo ( 'Disable' === $enable ) ? '#fee2e2' : '#f1f5f9'; ?>; color: <?php echo ( 'Disable' === $enable ) ? '#dc2626' : '#94a3b8'; ?>; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; font-size: 14px; color: <?php echo ( 'Disable' === $enable ) ? '#b91c1c' : '#1e293b'; ?>;"><?php esc_html_e( 'Disabled', 'frontend-dashboard-social-chat' ); ?></div>
                                            <div style="font-size: 12px; color: #64748b;"><?php esc_html_e( 'Hide widget completely', 'frontend-dashboard-social-chat' ); ?></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- User Roles Visibility Card -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                            <label style="display: block; font-size: 14.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                                <i class="fas fa-users-cog" style="color: #128c7e; margin-right: 8px;"></i> <?php esc_html_e( 'Target Audience & Role Visibility', 'frontend-dashboard-social-chat' ); ?>
                            </label>
                            <p style="margin: 0 0 14px 0; font-size: 13px; color: #64748b;">
                                <?php esc_html_e( 'Select which user roles can see and interact with the WhatsApp chat widget.', 'frontend-dashboard-social-chat' ); ?>
                            </p>

                            <?php
                            echo fed_user_role_checkboxes(
                                'settings[users][allow]',
                                $allowed_roles,
                                '4',
                                array( 'unregistered' => __( 'Unregistered (Guests / Visitors)', 'frontend-dashboard-social-chat' ) )
                            );
                            ?>
                        </div>

                        <!-- Action Submit Button -->
                        <div>
                            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: #033333; border: none; padding: 11px 26px; border-radius: 8px; font-weight: 600; font-size: 14px; color: #ffffff; cursor: pointer; box-shadow: 0 2px 8px rgba(3,51,51,0.2); transition: all 0.2s ease;">
                                <i class="fas fa-save"></i> <?php esc_html_e( 'Save General Settings', 'frontend-dashboard-social-chat' ); ?>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            <?php
        }

        public function layout()
        {
            $this->authorize();
            $chat_title       = fed_get_data( 'whatsapp.layout.chat.title', $this->settings, __( 'How may I help you?', 'frontend-dashboard-social-chat' ) );
            $header_title     = fed_get_data( 'whatsapp.layout.header.title', $this->settings, __( 'Start a Conversation', 'frontend-dashboard-social-chat' ) );
            $header_sub_title = fed_get_data( 'whatsapp.layout.header.sub_title', $this->settings, __( 'Click one of our team members below to chat on WhatsApp.', 'frontend-dashboard-social-chat' ) );
            $body_title       = fed_get_data( 'whatsapp.layout.body.title', $this->settings, __( 'The team typically replies in a few minutes', 'frontend-dashboard-social-chat' ) );
            $footer_title     = fed_get_data( 'whatsapp.layout.footer.title', $this->settings, '' );

            echo fed_loader();
            ?>
            <div class="bc_fed fed_whatsapp_layout_manager" style="font-family: inherit;">
                <form class="fed_ajax" method="post" action="<?php echo esc_url( fed_get_ajax_form_action( 'fed_ajax_request' ) . '&fed_action_hook=FEDSCHATWhatsapp@layout_update' ); ?>">
                    <?php fed_wp_nonce_field( 'fed_nonce', 'fed_nonce' ); ?>

                    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start;">
                        
                        <!-- Left: Form Inputs Column -->
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            
                            <!-- Field 1: Chat Prompt Bubble -->
                            <div class="fed_schat_form_card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <label for="fed_schat_input_chat_title" style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <i class="fas fa-comment-dots" style="color: #128c7e;"></i>
                                    <?php esc_html_e( 'Floating Prompt Message', 'frontend-dashboard-social-chat' ); ?>
                                </label>
                                <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #64748b;">
                                    <?php esc_html_e( 'Displayed in the floating prompt bubble next to the WhatsApp button to invite conversation.', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                                <div style="position: relative;">
                                    <input type="text" id="fed_schat_input_chat_title" name="layout[chat][title]" value="<?php echo esc_attr( $chat_title ); ?>" placeholder="<?php esc_attr_e( 'e.g. How may I help you?', 'frontend-dashboard-social-chat' ); ?>" class="form-control fed_schat_live_input" data-target="#fed_schat_preview_chat_bubble" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1e293b; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" />
                                </div>
                            </div>

                            <!-- Field 2: Modal Header Title -->
                            <div class="fed_schat_form_card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <label for="fed_schat_input_header_title" style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <i class="fas fa-heading" style="color: #128c7e;"></i>
                                    <?php esc_html_e( 'Chat Window Header Title', 'frontend-dashboard-social-chat' ); ?>
                                </label>
                                <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #64748b;">
                                    <?php esc_html_e( 'The primary headline displayed in the header of the popup chat box.', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                                <div style="position: relative;">
                                    <input type="text" id="fed_schat_input_header_title" name="layout[header][title]" value="<?php echo esc_attr( $header_title ); ?>" placeholder="<?php esc_attr_e( 'e.g. Start a Conversation', 'frontend-dashboard-social-chat' ); ?>" class="form-control fed_schat_live_input" data-target="#fed_schat_preview_header_title" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1e293b; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" />
                                </div>
                            </div>

                            <!-- Field 3: Modal Header Subtitle -->
                            <div class="fed_schat_form_card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <label for="fed_schat_input_header_sub_title" style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <i class="fas fa-align-left" style="color: #128c7e;"></i>
                                    <?php esc_html_e( 'Chat Window Subtitle / Description', 'frontend-dashboard-social-chat' ); ?>
                                </label>
                                <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #64748b;">
                                    <?php esc_html_e( 'A brief greeting or prompt shown underneath the header title.', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                                <div style="position: relative;">
                                    <input type="text" id="fed_schat_input_header_sub_title" name="layout[header][sub_title]" value="<?php echo esc_attr( $header_sub_title ); ?>" placeholder="<?php esc_attr_e( 'e.g. Click one of our team members below to chat on WhatsApp.', 'frontend-dashboard-social-chat' ); ?>" class="form-control fed_schat_live_input" data-target="#fed_schat_preview_header_sub_title" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1e293b; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" />
                                </div>
                            </div>

                            <!-- Field 4: Body Announcement -->
                            <div class="fed_schat_form_card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <label for="fed_schat_input_body_title" style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <i class="fas fa-bolt" style="color: #128c7e;"></i>
                                    <?php esc_html_e( 'Team Status & Response Time Badge', 'frontend-dashboard-social-chat' ); ?>
                                </label>
                                <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #64748b;">
                                    <?php esc_html_e( 'Highlighted notice chip displayed above the agent list (leave blank to hide).', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                                <div style="position: relative;">
                                    <input type="text" id="fed_schat_input_body_title" name="layout[body][title]" value="<?php echo esc_attr( $body_title ); ?>" placeholder="<?php esc_attr_e( 'e.g. The team typically replies in a few minutes', 'frontend-dashboard-social-chat' ); ?>" class="form-control fed_schat_live_input" data-target="#fed_schat_preview_body_title" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1e293b; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" />
                                </div>
                            </div>

                            <!-- Field 5: Footer Notice / Contact Info -->
                            <div class="fed_schat_form_card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                <label for="fed_schat_input_footer_title" style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <i class="fas fa-phone-alt" style="color: #128c7e;"></i>
                                    <?php esc_html_e( 'Footer Contact & Notice', 'frontend-dashboard-social-chat' ); ?>
                                </label>
                                <p style="margin: 0 0 12px 0; font-size: 12.5px; color: #64748b;">
                                    <?php esc_html_e( 'Optional phone number or assistance note displayed at the very bottom of the chat box.', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                                <div style="position: relative;">
                                    <input type="text" id="fed_schat_input_footer_title" name="layout[footer][title]" value="<?php echo esc_attr( $footer_title ); ?>" placeholder="<?php esc_attr_e( 'e.g. Call us at +1 800 555 0199 (Mon - Fri)', 'frontend-dashboard-social-chat' ); ?>" class="form-control fed_schat_live_input" data-target="#fed_schat_preview_footer_title" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #1e293b; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: #033333; border: none; padding: 11px 26px; border-radius: 8px; font-weight: 600; font-size: 14px; color: #ffffff; cursor: pointer; box-shadow: 0 2px 8px rgba(3,51,51,0.2); transition: all 0.2s ease;">
                                    <i class="fas fa-save"></i> <?php esc_html_e( 'Save Layout Settings', 'frontend-dashboard-social-chat' ); ?>
                                </button>
                            </div>

                        </div>

                        <!-- Right: Real-time Live Interactive Preview Column -->
                        <div style="position: sticky; top: 30px;">
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 4px 14px rgba(0,0,0,0.05);">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.04em;">
                                        <i class="fas fa-eye" style="color: #128c7e; margin-right: 6px;"></i> <?php esc_html_e( 'Live Preview', 'frontend-dashboard-social-chat' ); ?>
                                    </span>
                                    <span style="font-size: 11px; background: #dcfce7; color: #16a34a; font-weight: 600; padding: 2px 8px; border-radius: 9999px;">
                                        <?php esc_html_e( 'Real-time', 'frontend-dashboard-social-chat' ); ?>
                                    </span>
                                </div>

                                <!-- Mockup Widget Window -->
                                <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(0,0,0,0.08); background: #ffffff;">
                                    
                                    <!-- Header -->
                                    <div style="background: linear-gradient(135deg, #075E54 0%, #128C7E 100%); color: #ffffff; padding: 14px 16px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                                    <i class="fab fa-whatsapp"></i>
                                                </div>
                                                <div>
                                                    <div id="fed_schat_preview_header_title" style="font-size: 13.5px; font-weight: 700; line-height: 1.2;">
                                                        <?php echo esc_html( $header_title ); ?>
                                                    </div>
                                                    <div id="fed_schat_preview_header_sub_title" style="font-size: 11px; color: rgba(255,255,255,0.85); margin-top: 2px;">
                                                        <?php echo esc_html( $header_sub_title ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width: 20px; height: 20px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div style="background: #f8fafc; padding: 12px;">
                                        <!-- Announcement -->
                                        <div id="fed_schat_preview_body_title" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 5px 10px; border-radius: 9999px; font-size: 10.5px; font-weight: 600; text-align: center; margin-bottom: 10px;">
                                            <i class="fas fa-bolt" style="margin-right: 4px;"></i> <?php echo esc_html( $body_title ); ?>
                                        </div>

                                        <!-- Sample Agent -->
                                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                                            <div style="position: relative;">
                                                <div style="width: 30px; height: 30px; border-radius: 50%; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                                    <i class="fas fa-headset"></i>
                                                </div>
                                                <span style="position: absolute; bottom: 0; right: 0; width: 8px; height: 8px; border-radius: 50%; background: #22c55e; border: 1.5px solid #ffffff;"></span>
                                            </div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-size: 12px; font-weight: 700; color: #0f172a;"><?php esc_html_e( 'Customer Support', 'frontend-dashboard-social-chat' ); ?></div>
                                                <div style="font-size: 10px; color: #16a34a; font-weight: 600;"><?php esc_html_e( 'Online', 'frontend-dashboard-social-chat' ); ?></div>
                                            </div>
                                            <div style="width: 24px; height: 24px; border-radius: 6px; background: #25D366; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="fab fa-whatsapp"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div id="fed_schat_preview_footer_title" style="background: #ffffff; border-top: 1px solid #f1f5f9; padding: 8px 12px; font-size: 10.5px; color: #64748b; text-align: center; font-weight: 500;">
                                        <i class="fas fa-info-circle" style="margin-right: 4px;"></i> <?php echo ! empty( $footer_title ) ? esc_html( $footer_title ) : esc_html__( 'Assistance available 24/7', 'frontend-dashboard-social-chat' ); ?>
                                    </div>

                                </div>

                                <!-- Floating Trigger Preview -->
                                <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 14px; gap: 8px;">
                                    <div id="fed_schat_preview_chat_bubble" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 6px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 600; color: #1e293b;">
                                        <?php echo esc_html( $chat_title ); ?>
                                    </div>
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #25D366; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <?php
        }

        public function users()
        {
            $this->authorize();
            $users_raw = fed_get_data('whatsapp.users.details', $this->settings, false);
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

            echo fed_loader();
            ?>
            <div class="bc_fed fed_whatsapp_users_manager" style="font-family: inherit;">
                <div class="fed_whatsapp_users_list_container">
                    <form class="fed_ajax" method="post" action="<?php echo esc_url( fed_get_ajax_form_action( 'fed_ajax_request' ) . '&fed_action_hook=FEDSCHATWhatsapp@user_update' ); ?>">
                        <?php fed_wp_nonce_field( 'fed_nonce', 'fed_nonce' ); ?>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">
                                    <?php esc_html_e( 'WhatsApp Support Agents', 'frontend-dashboard-social-chat' ); ?>
                                </h3>
                                <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b;">
                                    <?php esc_html_e( 'Add team members who will receive visitor WhatsApp messages directly.', 'frontend-dashboard-social-chat' ); ?>
                                </p>
                            </div>
                            <button class="btn btn-primary" type="button" id="fed_whatsapp_add_new_user_button"
                                    data-url="<?php echo esc_url( fed_get_ajax_form_action( 'fed_ajax_request' ) . '&fed_action_hook=FEDSCHATWhatsapp@ajax_dummy_user_form&fed_nonce=' . wp_create_nonce( 'fed_nonce' ) ); ?>"
                                    style="display: inline-flex; align-items: center; gap: 8px; background: #128c7e; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; color: #ffffff; cursor: pointer; transition: all 0.2s ease;">
                                <i class="fas fa-user-plus"></i>
                                <?php esc_html_e( 'Add Support Agent', 'frontend-dashboard-social-chat' ); ?>
                            </button>
                        </div>

                        <div class="fed_whatsapp_users_list" style="display: flex; flex-direction: column; gap: 14px;">
                            <?php
                            if ( ! empty( $users ) ) {
                                foreach ( $users as $index => $user ) {
                                    $name   = esc_attr( fed_get_data( 'name', $user, '' ) );
                                    $number = esc_attr( fed_get_data( 'number', $user, '' ) );
                                    $status = fed_get_data( 'status', $user, 'active' );
                                    $role   = esc_attr( fed_get_data( 'role', $user, '' ) );
                                    $is_active = ( $status === 'active' );
                                    ?>
                                    <div class="fed_whatsapp_user_card fed_whatsapp_user_list" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.2s ease; position: relative;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: <?php echo $is_active ? '#dcfce7' : '#f1f5f9'; ?>; color: <?php echo $is_active ? '#16a34a' : '#94a3b8'; ?>; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                    <i class="fas fa-headset"></i>
                                                </div>
                                                <span style="font-weight: 700; font-size: 14px; color: #1e293b;">
                                                    <?php echo ! empty( $name ) ? $name : esc_html__( 'New Support Agent', 'frontend-dashboard-social-chat' ); ?>
                                                </span>
                                            </div>
                                            <button type="button" class="fed_whatsapp_delete_user_form" title="<?php esc_attr_e( 'Remove Agent', 'frontend-dashboard-social-chat' ); ?>" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s ease;">
                                                <i class="fas fa-trash-alt" style="font-size: 13px;"></i>
                                            </button>
                                        </div>

                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                                            <div class="form-group" style="margin: 0;">
                                                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                                                    <?php esc_html_e( 'Agent Name', 'frontend-dashboard-social-chat' ); ?> <span style="color: #ef4444;">*</span>
                                                </label>
                                                <input type="text" name="user[<?php echo esc_attr( $index ); ?>][name]" value="<?php echo $name; ?>" placeholder="<?php esc_attr_e( 'e.g. John Doe', 'frontend-dashboard-social-chat' ); ?>" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" required />
                                            </div>

                                            <div class="form-group" style="margin: 0;">
                                                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                                                    <?php esc_html_e( 'WhatsApp Phone Number', 'frontend-dashboard-social-chat' ); ?> <span style="color: #ef4444;">*</span>
                                                </label>
                                                <input type="text" name="user[<?php echo esc_attr( $index ); ?>][number]" value="<?php echo $number; ?>" placeholder="<?php esc_attr_e( 'e.g. 15551234567 (with country code)', 'frontend-dashboard-social-chat' ); ?>" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" required />
                                                <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;"><?php esc_html_e( 'Numbers only, no spaces or + symbol.', 'frontend-dashboard-social-chat' ); ?></small>
                                            </div>

                                            <div class="form-group" style="margin: 0;">
                                                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                                                    <?php esc_html_e( 'Department / Role', 'frontend-dashboard-social-chat' ); ?>
                                                </label>
                                                <input type="text" name="user[<?php echo esc_attr( $index ); ?>][role]" value="<?php echo $role; ?>" placeholder="<?php esc_attr_e( 'e.g. Technical Support, Sales', 'frontend-dashboard-social-chat' ); ?>" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" />
                                            </div>

                                            <div class="form-group" style="margin: 0;">
                                                <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                                                    <?php esc_html_e( 'Status', 'frontend-dashboard-social-chat' ); ?>
                                                </label>
                                                <select name="user[<?php echo esc_attr( $index ); ?>][status]" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;">
                                                    <option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active (Online)', 'frontend-dashboard-social-chat' ); ?></option>
                                                    <option value="inactive" <?php selected( $status, 'inactive' ); ?>><?php esc_html_e( 'Inactive (Offline)', 'frontend-dashboard-social-chat' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <!-- Empty State Notice (shown if 0 users) -->
                        <div id="fed_whatsapp_empty_state" style="display: <?php echo empty( $users ) ? 'block' : 'none'; ?>; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 36px 20px; text-align: center; margin: 16px 0;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 12px;">
                                <i class="fas fa-users-slash"></i>
                            </div>
                            <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #334155;">
                                <?php esc_html_e( 'No Support Agents Added Yet', 'frontend-dashboard-social-chat' ); ?>
                            </h4>
                            <p style="margin: 0; font-size: 13px; color: #64748b;">
                                <?php esc_html_e( 'Click "Add Support Agent" above to create your first WhatsApp agent profile.', 'frontend-dashboard-social-chat' ); ?>
                            </p>
                        </div>

                        <div class="form-group <?php echo empty( $users ) ? 'hide' : ''; ?>" id="fed_whatsapp_add_user_form_submit" style="margin-top: 24px;">
                            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: #033333; border: none; padding: 11px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; color: #ffffff; cursor: pointer; box-shadow: 0 2px 8px rgba(3,51,51,0.2);">
                                <i class="fas fa-save"></i> <?php esc_html_e( 'Save All Agents', 'frontend-dashboard-social-chat' ); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        }

        /**
         * @param $request
         */
        public function settings_update($request)
        {
            $this->authorize();

            fed_set_data(
                $this->settings,
                'whatsapp.settings.enable',
                fed_get_data( 'settings.enable', $request, 'Disable' ) === 'Enable' ? 'Enable' : 'Disable'
            );
            fed_set_data(
                $this->settings,
                'whatsapp.settings.users.allow',
                fed_get_data( 'settings.users.allow', $request, array() )
            );

            update_option( 'fed_social_chat_settings', $this->settings );

            wp_send_json_success( array(
                'message' => __( 'WhatsApp Settings successfully updated.', 'frontend-dashboard-social-chat' ),
            ) );
        }

        /**
         * @param $request
         */
        public function layout_update($request)
        {
            $this->authorize();

            fed_set_data(
                $this->settings,
                'whatsapp.layout.chat.title',
                sanitize_text_field( fed_get_data( 'layout.chat.title', $request, '' ) )
            );
            fed_set_data(
                $this->settings,
                'whatsapp.layout.header.title',
                sanitize_text_field( fed_get_data( 'layout.header.title', $request, '' ) )
            );
            fed_set_data(
                $this->settings,
                'whatsapp.layout.header.sub_title',
                sanitize_text_field( fed_get_data( 'layout.header.sub_title', $request, '' ) )
            );
            fed_set_data(
                $this->settings,
                'whatsapp.layout.body.title',
                sanitize_text_field( fed_get_data( 'layout.body.title', $request, '' ) )
            );
            fed_set_data(
                $this->settings,
                'whatsapp.layout.footer.title',
                sanitize_text_field( fed_get_data( 'layout.footer.title', $request, '' ) )
            );

            update_option( 'fed_social_chat_settings', $this->settings );

            wp_send_json_success( array(
                'message' => __( 'WhatsApp Layout Settings successfully updated.', 'frontend-dashboard-social-chat' ),
            ) );
        }

        /**
         * @param $request
         */
        public function user_update($request)
        {
            $this->authorize();
            $clean_users = array();

            if ( isset( $request['user'] ) && is_array( $request['user'] ) ) {
                foreach ( $request['user'] as $key => $user ) {
                    $name   = isset( $user['name'] ) ? sanitize_text_field( $user['name'] ) : '';
                    $number = isset( $user['number'] ) ? preg_replace( '/[^0-9]/', '', $user['number'] ) : '';
                    $role   = isset( $user['role'] ) ? sanitize_text_field( $user['role'] ) : '';
                    $status = ( isset( $user['status'] ) && $user['status'] === 'inactive' ) ? 'inactive' : 'active';

                    if ( ! empty( $name ) || ! empty( $number ) ) {
                        $clean_users[ $key ] = array(
                            'name'   => $name,
                            'number' => $number,
                            'role'   => $role,
                            'status' => $status,
                        );
                    }
                }
            }

            fed_set_data( $this->settings, 'whatsapp.users.details', serialize( $clean_users ) );
            update_option( 'fed_social_chat_settings', $this->settings );

            wp_send_json_success( array(
                'message' => __( 'WhatsApp Agents successfully updated.', 'frontend-dashboard-social-chat' ),
            ) );
        }

        /**
         * @return void
         */
        public function ajax_dummy_user_form()
        {
            $this->authorize();
            $random = fed_get_random_string( 6 );
            $html   = '';

            $html .= '<div class="fed_whatsapp_user_card fed_whatsapp_user_list" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.2s ease; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span style="font-weight: 700; font-size: 14px; color: #1e293b;">' . esc_html__( 'New Support Agent', 'frontend-dashboard-social-chat' ) . '</span>
                    </div>
                    <button type="button" class="fed_whatsapp_delete_user_form" title="' . esc_attr__( 'Remove Agent', 'frontend-dashboard-social-chat' ) . '" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s ease;">
                        <i class="fas fa-trash-alt" style="font-size: 13px;"></i>
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div class="form-group" style="margin: 0;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                            ' . esc_html__( 'Agent Name', 'frontend-dashboard-social-chat' ) . ' <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="user[' . esc_attr( $random ) . '][name]" placeholder="' . esc_attr__( 'e.g. John Doe', 'frontend-dashboard-social-chat' ) . '" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" required />
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                            ' . esc_html__( 'WhatsApp Phone Number', 'frontend-dashboard-social-chat' ) . ' <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="user[' . esc_attr( $random ) . '][number]" placeholder="' . esc_attr__( 'e.g. 15551234567 (with country code)', 'frontend-dashboard-social-chat' ) . '" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" required />
                        <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">' . esc_html__( 'Numbers only, no spaces or + symbol.', 'frontend-dashboard-social-chat' ) . '</small>
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                            ' . esc_html__( 'Department / Role', 'frontend-dashboard-social-chat' ) . '
                        </label>
                        <input type="text" name="user[' . esc_attr( $random ) . '][role]" placeholder="' . esc_attr__( 'e.g. Technical Support, Sales', 'frontend-dashboard-social-chat' ) . '" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;" />
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                            ' . esc_html__( 'Status', 'frontend-dashboard-social-chat' ) . '
                        </label>
                        <select name="user[' . esc_attr( $random ) . '][status]" class="form-control" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13.5px;">
                            <option value="active" selected>' . esc_html__( 'Active (Online)', 'frontend-dashboard-social-chat' ) . '</option>
                            <option value="inactive">' . esc_html__( 'Inactive (Offline)', 'frontend-dashboard-social-chat' ) . '</option>
                        </select>
                    </div>
                </div>
            </div>';

            wp_send_json_success( array( 'html' => $html ) );
        }
    }
}