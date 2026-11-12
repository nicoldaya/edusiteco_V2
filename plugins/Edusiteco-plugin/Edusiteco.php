<?php
/*
Plugin Name: Edusiteco
Description: Permite configurar y mostrar en el footer la información institucional del colegio (incluye redes sociales y enlaces de interés).
Version: 2.0
Author: Daniel Adrada
Author: Nicol anacona y Diego Muñoz
*/

if (!defined('ABSPATH')) exit;

function edusiteco_register_rectoria_role()
{
    $role = get_role('gestor_comunicados_rectoria');
    if (!$role) {
        add_role('gestor_comunicados_rectoria', 'Rectoría - Comunicados', array('read' => true));
        $role = get_role('gestor_comunicados_rectoria');
    }

    if ($role) {
        $role->add_cap('manage_edusiteco_comunicados');
        $role->add_cap('delete_edusiteco_comunicados');
        $role->add_cap('upload_files');
    }
}
add_action('init', 'edusiteco_register_rectoria_role', 5);

function edusiteco_enqueue_rectoria_comunicados_assets()
{
    $page = get_queried_object();
    if (
        !($page instanceof WP_Post)
        || !has_shortcode($page->post_content, 'edusiteco_rectoria_comunicados')
        || !is_user_logged_in()
        || (!current_user_can('manage_options') && !current_user_can('manage_edusiteco_comunicados'))
    ) {
        return;
    }

    wp_enqueue_script('jquery');
    wp_enqueue_media();
    wp_localize_script('jquery', 'edusiteco_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('edusiteco_admin_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'edusiteco_enqueue_rectoria_comunicados_assets');

function edusiteco_add_rectoria_admin_bar($wp_admin_bar)
{
    if (!is_user_logged_in()) {
        return;
    }

    if (!current_user_can('manage_options') && !current_user_can('manage_edusiteco_comunicados')) {
        return;
    }

    $rectoria_page = get_page_by_path('rectoria');
    if (!$rectoria_page instanceof WP_Post) {
        return;
    }

    $wp_admin_bar->add_node(array(
        'id' => 'edusiteco-panel-rectoria',
        'title' => 'Panel de Rectoría y Comunicados',
        'href' => get_permalink($rectoria_page),
        'meta' => array(
            'title' => 'Abrir el CRUD de comunicados y circulares',
        ),
    ));
}
add_action('admin_bar_menu', 'edusiteco_add_rectoria_admin_bar', 100);

function edusiteco_login_redirect_by_role($redirect_to, $requested_redirect_to, $user)
{
    if (!($user instanceof WP_User)) {
        return $redirect_to;
    }

    if (user_can($user, 'manage_options')) {
        return admin_url();
    }

    if (user_can($user, 'manage_edusiteco_comunicados')) {
        $rectoria_comunicados_page = get_page_by_path('rectoria/comunicados');
        return $rectoria_comunicados_page instanceof WP_Post
            ? get_permalink($rectoria_comunicados_page)
            : home_url('/rectoria/comunicados/');
    }

    return $redirect_to;
}
add_filter('login_redirect', 'edusiteco_login_redirect_by_role', 20, 3);

function edusiteco_rectoria_comunicados_shortcode()
{
    if (isset($_GET['edusiteco_logout']) && $_GET['edusiteco_logout'] === '1') {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'edusiteco_logout')) {
            wp_die('La solicitud de cierre de sesión no es válida.', 'Rectoría');
        }
        wp_logout();
        wp_safe_redirect(get_permalink());
        exit;
    }

    if (!is_user_logged_in()) {
        ob_start();
        ?>
        <style>
            body.page-id-<?php echo absint(get_queried_object_id()); ?> #primary.site-main { max-width: 1200px; padding: 1rem; }
            body.page-id-<?php echo absint(get_queried_object_id()); ?> .entry-header { display: none; }
            .edusiteco-rectoria-login { max-width: 480px; margin: 3rem auto; padding: 2.5rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 24px 60px rgba(15, 23, 42, .16); color: #1e293b; }
            .edusiteco-rectoria-login__brand { display: grid; width: 64px; height: 64px; margin: 0 auto 1.25rem; place-items: center; border-radius: 18px; background: linear-gradient(135deg, #0b65ad, #13a0dc); color: #fff; }
            .edusiteco-rectoria-login h1 { margin: 0; color: #0f172a; text-align: center; font-size: 1.8rem; }
            .edusiteco-rectoria-login__intro { margin: .75rem 0 1.75rem; color: #64748b; text-align: center; line-height: 1.6; }
            .edusiteco-rectoria-login form p { margin: 0 0 1rem; }
            .edusiteco-rectoria-login label { display: block; margin-bottom: .4rem; color: #334155; font-weight: 700; }
            .edusiteco-rectoria-login input[type="text"], .edusiteco-rectoria-login input[type="password"] { box-sizing: border-box; width: 100%; min-height: 48px; padding: .7rem .85rem; border: 1px solid #cbd5e1; border-radius: 10px; background: #fff; color: #0f172a; }
            .edusiteco-rectoria-login input:focus { border-color: #168bd0; outline: 3px solid rgba(22, 139, 208, .16); }
            .edusiteco-rectoria-login .login-remember label { display: flex; align-items: center; gap: .55rem; font-weight: 500; }
            .edusiteco-rectoria-login .login-submit { margin-top: 1.4rem; }
            .edusiteco-rectoria-login input[type="submit"] { width: 100%; min-height: 50px; border: 0; border-radius: 10px; background: #087fbd; color: #fff; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .2s ease, transform .2s ease; }
            .edusiteco-rectoria-login input[type="submit"]:hover { transform: translateY(-1px); background: #06689c; }
            .edusiteco-rectoria-login__help { margin: 1.25rem 0 0; text-align: center; }
            .edusiteco-rectoria-login a { color: #087fbd; font-weight: 600; text-decoration: none; }
            .edusiteco-rectoria-login a:hover { text-decoration: underline; }
            @media (max-width: 600px) { .edusiteco-rectoria-login { margin: 1.5rem 1rem; padding: 1.5rem; } }
        </style>
        <section class="edusiteco-rectoria-login" aria-labelledby="rectoria-login-title">
            <div class="edusiteco-rectoria-login__brand" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7l-8-4Z"></path>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
            </div>
            <h1 id="rectoria-login-title">Acceso de Rectoría</h1>
            <p class="edusiteco-rectoria-login__intro">Inicia sesión para administrar los comunicados y circulares de la institución.</p>
            <?php
            echo wp_login_form(array(
                'echo' => false,
                'redirect' => get_permalink(),
                'form_id' => 'edusiteco-rectoria-login-form',
                'label_username' => 'Usuario o correo electrónico',
                'label_password' => 'Contraseña',
                'label_remember' => 'Mantener mi sesión iniciada',
                'label_log_in' => 'Entrar al panel',
                'remember' => true,
                'value_remember' => false,
            ));
            ?>
            <p class="edusiteco-rectoria-login__help"><a href="<?php echo esc_url(wp_lostpassword_url(get_permalink())); ?>">¿Olvidaste tu contraseña?</a></p>
        </section>
        <?php
        return ob_get_clean();
    }

    if (current_user_can('manage_options')) {
        $switch_account_url = wp_nonce_url(add_query_arg('edusiteco_logout', '1', get_permalink()), 'edusiteco_logout');
        return '<div class="edusiteco-rectoria-login"><h2>Sesión de administrador</h2><p>Para administrar el sitio, usa el escritorio de WordPress. Las cuentas de WordPress se cambian al cerrar una sesión e iniciar la otra.</p><p><a href="' . esc_url(admin_url()) . '">Ir al escritorio de WordPress</a> · <a href="' . esc_url($switch_account_url) . '">Cambiar a la cuenta de Rectoría</a></p></div>';
    }

    if (!current_user_can('manage_edusiteco_comunicados')) {
        return '<div class="edusiteco-rectoria-login"><h2>Acceso restringido</h2><p>Esta página está disponible únicamente para Rectoría.</p></div>';
    }

    ob_start();
    ?>
    <style>
        body.page-id-<?php echo absint(get_queried_object_id()); ?> #primary.site-main { max-width: 1400px; padding: 1rem; }
        body.page-id-<?php echo absint(get_queried_object_id()); ?> .entry-header { display: none; }
        .edusiteco-rectoria-dashboard { max-width: 1200px; margin: 2rem auto; padding: clamp(1rem, 3vw, 2rem); background: #f8fafc; border: 1px solid #dbe3ec; border-radius: 20px; box-shadow: 0 24px 60px rgba(15, 23, 42, .12); color: #1e293b; }
        .edusiteco-rectoria-dashboard__hero { margin: calc(clamp(1rem, 3vw, 2rem) * -1) calc(clamp(1rem, 3vw, 2rem) * -1) 1.5rem; padding: 2rem clamp(1rem, 3vw, 2rem); border-radius: 19px 19px 0 0; background: linear-gradient(120deg, #0c355d, #087fbd); color: #fff; }
        .edusiteco-rectoria-dashboard__hero { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
        .edusiteco-rectoria-dashboard__hero h1 { margin: 0 0 .45rem; color: #fff; font-size: clamp(1.6rem, 3vw, 2.2rem); }
        .edusiteco-rectoria-dashboard__hero p { margin: 0; color: rgba(255,255,255,.88); }
        .edusiteco-rectoria-dashboard__logout { display: inline-flex; align-items: center; min-height: 40px; padding: .55rem 1rem; border: 1px solid rgba(255,255,255,.65); border-radius: 9px; color: #fff !important; background: rgba(255,255,255,.1); font-weight: 700; text-decoration: none; }
        .edusiteco-rectoria-dashboard__logout:hover { background: rgba(255,255,255,.2); color: #fff !important; }
        .edusiteco-rectoria-actions { display: flex; justify-content: flex-end; margin: -0.75rem 0 1rem; }
        .edusiteco-rectoria-actions .edusiteco-rectoria-dashboard__logout { border-color: #087fbd; background: #087fbd; color: #fff !important; }
        .edusiteco-rectoria-actions .edusiteco-rectoria-dashboard__logout:hover { background: #06689c; }
        .edusiteco-rectoria-dashboard h2, .edusiteco-rectoria-dashboard h3 { color: #172b43; }
        .edusiteco-rectoria-dashboard > h2 { margin: 1.6rem 0 .35rem; }
        .edusiteco-rectoria-dashboard > p { color: #64748b; }
        .edusiteco-rectoria-dashboard > div[style*="margin-top: 20px"] { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; margin: 1.25rem 0 !important; }
        .edusiteco-rectoria-dashboard .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; padding: .6rem 1rem; border: 1px solid #cbd5e1; border-radius: 9px; background: #fff; color: #1e293b; text-decoration: none; cursor: pointer; font-weight: 650; }
        .edusiteco-rectoria-dashboard .button-primary { border-color: #087fbd; background: #087fbd; color: #fff; }
        .edusiteco-rectoria-dashboard .button:hover { filter: brightness(.96); }
        .edusiteco-rectoria-dashboard input[type="text"], .edusiteco-rectoria-dashboard input[type="url"], .edusiteco-rectoria-dashboard input[type="date"], .edusiteco-rectoria-dashboard select, .edusiteco-rectoria-dashboard textarea { box-sizing: border-box; max-width: 100%; padding: .7rem .8rem; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #172b43; }
        .edusiteco-rectoria-dashboard #formulario-comunicado { padding: clamp(1rem, 3vw, 1.5rem) !important; border: 1px solid #dbe3ec !important; border-radius: 14px !important; box-shadow: 0 8px 24px rgba(15, 23, 42, .06); }
        .edusiteco-rectoria-dashboard #formulario-comunicado .form-table { width: 100%; }
        .edusiteco-rectoria-dashboard #formulario-comunicado .form-table th { width: 190px; text-align: left; color: #334155; }
        .edusiteco-rectoria-dashboard #formulario-comunicado .form-table td { text-align: left; }
        .edusiteco-rectoria-dashboard .wp-list-table { position: static !important; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-list { overflow-x: auto; padding: clamp(1rem, 2vw, 1.5rem) !important; border: 1px solid #dbe3ec !important; border-radius: 16px !important; box-shadow: 0 10px 28px rgba(15, 23, 42, .06); }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-list h3 { margin: 0 0 .4rem !important; color: #123b62; font-size: 1.35rem; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-list > p { margin: 0 0 1rem; color: #64748b; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-filters { align-items: center; gap: .75rem !important; padding: .85rem; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-filters input[type="search"] { flex: 1 1 280px; min-width: min(100%, 240px) !important; padding: .7rem .85rem !important; border: 1px solid #cbd5e1; border-radius: 9px; background: #fff; color: #172b43; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-filters select { min-height: 44px; }
        .edusiteco-rectoria-dashboard #contador-comunicados { margin-left: auto; color: #475569 !important; font-size: .9rem; font-weight: 600; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table { width: 100%; min-width: 900px; margin-top: 1rem; border: 1px solid #e2e8f0; border-spacing: 0; border-radius: 12px; overflow: hidden; background: #fff; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table thead th { padding: .9rem .75rem; border: 0; background: #123b62; color: #fff; text-align: left; font-size: .82rem; font-weight: 700; letter-spacing: .025em; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody td { padding: .85rem .75rem; border: 0; border-bottom: 1px solid #e8eef5; color: #475569; vertical-align: middle; line-height: 1.5; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody tr:nth-child(even) { background: #f8fafc; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody tr:hover { background: #eff6ff; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody td:first-child { color: #172b43; font-weight: 600; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody td:nth-child(4),
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table tbody td:nth-child(5) { white-space: nowrap; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(1) { width: 27%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(2) { width: 13%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(3) { width: 15%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(4) { width: 14%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(5) { width: 10%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-table th:nth-child(6) { width: 21%; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-pagination { display: flex; justify-content: center; align-items: center; gap: .85rem; padding: 1.15rem .25rem .1rem; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-pagination .button { min-height: 38px; padding: .45rem .85rem; border-radius: 8px; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicados-pagination .button:disabled { opacity: .48; cursor: not-allowed; }
        .edusiteco-rectoria-dashboard #comunicados-pagina-actual { min-width: 100px; color: #475569; text-align: center; font-size: .9rem; font-weight: 650; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicado-actions { display: flex; flex-direction: column; align-items: stretch; gap: .35rem; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicado-actions .button { box-sizing: border-box; width: 100%; min-height: 34px; margin-right: 0 !important; padding: .3rem .35rem; border-radius: 8px; font-size: .75rem; line-height: 1.2; white-space: nowrap; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicado-actions .button[style*="color:#b32d2e"] { color: #a32932 !important; border-color: #e5a3a8 !important; background: #fff7f7; }
        .edusiteco-rectoria-dashboard .edusiteco-comunicado-actions .button[style*="color:#b32d2e"]:hover { border-color: #b4232d !important; background: #fff0f1; }
        @media (max-width: 700px) {
            .edusiteco-rectoria-dashboard { margin: 1rem; }
            .edusiteco-rectoria-dashboard .edusiteco-comunicados-list { padding: .85rem !important; }
            .edusiteco-rectoria-dashboard #contador-comunicados { width: 100%; margin-left: 0; }
            .edusiteco-rectoria-dashboard .edusiteco-comunicados-pagination { gap: .5rem; }
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table,
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table tbody,
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table tr,
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table th,
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table td { display: block; width: 100%; box-sizing: border-box; }
            .edusiteco-rectoria-dashboard #formulario-comunicado .form-table th { padding-bottom: .35rem; }
        }
    </style>
    <div class="edusiteco-rectoria-dashboard">
        <header class="edusiteco-rectoria-dashboard__hero">
            <div>
                <h1>Panel de Rectoría</h1>
                <p>Publica y actualiza información para las familias, estudiantes y comunidad educativa.</p>
            </div>
        </header>
        <div class="edusiteco-rectoria-actions">
            <a class="edusiteco-rectoria-dashboard__logout" href="<?php echo esc_url(wp_nonce_url(add_query_arg('edusiteco_logout', '1', get_permalink()), 'edusiteco_logout')); ?>">Cerrar sesión</a>
        </div>
        <?php include plugin_dir_path(__FILE__) . 'admin/seccion-comunicados.php'; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('edusiteco_rectoria_comunicados', 'edusiteco_rectoria_comunicados_shortcode');

// Registrar menú en el admin
add_action('admin_menu', function () {
    add_menu_page(
        'Edusiteco',
        'Edusiteco',
        'manage_options',
        'info-colegio',
        'edusiteco_router',
        'dashicons-welcome-learn-more',
        20
    );

    // Submenú de Bienvenida
    add_submenu_page(
        'info-colegio',
        'Bienvenida',
        'Bienvenida',
        'manage_options',
        'info-colegio-bienvenida',
        'edusiteco_welcome_screen'
    );

});

// Registrar opciones
add_action('admin_init', function () {
    register_setting('info_colegio_group', 'info_colegio_settings');
});

// Cargar scripts de medios
add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos($hook, 'info-colegio') === false) return;

    wp_enqueue_media();
    wp_enqueue_script('jquery');
    
    wp_localize_script('jquery', 'edusiteco_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('edusiteco_admin_nonce')
    ));
});

function edusiteco_router()
{
    if (!isset($_GET['setup'])) {
        edusiteco_welcome_screen(); // Bienvenida primero
    } else {
        edusiteco_admin_page(); // Luego las pestañas
    }
}

// Pantalla de bienvenida del plugin
function edusiteco_welcome_screen()
{
?>
    <div class="wrap">
        <h1 style="font-size:32px; font-weight:bold; margin-bottom:10px;">Bienvenido a Edusiteco</h1>

        <p style="font-size:16px; max-width:700px; color:#444; line-height:1.6;">
            Gracias por instalar el plugin <strong>Edusiteco</strong>.
            Este módulo permite gestionar toda la información institucional del colegio
            de forma organizada y visualmente profesional.
            Desde aquí podrás configurar:
        </p>

        <ul style="font-size:15px; color:#333; margin-left:20px; margin-bottom:20px; line-height:1.7;">
            <li>✔ La página de inicio institucional</li>
            <li>✔ Misión, visión y valores</li>
            <li>✔ Directorio de personal</li>
            <li>✔ Historia del colegio</li>
            <li>✔ Símbolos institucionales</li>
            <li>✔ Información de contacto</li>
            <li>✔ Footer y enlaces importantes</li>
            //Plugin Name: Edusiteco 
            //Description: Permite configurar y mostrar en el footer la información institucional del colegio (incluye redes sociales y enlaces de interés).
            //Version: 2.0
            //Author: nicol anacona y diego muñoz

            <li>✔ comunicados y circulares</li>
            <li>✔ profesores y personal docente</li>
            <li>✔ psicoorientación y apoyo estudiantil</li>
    
        </ul>

        <p style="font-size:15px; color:#444; max-width:700px;">
            Para comenzar, haz clic en el siguiente botón y empieza a llenar la información del colegio.
        </p>

        <a href="<?php echo admin_url('admin.php?page=info-colegio&setup=true'); ?>"
            style="display:inline-block; margin-top:25px; background:#2271b1; color:white;
                  padding:12px 24px; border-radius:5px; font-size:16px; text-decoration:none;">
            👉 Gestionar Información Institucional
        </a>

        <style>
            .wrap {
                background: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            }
        </style>
    </div>
<?php
}

// Renderizar la página del plugin
function edusiteco_admin_page()
{
    $o = get_option('info_colegio_settings');
?>
    <div class="wrap">
        <h1>Configuración de la Información del Colegio</h1>
        <p style="max-width:700px; font-size:14px; color:#555;">
            Desde esta sección puedes ingresar toda la información institucional del colegio dividida por categorías.
            Selecciona una pestaña para editar los datos correspondientes (misión, historia, docentes, símbolos, etc.).
        </p>
        <a href="<?php echo admin_url('admin.php?page=info-colegio-bienvenida'); ?>"
            style="display:inline-block; margin-top:25px; background:#2271b1; color:white;
                  padding:12px 24px; border-radius:5px; font-size:16px; text-decoration:none;">
            👈 Volver a la Bienvenida
        </a>

        <h2 class="nav-tab-wrapper">
            <a href="#tab-paginaInicio" class="nav-tab nav-tab-active">Pagina de Inicio</a>
            <a href="#tab-identidad" class="nav-tab">Misión, Visión y Valores</a>
            <a href="#tab-historia" class="nav-tab">Historia</a>
            <a href="#tab-directorio" class="nav-tab">Directorio</a>
            <a href="#tab-simbolosInstitucionales" class="nav-tab">Símbolos Institucionales</a>
            <a href="#tab-contactanos" class="nav-tab">Contactanos</a>
            <a href="#tab-footer" class="nav-tab">Footer</a>
            <a href="#tab-profesores" class="nav-tab">Profesores</a>
            <a href="#tab-psicoorientacion" class="nav-tab">Psicoorientación</a>
        </h2>

        <form method="post" action="options.php">
            <?php settings_fields('info_colegio_group'); ?>

            <!-- ===== Secciones con pestañas ===== -->

            <div id="tab-paginaInicio" class="tab-content" style="display:block;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-paginaInicio.php'; ?>
            </div>

            <div id="tab-identidad" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-identidad.php'; ?>
            </div>

            <div id="tab-directorio" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-directorio.php'; ?>
            </div>

            <div id="tab-historia" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-historia.php'; ?>
            </div>

            <div id="tab-simbolosInstitucionales" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-simbolosInstitucionales.php'; ?>
            </div>

            <div id="tab-contactanos" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-contactanos.php'; ?>
            </div>

            <div id="tab-footer" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-footer.php'; ?>
            </div>


            <div id="tab-profesores" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-profesores.php'; ?>
            </div>

            <div id="tab-psicoorientacion" class="tab-content" style="display:none;">
                <?php include plugin_dir_path(__FILE__) . 'admin/seccion-psicoorientacion.php'; ?>
            </div>

            <?php submit_button('Guardar cambios'); ?>
        </form>
    </div>

    <script>
        // ==== Cambiar entre pestañas ====
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.nav-tab');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', e => {
                    e.preventDefault();
                    // Quitar selección anterior
                    tabs.forEach(t => t.classList.remove('nav-tab-active'));
                    contents.forEach(c => c.style.display = 'none');

                    // Activar nueva pestaña
                    tab.classList.add('nav-tab-active');
                    const target = document.querySelector(tab.getAttribute('href'));
                    if (target) target.style.display = 'block';
                });
            });
        });
    </script>

    <style>
        .nav-tab-wrapper {
            margin-bottom: 0;
            border-bottom: 1px solid #ccc;
        }

        .nav-tab {
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .nav-tab:hover {
            background: #f0f0f1;
        }

        .nav-tab-active {
            background: #fff;
            border-bottom: 1px solid #fff !important;
        }

        .tab-content {
            background: #fff;
            padding: 25px;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 6px 6px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

<?php
}

function edusiteco_remove_admin_footer()
{
    $screen = get_current_screen();

    if ($screen && $screen->id === 'toplevel_page_info-colegio') {
        add_filter('admin_footer_text', '__return_empty_string');
        add_filter('update_footer', '__return_empty_string');
    }
}
add_action('admin_head', 'edusiteco_remove_admin_footer');

// Incluir el módulo de accesibilidad
include_once plugin_dir_path(__FILE__) . 'admin/accesibilidad/wp-accessibility-plugin.php';

// Incluir controladores AJAX
include_once plugin_dir_path(__FILE__) . 'admin/ajax-profesores.php';
include_once plugin_dir_path(__FILE__) . 'admin/ajax-comunicados.php';
include_once plugin_dir_path(__FILE__) . 'admin/ajax-psicoorientacion.php';
