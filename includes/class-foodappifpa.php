<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       mailto:ialvsconcelos@gmail.com
 * @since      1.0.0
 *
 * @package    Foodappifpa
 * @subpackage Foodappifpa/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Foodappifpa
 * @subpackage Foodappifpa/includes
 * @author     Alvaro Vasconcelos <ialvsconcelos@gmail.com>
 */
class Foodappifpa
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Foodappifpa_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('FOODAPPIFPA_VERSION')) {
			$this->version = FOODAPPIFPA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'foodappifpa';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Foodappifpa_Loader. Orchestrates the hooks of the plugin.
	 * - Foodappifpa_i18n. Defines internationalization functionality.
	 * - Foodappifpa_Admin. Defines all hooks for the admin area.
	 * - Foodappifpa_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-foodappifpa-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-foodappifpa-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-foodappifpa-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-foodappifpa-public.php';

		$this->loader = new Foodappifpa_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Foodappifpa_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Foodappifpa_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Foodappifpa_Admin($this->get_plugin_name(), $this->get_version());

		/**
		 * Dependencies
		 */

		$plugin_admin->create_seller_role();

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_admin, 'create_food_products_post_type');
		$this->loader->add_action('init', $plugin_admin, 'create_food_taxonomy');
		$this->loader->add_action('add_meta_boxes_food_products', $plugin_admin, 'change_author_box_title');

		/**
		 * Metaboxes
		 */

		$this->loader->add_action('cmb2_init', $plugin_admin, 'create_seller_data_metaboxes');
		$this->loader->add_action('cmb2_init', $plugin_admin, 'create_product_data_metaboxes');
		// $this->loader->add_action('cmb2_save_field__faproduct_fireSavedEvent', $plugin_admin, 'my_post_save_function', 20, 3);

		/**
		 * Filters
		 */

		$this->loader->add_filter('use_block_editor_for_post_type', $plugin_admin, 'disable_gutenberg', 10, 2);
		$this->loader->add_filter('enter_title_here', $plugin_admin, 'change_default_title');
		$this->loader->add_filter('wp_dropdown_users_args', $plugin_admin, 'show_only_sellers_on_food_ctp', 10, 2);
		$this->loader->add_filter('cmb2_render_opening_hours', $plugin_admin, 'cmb2_render_opening_hours_field_callback', 10, 5);
		// add_filter( 'wp_dropdown_users_args', 'change_user_dropdown', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Foodappifpa_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Foodappifpa_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
