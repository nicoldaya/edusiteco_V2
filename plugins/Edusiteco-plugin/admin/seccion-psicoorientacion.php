<?php
// Evitar acceso directo
if (!defined('ABSPATH')) exit;

$o = get_option('info_colegio_settings');
$o = is_array($o) ? $o : array();
?>

<div class="edusiteco-section">
    <h2>Configuración de Psicoorientación</h2>
    <p class="description">Configura el encabezado, las áreas de ayuda y los recursos públicos de Psicoorientación.</p>

    <div class="psico-grid-container">
        <div class="psico-box">
            <h3>Encabezado de la página</h3>
            <p class="description">El título vacío conserva el título de la página. El subtítulo mantiene el mensaje actual como valor inicial.</p>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="psico_hero_titulo">Título principal</label></th>
                    <td>
                        <input type="text" id="psico_hero_titulo" name="info_colegio_settings[psico_hero_titulo]" value="<?php echo esc_attr($o['psico_hero_titulo'] ?? ''); ?>" class="regular-text" style="width:100%;" placeholder="Psicoorientación Escolar">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="psico_saludo">Subtítulo</label></th>
                    <td>
                        <textarea id="psico_saludo" name="info_colegio_settings[psico_saludo]" rows="3" class="regular-text" style="width:100%;"><?php echo esc_textarea($o['psico_saludo'] ?? 'Aquí estamos para escucharte. Este es un espacio seguro para ayudarte con tus emociones, tus estudios y tu futuro. No estás solo en tu proceso.'); ?></textarea>
                    </td>
                </tr>
            </table>
        </div>

        <div class="psico-box">
            <h3>Áreas de ayuda</h3>
            <p class="description">Agrega, edita o elimina las tarjetas visibles para estudiantes y familias.</p>
            <?php
            $default_areas = array(
                array('titulo' => 'Tus Emociones', 'texto' => '¿Te sientes triste, con ansiedad o problemas en casa? Hablemos.', 'icono' => '', 'material_icon' => 'favorite'),
                array('titulo' => 'Tus Estudios', 'texto' => 'Te ayudamos a organizar tu tiempo y a decidir qué carrera estudiar cuando te gradúes.', 'icono' => '', 'material_icon' => 'school'),
                array('titulo' => 'Convivencia', 'texto' => 'Ayuda para resolver conflictos con compañeros o detener el acoso escolar (bullying).', 'icono' => '', 'material_icon' => 'handshake'),
                array('titulo' => 'Para Papás', 'texto' => 'Orientación y consejos para que las familias apoyen a sus hijos.', 'icono' => '', 'material_icon' => 'family_restroom'),
            );
            $areas = isset($o['psico_areas']) && is_array($o['psico_areas']) ? $o['psico_areas'] : array();
            if (!isset($o['psico_areas'])) {
                foreach ($default_areas as $i => &$area) {
                    $number = $i + 1;
                    $area['titulo'] = $o["psico_s{$number}_titulo"] ?? $area['titulo'];
                    $area['texto'] = $o["psico_s{$number}_texto"] ?? $area['texto'];
                    $area['icono'] = $o["psico_s{$number}_icono"] ?? '';
                    $areas[] = $area;
                }
                unset($area);
            }
            ?>
            <div id="psico-areas-list" class="psico-areas-list">
                <input type="hidden" name="info_colegio_settings[psico_areas][_empty][titulo]" value="">
                <?php foreach ($areas as $index => $area) :
                    $area_title = isset($area['titulo']) ? $area['titulo'] : '';
                    if ($area_title === '') {
                        continue;
                    }
                    $area_text = isset($area['texto']) ? $area['texto'] : '';
                    $area_image = isset($area['icono']) ? $area['icono'] : '';
                    $area_icon = isset($area['material_icon']) ? $area['material_icon'] : 'help';
                    ?>
                    <div class="psico-area-item">
                        <div class="psico-area-heading">
                            <h4>Área de ayuda</h4>
                            <button type="button" class="button-link-delete btn-remove-psico-area">Eliminar área</button>
                        </div>
                        <label><strong>Nombre del área</strong>
                            <input type="text" name="info_colegio_settings[psico_areas][<?php echo esc_attr($index); ?>][titulo]" value="<?php echo esc_attr($area_title); ?>" class="widefat" required>
                        </label>
                        <label><strong>Descripción</strong>
                            <textarea name="info_colegio_settings[psico_areas][<?php echo esc_attr($index); ?>][texto]" rows="3" class="widefat"><?php echo esc_textarea($area_text); ?></textarea>
                        </label>
                        <label><strong>Nombre del ícono Material (opcional)</strong>
                            <input type="text" name="info_colegio_settings[psico_areas][<?php echo esc_attr($index); ?>][material_icon]" value="<?php echo esc_attr($area_icon); ?>" class="widefat" placeholder="ej. favorite, school, psychology">
                        </label>
                        <div class="psico-area-image">
                            <img id="psico_area_<?php echo esc_attr($index); ?>_preview" src="<?php echo esc_url($area_image); ?>" alt="" <?php echo empty($area_image) ? 'hidden' : ''; ?>>
                            <input type="hidden" id="psico_area_<?php echo esc_attr($index); ?>_url" name="info_colegio_settings[psico_areas][<?php echo esc_attr($index); ?>][icono]" value="<?php echo esc_url($area_image); ?>">
                            <button type="button" class="button btn-upload-icon" data-target="psico_area_<?php echo esc_attr($index); ?>">Seleccionar imagen</button>
                            <button type="button" class="button btn-remove-area-image" <?php echo empty($area_image) ? 'hidden' : ''; ?>>Quitar imagen</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button" id="btn-add-psico-area">Añadir área de ayuda</button>
            <template id="psico-area-template">
                <div class="psico-area-item">
                    <div class="psico-area-heading">
                        <h4>Área de ayuda</h4>
                        <button type="button" class="button-link-delete btn-remove-psico-area">Eliminar área</button>
                    </div>
                    <label><strong>Nombre del área</strong>
                        <input type="text" name="info_colegio_settings[psico_areas][__INDEX__][titulo]" class="widefat" required>
                    </label>
                    <label><strong>Descripción</strong>
                        <textarea name="info_colegio_settings[psico_areas][__INDEX__][texto]" rows="3" class="widefat"></textarea>
                    </label>
                    <label><strong>Nombre del ícono Material (opcional)</strong>
                        <input type="text" name="info_colegio_settings[psico_areas][__INDEX__][material_icon]" value="psychology" class="widefat" placeholder="ej. favorite, school, psychology">
                    </label>
                    <div class="psico-area-image">
                        <img id="psico_area___INDEX___preview" src="" alt="" hidden>
                        <input type="hidden" id="psico_area___INDEX___url" name="info_colegio_settings[psico_areas][__INDEX__][icono]" value="">
                        <button type="button" class="button btn-upload-icon" data-target="psico_area___INDEX__">Seleccionar imagen</button>
                        <button type="button" class="button btn-remove-area-image" hidden>Quitar imagen</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- BLOQUE 3: Citas -->
        <div class="psico-box">
            <h3>3. ¿Cómo pedir una cita?</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Foto del Orientador(a)</th>
                    <td>
                        <input type="hidden" id="psico_foto_url" name="info_colegio_settings[psico_foto]" value="<?php echo esc_url($o['psico_foto'] ?? ''); ?>">
                        
                        <div id="psico_foto_preview" style="margin-bottom:10px; max-width:150px;">
                            <?php if (!empty($o['psico_foto'])): ?>
                                <img src="<?php echo esc_url($o['psico_foto']); ?>" style="max-width:100%; height:auto; border-radius:8px; border:1px solid #ddd;">
                            <?php endif; ?>
                        </div>

                        <button type="button" class="button" id="btn_upload_psico_foto">Seleccionar / Subir Foto</button>
                        <button type="button" class="button" id="btn_remove_psico_foto" style="<?php echo empty($o['psico_foto']) ? 'display:none;' : ''; ?> color:red;">Quitar Foto</button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Horario de Atención</th>
                    <td>
                        <input type="text" name="info_colegio_settings[psico_horario]" value="<?php echo esc_attr($o['psico_horario'] ?? 'Atendemos de Lunes a Viernes de 8:00 am a 12:00 pm en la oficina del primer piso.'); ?>" class="regular-text" style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Link del botón "Pedir cita"</th>
                    <td>
                        <input type="url" name="info_colegio_settings[psico_cita_url]" value="<?php echo esc_url($o['psico_cita_url'] ?? ''); ?>" class="regular-text" style="width:100%;" placeholder="https://forms.google.com/...">
                        <p class="description">Pega aquí el enlace del formulario, correo o WhatsApp.</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- BLOQUE 4: Teléfonos de Emergencia -->
        <div class="psico-box">
            <h3>4. Emergencias (Botiquín)</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Teléfonos Importantes</th>
                    <td>
                        <textarea name="info_colegio_settings[psico_emergencias]" rows="3" class="regular-text" style="width:100%;"><?php echo esc_textarea($o['psico_emergencias'] ?? 'Si tienes una emergencia fuera del colegio o te sientes en peligro, llama a la línea 141 del ICBF (apoyo a menores) o a la línea local de salud mental.'); ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <hr style="margin: 40px 0;">

    <!-- ============================================== -->
    <!-- GESTIÓN DEL BOTIQUÍN (CRUD AJAX) -->
    <!-- ============================================== -->
    <div class="psico-crud-section">
        <h2>Gestión de Recursos (Botiquín de Ayuda)</h2>
        <p class="description">Añade materiales en PDF o imagen con una descripción para los estudiantes y sus familias.</p>
        
        <button type="button" class="button button-primary" id="btn-nuevo-recurso" style="margin-bottom: 20px;">
            <span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Añadir Nuevo Recurso
        </button>

        <table class="wp-list-table widefat fixed striped" id="tabla-recursos">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:80px;">Archivo</th>
                    <th>Título del Recurso</th>
                    <th>Descripción</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">Cargando recursos...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL PARA RECURSO -->
