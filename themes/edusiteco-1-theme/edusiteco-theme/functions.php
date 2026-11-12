<?php
/**
 * edusiteco functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package edusiteco
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function edusiteco_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on edusiteco, use a find and replace
		* to change 'edusiteco' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'edusiteco', get_template_directory() . '/languages' );

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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'edusiteco' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'edusiteco_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add theme support for block templates. This is key for hybrid themes.
	add_theme_support( 'block-templates' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'edusiteco_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function edusiteco_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'edusiteco_content_width', 640 );
}
add_action( 'after_setup_theme', 'edusiteco_content_width', 0 );

/**
 * Add editable section headings to the WordPress page editor.
 */
function edusiteco_add_identity_headings_metabox() {
	$screen = get_current_screen();
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-mision-vision-values.php',
			'number'     => 1,
		)
	);

	if ( empty( $pages ) ) {
		return;
	}

	add_meta_box(
		'edusiteco-identity-headings',
		__( 'Títulos de Misión, Visión y Valores', 'edusiteco' ),
		'edusiteco_render_identity_headings_metabox',
		'page',
		'normal',
		'default',
		array( 'page_id' => $pages[0]->ID )
	);
}
add_action( 'add_meta_boxes_page', 'edusiteco_add_identity_headings_metabox' );

/**
 * Render heading fields only on the institutional identity page.
 *
 * @param WP_Post $post Current page.
 * @param array   $box  Meta box arguments.
 */
function edusiteco_render_identity_headings_metabox( $post, $box ) {
	if ( get_page_template_slug( $post ) !== 'page-mision-vision-values.php' ) {
		return;
	}

	wp_nonce_field( 'edusiteco_save_identity_headings', 'edusiteco_identity_headings_nonce' );
	$fields = array(
		'_edusiteco_identity_mission_heading' => __( 'Título de Misión', 'edusiteco' ),
		'_edusiteco_identity_vision_heading'  => __( 'Título de Visión', 'edusiteco' ),
		'_edusiteco_identity_values_heading'  => __( 'Título de Valores', 'edusiteco' ),
	);

	foreach ( $fields as $meta_key => $label ) {
		$value = get_post_meta( $post->ID, $meta_key, true );
		?>
		<p>
			<label for="<?php echo esc_attr( $meta_key ); ?>"><?php echo esc_html( $label ); ?></label><br>
			<input class="widefat" type="text" id="<?php echo esc_attr( $meta_key ); ?>" name="<?php echo esc_attr( $meta_key ); ?>" value="<?php echo esc_attr( $value ); ?>">
		</p>
		<?php
	}
	?>
	<p class="description"><?php esc_html_e( 'Déjalo vacío para usar el título predeterminado. Los títulos de los comunicados se editan directamente en el campo Título de cada comunicado.', 'edusiteco' ); ?></p>
	<?php
}

/**
 * Save the institutional identity headings from the standard page editor.
 *
 * @param int $post_id Saved page ID.
 */
