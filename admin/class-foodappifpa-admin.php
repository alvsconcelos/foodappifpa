<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       mailto:ialvsconcelos@gmail.com
 * @since      1.0.0
 *
 * @package    Foodappifpa
 * @subpackage Foodappifpa/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Foodappifpa
 * @subpackage Foodappifpa/admin
 * @author     Alvaro Vasconcelos <ialvsconcelos@gmail.com>
 */
class Foodappifpa_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Foodappifpa_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Foodappifpa_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/foodappifpa-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Foodappifpa_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Foodappifpa_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/foodappifpa-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Food sellers
	 */

	/*
	// Create custom post type

	public function create_food_sellers_post_type()
	{
		$labels = array(
			'name'                  => _x('Vendedores', 'Post Type General Name', 'fa_ifpa'),
			'singular_name'         => _x('Vendedor', 'Post Type Singular Name', 'fa_ifpa'),
			'menu_name'             => __('Vendedores', 'fa_ifpa'),
			'name_admin_bar'        => __('Vendedor', 'fa_ifpa'),
			'all_items'             => __('Todos os vendedores', 'fa_ifpa'),
			'add_new_item'          => __('Adicionar novo vendedor', 'fa_ifpa'),
			'add_new'               => __('Adicionar novo', 'fa_ifpa'),
			'new_item'              => __('Novo vendedor', 'fa_ifpa'),
			'edit_item'             => __('Editar dados do vendedor', 'fa_ifpa'),
			'update_item'           => __('Atualizar dados do vendedor', 'fa_ifpa'),
			'featured_image'        => __('Logotipo', 'fa_ifpa'),
			'set_featured_image'    => __('Adicionar logotipo', 'fa_ifpa'),
			'remove_featured_image' => __('Remover logotipo', 'fa_ifpa'),
			'use_featured_image'    => __('Usar como logotipo', 'fa_ifpa'),
		);
		$args = array(
			'label'                 => __('Vendedor', 'fa_ifpa'),
			'description'           => __('Vendedores de alimentos', 'fa_ifpa'),
			'labels'                => $labels,
			'supports'              => array('title', 'thumbnail'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-store',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
		);
		register_post_type('food_sellers', $args);
	}

	*/

	// Build seller base 

	public function create_seller_role()
	{
		add_role(
			'food_seller',
			__('Vendedor'),
			array(
				'read' => true, // true allows this capability
				'edit_posts' => true, // Allows user to edit their own posts
				'edit_pages' => false, // Allows user to edit pages
				'edit_others_posts' => false, // Allows user to edit others posts not just their own
				'create_posts' => true, // Allows user to create new posts
				'manage_categories' => false, // Allows user to manage post categories
				'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
			)
		);
	}

	// Create metaboxes

	public function create_seller_data_metaboxes()
	{

		$prefix = '_faseller_';

		$cmb = new_cmb2_box(array(
			'id'           => $prefix . 'seller_data',
			'title'        => __('Dados do vendedor', 'fa_ifpa'),
			'object_types' => array('user'),
			'context'      => 'normal',
			'priority'     => 'default',
			'show_on_cb' => array($this, 'cmb_show_meta_to_chosen_roles'),
			'show_on_roles' => array( 'food_seller', 'administrator' ),			
		));

		$cmb->add_field(array(
			'name' => 'Dados do vendedor',
			'desc' => 'Dados a serem exibidos no app.',
			'type' => 'title',
			'id'   => 'seller_data_title'
		));

		$cmb->add_field(array(
			'name' => __('Nome do estabelecimento', 'fa_ifpa'),
			'id' => $prefix . 'name',
			'type' => 'text_medium',
			'attributes' => array(
				'style' => 'width:500px;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Descrição', 'fa_ifpa'),
			'id' => $prefix . 'description',
			'type' => 'textarea_small',
			'attributes' => array(
				'style' => 'width:500px;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Endereço', 'fa_ifpa'),
			'id' => $prefix . 'address',
			'type' => 'text_medium',
			'attributes' => array(
				'style' => 'width:500px;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Telefone', 'fa_ifpa'),
			'id' => $prefix . 'phone',
			'type' => 'text_medium',
			'attributes' => array(
				'style' => 'width:500px;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Whatsapp', 'fa_ifpa'),
			'id' => $prefix . 'whatsapp',
			'type' => 'text_medium',
			'attributes' => array(
				'style' => 'width:500px;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Valor da taxa de entrega', 'fa_ifpa'),
			'id' => $prefix . 'deliverytax',
			'type' => 'text_money',
			'default' => '0',
			'before_field' => 'R$',
			'desc' => 'Se não existir taxa deixar o campo vazio.',
		));

		$cmb->add_field(array(
			'name' => __('Tipos de pagamento aceitos', 'fa_ifpa'),
			'id' => $prefix . 'payments',
			'type' => 'multicheck',
			'options' => array(
				'credit_card' => __('Cartão de crédito', 'fa_ifpa'),
				'money' => __('Dinheiro', 'fa_ifpa'),
				'debit_card' => __('Cartão de débito', 'fa_ifpa'),
				'check' => __('Cheque', 'fa_ifpa'),
				'bank_transfer' => __('Transferência bancária', 'fa_ifpa'),
			),
		));

		$cmb->add_field(array(
			'name' => 'Horário de funcionamento',
			'desc' => 'Caso o estabelecimento não abra em um dia da semana, deixar os campos relacionados a este dia vazios.',
			'id' => $prefix . 'adres',
			'type' => 'opening_hours',
		));
	}

	// Create custom metabox field types and filters

	public function cmb_show_meta_to_chosen_roles( $cmb ) {
		$roles = $cmb->prop( 'show_on_roles', array() );
	
		// Do not limit the box display unless the roles are defined.
		if ( empty( $roles ) ) {
			return true;
		}
	
	
		$user = wp_get_current_user();
	
		// No user found, return
		if ( empty( $user ) ) {
			return false;
		}
	
		$has_role = array_intersect( (array) $roles, $user->roles );
	
		// Will show the box if user has one of the defined roles.
		return ! empty( $has_role );
	}	

	public function cmb2_render_opening_hours_field_callback($field, $value, $object_id, $object_type, $field_type)
	{
		echo $field_type->_desc(true);

		$value = wp_parse_args($value, array());
		$days_of_the_week = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
		$statuses = array(
			'_open' => 'Abre:',
			'_close' => 'Fecha:'
		);

		foreach ($days_of_the_week as $index => $day) {

			echo '<strong><p>' . $day . '</p></strong> <div>';

			foreach ($statuses as $status => $name) {
				$field_slug = $index . $status;

				echo '<label class="m-r1" for="' . $field_type->_id($field_slug) . '">' . $name . '</label>';

				echo $field_type->input(
					array(
						'class' => 'cmb_text_small m-r1"',
						'name'  => $field_type->_name('[' . $field_slug . ']'),
						'id'    => $field_type->_id($field_slug),
						'value' => $value[$field_slug],
						'type' => 'time',
						'desc' => '',
					)
				);
			}

			echo '</div><br>';
		}

		// Style metabox fields

		echo '<style>.m-r1{margin-right:10px;}</style>';
	}

	/**
	 * Food Products
	 */

	public function create_food_products_post_type()
	{

		$labels = array(
			'name'                  => _x('Produtos', 'Post Type General Name', 'fa_ifpa'),
			'singular_name'         => _x('Produto', 'Post Type Singular Name', 'fa_ifpa'),
			'menu_name'             => __('Produtos', 'fa_ifpa'),
			'name_admin_bar'        => __('Produto', 'fa_ifpa'),
			'add_new_item'          => __('Adicionar novo produto', 'fa_ifpa'),
			'add_new'               => __('Adicionar novo', 'fa_ifpa'),
			'new_item'              => __('Novo produto', 'fa_ifpa'),
			'edit_item'             => __('Editar dados do produto', 'fa_ifpa'),
			'update_item'           => __('Atualizar dados do produto', 'fa_ifpa'),
			'all_items'             => __('Todos os produtos', 'fa_ifpa'),
			'featured_image'        => __('Imagem principal', 'fa_ifpa'),
			'set_featured_image'    => __('Adicionar imagem principal', 'fa_ifpa'),
			'remove_featured_image' => __('Remover imagem principal', 'fa_ifpa'),
			'use_featured_image'    => __('Usar como imagem principal', 'fa_ifpa'),
		);
		$args = array(
			'label'                 => __('Produto', 'fa_ifpa'),
			'description'           => __('Itens do cardápio', 'fa_ifpa'),
			'labels'                => $labels,
			'supports'              => array('title', 'thumbnail', 'author'),
			'taxonomies'			=> array('food_category'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-carrot',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
		);
		register_post_type('food_products', $args);
	}

	public function create_product_data_metaboxes()
	{

		$prefix = '_faproduct_';

		$cmb = new_cmb2_box(array(
			'id'           => $prefix . 'product_data',
			'title'        => __('Dados do produto', 'fa_ifpa'),
			'object_types' => array('food_products'),
			'context'      => 'normal',
			'priority'     => 'default',
		));

		$cmb->add_field(array(
			'name' => __('Descrição', 'fa_ifpa'),
			'id' => $prefix . 'description',
			'type' => 'textarea_small',
			'attributes' => array(
				'style' => 'width:100%;',
			),
		));

		$cmb->add_field(array(
			'name' => __('Preço', 'fa_ifpa'),
			'id' => $prefix . 'price',
			'type' => 'text_money',
			'default' => '0',
			'before_field' => 'R$',
			'desc' => 'Inserir os valores sem vírgula ou ponto.',
		));

		$cmb->add_field(array(
			'name' => __('Preço promocional', 'fa_ifpa'),
			'id' => $prefix . 'promo_price',
			'type' => 'text_money',
			'default' => '0',
			'before_field' => 'R$',
			'desc' => 'Deixe em branco caso o produto não esteja em promoção.',
		));

		$cmb->add_field(array(
			'name' => __('Imagens do produto', 'fa_ifpa'),
			'id' => $prefix . 'photos',
			'type' => 'file_list',
			'query_args' => array('type' => 'image'),
			'text' => array(
				'add_upload_files_text' => 'Adicionar imagens do produto',
				'remove_image_text' => 'Remover imagem',
				'file_text' => 'Imagem:',
			),
		));
	}

	public function create_food_taxonomy()
	{

		$labels = array(
			'name'                       => _x('Categorias', 'Taxonomy General Name', 'fa_ifpa'),
			'singular_name'              => _x('Categoria', 'Taxonomy Singular Name', 'fa_ifpa'),
			'menu_name'                  => __('Categorias', 'fa_ifpa'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
		);
		register_taxonomy('food_category', array('food_products'), $args);
	}

	public function cmb2_get_post_options($query_args)
	{

		$args = wp_parse_args($query_args, array(
			'product_seller'   => '',
		));

		$posts = get_posts($args);

		$post_options = array();
		if ($posts) {
			foreach ($posts as $post) {
				$post_options[$post->ID] = $post->post_title;
			}
		}

		return $post_options;


		$args = array(
			'role'           => 'food_seller',
			'fields'         => 'all_with_meta',
		);


		$user_query = new WP_User_Query($args);


		if (!empty($user_query->results)) {
			foreach ($user_query->results as $user) { }
		} else { }
	}

	/**
	 * Gets 5 posts for your_post_type and displays them as options
	 * @return array An array of options that matches the CMB2 options array
	 */
	function cmb2_get_your_post_type_post_options()
	{
		return cmb2_get_post_options(array('post_type' => 'your_post_type', 'numberposts' => 5));
	}



	/**
	 * General functions
	 */

	public function disable_gutenberg($current_status, $post_type)
	{
		if ($post_type === 'food_products' || $post_type === 'food_sellers') return false;
		return $current_status;
	}

	public function change_default_title($title)
	{
		$screen = get_current_screen();

		if ('food_sellers' == $screen->post_type) {
			$title = 'Nome do estabelecimento';
		} elseif ('food_products' == $screen->post_type) {
			$title = 'Nome do produto';
		}

		return $title;
	}
}
