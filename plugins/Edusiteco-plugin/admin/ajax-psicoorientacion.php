<?php
// Evitar acceso directo
if (!defined('ABSPATH')) exit;

/**
 * Obtener todos los recursos del botiquín
 */
add_action('wp_ajax_edusiteco_get_recursos', function () {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('No tienes permisos');

    $args = array(
        'post_type'      => 'psico_recurso',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query = new WP_Query($args);
    $recursos = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $thumb_url = '';
            $has_attachment = false;
            
            $attachment_id = get_post_thumbnail_id();
            if ($attachment_id) {
                $has_attachment = true;
                $thumb_src = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                if ($thumb_src) {
                    // Si es una imagen, devuelve la miniatura, si es PDF o documento no devolverá src válido para img a menos que WP genere fallback
                    // wp_get_attachment_image_src puede devolver false para PDFs, manejémoslo:
                    $mime = get_post_mime_type($attachment_id);
                    if (strpos($mime, 'image/') !== false) {
                        $thumb_url = $thumb_src[0];
                    }
                }
            }

            $recursos[] = array(
                'id'             => get_the_ID(),
                'title'          => get_the_title(),
                'description'    => get_the_content(),
                'thumb_url'      => $thumb_url,
                'has_attachment' => $has_attachment,
                'file_url'       => $attachment_id ? wp_get_attachment_url($attachment_id) : ''
            );
        }
    }
    wp_reset_postdata();

    wp_send_json_success($recursos);
});

/**
 * Guardar (Crear o Actualizar) Recurso
 */
add_action('wp_ajax_edusiteco_save_recurso', function () {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('No tienes permisos');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
    $description = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';
    $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;

    if (empty($title)) {
        wp_send_json_error('El título es obligatorio.');
    }

    if ($id > 0) {
        $existing_post = get_post($id);
        if (!$existing_post || $existing_post->post_type !== 'psico_recurso') {
            wp_send_json_error('Recurso no encontrado.');
        }
        if (!current_user_can('edit_post', $id)) {
            wp_send_json_error('No tienes permisos para editar este recurso.');
        }
    }

    if ($attachment_id > 0) {
        $attachment = get_post($attachment_id);
        $mime_type = $attachment ? get_post_mime_type($attachment_id) : '';
        if (!$attachment || $attachment->post_type !== 'attachment' || (strpos($mime_type, 'image/') !== 0 && $mime_type !== 'application/pdf')) {
            wp_send_json_error('Selecciona un archivo PDF o una imagen válida.');
        }
    }

    $post_data = array(
        'post_title'  => $title,
        'post_content' => $description,
        'post_type'   => 'psico_recurso',
        'post_status' => 'publish',
    );

    if ($id > 0) {
        $post_data['ID'] = $id;
        $post_id = wp_update_post($post_data);
    } else {
        $post_id = wp_insert_post($post_data);
    }

    if (is_wp_error($post_id) || !$post_id) {
        wp_send_json_error(is_wp_error($post_id) ? $post_id->get_error_message() : 'Error al guardar el recurso.');
    }

    // Gestionar attachment (Thumbnail) y URL directa
    if ($attachment_id > 0) {
        set_post_thumbnail($post_id, $attachment_id);
        $file_url = wp_get_attachment_url($attachment_id);
        if ($file_url) {
            update_post_meta($post_id, 'recurso_url', $file_url);
        }
    } else {
        delete_post_thumbnail($post_id);
        delete_post_meta($post_id, 'recurso_url');
    }

    wp_send_json_success(array('id' => $post_id));
});

/**
 * Obtener un recurso específico para editar
 */
add_action('wp_ajax_edusiteco_get_recurso_single', function () {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('No tienes permisos');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $post = get_post($id);

    if (!$post || $post->post_type !== 'psico_recurso') {
        wp_send_json_error('Recurso no encontrado.');
    }
    if (!current_user_can('edit_post', $id)) {
        wp_send_json_error('No tienes permisos para editar este recurso.');
    }

    $attachment_id = get_post_thumbnail_id($id);
    $filename = '';
    $thumb_url = '';

    if ($attachment_id) {
        $file_path = get_attached_file($attachment_id);
        if ($file_path) {
            $filename = wp_basename($file_path);
        }
        $mime = get_post_mime_type($attachment_id);
        if (strpos($mime, 'image/') !== false) {
            $thumb_src = wp_get_attachment_image_src($attachment_id, 'thumbnail');
            if ($thumb_src) {
                $thumb_url = $thumb_src[0];
            }
        }
    }

    $data = array(
        'id'            => $post->ID,
        'title'         => $post->post_title,
        'description'   => $post->post_content,
        'attachment_id' => $attachment_id,
        'filename'      => $filename,
        'thumb_url'     => $thumb_url
    );

    wp_send_json_success($data);
});

/**
 * Borrar Recurso
 */
add_action('wp_ajax_edusiteco_delete_recurso', function () {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('No tienes permisos');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $post = get_post($id);

    if (!$post || $post->post_type !== 'psico_recurso') {
        wp_send_json_error('Recurso no encontrado.');
    }
    if (!current_user_can('delete_post', $id)) {
        wp_send_json_error('No tienes permisos para eliminar este recurso.');
    }

    if (!wp_delete_post($id, true)) {
        wp_send_json_error('No se pudo eliminar el recurso.');
    }

    wp_send_json_success();
});