function edusiteco_save_identity_headings( $post_id ) {
	if (
		! isset( $_POST['edusiteco_identity_headings_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edusiteco_identity_headings_nonce'] ) ), 'edusiteco_save_identity_headings' )
		|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		|| 'page-mision-vision-values.php' !== get_page_template_slug( $post_id )
		|| ! current_user_can( 'edit_page', $post_id )
	) {
		return;
	}

	$fields = array(
		'_edusiteco_identity_mission_heading',
		'_edusiteco_identity_vision_heading',
		'_edusiteco_identity_values_heading',
	);

	foreach ( $fields as $meta_key ) {
		if ( ! isset( $_POST[ $meta_key ] ) ) {
			continue;
		}

		$value = sanitize_text_field( wp_unslash( $_POST[ $meta_key ] ) );
		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post_page', 'edusiteco_save_identity_headings' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function edusiteco_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'edusiteco' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'edusiteco' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'edusiteco_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function edusiteco_scripts() {
	// WordPress theme info (required but contains only header)
	wp_enqueue_style( 'edusiteco-style', get_stylesheet_uri(), array(), _S_VERSION );
	
	// Main Tailwind CSS stylesheet
	$base_css_path = get_template_directory() . '/assets/css/index.css';
	$base_css_version = file_exists( $base_css_path ) ? filemtime( $base_css_path ) : _S_VERSION;
	wp_enqueue_style( 'edusiteco-base', get_template_directory_uri() . '/assets/css/index.css', array(), $base_css_version );
	wp_style_add_data( 'edusiteco-base', 'rtl', 'replace' );

	wp_enqueue_style( 'edusiteco-nav-menu', get_template_directory_uri() . '/src/css/nav-menu.css', array( 'edusiteco-base' ), _S_VERSION );

	// RTL stylesheet for Tailwind
	if ( is_rtl() ) {
		wp_enqueue_style( 'edusiteco-base-rtl', get_template_directory_uri() . '/assets/css/base-rtl.css', array( 'edusiteco-base' ), _S_VERSION );
	}

	// Main theme script
	$main_js_path = get_template_directory() . '/assets/js/index.asset.php';
    $main_js_dependencies = file_exists($main_js_path) ? require($main_js_path) : array('dependencies' => array(), 'version' => _S_VERSION);

	wp_enqueue_script( 'edusiteco-index-scripts', get_template_directory_uri() . '/assets/js/index.js', $main_js_dependencies['dependencies'], $main_js_dependencies['version'], true );
	wp_enqueue_script( 'edusiteco-custom-admin', get_template_directory_uri() . '/assets/js/custom-admin.js', array(), $main_js_dependencies['version'], true );

	// Navigation script (keep existing if exists)
	if ( file_exists( get_template_directory() . '/js/navigation.js' ) ) {
		wp_enqueue_script( 'edusiteco-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	}

	// Mobile menu toggle script
	wp_enqueue_script( 'edusiteco-mobile-menu', get_template_directory_uri() . '/src/js/mobile-menu.js', array(), _S_VERSION, true );

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Pass data to JavaScript
	wp_localize_script( 'edusiteco-main', 'edusiteco_data', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'edusiteco_nonce' ),
		'theme_url' => get_template_directory_uri(),
	) );
}
add_action( 'wp_enqueue_scripts', 'edusiteco_scripts' );



/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/edusiteco-customizer-color.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


// Incluir walkers de navegación
require get_template_directory() . '/inc/nav-walkers.php';

// Registrar menús de navegación
function edusiteco_register_menus() {
    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary Menu', 'edusiteco'),
        )
    );
}
add_action('init', 'edusiteco_register_menus');

