<?php
/**
 * Custom Post Type: Psico_Recurso (Botiquín de Psicoorientación)
 */

if (!function_exists('edusiteco_cpt_psico_recursos')) {
    function edusiteco_cpt_psico_recursos() {
        $labels = array(
            'name'                  => _x('Recursos Psico', 'Post Type General Name', 'edusiteco'),
            'singular_name'         => _x('Recurso Psico', 'Post Type Singular Name', 'edusiteco'),
            'menu_name'             => __('Botiquín Psico', 'edusiteco'),
            'name_admin_bar'        => __('Recurso Psico', 'edusiteco'),
            'archives'              => __('Archivos de Recursos', 'edusiteco'),
            'attributes'            => __('Atributos de Recursos', 'edusiteco'),
            'parent_item_colon'     => __('Recurso Padre:', 'edusiteco'),
            'all_items'             => __('Todos los Recursos', 'edusiteco'),
            'add_new_item'          => __('Añadir Nuevo Recurso', 'edusiteco'),
            'add_new'               => __('Añadir Nuevo', 'edusiteco'),
            'new_item'              => __('Nuevo Recurso', 'edusiteco'),
            'edit_item'             => __('Editar Recurso', 'edusiteco'),
            'update_item'           => __('Actualizar Recurso', 'edusiteco'),
            'view_item'             => __('Ver Recurso', 'edusiteco'),
            'view_items'            => __('Ver Recursos', 'edusiteco'),
            'search_items'          => __('Buscar Recurso', 'edusiteco'),
            'not_found'             => __('No encontrado', 'edusiteco'),
            'not_found_in_trash'    => __('No encontrado en papelera', 'edusiteco'),
            'featured_image'        => __('Archivo Adjunto (Imagen/PDF)', 'edusiteco'),
            'set_featured_image'    => __('Establecer adjunto', 'edusiteco'),
            'remove_featured_image' => __('Remover adjunto', 'edusiteco'),
            'use_featured_image'    => __('Usar como adjunto', 'edusiteco'),
            'insert_into_item'      => __('Insertar en el recurso', 'edusiteco'),
            'uploaded_to_this_item' => __('Subido a este recurso', 'edusiteco'),
            'items_list'            => __('Lista de recursos', 'edusiteco'),
            'items_list_navigation' => __('Navegación de lista de recursos', 'edusiteco'),
            'filter_items_list'     => __('Filtrar lista de recursos', 'edusiteco'),
        );

        $args = array(
            'label'                 => __('Recurso Psico', 'edusiteco'),
            'description'           => __('Recursos y Tips para el Botiquín de Ayuda de Psicoorientación', 'edusiteco'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail'),
            'hierarchical'          => false,
            'public'                => false, // No necesitan página singular en el frontend, se listan en Psicoorientación
            'show_ui'               => true,
            'show_in_menu'          => false, // Oculto del menú principal de WP (se maneja por el plugin)
            'menu_position'         => 20,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
        );
        register_post_type('psico_recurso', $args);
    }
    add_action('init', 'edusiteco_cpt_psico_recursos', 0);
}
