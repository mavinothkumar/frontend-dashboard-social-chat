<?php
if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('FEDSCHATMenu')) {
    /**
     * Class FEDSCHATMenu
     */
    class FEDSCHATMenu
    {
        public function __construct()
        {
            add_filter('fed_add_main_sub_menu', array($this, 'menu'));
            add_filter('fed_plugin_versions', array($this, 'plugin_versions'));
        }

        /**
         * @param $menu
         *
         * @return mixed
         */
        public function menu($menu)
        {
            $menu['social_chat'] = array(
                'page_title' => __('Social Chat', BC_FED_SCHAT_PLUGIN_SLUG),
                'menu_title' => __('Social Chat', BC_FED_SCHAT_PLUGIN_SLUG),
                'capability' => 'manage_options',
                'callback'   => array($this, 'main_menu'),
                'position'   => 30,
            );

            return $menu;
        }

        public function main_menu()
        {
            $menus = apply_filters('fed_social_chat_menu', array(
                'whatsapp' => array(
                    'icon'    => 'fa fa-whatsapp',
                    'name'    => __('Whatsapp', BC_FED_SCHAT_PLUGIN_SLUG),
                    'submenu' => array(
                        'FEDSCHATWhatsapp@settings' => array(
                            'icon' => 'fa fa-cogs',
                            'name' => __('Settings', BC_FED_SCHAT_PLUGIN_SLUG),
                            'menu' => array('FEDSCHATWhatsapp@settings'),
                        ),
                        'FEDSCHATWhatsapp@users' => array(
                            'icon' => 'fa fa-users',
                            'name' => __('Users', BC_FED_SCHAT_PLUGIN_SLUG),
                            'menu' => array('FEDSCHATWhatsapp@users'),
                        ),
                        'FEDSCHATWhatsapp@layout'   => array(
                            'icon' => 'fa fa-paint-brush',
                            'name' => __('Layouts', BC_FED_SCHAT_PLUGIN_SLUG),
                            'menu' => array('FEDSCHATWhatsapp@layout'),
                        ),
                    ),
                ),
            ));
            ?>
            <div class="bc_fed">
                <div class="container">
                    <?php if (count($menus)) { ?>
                        <div class="m-t-10">
                            <h3 class="fed_header_font_color">Social Chat</h3>
                            <?php $this->header_menu($menus) ?>
                        </div>
                        <div class="m-t-10">
                            <?php $this->body_content($menus) ?>
                        </div>
                    <?php } else {
                        ?>
                        <div class="m-t-10">
                            <?php _e('Something went wrong', BC_FED_SCHAT_PLUGIN_SLUG) ?>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
            <?php
        }

        /**
         * @param $menus
         */
        public function header_menu($menus)
        {
            ?>
            <ul class="nav nav-tabs" id="" role="tablist">
                <?php foreach ($menus as $index => $item) {
                    $active = '';
                    if (isset($_GET, $_GET['menu']) && esc_html($_GET['menu']) === $index) {
                        $active = 'active';
                    }
                    if ( ! isset($_GET['menu']) && $_GET['page'] === 'social_chat' && $index === 'settings') {
                        $active = 'active';
                    }
                    ?>
                    <li class="<?php echo $active; ?>">
                        <a href="<?php echo fed_menu_page_url('social_chat', array(
                            'menu' => esc_attr($index),
                        )); ?>">
                            <i class="<?php esc_attr_e(fed_get_data('icon', $item)) ?>"></i>
                            <?php esc_attr_e(fed_get_data('name', $item)) ?>
                        </a>
                    </li>
                <?php } ?>

            </ul>
            <?php
        }

        /**
         * @param $menus
         */
        public function body_content($menus)
        {
            $_menu    = fed_get_data('menu');
            $_submenu = fed_get_data('submenu');
            $menu     = ! empty($_menu) ? esc_html($_menu) : fed_get_first_key_in_array($menus);
            $submenu  = ! empty($_submenu) ? esc_html($_submenu) : false;

            if ($menu) {
                if (isset($menus[$menu]['submenu']) && is_array($menus[$menu]['submenu']) && count($menus[$menu]['submenu'])) {
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="list-group">
                                <?php foreach ($menus[$menu]['submenu'] as $index => $sub_menu) {
                                    $active = '';
                                    if ( ! $submenu) {
                                        $submenu = fed_get_first_key_in_array($menus[$menu]['submenu']);
                                    }
                                    if (in_array($submenu, $sub_menu['menu'])) {
                                        $active = 'active';
                                    }
                                    ?>
                                    <li class="list-group-item <?php echo $active; ?>">

                                        <a href="<?php echo fed_menu_page_url('social_chat', array(
                                            'menu'    => $menu,
                                            'submenu' => $index,
                                        )); ?>">
                                            <i class="<?php echo esc_html($sub_menu['icon']); ?>"></i>
                                            <?php echo esc_attr($sub_menu['name']); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="<?php echo esc_attr($menus[$menu]['submenu'][$submenu]['icon']); ?>"></i>
                                        <?php echo esc_attr($menus[$menu]['submenu'][$submenu]['name']); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    if (is_string($submenu)) {
                                        fed_execute_method_by_string($submenu, $_GET);
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                } else {
                    if (isset($menus[$menu]['submenu']) && is_string($menus[$menu]['submenu'])) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <i class="<?php echo esc_attr($menus[$menu]['icon']); ?>"></i>
                                            <?php echo esc_attr($menus[$menu]['name']); ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php fed_execute_method_by_string($menus[$menu]['submenu'], $_GET); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                    } elseif (isset($menus[$menu])) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php _e('Under Construction', BC_FED_SCHAT_PLUGIN_SLUG); ?>
                            </div>
                        </div>
                        <?php
                    } else { ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong><?php _e('Invalid Menu Item | Error FED|Admin|Payment|FEDPaymentMenu@body_content',
                                    BC_FED_SCHAT_PLUGIN_SLUG); ?></strong>
                        </div>
                        <?php
                        FED_Log::writeLog('Invalid Menu Item | Error FEDCHAT|Admin|Settings|FEDSCHAT@body_content');
                    }
                }
            } else {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong><?php _e('Ah! something missing | Error FED|Admin|Payment|FEDPaymentMenu@body_content',
                                    BC_FED_SCHAT_PLUGIN_SLUG); ?></strong>
                        </div>
                        <?php
                        FED_Log::writeLog('Ah! something missing | Error FEDCHAT|Admin|Settings|FEDSCHAT@body_content');
                        ?>
                    </div>
                </div>
                <?php
            }
        }


        /**
         * @param $version
         *
         * @return array
         */
        public function plugin_versions($version)
        {
            return array_merge($version, array(
                'social_chat' => sprintf(__('Social Chat (%s)', BC_FED_SCHAT_PLUGIN_SLUG), BC_FED_SCHAT_PLUGIN_VERSION),
            ));
        }
    }

    new FEDSCHATMenu();
}
