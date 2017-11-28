<?php
/**
 * Created by IntelliJ IDEA.
 * User: slabre
 * Date: 28/11/2017
 * Time: 22:14
 */
if(!class_exists('EOD_Stock_Prices_Admin')) {
    class EOD_Stock_Prices_Admin {
        /**
         * Prepare plugin hooks / filters
         */
        public function __construct(){

            if(is_admin()){
                add_action( 'admin_menu', array(&$this,'admin_menu'));
                add_action( 'admin_init', array(&$this,'admin_settings') );

            }
            add_action( 'admin_enqueue_scripts',  array(&$this,'admin_scripts'));
        }

        /**
         *
         */
        public function admin_scripts(){
            //bootstrap
            wp_enqueue_style('bootstrap_css',plugins_url('/../vendor/bootstrap/css/bootstrap.css',__FILE__));
            wp_enqueue_script( 'bootstrap_popper', plugins_url( '/../vendor/bootstrap/js/popper.min.js', __FILE__ ), array('jquery'), false,true);
            wp_enqueue_script( 'bootstrap', plugins_url( '/../vendor/bootstrap/js/bootstrap.min.js', __FILE__ ), array('bootstrap_popper'), false,true);
            wp_enqueue_style('glyphicons_css',plugins_url('/../vendor/bootstrap/css/glyphicons.css',__FILE__));

            //css
            wp_enqueue_style('le_time_slots_admin_css',plugins_url('/css/eod-stock-prices-admin.css',__FILE__));
        }

        /**
         * Add an admin menu entry for options page
         */
        public function admin_menu(){
            add_menu_page('Stock Prices Plugin', 'Stock Prices Plugin', 'manage_options', 'eod-stock-prices-admin',array(&$this,'options_page'),'dashicons-chart-line' );
        }

        /**
         * Prepare the options registering fields (for admin configuration page)
         */
        public function admin_settings(){
            register_setting( 'eod_options', 'eod_options', array(&$this, 'eod_options_validate'));
            add_settings_section('eod_options_main', 'API Settings',  array(&$this, 'eod_options_main_text'), 'eod_options_section');
            add_settings_field('eod_option_api_key', 'Your API Key', array(&$this, 'eod_option_api_key_render'), 'eod_options_section', 'eod_options_main');
        }


        /**
         * Displays the configuration page
         */
        public function options_page(){
            include(dirname(__FILE__).'/template/options.php');
        }

        /**
         * Display the text of the section
         */
        public function eod_options_main_text(){
            echo '<p>Find here all API parameters for Stock Prices plugin. Demo API Key is <span>OeAFFmMliFG5orCUuwAKQ8l4WWFQ67YX</span> (only works for AAPL.US).</p>';
        }

        /**
         * Displays the API key input
         */
        public function eod_option_api_key_render(){
            $options = get_option('eod_options');
            echo "<input id='eod_option_api_key' name='eod_options[api_key]' size='40' type='text' value='{$options['api_key']}' placeholder='Your 32 characters API key' />";
        }

        /**
         * Validates the options form
         * @param $input
         * @return mixed
         */
        function eod_options_validate($input) {
            $newinput['api_key'] = trim($input['api_key']);
            if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['api_key'])) {
                $newinput['api_key'] = '';
            }
            return $newinput;
        }
    }
}
