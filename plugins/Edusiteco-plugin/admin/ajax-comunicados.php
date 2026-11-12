<?php
if (!defined('ABSPATH')) exit;

function edusiteco_ajax_can_manage_comunicados()
{
    return current_user_can('manage_options') || current_user_can('manage_edusiteco_comunicados');
}

function edusiteco_ajax_rectoria_can_manage_post($post_id)
{
    return has_category(array('comunicado', 'circulares'), $post_id)
        && !has_category('eventos', $post_id);
}

/**
 * Obtener todos los comunicados
 */
add_action('wp_ajax_edusiteco_get_comunicados', 'edusiteco_ajax_get_comunicados');
function edusiteco_ajax_get_comunicados() {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');

    if (!edusiteco_ajax_can_manage_comunicados()) {
        wp_send_json_error('No tienes permisos.');
    }

    $args = array(
        'post_type' => 'comunicado',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'draft'),
        'orderby' => array(
            'date' => 'DESC',
            'ID' => 'DESC'
        ),
        'ignore_sticky_posts' => true
    );
    if (!current_user_can('manage_options')) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => array('comunicado', 'circulares'),
                'operator' => 'IN',
            ),
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => array('eventos'),
                'operator' => 'NOT IN',
            ),
        );
    }

    $query = new WP_Query($args);
    $comunicados = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            
            // Categoría
            $terms = get_the_terms($post_id, 'category');
            $category_id = '';
            $category_name = '—';
            if ($terms && !is_wp_error($terms)) {
                $category_id = $terms[0]->term_id;
                $category_name = $terms[0]->name;
            }

            // Imagen Destacada (Thumbnail)
            $imagen_id = get_post_thumbnail_id($post_id);
            $imagen_url = '';
            if ($imagen_id) {
                $img_src = wp_get_attachment_image_src($imagen_id, 'thumbnail');
                if ($img_src) {
                    $imagen_url = $img_src[0];
                }
            }

            $comunicados[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'content' => get_the_content(),
                'estado' => get_post_status($post_id),
                'es_historico' => get_post_meta($post_id, '_edusiteco_historic_archive', true) === '1',
                'category_id' => $category_id,
                'category_name' => $category_name,
                'fecha_evento' => get_post_meta($post_id, '_fecha_evento', true),
                'tipo_evento' => get_post_meta($post_id, '_tipo_evento', true),
                'lugar_evento' => get_post_meta($post_id, '_lugar_evento', true),
                'pdf_url' => get_post_meta($post_id, '_pdf_url', true),
                'es_destacado' => get_post_meta($post_id, '_es_destacado', true),
                'imagen_id' => $imagen_id,
                'imagen_url' => $imagen_url,
                'fecha_publicacion' => get_the_date('d/m/Y'),
                'fecha_orden' => (int) get_post_time('U', true, $post_id),
            );
        }
        wp_reset_postdata();
    }

    usort($comunicados, function ($first, $second) {
        $first_published = $first['estado'] === 'publish' ? 0 : 1;
        $second_published = $second['estado'] === 'publish' ? 0 : 1;
        if ($first_published !== $second_published) {
            return $first_published <=> $second_published;
        }

        return $second['fecha_orden'] <=> $first['fecha_orden'];
    });

    foreach ($comunicados as &$comunicado) {
        unset($comunicado['fecha_orden']);
    }
    unset($comunicado);

    wp_send_json_success($comunicados);
}

/**
 * Guardar o actualizar un comunicado
 */
