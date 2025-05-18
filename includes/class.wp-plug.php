<?php
class WP_Plug
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

        add_action('the_content', callback: array($this, 'content_reading_time'));

        add_action('wp_enqueue_scripts', function() {
            $this->enqueue_styles();
            $this->enqueue_scripts();
        });
    }

    private function filters()
    {
        /** 
         * Add your filter hooks here
         * For example, you can modify the content, title, etc.
         * add_filter('the_content', array($this, 'modify_content')); 
         **/
        add_filter('the_title', array($this, 'scroll_progress_bar'));

        add_filter( 'show_admin_bar', array($this, 'hide_admin_bar_for_roles'));
    }

    // Enqueue styles and scripts
    private function enqueue_styles()
    {
        $styles = ['style'];
        foreach ($styles as $style) {
            wp_enqueue_style(WP_PLUG_SLUG . '-' . $style, WP_PLUG_DIR_URL . 'assets/css/' . $style . '.css', [], WP_PLUG_VERSION, 'all');
        }
    }
    
    public function enqueue_scripts()
    {
        wp_enqueue_script(WP_PLUG_SLUG, WP_PLUG_DIR_URL . 'assets/js/script.js', ['jquery'], WP_PLUG_VERSION, array('in_footer' => true));   
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
            WP_PLUG_NAME,
            WP_PLUG_NAME,
            'manage_options',
            WP_PLUG_SLUG . '-settings',
            array($this, 'settings_page'),
            'dashicons-admin-generic',
            100
        );
    }

    public function settings_page()
    {
        require_once WP_PLUG_DIR_PATH . 'views/settings.php';
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
        $progress_bar = '<div class="scroll-progress-bar"></div>';
        $title = $progress_bar . $title;

        wp_enqueue_style(WP_PLUG_SLUG . '-scroll-progress-bar', WP_PLUG_DIR_URL . 'assets/css/scroll-progress-bar.css', [], WP_PLUG_VERSION, 'all');

        return $title;
    }

    function hide_admin_bar_for_roles( $show_admin_bar ) {
        if ( ! is_admin() ) {
            return !$show_admin_bar; // Hide admin bar for non-logged-in users
        }

        return $show_admin_bar; // Show admin bar for other roles
    }
}