<!-- ============================================== -->
<div id="modal-recurso" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 500px;">
        <h2 id="modal-recurso-titulo">Añadir Nuevo Recurso</h2>
        
        <div id="form-recurso">
            <input type="hidden" id="recurso_id" name="recurso_id" value="">
            
            <table class="form-table">
                <tr>
                    <th><label for="recurso_titulo">Título del Recurso</label></th>
                    <td>
                        <input type="text" id="recurso_titulo" name="recurso_titulo" class="regular-text" style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th><label for="recurso_descripcion">Descripción</label></th>
                    <td>
                        <textarea id="recurso_descripcion" name="recurso_descripcion" rows="4" class="widefat"></textarea>
                    </td>
                </tr>
                <tr>
                    <th><label>Archivo Adjunto (PDF o Imagen)</label></th>
                    <td>
                        <input type="hidden" id="recurso_attachment_id" name="recurso_attachment_id" value="">
                        
                        <div id="recurso_attachment_preview" style="margin-bottom:10px;">
                            <span style="color:#666; font-style:italic;">Ningún archivo seleccionado</span>
                        </div>

                        <button type="button" class="button" id="btn_upload_recurso_file">Seleccionar Archivo</button>
                        <button type="button" class="button" id="btn_remove_recurso_file" style="display:none; color:red;">Quitar</button>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20px; text-align: right;">
                <button type="button" class="button modal-close" id="btn-cancelar-recurso">Cancelar</button>
                <button type="button" class="button button-primary" id="btn-guardar-recurso">Guardar Recurso</button>
            </div>
            <div id="recurso-feedback" style="margin-top: 10px; font-weight: bold; text-align: right;"></div>
        </div>
    </div>
