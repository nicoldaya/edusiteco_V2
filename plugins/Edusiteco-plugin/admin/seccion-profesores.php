<h2 style="margin-bottom:10px;">Gestión de Profesores y Directivos</h2>
<p style="margin-bottom:20px;">Agrega o edita a los profesores desde este panel rápido. Todo se guarda automáticamente.</p>

<h2>Configuración del Encabezado (Hero)</h2>
<p>Personaliza el título y subtítulo que aparecen en la parte superior de la página de Profesores.</p>
<?php $o = get_option('info_colegio_settings'); ?>
<table class="form-table">
    <tr>
        <th scope="row">Título Principal</th>
        <td>
            <input type="text" name="info_colegio_settings[profesores][titulo]" value="<?php echo esc_attr($o['profesores']['titulo'] ?? 'Nuestro Equipo Docente'); ?>" class="regular-text" style="width:100%;">
        </td>
    </tr>
    <tr>
        <th scope="row">Subtítulo / Descripción</th>
        <td>
            <textarea name="info_colegio_settings[profesores][subtitulo]" rows="2" class="regular-text" style="width:100%;"><?php echo esc_textarea($o['profesores']['subtitulo'] ?? 'Conoce a los profesionales comprometidos con la formación de nuestros estudiantes'); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row">Imagen Principal (Hero)</th>
        <td>
            <input type="text" name="info_colegio_settings[profesores][hero_image]" id="profesores_hero_imagen" value="<?php echo esc_attr($o['profesores']['hero_image'] ?? ''); ?>" class="regular-text upload-foto-url" placeholder="URL de la imagen hero">
            <button type="button" class="button upload-foto-btn-hero">Subir Imagen</button>
        </td>
    </tr>
</table>

<hr style="margin: 30px 0;">

<div class="edusiteco-profesores-container">
    
    <!-- TARJETA DEL FORMULARIO -->
    <div class="edu-card">
        <h3 id="form-profesor-title">Añadir Nuevo Profesor</h3>
        <div class="edu-form-row">
            <div class="edu-input-group">
                <label>Nombre del Profesor</label>
                <input type="text" id="profesor_nombre" placeholder="Ej. Juan Pérez">
                <input type="hidden" id="profesor_id" value="0">
            </div>
            <div class="edu-input-group">
                <label>Cargo o Asignatura</label>
                <input type="text" id="profesor_asignatura" placeholder="Ej. Matemáticas">
            </div>
        </div>

        <div class="edu-form-row">
            <div class="edu-input-group" style="flex: 2;">
                <label>Descripción del docente</label>
                <textarea id="profesor_descripcion" rows="4" placeholder="Breve biografía o descripción del profesor..."></textarea>
            </div>
            <div class="edu-input-group" style="flex: 1;">
                <label>Foto del Profesor</label>
                <div class="edu-photo-preview" id="profesor_photo_preview">
                    <img src="" id="profesor_photo_img" style="display:none; width: 100%; height: 100%; object-fit: cover;">
                    <span id="profesor_photo_placeholder">Sin foto</span>
                </div>
                <input type="hidden" id="profesor_foto_id" value="0">
                <button type="button" class="button" id="btn_upload_photo" style="width: 100%; margin-top: 5px;">Seleccionar Foto</button>
            </div>
        </div>

        <div class="edu-form-actions">
            <button type="button" class="button button-primary" id="btn_save_profesor">Añadir a la lista</button>
            <button type="button" class="button" id="btn_cancel_profesor" style="display:none;">Cancelar Edición</button>
        </div>
        <p id="profesor_message" style="display:none; font-weight:bold;"></p>
    </div>

    <!-- TARJETA DE LA TABLA -->
    <div class="edu-card" style="margin-top: 20px;">
        <h3>Profesores Registrados</h3>
        <table class="wp-list-table widefat fixed striped edu-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Foto</th>
                    <th>Nombre</th>
                    <th>Asignatura / Cargo</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="profesores_table_body">
                <tr><td colspan="4">Cargando profesores...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<style>
