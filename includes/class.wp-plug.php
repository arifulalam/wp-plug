<?php
class WP_Utility_Plug
{
    private static $instance = null;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    public function __construct()
    {
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        $this->actions();
        $this->filters();
    }

    private function actions()
    {
        /** 
         * Add your action hooks here
         * For example, you can add custom post types, taxonomies, etc.
         * add_action('init', array($this, 'custom_post_type')); 
         **/

        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        add_action('wp_enqueue_scripts', function () {
            $this->enqueue_scripts_styles();
        });

        add_action('admin_enqueue_scripts', function () {
            $this->enqueue_scripts_styles_admin();
        });
    }

    private function filters()
    {
        /** 
         * Add your filter hooks here
         * For example, you can modify the content, title, etc.
         * add_filter('the_content', array($this, 'modify_content')); 
         **/
        add_filter('show_admin_bar', array($this, 'hide_admin_bar_for_roles'));

        add_filter('the_title', array($this, 'scroll_progress_bar'));

        add_filter('the_content', array($this, 'content_reading_time'));

        add_filter('the_content', array($this, 'content_url_qr_code'));
    }

    // Enqueue styles and scripts
    private function enqueue_scripts_styles()
    {
        //Styles
        $styles = ['style'];
        foreach ($styles as $style) {
            wp_enqueue_style(WP_UTILITY_PLUG_SLUG . '-' . $style, WP_UTILITY_PLUG_DIR_URL . 'assets/css/' . $style . '.css', [], WP_UTILITY_PLUG_VERSION, 'all');
        }

        //Scripts
        wp_enqueue_script(WP_UTILITY_PLUG_SLUG, WP_UTILITY_PLUG_DIR_URL . 'assets/js/script.js', ['jquery'], WP_UTILITY_PLUG_VERSION, array('in_footer' => true));
    }

    // Enqueue styles and scripts for admin
    private function enqueue_scripts_styles_admin()
    {
        //Styles
        $styles = ['style'];
        foreach ($styles as $style) {
            wp_enqueue_style(WP_UTILITY_PLUG_SLUG . '-' . $style, WP_UTILITY_PLUG_ADMIN_ASSETS_URL . 'css/' . $style . '.css', [], WP_UTILITY_PLUG_VERSION, 'all');
        }

        //Scripts
        wp_enqueue_script(WP_UTILITY_PLUG_SLUG, WP_UTILITY_PLUG_ADMIN_ASSETS_URL . 'js/script.js', ['jquery'], WP_UTILITY_PLUG_VERSION, array('in_footer' => true));
    }

    public function content_url_qr_code($content)
    {
        if (is_single() || is_page()) {
            wp_enqueue_script(WP_UTILITY_PLUG_SLUG . '-qr-border-plugin', WP_UTILITY_PLUG_ASSETS_URL . 'vendors/qr-code-styling/qr-border-plugin.min.js', [], WP_UTILITY_PLUG_VERSION, array('in_footer' => true));
            wp_enqueue_script(WP_UTILITY_PLUG_SLUG . '-qr-code-styling', WP_UTILITY_PLUG_ASSETS_URL . 'vendors/qr-code-styling/qr-code-styling.min.js', [], WP_UTILITY_PLUG_VERSION, array('in_footer' => true));
            
            $url = get_permalink();

            $qrCodeConfig = get_option('qr_code_config', array(
                'containerId' => 'qr-code-container',
                'download' => true,
                'type' => 'svg', // "svg" or "canvas"
                'shape' => 'circle', // "square" or "circle"
                'width' => 250,
                'height' => 250,
                'data' => $url,
                'margin' => 40,
                'logo' => '',
                'dotsOptions' => array(
                    'type' => 'square', // "square", "rounded", "dots", "classy", "classy-rounded", "extra-rounded"
                    'color' => '#df349e',
                    'roundSize' => true,
                    'gradient' => array(
                        'type' => 'linear', // "linear" or "radial",
                        'rotation' => 0,
                        'colorStops' => array(
                            array(
                                'offset' => 0,
                                'color' => '#6a1a4c',
                            ),
                            array(
                                'offset' => 1,
                                'color' => 'green',
                            ),
                        ),
                    ),
                ),
                'cornersSquareOptions' => array(
                    'type' => 'dot', // "none" or "dot" or "square" or "extra-rounded"
                    'color' => '#000000',
                    'gradient' => array(
                        'type' => 'linear',
                        'rotation' => 0,
                        'colorStops' => array(
                            array(
                                'offset' => 0,
                                'color' => '#000000',
                            ),
                            array(
                                'offset' => 1,
                                'color' => '#a61717',
                            ),
                        ),
                    ),
                ),
                'cornersDotOptions' => array(
                    'type' => 'dot', // "none" or "dot" or "square" or "extra-rounded"
                    'color' => '#000000',
                ),
                'backgroundOptions' => array(
                    'color' => '#ffffff',
                ),
                'border' => array(
                    'color' => '#000000',
                    'thickness' => 40,
                    'borderInnerColor' => '#000000',
                    'borderOuterColor' => '#000000',
                    'direction' => array(
                        'top' => array(
                            'text' => 'Read me on other devices',
                            'color' => '#D5B882;',
                        ),
                        'bottom' => array(
                            'text' => 'SCAN ME',
                            'color' => '#D5B882;',
                        ),
                    ),
                ),
            ));

            $qrCodeConfig['Data'] = $url;

            wp_enqueue_script(WP_UTILITY_PLUG_SLUG . '-qr-code', WP_UTILITY_PLUG_ASSETS_URL . 'vendors/qr-code-styling/qr-code.js', [], WP_UTILITY_PLUG_VERSION, array('in_footer' => true));
            wp_localize_script(WP_UTILITY_PLUG_SLUG . '-qr-code', 'qrCodeConfig', $qrCodeConfig);
            wp_add_inline_script(WP_UTILITY_PLUG_SLUG . '-qr-code', 'generateQrCode()', 'after');
        }
        // Generate QR code for the content URL
        //$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($url) . '&size=150x150';

        // Append the QR code image to the content
        $content .= '<div id="qr-code-container"></div>';
        return $content;
    }