# Front Page
function edusiteco_enqueue_swiper() {
    if (is_front_page() || (is_singular() && has_block('edusiteco/teacher-project'))) {
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', array(), '12.0.0', true);
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', array(), '12.0.0');
    }
}
add_action('wp_enqueue_scripts', 'edusiteco_enqueue_swiper');
# Leaflet Maps
function edusiteco_enqueue_leaflet() {
	if (is_page_template('page-contact.php') || is_front_page()) {
		wp_enqueue_style('leaflet-css', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css', array(), '1.9.4');
		wp_enqueue_script('leaflet-js', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js', array(), '1.9.4', true);
	}
}
add_action('wp_enqueue_scripts', 'edusiteco_enqueue_leaflet');
# Google Icons
function edusiteco_enqueue_google_icons() {
	wp_enqueue_style('google-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
}
add_action('wp_enqueue_scripts', 'edusiteco_enqueue_google_icons');

/**
 * Enqueue Customizer panel styles.
 *
 * @return void
 */
function edusiteco_customize_controls_styles() {
    wp_enqueue_style( 
        'edusiteco-customizer-panel-styles', 
        get_template_directory_uri() . '/assets/css/customizer-panel.css'
    );
}
add_action( 'customize_controls_enqueue_scripts', 'edusiteco_customize_controls_styles' );



/**************************/
/** CPT PROJECT TEACHERS **/
/**************************/
require_once get_template_directory() . '/inc/edusiteco-cpt-teachers.php';

/***************************/
/***** CPT COMUNICADOS *****/
/***************************/
require_once get_template_directory() . '/inc/edusiteco-cpt-communications.php';

/***************************/
/***** CPT PSICO RECURSOS **/
/***************************/
require_once get_template_directory() . '/inc/edusiteco-cpt-psico-recursos.php';


/**
 * Creación de páginas por defecto
 */
require_once get_template_directory() . '/inc/edusiteco-default-pages.php';

/**
 * Filtro para eliminar los prefijos de los títulos de archivo (ej. "Categoría:", "Archivo:").
 *
 * @param string $title Título original del archivo.
 * @return string Título modificado sin prefijo.
 */
add_filter( 'get_the_archive_title', function ( $title ) {
    if ( is_category() || is_tag() || is_tax() ) {
        $title = single_term_title( '', false );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    return $title;
});

/**
 * Include the communications post type in the Circulares category archive query.
 */
function edusiteco_include_comunicados_in_circulares_archive( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_post_type_archive( 'comunicado' ) ) {
        $query->set( 'tax_query', array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( 'eventos' ),
                'operator' => 'NOT IN',
            ),
        ) );
        return;
    }

    if ( ! $query->is_category( array( 'circulares', 'eventos' ) ) ) {
        return;
    }

    $query->set( 'post_type', 'comunicado' );
    if ( $query->is_category( 'eventos' ) ) {
        $query->set( 'category_name', '' );
        $query->set( 'cat', '' );
        $query->set( 'tax_query', array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( 'eventos', 'comunicado', 'circulares' ),
                'operator' => 'IN',
            ),
        ) );
        $query->set( 'posts_per_page', 10 );
        $query->set( 'meta_key', '_fecha_evento' );
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_type', 'DATE' );
        $query->set( 'order', 'ASC' );
        $query->set( 'meta_query', array(
            array(
                'key'     => '_fecha_evento',
                'value'   => '',
                'compare' => '!=',
            ),
        ) );
    }
}
add_action( 'pre_get_posts', 'edusiteco_include_comunicados_in_circulares_archive' );

/**
 * Redirect to the page when a search uniquely matches its title.
 */
function edusiteco_redirect_unique_title_search_result() {
	global $wp_query;

	if ( is_admin() || ! is_search() || is_paged() ) {
		return;
	}

	$normalized_query = strtolower( remove_accents( sanitize_text_field( get_search_query( false ) ) ) );

	if ( preg_match( '/\bcomunicados?\b/u', $normalized_query ) ) {
		$communications_archive = get_post_type_archive_link( 'comunicado' );
		if ( $communications_archive ) {
			wp_safe_redirect( $communications_archive, 302, 'Edusiteco Search' );
			exit;
		}
	}

	if ( preg_match( '/psicoor(?:ientacion|entacion)/u', $normalized_query ) ) {
		$psychology_page = get_page_by_path( 'psicoorientacion-escolar' );
		if ( $psychology_page && 'publish' === get_post_status( $psychology_page ) ) {
			$psychology_permalink = get_permalink( $psychology_page );
			if ( $psychology_permalink ) {
				wp_safe_redirect( $psychology_permalink, 302, 'Edusiteco Search' );
				exit;
			}
		}
	}

	if ( 1 !== (int) $wp_query->post_count ) {
		return;
	}

	$search_terms = preg_split(
		'/\s+/',
		trim( preg_replace( '/[^\p{L}\p{N}\s]/u', ' ', $normalized_query ) ),
		-1,
		PREG_SPLIT_NO_EMPTY
	);

	if ( empty( $search_terms ) ) {
		return;
	}

	$result       = $wp_query->posts[0];
	$normalized_title = strtolower( remove_accents( sanitize_text_field( get_the_title( $result ) ) ) );

	foreach ( $search_terms as $term ) {
		if ( false === strpos( $normalized_title, $term ) ) {
			return;
		}
	}

	if ( 'publish' !== get_post_status( $result ) ) {
		return;
	}

	$permalink = get_permalink( $result );
	if ( $permalink ) {
		wp_safe_redirect( $permalink, 302, 'Edusiteco Search' );
		exit;
	}
}
add_action( 'template_redirect', 'edusiteco_redirect_unique_title_search_result' );