.edusiteco-profesores-container {
    max-width: 900px;
}
.edu-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.edu-card h3 {
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.edu-form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}
.edu-input-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.edu-input-group label {
    font-weight: 600;
    margin-bottom: 5px;
}
.edu-input-group input[type="text"],
.edu-input-group textarea {
    width: 100%;
}
.edu-photo-preview {
    width: 100px;
    height: 100px;
    background: #f0f0f1;
    border: 1px dashed #b4b9be;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin: 0 auto;
}
.edu-photo-preview span {
    color: #a0a5aa;
    font-size: 12px;
}
.edu-form-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.edu-btn-edit {
    background: transparent;
    color: #2271b1;
    border: 1px solid #2271b1;
    padding: 4px 10px;
    border-radius: 3px;
    cursor: pointer;
    text-decoration: none;
    font-size: 12px;
}
.edu-btn-edit:hover { background: #2271b1; color: white; }
.edu-btn-delete {
    background: transparent;
    color: #d63638;
    border: 1px solid #d63638;
    padding: 4px 10px;
    border-radius: 3px;
    cursor: pointer;
    text-decoration: none;
    font-size: 12px;
}
.edu-btn-delete:hover { background: #d63638; color: white; }
</style>

<script>
jQuery(document).ready(function($) {
    if (typeof edusiteco_ajax === 'undefined') return;

    var file_frame;
    var all_profesores = []; // Para editar rápido

    // === MEDIA UPLOADER HERO ===
    var file_frame_hero;
    $('.upload-foto-btn-hero').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        if (file_frame_hero) {
            file_frame_hero.open();
            return;
        }
        file_frame_hero = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar imagen hero',
            button: { text: 'Usar esta imagen' },
            multiple: false
        });
        file_frame_hero.on('select', function() {
            var attachment = file_frame_hero.state().get('selection').first().toJSON();
            button.siblings('.upload-foto-url').val(attachment.url);
        });
        file_frame_hero.open();
    });

    // === MEDIA UPLOADER PROFESOR ===
    $('#btn_upload_photo').on('click', function(e) {
        e.preventDefault();
        if (file_frame) {
            file_frame.open();
            return;
        }
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar foto del profesor',
            button: { text: 'Usar esta imagen' },
            multiple: false
        });
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $('#profesor_foto_id').val(attachment.id);
            var img_url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            $('#profesor_photo_img').attr('src', img_url).show();
            $('#profesor_photo_placeholder').hide();
        });
        file_frame.open();
    });

    // === LOAD LIST ===
    function load_profesores() {
        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_load_profesores',
            security: edusiteco_ajax.nonce
        }, function(res) {
            if (res.success) {
                all_profesores = res.data.profesores;
                render_table();
            }
        });
    }

    function render_table() {
        var tbody = $('#profesores_table_body');
        tbody.empty();
        if (all_profesores.length === 0) {
            tbody.append('<tr><td colspan="4">No hay profesores registrados.</td></tr>');
            return;
        }
        $.each(all_profesores, function(i, p) {
            var tr = $('<tr>');
            tr.append('<td><img src="'+p.thumbnail_url+'" style="width:50px;height:50px;object-fit:cover;border-radius:4px;"></td>');
            tr.append('<td><strong>'+p.title+'</strong></td>');
            tr.append('<td>'+p.asignatura+'</td>');
            var btn_edit = $('<button type="button" class="edu-btn-edit">Editar</button>').data('index', i);
            var btn_del = $('<button type="button" class="edu-btn-delete" style="margin-left:5px;">Eliminar</button>').data('id', p.id);
            var td_actions = $('<td>').append(btn_edit).append(btn_del);
            tr.append(td_actions);
            tbody.append(tr);
        });
    }

    // === EDIT CLICK ===
    $(document).on('click', '.edu-btn-edit', function(e) {
        e.preventDefault();
        var index = $(this).data('index');
        var p = all_profesores[index];
        $('#profesor_id').val(p.id);
        $('#profesor_nombre').val(p.title);
        $('#profesor_asignatura').val(p.asignatura);
        $('#profesor_descripcion').val(p.description);
        
        if (p.thumbnail_id > 0) {
            $('#profesor_foto_id').val(p.thumbnail_id);
            $('#profesor_photo_img').attr('src', p.thumbnail_url).show();
            $('#profesor_photo_placeholder').hide();
        } else {
            $('#profesor_foto_id').val('');
            $('#profesor_photo_img').hide().attr('src', '');
            $('#profesor_photo_placeholder').show();
        }

        $('#form-profesor-title').text('Editar Profesor: ' + p.title);
        $('#btn_save_profesor').text('Guardar Cambios');
        $('#btn_cancel_profesor').show();
        $('html, body').animate({ scrollTop: $(".edusiteco-profesores-container").offset().top - 50 }, 500);
    });

    // === CANCEL EDIT ===
    $('#btn_cancel_profesor').on('click', function(e) {
        reset_form();
    });

    function reset_form() {
        $('#profesor_id').val('0');
        $('#profesor_nombre').val('');
        $('#profesor_asignatura').val('');
        $('#profesor_descripcion').val('');
        $('#profesor_foto_id').val('');
        $('#profesor_photo_img').hide().attr('src', '');
        $('#profesor_photo_placeholder').show();
        $('#form-profesor-title').text('Añadir Nuevo Profesor');
        $('#btn_save_profesor').text('Añadir a la lista');
        $('#btn_cancel_profesor').hide();
    }

    // === SAVE / UPDATE ===
    $('#btn_save_profesor').on('click', function(e) {
        e.preventDefault();
        var data = {
            action: 'edusiteco_save_profesor',
            security: edusiteco_ajax.nonce,
            profesor_id: $('#profesor_id').val(),
            nombre: $('#profesor_nombre').val(),
            asignatura: $('#profesor_asignatura').val(),
            descripcion: $('#profesor_descripcion').val(),
            foto_id: $('#profesor_foto_id').val()
        };

        if (data.nombre === '') {
            alert('El nombre es obligatorio.');
            return;
        }

        var btn = $(this);
        btn.prop('disabled', true).text('Guardando...');

        $.post(edusiteco_ajax.ajax_url, data, function(res) {
            if (res.success) {
                reset_form();
                load_profesores();
                var msg = $('#profesor_message');
                msg.css('color', 'green').text(res.data.message).fadeIn().delay(3000).fadeOut();
            } else {
                alert('Error: ' + res.data);
            }
        }).always(function() {
            btn.prop('disabled', false).text(data.profesor_id == 0 ? 'Añadir a la lista' : 'Guardar Cambios');
        });
    });

    // === DELETE ===
    $(document).on('click', '.edu-btn-delete', function(e) {
        e.preventDefault();
        var p_id = $(this).data('id');
        if (!confirm('¿Estás seguro de que quieres eliminar a este profesor?')) return;

        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_delete_profesor',
            security: edusiteco_ajax.nonce,
            profesor_id: p_id
        }, function(res) {
            if (res.success) {
                load_profesores();
            } else {
                alert('Error: ' + res.data);
            }
        });
    });

    // Init load
    load_profesores();
});
</script>