    // Activation function
    public function activate()
    {
        // Activation code here
        // For example, you can create a custom database table or set default options
        // global $wpdb;
        // $table_name = $wpdb->prefix . 'wp_plug_table'; // Change the table name as needed
        // $charset_collate = $wpdb->get_charset_collate();
        // $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     name tinytext NOT NULL,
        //     email varchar(100) NOT NULL,
        //     message text NOT NULL,
        //     PRIMARY KEY  (id)
        // ) $charset_collate;";
        // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        // dbDelta($sql);
        echo "Plugin activated successfully!";
    }

    // Deactivation function
    public function deactivate()
    {
        // Deactivation code here
        // For example, you can drop the custom database table or clean up options
        // global $wpdb;
        // $table_name = $wpdb->prefix . 'wp_plug_table'; // Change the table name as needed
        // $sql = "DROP TABLE IF EXISTS $table_name;";
        // $wpdb->query($sql);

        echo "Plugin deactivated successfully!";
    }

    // Add admin menu
    public function add_admin_menu()
    {
        add_menu_page(
            WP_UTILITY_PLUG_NAME,
            WP_UTILITY_PLUG_NAME,
            'manage_options',
            WP_UTILITY_PLUG_SLUG,
            array($this, 'admin_dashboard'),
            'dashicons-admin-generic',
            100
        );

        add_submenu_page(
            WP_UTILITY_PLUG_SLUG,
            WP_UTILITY_PLUG_NAME . ' Content QR Code',
            'Content QR Code',
            'manage_options',
            WP_UTILITY_PLUG_SLUG . '-qr-code',
            array($this, 'settings_qr_code'),
        );

        add_submenu_page(
            WP_UTILITY_PLUG_SLUG,
            WP_UTILITY_PLUG_NAME . ' Settings',
            'Settings',
            'manage_options',
            WP_UTILITY_PLUG_SLUG . '-settings',
            array($this, 'settings_page'),
        );
    }

    public function settings_qr_code()
    {
        require_once WP_UTILITY_PLUG_ADMIN_DIR . 'views/settings_qr_code.php';
        // Include the settings page view file
        // This file should contain the HTML and PHP code for the settings page
    }

    public function admin_dashboard()
    {
        require_once WP_UTILITY_PLUG_ADMIN_DIR . 'views/dashboard.php';
        // Include the settings page view file
        // This file should contain the HTML and PHP code for the settings page
    }

    public function settings_page()
    {
        require_once WP_UTILITY_PLUG_ADMIN_DIR . 'views/settings.php';
        // Include the settings page view file
        // This file should contain the HTML and PHP code for the settings page
    }

    public function content_reading_time($content)
    {
        // Calculate reading time based on the content length
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Assuming an average reading speed of 200 words per minute

        // Append the reading time to the content
        $content = '<p>Estimated reading time: ' . $reading_time . ' minute(s)</p>' . $content;
        return $content;
    }

    public function scroll_progress_bar($title)
    {
        if (!is_admin()) {
            $progress_bar = '<div class="scroll-progress-bar"></div>';
            $title = $progress_bar . $title;

            wp_enqueue_style(WP_UTILITY_PLUG_SLUG . '-scroll-progress-bar', WP_UTILITY_PLUG_DIR_URL . 'assets/css/scroll-progress-bar.css', [], WP_UTILITY_PLUG_VERSION, 'all');
        }

        return $title;
    }

    function hide_admin_bar_for_roles($show_admin_bar)
    {
        if (!is_admin()) {
            return !$show_admin_bar; // Hide admin bar for front/content pages
        }

        return $show_admin_bar; // Show admin bar for other roles
    }
}