#########################
#    EDUSITECO BLOCKS   #
#########################
require_once get_template_directory() . '/inc/edusiteco-register-blocks.php';

/**
 * Encolar los estilos compilados (Tailwind) dentro del editor de bloques.
 *
 * Esto hace que las utilidades de Tailwind (p-6, text-gray-600, etc.) estén
 * disponibles cuando se renderiza el bloque en el editor.
 */
function edusiteco_block_editor_styles() {
	$css_path = get_template_directory() . '/assets/css/index.css';
	
	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'edusiteco-editor-styles',
			get_template_directory_uri() . '/assets/css/index.css',
			array(),
			filemtime( $css_path )
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'edusiteco_block_editor_styles' );

/**
 * Clase para crear un control de separador/título en el Personalizador.
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class Edusiteco_Separator_Control extends WP_Customize_Control {
        public $type = 'separator';

        public function render_content() {
            ?>
            <div style="margin-top: 15px; margin-bottom: 5px; border-top: 1px solid #ddd; padding-top: 15px;">
                <h3 style="font-weight: 600; font-size: 14px; margin: 0;"><?php echo esc_html( $this->label ); ?></h3>
            </div>
            <?php
        }
    }
}

function edusiteco_assign_teacher_to_comunicado($post_id, $post, $update)
{
    if ($post->post_type !== 'comunicado') return;

    $user = wp_get_current_user();

    if (in_array('profesor_role', (array) $user->roles)) {
        update_post_meta($post_id, '_teacher_user_id', $user->ID);
    }
}
add_action('save_post', 'edusiteco_assign_teacher_to_comunicado', 10, 3);

function edusite_register_teacher_meta() {
    register_post_meta('comunicado', '_teacher_user_id', [
        'type'              => 'integer',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () {
            return current_user_can('edit_posts');
        }
    ]);
}
add_action('init', 'edusite_register_teacher_meta');
/**
 * Forzar categorías y etiquetas en Comunicados (fix Gutenberg)
 */
function edusite_force_comunicado_taxonomies() {

    register_taxonomy_for_object_type('category', 'comunicado');
    register_taxonomy_for_object_type('post_tag', 'comunicado');

}
add_action('init', 'edusite_force_comunicado_taxonomies', 20);

/**
 * Habilitar categorías en Comunicados para Admin y Profesor
 */
function edusite_fix_comunicado_category_caps() {

    // ADMIN
    $admin = get_role('administrator');
    if ($admin) {
        $admin->add_cap('assign_categories');
        $admin->add_cap('edit_categories');
    }

    // PROFESOR
    $profesor = get_role('profesor');
    if ($profesor) {
        $profesor->add_cap('assign_categories');
    }
}
add_action('init', 'edusite_fix_comunicado_category_caps');

add_action('add_meta_boxes', function () {
    add_meta_box(
        'categorydiv',
        __('Categorías'),
        'post_categories_meta_box',
        'comunicado',
        'side',
        'default'
    );
});

function edusite_add_comunicado_caps_to_profesor()
{
    $role = get_role('profesor_role');
    if (!$role) return;

    $caps = [
        'read',
        'read_comunicado',
        'read_private_comunicados',

        'edit_comunicado',
        'edit_comunicados',
        'edit_published_comunicados',
        'publish_comunicados',

        'delete_comunicado',
        'delete_comunicados',
        'delete_published_comunicados',
    ];

    foreach ($caps as $cap) {
        $role->add_cap($cap);
    }
}
add_action('init', 'edusite_add_comunicado_caps_to_profesor');


add_action('admin_init', function () {
    $user = wp_get_current_user();
    error_log(print_r($user->roles, true));
});

function cargar_css_directory() {
    if (is_page_template('page-directory.php')) {
        wp_enqueue_style(
            'directory-css',
            get_template_directory_uri() . '/assets/css/directory.css',
            array(),
            '1.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'cargar_css_directory');
