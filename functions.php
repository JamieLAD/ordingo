<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class Ordingo extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_option_pages' ) );
		add_action( 'init', array( $this, 'disable_theme_editior' ) );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts' ) );
		add_action( 'acf/init', array( $this, 'my_acf_init') );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	public function disable_theme_editior() {
		define('DISALLOW_FILE_EDIT', TRUE);
	}

	public function register_option_pages() {
		if( function_exists('acf_add_options_page') && function_exists('acf_add_options_sub_page') ) {

			acf_add_options_page(array(
				'page_title' 	=> "Theme Options",
				'menu_title'	=> "Theme Options",
				'menu_slug' 	=> 'theme-options',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			));
			
		}
	}

	public function register_nav_menus() {

		register_nav_menus( array(
			'navigation' => 'Navigation',
			'footer_onw' => 'Footer One',
			'footer_two' => 'Footer Two',
			'legal_links' => 'Legal Links',
		) );

	}


	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['navigation'] = new Timber\Menu( 'Navigation' );
		$context['footer_one'] = new Timber\Menu( 'Footer One' );
		$context['footer_two'] = new Timber\Menu( 'Footer Two' );
		$context['legal_links'] = new Timber\Menu( 'Legal Links' );
		$context['options'] = get_fields('option');
		$context['site'] = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

	public function loadScripts() {
        wp_enqueue_script( 'app.js', get_template_directory_uri() . '/js/app.js', array(), '1.0.0', true );
	}

	public function my_acf_init() {
		if( function_exists('acf_register_block') ) {
			
			acf_register_block(array(
				'name'				=> 'hero',
				'title'				=> __('Hero'),
				'description'		=> __('Hero Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('hero'),
			));

			acf_register_block(array(
				'name'				=> 'features',
				'title'				=> __('Features'),
				'description'		=> __('Features Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('features'),
			));

			acf_register_block(array(
				'name'				=> 'reviews',
				'title'				=> __('Reviews'),
				'description'		=> __('Reviews Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('reviews'),
			));

			acf_register_block(array(
				'name'				=> 'pricing',
				'title'				=> __('Pricing'),
				'description'		=> __('Pricing Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('pricing'),
			));

			acf_register_block(array(
				'name'				=> 'faqs',
				'title'				=> __('Faqs'),
				'description'		=> __('Faqs Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('faqs'),
			));

			acf_register_block(array(
				'name'				=> 'calltoaction',
				'title'				=> __('Call To Action'),
				'description'		=> __('Call To Action Block.'),
				'render_callback'	=> array( $this, 'my_acf_block_render_callback'),
				'keywords'			=> array('calltoaction'),
			));

		}
	}

	function my_acf_block_render_callback( $block, $content = '', $is_preview = false ) {
		$slug = str_replace('acf/', '', $block['name']);
		$context = Timber::context();
		$context['post'] = Timber::query_post();
		$context['block'] = $block;
		$context['fields'] = get_fields();
		$context['is_preview'] = $is_preview;
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$context['breadcrumbs'] = yoast_breadcrumb('<p id="breadcrumbs" class="breadcrumbs">','</p>', false );
		}
		Timber::render( "blocks/{$slug}.twig", $context );
	}

}

new Ordingo();
