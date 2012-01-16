<?php
class BasePlugin {
    
    // Container variables
    public $view = '';
    public $data = array();
    public $wpdb;
    public $nonce;
    
    // Assets to load
    public $admin_css = array();
    public $admin_js = array();
    public $plugin_css = array();
    public $plugin_js = array();
    
    // Paths
    public $css_path = 'css';
    public $js_path = 'js';
    public $plugin_dir = '';
    public $plugin_dir_name = '';

    // AJAX actions
    public $ajax_actions = array(
        'admin' => array(),
        'plugin' => array()
    );
    
    public function __construct($here = __FILE__) 
    {
        global $wpdb;

        $this->add_ajax_actions();
        $this->wpdb = $wpdb;

        if (empty($this->plugin_dir)) 
        {
            $this->plugin_dir = WP_PLUGIN_DIR.'/'.basename(dirname($here));
        }

        $this->plugin_dir_name = basename(dirname($here));
        $this->css_path = WP_PLUGIN_URL.'/'.$this->plugin_dir_name.'/css/';
        $this->js_path = WP_PLUGIN_URL.'/'.$this->plugin_dir_name.'/js/';

        add_action('wp_loaded', array(&$this, 'create_nonce'));

        if ( ! empty($this->admin_css) || !empty($this->admin_js)) 
        {
            add_action('admin_enqueue_scripts', array(&$this, 'load_admin_scripts'));
        }

        if ( ! empty($this->plugin_css) || !empty($this->plugin_js)) 
        {
            // TODO: enqueue plugin scripts
        }
    }
    
	/**
	 * Loads admin-facing CSS and JS.
	 */
    public function load_admin_scripts() 
    {
        foreach ($this->admin_css as $css) 
        {
            wp_enqueue_style($css, $this->css_path.$css.'.css');
        }

        foreach ($this->admin_js as $js) 
        {
            wp_enqueue_script($js, $this->js_path.$js.'.js');
        }
    }

    /**
     * Loads front-facing CSS and JS.
     */
    public function load_plugin_scripts() 
    {
        foreach ($this->plugin_css as $css) 
        {
            wp_enqueue_style($css, $this->css_path.$css.'.css');
        }

        foreach ($this->plugin_js as $js)
        {
            wp_enqueue_script($js, $this->js_path.$js.'.js');
        }
    }

	/**
	 * A security feature that Sanity presumes you should use. Please
	 * refer to: http://codex.wordpress.org/WordPress_Nonces
	 */
	public function create_nonce() 
    {
		$this->nonce = wp_create_nonce('baseplugin-nonce');
	}
		
	/**
	 * Loops through $this->ajax_actions['admin'] and $this->ajax_actions['plugin'] and
	 * registers ajax actions. This makes the actions available in the client plugin.
	 */
	public function add_ajax_actions() 
    {
		if ( ! empty($this->ajax_actions['admin'])) 
        {
			foreach ($this->ajax_actions['admin'] as $action) 
            {
				add_action("wp_ajax_$action", array(&$this, $action));
			}
		}

		if ( ! empty($this->ajax_actions['plugin'])) 
        {
			foreach ($this->ajax_actions['plugin'] as $action) 
            {
				add_action("wp_ajax_nopriv_$action", array(&$this, $action));
			}
		}				
	}
    
	/**
	 * Loads a view from within the /plugin/views folder. Keep in mind
	 * that any data you need should be passed through the $this->data array.
	 * A few examples:
	 *
	 *    Load /Plugin/views/example.php
	 *    $this->render('example');
	 *
	 *    Load /Plugin/views/subfolder/example.php
	 *    $this->render('subfolder/example);
	 */
    public function render($view) 
    {
        $template_path = $this->plugin_dir.'/views/'.$view.'.php';

        ob_start();
        include $template_path;
        $output = ob_get_clean();

        return $output;
    }
    
}