<?php
/*
    Plugin Name: Your Plugin Name
    Plugin URI: http://www.your-plugin.com
    Description: Your well written plugin description.
    Author: Your Name
    Version: 1.0
    Author URI: http://www.your-github-account.com/
*/

$plugin_path = dirname(__FILE__).'/';

if (class_exists('BasePlugin') !== TRUE)
{
     require $plugin_path.'lib/baseplugin.php';
}


/**
 * Define your plugin class which extends the BasePlugin
 * Make sure you skip down to the end of this file, as there are a few
 * lines of code that are very important.
 */ 
class ExamplePlugin extends BasePlugin {

    /**
     * Some required plugin information
     */
    public $version = '1.0';

    /**
     * Required __construct() function that initalizes the Sanity Framework
     */
    public function __construct() 
    {
        parent::__construct(__FILE__);
    }

    /**
     * Run during the activation of the plugin
     */
    public function activate() 
    {

    }

    /**
     * Run during the initialization of Wordpress
     */
    public function initialize() 
    {

    }
}

// Initalize the your plugin
$ExamplePlugin = new ExamplePlugin();

// Add an activation hook
register_activation_hook(__FILE__, array(&$ExamplePlugin, 'activate'));

// Run the plugins initialization method
add_action('init', array(&$ExamplePlugin, 'initialize'));