add_action('wp_ajax_edusiteco_save_comunicado', 'edusiteco_ajax_save_comunicado');
function edusiteco_ajax_save_comunicado() {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');

    if (!edusiteco_ajax_can_manage_comunicados()) {
        wp_send_json_error('No tienes permisos.');
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $titulo = isset($_POST['titulo']) ? sanitize_text_field(wp_unslash($_POST['titulo'])) : '';
    $contenido = isset($_POST['contenido']) ? wp_kses_post(wp_unslash($_POST['contenido'])) : '';
    $categoria_id = isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : 0;
    $fecha_evento = isset($_POST['fecha_evento']) ? sanitize_text_field(wp_unslash($_POST['fecha_evento'])) : '';
    $tipo_evento = isset($_POST['tipo_evento']) ? sanitize_text_field(wp_unslash($_POST['tipo_evento'])) : '';
    $lugar_evento = isset($_POST['lugar_evento']) ? sanitize_text_field(wp_unslash($_POST['lugar_evento'])) : '';
    $pdf_url = isset($_POST['pdf_url']) ? esc_url_raw(wp_unslash($_POST['pdf_url'])) : '';
    $es_destacado = isset($_POST['es_destacado']) ? sanitize_text_field(wp_unslash($_POST['es_destacado'])) : '0';
    $imagen_id = isset($_POST['imagen_id']) ? intval($_POST['imagen_id']) : 0;

    if (empty($titulo)) {
        wp_send_json_error('El título es obligatorio.');
    }

    if (!current_user_can('manage_options')) {
        $category = get_term($categoria_id, 'category');
        if (!$category || is_wp_error($category) || !in_array($category->slug, array('comunicado', 'circulares'), true)) {
            wp_send_json_error('Rectoría solo puede publicar en las categorías Comunicados y Circulares.');
        }
    }

    $event_category = get_category_by_slug('eventos');
    $is_event = ($event_category && $categoria_id === (int) $event_category->term_id) || $fecha_evento !== '';
    if ($is_event) {
        $allowed_event_types = array('ACADEMICO', 'REUNION DE PADRES', 'EVENTO CULTURAL', 'JORNADA PEDAGOGICA', 'EVENTO DEPORTIVO', 'EVENTO INSTITUCIONAL');
        $event_date = DateTime::createFromFormat('!Y-m-d', $fecha_evento);
        if (!$event_date || $event_date->format('Y-m-d') !== $fecha_evento) {
            wp_send_json_error('Selecciona una fecha válida para la actividad o el evento.');
        }
        if (!in_array($tipo_evento, $allowed_event_types, true)) {
            wp_send_json_error('Selecciona el tipo de evento.');
        }
        if ($lugar_evento === '') {
            wp_send_json_error('Escribe el lugar del evento.');
        }
    }

    if ($id > 0) {
        $existing_post = get_post($id);
        $can_edit_post = current_user_can('manage_options')
            ? current_user_can('edit_post', $id)
            : ($existing_post && edusiteco_ajax_can_manage_comunicados() && edusiteco_ajax_rectoria_can_manage_post($id));
        if (!$existing_post || $existing_post->post_type !== 'comunicado' || !$can_edit_post) {
            wp_send_json_error('No tienes permisos para editar este comunicado.');
        }
    }

    $post_data = array(
        'post_title' => $titulo,
        'post_content' => $contenido,
        'post_status' => 'publish',
        'post_type' => 'comunicado'
    );

    if ($id > 0) {
        $post_data['ID'] = $id;
        $post_id = wp_update_post($post_data);
    } else {
        $post_id = wp_insert_post($post_data);
    }

    if (is_wp_error($post_id) || !$post_id) {
        wp_send_json_error(is_wp_error($post_id) ? $post_id->get_error_message() : 'Error al guardar el comunicado.');
    }

    // Guardar Categoría
    if ($categoria_id > 0) {
        wp_set_post_categories($post_id, array($categoria_id));
    }

    // Guardar Metadatos
    update_post_meta($post_id, '_fecha_evento', $fecha_evento);
    update_post_meta($post_id, '_tipo_evento', $is_event ? $tipo_evento : '');
    update_post_meta($post_id, '_lugar_evento', $is_event ? $lugar_evento : '');
    update_post_meta($post_id, '_pdf_url', $pdf_url);
    update_post_meta($post_id, '_es_destacado', $es_destacado);

    // Guardar Imagen Destacada
    if ($imagen_id > 0) {
        set_post_thumbnail($post_id, $imagen_id);
    } else {
        delete_post_thumbnail($post_id);
    }

    wp_send_json_success(array('message' => 'Comunicado guardado exitosamente.'));
}

/**
 * Eliminar un comunicado
 */
add_action('wp_ajax_edusiteco_delete_comunicado', 'edusiteco_ajax_delete_comunicado');
function edusiteco_ajax_delete_comunicado() {
    check_ajax_referer('edusiteco_admin_nonce', 'nonce');

    $is_admin = current_user_can('manage_options');
    $can_delete_comunicados = current_user_can('delete_edusiteco_comunicados');
    if (!$is_admin && !$can_delete_comunicados) {
        wp_send_json_error('No tienes permisos.');
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $post = get_post($id);
        $is_allowed_category = edusiteco_ajax_rectoria_can_manage_post($id);
        if (
            !$post
            || $post->post_type !== 'comunicado'
            || (!$is_admin && !$is_allowed_category)
            || ($is_admin && !current_user_can('delete_post', $id))
        ) {
            wp_send_json_error('No tienes permisos para eliminar este comunicado.');
        }
        $result = $is_admin ? wp_delete_post($id, true) : wp_trash_post($id);
        if ($result) {
            wp_send_json_success('Comunicado eliminado.');
        } else {
            wp_send_json_error('Error al eliminar el comunicado.');
        }
    } else {
        wp_send_json_error('ID de comunicado inválido.');
    }
}
