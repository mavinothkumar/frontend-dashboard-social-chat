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

            $form = array(
                'form'  => array(
                    'method' => '',
                    'class'  => 'fed_admin_menu fed_ajax',
                    'attr'   => '',
                    'action' => array(
                        'url'        => '',
                        'action'     => 'fed_ajax_request',
                        'parameters' => array(
                            'fed_action_hook' => 'FEDSCHATWhatsapp@settings_update',
                        ),
                    ),
                    'nonce'  => array('action' => '', 'name' => ''),
                    'loader' => '',
                ),
                'input' => array(
                    'Enable'    => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Enable Whatsapp Chat', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'settings[enable]',
                                'user_value'  => fed_get_data('whatsapp.settings.enable', $this->settings),
                                'input_type'  => 'radio',
                                'input_value' => array(
                                    'Enable'  => __('Enable', BC_FED_SCHAT_PLUGIN_SLUG),
                                    'Disable' => __('Disable', BC_FED_SCHAT_PLUGIN_SLUG),
                                ),
                                //'is_required' => true,
                                'class_name'  => 'm-r-10',
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('By Checking this, you are enabling the Whatsapp Chat Message',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                    'User Role' => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Allow User Roles to View Whatsapp Button', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        => fed_user_role_checkboxes('settings[users][allow]',
                            fed_get_data('whatsapp.settings.users.allow',
                                $this->settings, array()), 4, array('unregistered' => 'Unregistered')),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('Allow this users roles to view the Whatsapp Chat Screen',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                ),
            );

            fed_common_simple_layout($form);


        }

        public function layout()
        {
            $this->authorize();
            $form = array(
                'form'  => array(
                    'method' => '',
                    'class'  => 'fed_admin_menu fed_ajax',
                    'attr'   => '',
                    'action' => array(
                        'url'        => '',
                        'action'     => 'fed_ajax_request',
                        'parameters' => array(
                            'fed_action_hook' => 'FEDSCHATWhatsapp@layout_update',
                        ),
                    ),
                    'nonce'  => array('action' => '', 'name' => ''),
                    'loader' => '',
                ),
                'input' => array(
                    'Chat Message'     => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Chat Message', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'layout[chat][title]',
                                'user_value'  => fed_get_data('whatsapp.layout.chat.title', $this->settings),
                                'input_type'  => 'single_line',
                                'placeholder' => __('How may I help you', 'frontend-dashboard-social-chat'),
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('This will show in the Initial Chat Display',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                    'Header Title'     => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Header Title', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'layout[header][title]',
                                'user_value'  => fed_get_data('whatsapp.layout.header.title', $this->settings),
                                'input_type'  => 'single_line',
                                'placeholder' => __('Start a Conversation', 'frontend-dashboard-social-chat'),
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('This will show in the Top Header Title of the Chat Window',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                    'Header Sub Title' => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Header Sub Title', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'layout[header][sub_title]',
                                'user_value'  => fed_get_data('whatsapp.layout.header.sub_title', $this->settings),
                                'input_type'  => 'single_line',
                                'placeholder' => __('Hi! Click one of our members below to chat on WhatsApp',
                                    'frontend-dashboard-social-chat'),
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('This will show in the Top Header Sub Title of the Chat Window',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                    'Body Title'       => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Body Title', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'layout[body][title]',
                                'user_value'  => fed_get_data('whatsapp.layout.body.title', $this->settings),
                                'input_type'  => 'single_line',
                                'placeholder' => __('The team typically replies in a few minutes',
                                    'frontend-dashboard-social-chat'),
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('This will show in the Top Body Title of the Chat Window',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                    'Footer Title'     => array(
                        'col'          => 'col-md-12',
                        'name'         => __('Footer Title', BC_FED_SCHAT_PLUGIN_SLUG),
                        'input'        =>
                            fed_get_input_details(array(
                                'input_meta'  => 'layout[footer][title]',
                                'user_value'  => fed_get_data('whatsapp.layout.footer.title', $this->settings),
                                'input_type'  => 'single_line',
                                'placeholder' => __('Call us to +9999999999 from 0:00hrs to 24:00hrs',
                                    'frontend-dashboard-social-chat'),
                            )),
                        'help_message' => fed_show_help_message(array(
                            'content' => __('This will show in the Top Footer Title of the Chat Window',
                                BC_FED_SCHAT_PLUGIN_SLUG),
                        )),
                    ),
                ),
            );
            fed_common_simple_layout($form);
        }

        public function users()
        {
            $this->authorize();
            $users = fed_get_data('whatsapp.users.details', $this->settings, false);

            $hide = true;
            echo fed_loader();
            ?>
            <div class="bc_fed">
                <div class="container">
                    <div class="fed_whatsapp_users_list_container">
                        <form class="fed_ajax" method="post"
                              action="<?php echo fed_get_ajax_form_action('fed_ajax_request').'&fed_action_hook=FEDSCHATWhatsapp@user_update'; ?>">
                            <?php fed_wp_nonce_field('fed_nonce', 'fed_nonce') ?>
                            <div class="fed_whatsapp_add_new_user_form">
                                <button class="btn btn-primary" type="button" id="fed_whatsapp_add_new_user_button"
                                        data-url="<?php echo fed_get_ajax_form_action('fed_ajax_request').'&fed_action_hook=FEDSCHATWhatsapp@ajax_dummy_user_form&fed_nonce='.wp_create_nonce('fed_nonce'); ?>">
                                    <i class="fa fa-user-plus"></i>
                                    <?php _e('Add New User', 'frontend-dashboard-social-chat') ?>
                                </button>
                            </div>
                            <div class="fed_whatsapp_users_list m-t-20">
                                <?php
                                if ($users) {
                                    $hide  = false;
                                    $users = unserialize($users);
                                    foreach ($users as $index => $user) {
                                        $name   = fed_get_data('name', $user);
                                        $number = fed_get_data('number', $user);
                                        $status = fed_get_data('status', $user);
                                        $role   = fed_get_data('role', $user);
                                        echo '<div class="row fed_whatsapp_user_list">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>'.__('User Name', 'frontend-dashboard-social-chat').'</label>
                                    <input type="text" name="user['.$index.'][name]" value="'.$name.'" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>'.__('Phone Number', 'frontend-dashboard-social-chat').'</label>
                                    <input type="text" name="user['.$index.'][number]" value="'.$number.'" class="form-control"/>
                                    <small>'.__('With Country Code & Without +', 'frontend-dashboard-social-chat').'</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                    <div class="form-group">
                        <label>'.__('Role', 'frontend-dashboard-social-chat').'</label>
                        <input type="text" name="user['.$index.'][role]" value="'.$role.'"  class="form-control"/>
                    </div>
                </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>'.__('Status', 'frontend-dashboard-social-chat').'</label>
                                    '.fed_get_input_details(array(
                                                'input_meta'  => 'user['.$index.'][status]',
                                                'input_type'  => 'select',
                                                'user_value'  => $status,
                                                'input_value' => array(
                                                    'active'   => 'Active',
                                                    'inactive' => 'Inactive',
                                                ),
                                            )).'
                                </div>
                            </div>
                            <div class="col-md-2">
                            <label></label>
                                <div class="form-group fed_whatsapp_delete_user_form">
                                    <i class="fa fa-times fa-2x"></i>
                                </div>
                            </div>
                        </div>';
                                    }
                                }
                                ?>
                            </div>

                            <div class="form-group <?php echo $hide ? 'hide' : ''; ?>"
                                 id="fed_whatsapp_add_user_form_submit">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
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

            fed_set_data($this->settings, 'whatsapp.settings.enable',
                fed_get_data('settings.enable', $request, 'Disable'));
            fed_set_data($this->settings, 'whatsapp.settings.users.allow',
                fed_get_data('settings.users.allow', $request, array()));

            update_option('fed_social_chat_settings', $this->settings);

            wp_send_json_success(array(
                'message' => __('Whatsapp Settings Successfully Updated', BC_FED_SCHAT_PLUGIN_SLUG),
            ));
        }

        /**
         * @param $request
         */
        public function layout_update($request)
        {
            $this->authorize();

            fed_set_data($this->settings, 'whatsapp.layout.chat.title',
                fed_get_data('layout.chat.title', $request, 'Disable'));
            fed_set_data($this->settings, 'whatsapp.layout.header.title',
                fed_get_data('layout.header.title', $request, 'Disable'));
            fed_set_data($this->settings, 'whatsapp.layout.header.sub_title',
                fed_get_data('layout.header.sub_title', $request, array()));
            fed_set_data($this->settings, 'whatsapp.layout.body.title',
                fed_get_data('layout.body.title', $request, array()));
            fed_set_data($this->settings, 'whatsapp.layout.footer.title',
                fed_get_data('layout.footer.title', $request, array()));

            update_option('fed_social_chat_settings', $this->settings);

            wp_send_json_success(array(
                'message' => __('Whatsapp Layout Settings Successfully Updated', BC_FED_SCHAT_PLUGIN_SLUG),
            ));
        }

        /**
         * @param $request
         */
        public function user_update($request)
        {
            $this->authorize();
            if (isset($request['user']) && count($request['user'])) {
                fed_set_data($this->settings, 'whatsapp.users.details',
                    serialize($request['user']));
            }
            update_option('fed_social_chat_settings', $this->settings);

            wp_send_json_success(array(
                'message' => __('Whatsapp Layout Settings Successfully Updated', BC_FED_SCHAT_PLUGIN_SLUG),
            ));
        }

        /**
         * @return false|string
         */
        public function ajax_dummy_user_form()
        {
            $random = fed_get_random_string(6);
            $html   = '';

            $html .= '<div class="row fed_whatsapp_user_list">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>'.__('User Name', 'frontend-dashboard-social-chat').'</label>
                        <input type="text" name="user['.$random.'][name]" class="form-control"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>'.__('Phone Number', 'frontend-dashboard-social-chat').'</label>
                        <input type="text" name="user['.$random.'][number]" class="form-control"/>
                        <small>'.__('With Country Code & Without +', 'frontend-dashboard-social-chat').'</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>'.__('Role', 'frontend-dashboard-social-chat').'</label>
                        <input type="text" name="user['.$random.'][role]" class="form-control"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>'.__('Status', 'frontend-dashboard-social-chat').'</label>
                        '.fed_get_input_details(array(
                    'input_meta'  => 'user['.$random.'][status]',
                    'user_value'  => '',
                    'input_type'  => 'select',
                    'input_value' => array('active' => 'Active', 'inactive' => 'Inactive'),
                )).'
                    </div>
                </div>
                <div class="col-md-2">
                <label></label>
                    <div class="form-group fed_whatsapp_delete_user_form">
                        <i class="fa fa-times fa-2x"></i>
                    </div>
                </div>
            </div>';

            wp_send_json_success(array('html' => $html));
        }
    }
}