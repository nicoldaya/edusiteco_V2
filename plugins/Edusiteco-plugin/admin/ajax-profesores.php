<?php
if (!defined('ABSPATH')) exit;

// ==========================================
// CARGAR LISTA DE PROFESORES
// ==========================================
add_action('wp_ajax_edusiteco_load_profesores', 'edusiteco_load_profesores_ajax');
function edusiteco_load_profesores_ajax() {
    check_ajax_referer('edusiteco_admin_nonce', 'security');

    $args = array(
        'post_type'      => 'profesor',
        'posts_per_page' => -1,
        'post_status'    => array('publish', 'draft'),
        'orderby'        => 'title',
        'order'          => 'ASC'
    );
    $query = new WP_Query($args);
    
    $profesores = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $id = get_the_ID();
            $thumbnail = get_the_post_thumbnail_url($id, 'thumbnail');
            if (!$thumbnail) {
                // Fallback
                $thumbnail = 'https://www.gravatar.com/avatar/0?d=mp&f=y';
            }
            
            $asignatura = get_post_meta($id, 'profesor_asignatura', true);
            $content = get_post_field('post_content', $id);
            $thumbnail_id = get_post_thumbnail_id($id);
            
            $profesores[] = array(
                'id' => $id,
                'title' => get_the_title(),
                'asignatura' => $asignatura,
                'description' => $content,
                'thumbnail_url' => $thumbnail,
                'thumbnail_id' => $thumbnail_id
            );
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success(array('profesores' => $profesores));
}

// ==========================================
// GUARDAR / EDITAR PROFESOR
// ==========================================
add_action('wp_ajax_edusiteco_save_profesor', 'edusiteco_save_profesor_ajax');
function edusiteco_save_profesor_ajax() {
    check_ajax_referer('edusiteco_admin_nonce', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos.');
    }

    $id = isset($_POST['profesor_id']) ? intval($_POST['profesor_id']) : 0;
    $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
    $asignatura = isset($_POST['asignatura']) ? sanitize_text_field($_POST['asignatura']) : '';
    $descripcion = isset($_POST['descripcion']) ? wp_kses_post(stripslashes($_POST['descripcion'])) : '';
    $foto_id = isset($_POST['foto_id']) ? intval($_POST['foto_id']) : 0;

    if (empty($nombre)) {
        wp_send_json_error('El nombre es obligatorio.');
    }

    $post_data = array(
        'post_title'   => $nombre,
        'post_content' => $descripcion,
        'post_type'    => 'profesor',
        'post_status'  => 'publish'
    );

    if ($id > 0) {
        $post_data['ID'] = $id;
        $post_id = wp_update_post($post_data);
    } else {
        $post_id = wp_insert_post($post_data);
    }

    if (is_wp_error($post_id)) {
        wp_send_json_error('Error al guardar el profesor.');
    }

    update_post_meta($post_id, 'profesor_asignatura', $asignatura);

    if ($foto_id > 0) {
        set_post_thumbnail($post_id, $foto_id);
    } else {
        delete_post_thumbnail($post_id);
    }

    wp_send_json_success(array(
        'message' => 'Profesor guardado exitosamente.',
        'post_id' => $post_id
    ));
}

// ==========================================
// ELIMINAR PROFESOR
// ==========================================
add_action('wp_ajax_edusiteco_delete_profesor', 'edusiteco_delete_profesor_ajax');
function edusiteco_delete_profesor_ajax() {
    check_ajax_referer('edusiteco_admin_nonce', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No tienes permisos.');
    }

    $id = isset($_POST['profesor_id']) ? intval($_POST['profesor_id']) : 0;

    if ($id > 0) {
        $result = wp_delete_post($id, true);
        if ($result) {
            wp_send_json_success('Profesor eliminado.');
        } else {
            wp_send_json_error('Error al eliminar el profesor.');
        }
    } else {
        wp_send_json_error('ID inválido.');
    }
}
