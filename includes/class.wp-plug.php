<?php
class wp_plug
{
    // Plugin version
    public static $version = '1.0.0';

    // Plugin name
    public static $name = 'WP Plug';

    // Plugin slug
    public static $slug = 'wp-plug';

    // Plugin directory path
    public static $dir_path = __DIR__;

    // Plugin directory URL
    //public static $dir_url = plugin_dir_url(__FILE__);

    private static $instance = null;

    public static function instance($dir_path)
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        self::$dir_path = $dir_path;
        return self::$instance;
    }

    // Constructor
    public function __construct()
    {
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        add_action('the_content', array($this, 'content_reading_time'));

        add_filter('the_content', array($this, 'scroll_progress_bar'));

        add_filter( 'show_admin_bar', array($this, 'hide_admin_bar_for_roles'));
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
            self::$name,
            self::$name,
            'manage_options',
            self::$slug . '-settings',
            array($this, 'settings_page'),
            'dashicons-admin-generic',
            100
        );
    }

    public function settings_page()
    {
        require_once self::$dir_path . 'views/settings.php';
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

    public function scroll_progress_bar($content)
    {
        // Add the scroll progress bar HTML and CSS to the content
        $progress_bar = '<div class="scroll-progress-bar"></div>';
        $content .= $progress_bar;

        add_filter('wp_head', array($this, 'progress_bar_styles'));

        return $content;
    }

    public function progress_bar_styles()
    {
        // Add custom CSS for the scroll progress bar
        echo '<style>
            .scroll-progress-bar {
                animation:
                    scaleProgress auto linear,
                    colorChange auto linear;
                animation-timeline: scroll(root);
            }

            @keyframes scaleProgress {
                0% {
                    transform: scaleX(0);
                }
                100% {
                    transform: scaleX(1);
                }
                }

            @keyframes colorChange {
                0% {
                    background-color: red;
                }
                50% {
                    background-color: yellow;
                }
                100% {
                    background-color: lime;
                }
            }
        </style>';
    }

    function hide_admin_bar_for_roles( $show_admin_bar ) {
        if ( ! is_admin() ) {
            return !$show_admin_bar; // Hide admin bar for non-logged-in users
        }

        return $show_admin_bar; // Show admin bar for other roles
    }
}