</div>

<style>
    .psico-grid-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .psico-box {
        background: #fff;
        border: 1px solid #ccd0d4;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .psico-box h3 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .psico-areas-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .psico-area-item {
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 15px;
    }
    .psico-area-item label {
        display: block;
    }
    .psico-area-item .widefat {
        margin-top: 5px;
    }
    .psico-area-heading,
    .psico-area-image {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .psico-area-heading {
        justify-content: space-between;
    }
    .psico-area-heading h4 {
        margin: 0;
    }
    .psico-area-image img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        background: #eaeaea;
        border-radius: 4px;
    }
    
    /* Reutilizamos el modal de profesores/comunicados */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }
</style>

<script>
jQuery(document).ready(function($) {
    
    // ==========================================
    // 1. MEDIA UPLOADER (FOTO ORIENTADOR)
    // ==========================================
    var frameFoto;
    $('#btn_upload_psico_foto').on('click', function(e) {
        e.preventDefault();
        if (frameFoto) { frameFoto.open(); return; }

        frameFoto = wp.media({
            title: 'Seleccionar Foto del Orientador',
            button: { text: 'Usar esta foto' },
            multiple: false
        });

        frameFoto.on('select', function() {
            var attachment = frameFoto.state().get('selection').first().toJSON();
            $('#psico_foto_url').val(attachment.url);
            $('#psico_foto_preview').html('<img src="'+attachment.url+'" style="max-width:100%; height:auto; border-radius:8px; border:1px solid #ddd;">');
            $('#btn_remove_psico_foto').show();
        });

        frameFoto.open();
    });

    $('#btn_remove_psico_foto').on('click', function(e) {
        e.preventDefault();
        $('#psico_foto_url').val('');
        $('#psico_foto_preview').html('');
        $(this).hide();
    });

    // ==========================================
    // 2. MEDIA UPLOADER (BOTIQUÍN)
    // ==========================================
    var frameRecurso;
    $('#btn_upload_recurso_file').on('click', function(e) {
        e.preventDefault();
        if (frameRecurso) { frameRecurso.open(); return; }

        frameRecurso = wp.media({
            title: 'Seleccionar Archivo o Imagen',
            button: { text: 'Usar este archivo' },
            multiple: false
        });

        frameRecurso.on('select', function() {
            var attachment = frameRecurso.state().get('selection').first().toJSON();
            $('#recurso_attachment_id').val(attachment.id);
            
            var $preview = $('#recurso_attachment_preview').empty();
            if (attachment.type === 'image') {
                $('<img>').attr('src', attachment.url).attr('alt', '').css({
                    maxWidth: '100px',
                    maxHeight: '100px',
                    display: 'block',
                    marginBottom: '5px'
                }).appendTo($preview);
            }
            
            $('<strong>').text(attachment.filename).appendTo($preview);
            $('#btn_remove_recurso_file').show();
        });

        frameRecurso.open();
    });

    $('#btn_remove_recurso_file').on('click', function(e) {
        e.preventDefault();
        $('#recurso_attachment_id').val('');
        $('#recurso_attachment_preview').html('<span style="color:#666; font-style:italic;">Ningún archivo seleccionado</span>');
        $(this).hide();
    });

    // ==========================================
    // 2.5 MEDIA UPLOADER (ÍCONOS DE PILARES)
    // ==========================================
    $(document).on('click', '.btn-upload-icon', function(e) {
        e.preventDefault();
        var targetID = $(this).data('target'); 
        
        var frameIcono = wp.media({
            title: 'Seleccionar Ícono o Imagen',
            button: { text: 'Usar este ícono' },
            multiple: false,
            library: { type: 'image' }
        });

        frameIcono.on('select', function() {
            var attachment = frameIcono.state().get('selection').first().toJSON();
            $('#' + targetID + '_url').val(attachment.url);
            $('#' + targetID + '_preview').attr('src', attachment.url).show();
            $('#' + targetID + '_preview').prop('hidden', false);
            $('#' + targetID + '_preview').closest('.psico-area-image').find('.btn-remove-area-image').prop('hidden', false);
        });

        frameIcono.open();
    });

    var nextPsicoAreaIndex = <?php echo (int) (empty($areas) ? 0 : max(array_map('intval', array_keys($areas))) + 1); ?>;
    $('#btn-add-psico-area').on('click', function() {
        var html = $('#psico-area-template').html().replace(/__INDEX__/g, nextPsicoAreaIndex++);
        $('#psico-areas-list').append(html);
    });

    $(document).on('click', '.btn-remove-psico-area', function() {
        if (window.confirm('¿Estás segura de que deseas eliminar esta área de ayuda?')) {
            $(this).closest('.psico-area-item').remove();
        }
    });

    $(document).on('click', '.btn-remove-area-image', function() {
        var container = $(this).closest('.psico-area-image');
        container.find('input[type="hidden"]').val('');
        container.find('img').attr('src', '').prop('hidden', true);
        $(this).prop('hidden', true);
    });

    // ==========================================
    // 3. CRUD BOTIQUÍN (AJAX)
    // ==========================================
    function cargarRecursos() {
        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_get_recursos',
            nonce: edusiteco_ajax.nonce
        }, function(response) {
            if(response.success) {
                var $tbody = $('#tabla-recursos tbody').empty();
                if (response.data.length === 0) {
                    $('<tr>').append($('<td>').attr('colspan', 5).text('No hay recursos creados.')).appendTo($tbody);
                } else {
                    $.each(response.data, function(i, recurso) {
                        var $row = $('<tr>');
                        var $fileCell = $('<td>');
                        if (recurso.thumb_url) {
                            $('<img>').attr('src', recurso.thumb_url).attr('alt', '').css({
                                width: '40px',
                                height: '40px',
                                objectFit: 'cover',
                                borderRadius: '4px'
                            }).appendTo($fileCell);
                        } else {
                            $fileCell.text(recurso.has_attachment ? 'Archivo adjunto' : 'Sin archivo');
                        }
                        $row.append($('<td>').text(recurso.id));
                        $row.append($fileCell);
                        $row.append($('<td>').append($('<strong>').text(recurso.title)));
                        $row.append($('<td>').text(recurso.description || ''));
                        var $actions = $('<td>');
                        $('<button>').attr({type: 'button', 'data-id': recurso.id}).addClass('button btn-editar-recurso').text('Editar').appendTo($actions);
                        $actions.append(' ');
                        $('<button>').attr({type: 'button', 'data-id': recurso.id}).addClass('button btn-borrar-recurso').css({color: 'red', borderColor: 'red'}).text('Borrar').appendTo($actions);
                        $row.append($actions).appendTo($tbody);
                    });
                }
            } else {
                $('#tabla-recursos tbody').empty().append($('<tr>').append($('<td>').attr('colspan', 5).text(response.data || 'No fue posible cargar los recursos.')));
            }
        }).fail(function() {
            $('#tabla-recursos tbody').empty().append($('<tr>').append($('<td>').attr('colspan', 5).text('Error de conexión al cargar los recursos.')));
        });
    }

    cargarRecursos();

    // ABRIR MODAL NUEVO
    $('#btn-nuevo-recurso').on('click', function() {
        $('#recurso_titulo').val('');
        $('#recurso_descripcion').val('');
        $('#recurso_id').val('');
        $('#recurso_attachment_id').val('');
        $('#recurso_attachment_preview').html('<span style="color:#666; font-style:italic;">Ningún archivo seleccionado</span>');
        $('#btn_remove_recurso_file').hide();
        $('#modal-recurso-titulo').text('Añadir Nuevo Recurso');
        $('#recurso-feedback').text('');
        $('#modal-recurso').fadeIn(200);
    });

    // CERRAR MODAL
    $('#btn-cancelar-recurso').on('click', function() {
        $('#modal-recurso').fadeOut(200);
    });

    // GUARDAR RECURSO
    $('#btn-guardar-recurso').on('click', function(e) {
        e.preventDefault();
        $('#btn-guardar-recurso').prop('disabled', true).text('Guardando...');
        
        var data = {
            action: 'edusiteco_save_recurso',
            nonce: edusiteco_ajax.nonce,
            id: $('#recurso_id').val(),
            title: $('#recurso_titulo').val(),
            description: $('#recurso_descripcion').val(),
            attachment_id: $('#recurso_attachment_id').val()
        };

        $.post(edusiteco_ajax.ajax_url, data, function(response) {
            $('#btn-guardar-recurso').prop('disabled', false).text('Guardar Recurso');
            if(response.success) {
                $('#modal-recurso').fadeOut(200);
                cargarRecursos();
            } else {
                $('#recurso-feedback').css('color', 'red').text(response.data || 'Error al guardar.');
            }
        }).fail(function() {
            $('#btn-guardar-recurso').prop('disabled', false).text('Guardar Recurso');
            $('#recurso-feedback').css('color', 'red').text('Error de conexión al guardar el recurso.');
        });
    });

    // EDITAR RECURSO (Cargar datos)
    $(document).on('click', '.btn-editar-recurso', function() {
        var id = $(this).data('id');
        
        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_get_recurso_single',
            nonce: edusiteco_ajax.nonce,
            id: id
        }, function(response) {
            if(response.success) {
                var r = response.data;
                $('#recurso_id').val(r.id);
                $('#recurso_titulo').val(r.title);
                $('#recurso_descripcion').val(r.description || '');
                
                if (r.attachment_id) {
                    $('#recurso_attachment_id').val(r.attachment_id);
                    $('#btn_remove_recurso_file').show();
                    
                    var $preview = $('<strong>').text(r.filename);
                    $('#recurso_attachment_preview').empty();
                    if (r.thumb_url) {
                        $('<img>').attr('src', r.thumb_url).attr('alt', '').css({
                            maxWidth: '100px',
                            maxHeight: '100px',
                            display: 'block',
                            marginBottom: '5px'
                        }).appendTo('#recurso_attachment_preview');
                    }
                    $('#recurso_attachment_preview').append($preview);
                } else {
                    $('#recurso_attachment_id').val('');
                    $('#recurso_attachment_preview').html('<span style="color:#666; font-style:italic;">Ningún archivo seleccionado</span>');
                    $('#btn_remove_recurso_file').hide();
                }

                $('#modal-recurso-titulo').text('Editar Recurso');
                $('#recurso-feedback').text('');
                $('#modal-recurso').fadeIn(200);
            } else {
                alert('Error al cargar recurso');
            }
        }).fail(function() {
            alert('Error de conexión al cargar el recurso.');
        });
    });

    // BORRAR RECURSO
    $(document).on('click', '.btn-borrar-recurso', function() {
        if(confirm('¿Seguro que deseas borrar este recurso?')) {
            var id = $(this).data('id');
            $.post(edusiteco_ajax.ajax_url, {
                action: 'edusiteco_delete_recurso',
                nonce: edusiteco_ajax.nonce,
                id: id
            }, function(response) {
                if(response.success) {
                    cargarRecursos();
                } else {
                    alert('Error al borrar: ' + (response.data || ''));
                }
            }).fail(function() {
                alert('Error de conexión al borrar el recurso.');
            });
        }
    });

});
</script>
