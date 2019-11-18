<?php

if ( ! class_exists('FEDSCHATWhatsappLayout')) {
    /**
     * Class FEDSCHATWhatsappLayout
     */
    class FEDSCHATWhatsappLayout
    {
        /**
         * @var void
         */
        public $settings;

        public function __construct()
        {
            add_action('wp_footer', array($this, 'layout'));
            add_action('wp_enqueue_scripts', array($this, 'scripts'));
            add_action('admin_enqueue_scripts', array($this, 'scripts'));
            add_action('fed_head_css', array($this, 'custom_css'));
            $this->settings = get_option('fed_social_chat_settings');
        }

        /**
         * @param $css
         */
        public function custom_css($css)
        {
            ?>
            <style>
                .fed_wa_user_status.online {
                    background: <?php echo $css['pbg_color'] ?>;
                }

                .fed_wa_body_user_status_message.active {
                    color: <?php echo $css['pbg_color'] ?>;
                }

                .fed_wa_body_user_status_message.inactive {
                    color: <?php echo $css['sbg_color'] ?>;
                }

                .fed_wa_body_footer_wrapper,
                .fed_wa_footer_chat_container,
                .fed_wa_header_container {
                    background: <?php echo $css['pbg_color'] ?>;
                    color: <?php echo $css['pbg_font_color'] ?>;
                }

                .fed_wa_close {
                    background: <?php echo $css['sbg_color'] ?>;
                }

                .fed_wa_body_user.active {
                    border-left: 3px solid<?php echo $css['pbg_color'] ?>;
                    color: <?php echo $css['pbg_color'] ?>;
                }

                .fed_wa_body_user.inactive {
                    border-left: 3px solid<?php echo $css['sbg_color'] ?>;
                    color: <?php echo $css['sbg_color'] ?>;
                }
            </style>

            <?php
        }

        /**
         * @param $scripts
         *
         *
         */
        public function scripts()
        {
            FED_Log::writeLog('$message');
            if ($this->is_enable()) {
                FED_Log::writeLog('ddd');
                wp_enqueue_style('fed_schat_style', plugins_url('/assets/fed_schat_style.css', BC_FED_SCHAT_PLUGIN),
                    array(), BC_FED_SCHAT_PLUGIN_VERSION, 'all');
                wp_enqueue_style('fed_admin_font_awesome',
                    plugins_url('/common/assets/css/fontawesome.css', BC_FED_PLUGIN),
                    array(), BC_FED_PLUGIN_VERSION, 'all');
                wp_enqueue_style('fed_admin_font_awesome-shims', plugins_url('/common/assets/css/fontawesome-shims.css',
                    BC_FED_PLUGIN),
                    array(), BC_FED_PLUGIN_VERSION, 'all');
                wp_enqueue_script('fed_schat_script', plugins_url('/assets/fed_schat_script.js', BC_FED_SCHAT_PLUGIN),
                    array('jquery'));
            }
        }

        /**
         * @return bool
         */
        public function is_enable()
        {
            $is_enable    = fed_get_data('whatsapp.settings.enable', $this->settings, false);
            $user_allowed = fed_get_data('whatsapp.settings.users.allow', $this->settings,
                array());
            if (is_user_logged_in()) {
                $user_can = fed_is_current_user_role($user_allowed);
            } else {
                $user_can = array_key_exists('unregistered', $user_allowed);
            }


            return $is_enable && $is_enable === 'Enable' && $user_can;
        }

        public function layout()
        {
            if ($this->is_enable()) {
                $users = fed_get_data('whatsapp.users.details', $this->settings, false);
                if ($users) {
                    $users = unserialize($users);
                    ?>
                    <div class="bc_fed" id="fed_wa_container">
                        <div class="fed_wa_container fed_hide">
                            <div class="fed_wa_header_container">
                                <div class="fed_wa_header_wrapper">
                                    <div class="fed_wa_logo">
                                        <div class="fed_wa_logo_wrapper">
                                            <i class="fa fa-whatsapp fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="fed_wa_header_title_wrapper">
                                        <div class="fed_wa_header_title">
                                            <?php echo fed_get_data('whatsapp.layout.header.title', $this->settings,
                                                __('Start a Conversation', 'frontend-dashboard-social-chat')); ?>
                                        </div>
                                        <div class="fed_wa_header_sub_title">
                                            <?php echo fed_get_data('whatsapp.layout.header.sub_title',
                                                $this->settings,
                                                __('This will show in the Top Header Title of the Chat Window',
                                                    'frontend-dashboard-social-chat')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fed_wa_body_container">
                                <div class="fed_wa_body_wrapper">
                                    <div class="fed_wa_body_announcement">
                                        <?php echo fed_get_data('whatsapp.layout.body.title', $this->settings,
                                            __('The team typically replies in a few minutes',
                                                'frontend-dashboard-social-chat')); ?>
                                    </div>
                                    <div class="fed_wa_body_users">
                                        <?php
                                        foreach ($users as $index => $user) {
                                            $name   = fed_get_data('name', $user);
                                            $number = fed_get_data('number', $user);
                                            $status = fed_get_data('status', $user);
                                            $role   = fed_get_data('role', $user);
                                            $url    = $status === 'active' ? 'https://web.whatsapp.com/send?phone='.$number : '#';

                                            ?>
                                            <a class="fed_wa_user_link <?php echo $status; ?>" target="_blank"
                                               href="<?php echo $url; ?>">
                                                <div class="fed_wa_body_user <?php echo $status; ?>">

                                                    <div class="fed_wa_body_user_image">
                                                        <i class="fa fa-user-circle fa-3x <?php echo $status === 'active' ? 'active' : 'inactive'; ?>"></i>
                                                    </div>
                                                    <div class="fed_wa_body_user_text">
                                                        <div class="fed_wa_body_user_title">
                                                            <?php echo $name; ?>
                                                        </div>
                                                        <div class="fed_wa_body_user_sub_title">
                                                            <?php echo $role; ?>
                                                        </div>
                                                        <div class="fed_wa_body_user_status_message <?php echo $status; ?>">
                                                            <?php echo strtoupper($status); ?>
                                                        </div>
                                                    </div>

                                                </div>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="fed_wa_body_footer_wrapper">
                                    <div class="fed_wa_body_footer_item">
                                        <?php echo fed_get_data('whatsapp.layout.footer.title',
                                            $this->settings,
                                            __('Call us to +9999999999 from 0:00hrs to 24:00hrs',
                                                'frontend-dashboard-social-chat')); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="fed_wa_footer_container">
                            <div class="fed_wa_footer_wrapper">
                                <div class="fed_wa_close fed_hide">
                                    <div class="fed_wa_close_x ">
                                        <i class="fa fa-times fa-2x"></i>
                                    </div>
                                </div>
                                <div class="fed_wa_footer_chat">
                                    <div class="fed_wa_footer_chat_container">
                                        <div class="fed_wa_footer_chat_logo">
                                            <i class="fa fa-whatsapp fa-2x"></i>
                                        </div>
                                        <div class="fed_wa_footer_chat_message">
                                            <?php echo fed_get_data('whatsapp.layout.chat.title', $this->settings,
                                                __('How may I help you', 'frontend-dashboard-social-chat')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }

    new FEDSCHATWhatsappLayout();
}