<?php $edusiteco_is_rectoria_user = !current_user_can('manage_options') && current_user_can('manage_edusiteco_comunicados'); ?>
<h2><?php echo $edusiteco_is_rectoria_user ? 'Gestión de Comunicados y Circulares' : 'Gestión de Comunicados y Eventos'; ?></h2>
<p><?php echo $edusiteco_is_rectoria_user ? 'Crea una publicación nueva o elige una existente para actualizarla. Los cambios se publican en el sitio institucional.' : 'Desde este panel puedes administrar rápidamente los comunicados, circulares y eventos del colegio sin salir de esta página.'; ?></p>

<div style="margin-top: 20px; margin-bottom: 20px;">
    <button type="button" class="button button-primary button-large" id="btn-nuevo-comunicado" style="margin-right: 10px;">
        <?php echo $edusiteco_is_rectoria_user ? 'Crear comunicado o circular' : 'Añadir Nuevo Comunicado'; ?>
    </button>
    <?php if (!$edusiteco_is_rectoria_user) : ?>
        <button type="button" class="button button-secondary button-large" id="btn-nuevo-evento" style="margin-right: 10px;">
            Añadir Evento
        </button>
        <a href="<?php echo admin_url('edit-tags.php?taxonomy=category&post_type=comunicado'); ?>" class="button button-secondary" target="_blank">
            Gestionar Categorías
        </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORMULARIO PARA CREAR/EDITAR COMUNICADO (Oculto) -->
<!-- ============================================== -->
<div id="formulario-comunicado" style="display:none; background:#fff; padding:20px; border:1px solid #ccd0d4; border-radius:4px; margin-bottom:20px;">
    <h3 id="form-comunicado-titulo" style="margin-top:0;">Crear Nuevo Comunicado</h3>
    <?php if ($edusiteco_is_rectoria_user) : ?>
        <p>Completa el título y la categoría, redacta el contenido y agrega una imagen o un PDF si hace falta. Al guardar, la publicación quedará visible en el sitio.</p>
    <?php endif; ?>
    
    <input type="hidden" id="comunicado_id" value="">
    
    <table class="form-table">
        <tr>
            <th scope="row"><label for="comunicado_titulo" id="comunicado-titulo-label">Título del Comunicado</label></th>
            <td>
                <input type="text" id="comunicado_titulo" class="regular-text" placeholder="Ej: Invitación Día de la Familia">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="comunicado_categoria">Categoría</label></th>
            <td>
                <select id="comunicado_categoria">
                    <option value="">Selecciona una categoría...</option>
                    <?php
                    $categorias = get_categories(array(
                        'hide_empty' => false,
                    ));
                    foreach ($categorias as $cat) {
                        if ($edusiteco_is_rectoria_user && !in_array($cat->slug, array('comunicado', 'circulares'), true)) {
                            continue;
                        }
                        echo '<option value="' . esc_attr($cat->term_id) . '" data-slug="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</option>';
                    }
                    ?>
                </select>
                <?php if (!$edusiteco_is_rectoria_user) : ?>
                    <p class="description">Si necesitas más categorías, usa el botón "Gestionar Categorías".</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="no-event-field">
            <th scope="row"><label>Imagen Representativa</label></th>
            <td>
                <input type="hidden" id="comunicado_imagen_id" value="">
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div id="comunicado_imagen_preview" style="width: 150px; height: 100px; background: #f0f0f1; border: 1px dashed #b4b9be; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 4px;">
                        <span style="color:#a0a5aa; font-size:12px;">Sin imagen</span>
                    </div>
                    <div>
                        <button type="button" id="upload_comunicado_imagen_btn" class="button">Seleccionar Imagen</button>
                        <button type="button" id="remove_comunicado_imagen_btn" class="button" style="display:none; color: #d63638; border-color: #d63638;">Eliminar</button>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="no-event-field">
            <th scope="row"><label for="comunicado_contenido">Descripción / Contenido</label></th>
            <td>
                <textarea id="comunicado_contenido" rows="6" style="width:100%; max-width:600px;" placeholder="Escribe aquí los detalles del comunicado..."></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="comunicado_fecha_evento">Fecha de actividad o evento</label></th>
            <td>
                <input type="date" id="comunicado_fecha_evento" class="regular-text">
            </td>
        </tr>
        <tr class="evento-only-field" style="display:none;">
            <th scope="row"><label for="comunicado_tipo_evento">Tipo de Evento</label></th>
            <td>
                <select id="comunicado_tipo_evento">
                    <option value="ACADEMICO">Académico</option>
                    <option value="REUNION DE PADRES">Reunión de padres</option>
                    <option value="EVENTO CULTURAL">Evento cultural</option>
                    <option value="JORNADA PEDAGOGICA">Jornada pedagógica</option>
                    <option value="EVENTO DEPORTIVO">Evento deportivo</option>
                    <option value="EVENTO INSTITUCIONAL">Evento institucional</option>
                </select>
            </td>
        </tr>
        <tr class="evento-only-field" style="display:none;">
            <th scope="row"><label for="comunicado_lugar_evento">Lugar</label></th>
            <td>
                <input type="text" id="comunicado_lugar_evento" class="regular-text" value="I.E. Rafael Pombo">
            </td>
        </tr>
        <tr class="no-event-field">
            <th scope="row"><label for="comunicado_pdf_url">Archivo PDF (Opcional)</label></th>
            <td>
                <div style="display:flex; align-items:center; gap:8px;">
                    <input type="url" id="comunicado_pdf_url" class="regular-text" placeholder="https://ejemplo.com/archivo.pdf">
                    <button type="button" id="upload_comunicado_pdf_btn" class="button">Subir/Seleccionar PDF</button>
                </div>
            </td>
        </tr>
        <tr class="no-event-field">
            <th scope="row"><label for="comunicado_es_destacado">¿Destacar en Inicio?</label></th>
            <td>
                <label>
                    <input type="checkbox" id="comunicado_es_destacado" value="1">
                    Marcar como destacable en la página de inicio
                </label>
            </td>
        </tr>
    </table>
    
    <div style="margin-top:20px;">
        <button type="button" class="button button-primary" id="btn-guardar-comunicado">Guardar Comunicado</button>
        <button type="button" class="button" id="btn-cancelar-comunicado" style="margin-left:10px;">Cancelar</button>
        <span class="spinner" id="comunicado-spinner" style="float:none; margin-top:4px;"></span>
    </div>
</div>

<!-- ============================================== -->
<!-- TABLA DE COMUNICADOS -->
<!-- ============================================== -->
<div class="edusiteco-comunicados-list" style="background: #fff; border: 1px solid #ccd0d4; padding: 15px; border-radius: 4px;">
    <h3 style="margin-top: 0;"><?php echo $edusiteco_is_rectoria_user ? 'Listado de comunicados y circulares' : 'Lista de Comunicados'; ?></h3>
    <?php if ($edusiteco_is_rectoria_user) : ?>
        <p>Las publicaciones más recientes aparecen primero. Usa <strong>Editar</strong> para actualizar una publicación o <strong>Eliminar</strong> para enviarla a la papelera.</p>
    <?php endif; ?>
    <div class="edusiteco-comunicados-filters" style="display:flex; flex-wrap:wrap; gap:10px; margin:14px 0;">
        <input type="search" id="filtro-comunicados-texto" placeholder="Buscar por título o categoría..." aria-label="Buscar comunicados" style="min-width:260px; padding:8px 10px;">
        <select id="filtro-comunicados-estado" aria-label="Filtrar por estado" style="padding:8px 10px;">
            <option value="">Todos los estados</option>
            <option value="publish">Publicados</option>
            <option value="draft">Borradores</option>
        </select>
        <span id="contador-comunicados" style="align-self:center; color:#64748b;"></span>
    </div>
    
    <table class="wp-list-table widefat fixed striped edusiteco-comunicados-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Evento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="lista-comunicados-tbody">
            <tr>
                <td colspan="6">Cargando comunicados...</td>
            </tr>
        </tbody>
    </table>
    <nav class="edusiteco-comunicados-pagination" aria-label="Paginación de comunicados">
        <button type="button" class="button" id="comunicados-pagina-anterior">Anterior</button>
        <span id="comunicados-pagina-actual" aria-live="polite"></span>
        <button type="button" class="button" id="comunicados-pagina-siguiente">Siguiente</button>
    </nav>
</div>

<script>
jQuery(document).ready(function($) {
    var $formContainer = $('#formulario-comunicado');
    var $tbody = $('#lista-comunicados-tbody');
    var canDeleteComunicados = <?php echo (current_user_can('manage_options') || current_user_can('delete_edusiteco_comunicados')) ? 'true' : 'false'; ?>;
    var isRectoriaUser = <?php echo $edusiteco_is_rectoria_user ? 'true' : 'false'; ?>;
    var comunicadosItems = [];
    var paginaComunicados = 1;
    var publicacionesPorPagina = 5;
    var mediaUploaderPDF;
    var mediaUploaderImagen;

    // Abrir Media Uploader para la Imagen Destacada
    $('#upload_comunicado_imagen_btn').click(function(e) {
        e.preventDefault();
        if (mediaUploaderImagen) {
            mediaUploaderImagen.open();
            return;
        }
        mediaUploaderImagen = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar Imagen Representativa',
            button: { text: 'Usar esta imagen' },
            multiple: false,
            library: { type: 'image' }
        });
        mediaUploaderImagen.on('select', function() {
            var attachment = mediaUploaderImagen.state().get('selection').first().toJSON();
            $('#comunicado_imagen_id').val(attachment.id);
            $('#comunicado_imagen_preview').html('<img src="' + attachment.url + '" style="max-width:100%; max-height:100%; object-fit:cover;">');
            $('#remove_comunicado_imagen_btn').show();
        });
        mediaUploaderImagen.open();
    });

    $('#remove_comunicado_imagen_btn').click(function(e) {
        e.preventDefault();
        $('#comunicado_imagen_id').val('');
        $('#comunicado_imagen_preview').html('<span style="color:#a0a5aa; font-size:12px;">Sin imagen</span>');
        $(this).hide();
    });

    // Abrir Media Uploader para el PDF
    $('#upload_comunicado_pdf_btn').click(function(e) {
        e.preventDefault();
        if (mediaUploaderPDF) {
            mediaUploaderPDF.open();
            return;
        }
        mediaUploaderPDF = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar un PDF',
            button: { text: 'Usar este archivo' },
            multiple: false,
            library: { type: 'application/pdf' }
        });
        mediaUploaderPDF.on('select', function() {
            var attachment = mediaUploaderPDF.state().get('selection').first().toJSON();
            $('#comunicado_pdf_url').val(attachment.url);
        });
        mediaUploaderPDF.open();
    });

    function renderizarComunicados() {
        var search = $('#filtro-comunicados-texto').val().toLowerCase().trim();
        var status = $('#filtro-comunicados-estado').val();
        var filteredItems = comunicadosItems.filter(function(item) {
            var matchesSearch = !search || (item.title + ' ' + item.category_name).toLowerCase().indexOf(search) !== -1;
            return matchesSearch && (!status || item.estado === status);
        });
        var totalPaginas = Math.max(1, Math.ceil(filteredItems.length / publicacionesPorPagina));
        paginaComunicados = Math.min(paginaComunicados, totalPaginas);
        var inicio = (paginaComunicados - 1) * publicacionesPorPagina;
        var publicacionesPagina = filteredItems.slice(inicio, inicio + publicacionesPorPagina);

        $tbody.empty();
        var primerResultado = filteredItems.length ? inicio + 1 : 0;
        var ultimoResultado = Math.min(inicio + publicacionesPorPagina, filteredItems.length);
        $('#contador-comunicados').text('Mostrando ' + primerResultado + '–' + ultimoResultado + ' de ' + filteredItems.length + ' publicaciones');
        $('#comunicados-pagina-actual').text('Página ' + paginaComunicados + ' de ' + totalPaginas);
        $('#comunicados-pagina-anterior').prop('disabled', paginaComunicados === 1);
        $('#comunicados-pagina-siguiente').prop('disabled', paginaComunicados === totalPaginas);

        if (!filteredItems.length) {
            $tbody.append($('<tr><td colspan="6">No se encontraron publicaciones con esos filtros.</td></tr>'));
            return;
        }

        publicacionesPagina.forEach(function(item) {
            var tr = $('<tr></tr>');
            var titleCell = $('<td></td>');
            titleCell.append($('<strong></strong>').text(item.title));
            if (item.es_destacado == '1') {
                titleCell.append($('<span style="color:orange;"></span>').text(' (⭐ Destacado)'));
            }
            tr.append(titleCell);
            tr.append($('<td></td>').text(item.category_name));
            var estado = item.es_historico ? 'Publicado · archivo histórico' : (item.estado === 'publish' ? 'Publicado' : 'Borrador');
            tr.append($('<td></td>').text(estado));
            tr.append($('<td></td>').text(item.fecha_publicacion));
            tr.append($('<td></td>').text(item.fecha_evento || '—'));

            var tdAcciones = $('<td></td>');
            var acciones = $('<div class="edusiteco-comunicado-actions"></div>');
            var btnEdit = $('<button type="button" class="button button-small" style="margin-right:5px;">Editar</button>');
            btnEdit.on('click', function() {
                editarComunicado(item);
            });
            acciones.append(btnEdit);

            if (canDeleteComunicados) {
                var btnDelete = $('<button type="button" class="button button-small" style="color:#b32d2e; border-color:#b32d2e;"></button>');
                btnDelete.text(isRectoriaUser ? 'Enviar a papelera' : 'Eliminar');
                btnDelete.on('click', function() {
                    var confirmation = isRectoriaUser
                        ? '¿Enviar este comunicado o circular a la papelera? Podrás restaurarlo desde allí.'
                        : '¿Estás seguro de que deseas eliminar este comunicado?';
                    if (confirm(confirmation)) {
                        eliminarComunicado(item.id);
                    }
                });
                acciones.append(btnDelete);
            }
            tdAcciones.append(acciones);
            tr.append(tdAcciones);
            $tbody.append(tr);
        });
    }

    $('#filtro-comunicados-texto, #filtro-comunicados-estado').on('input change', function() {
        paginaComunicados = 1;
        renderizarComunicados();
    });
    $('#comunicados-pagina-anterior').on('click', function() {
        if (paginaComunicados > 1) {
            paginaComunicados--;
            renderizarComunicados();
        }
    });
    $('#comunicados-pagina-siguiente').on('click', function() {
        paginaComunicados++;
        renderizarComunicados();
    });

    // Cargar la lista
    function cargarComunicados() {
        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_get_comunicados',
            nonce: edusiteco_ajax.nonce
        }, function(response) {
            if (response.success) {
                comunicadosItems = response.data;
                renderizarComunicados();
            } else {
                alert('Error al cargar los comunicados: ' + response.data);
            }
        }).fail(function() {
            alert('No se pudo conectar para cargar los comunicados. Recarga la página e inténtalo nuevamente.');
        });
    }

    // Inicializar carga
    cargarComunicados();

    // Mostrar formulario
    $('#btn-nuevo-comunicado').click(function(e) {
        e.preventDefault();
        resetFormulario();
        $('#form-comunicado-titulo').text('Crear Nuevo Comunicado');
        $formContainer.slideDown();
    });

    // Ocultar formulario
    $('#btn-cancelar-comunicado').click(function(e) {
        e.preventDefault();
        $formContainer.slideUp();
    });

    // Resetear form
    function resetFormulario() {
        $('#comunicado_id').val('');
        $('#comunicado_titulo').val('');
        $('#comunicado_categoria').val('');
        $('#comunicado_contenido').val('');
        $('#comunicado_fecha_evento').val('');
        $('#comunicado_tipo_evento').val('ACADEMICO');
        $('#comunicado_lugar_evento').val('I.E. Rafael Pombo');
        $('#comunicado_pdf_url').val('');
        $('#comunicado_es_destacado').prop('checked', false);
        
        $('#comunicado_imagen_id').val('');
        $('#comunicado_imagen_preview').html('<span style="color:#a0a5aa; font-size:12px;">Sin imagen</span>');
        $('#remove_comunicado_imagen_btn').hide();
        actualizarCamposEvento();
    }

    function actualizarCamposEvento() {
        var esEvento = $('#comunicado_categoria option:selected').data('slug') === 'eventos' || $('#comunicado_fecha_evento').val() !== '';
        $('.evento-only-field').toggle(esEvento);
        $('.no-event-field').toggle(!esEvento);
        $('#comunicado_fecha_evento').prop('required', $('#comunicado_categoria option:selected').data('slug') === 'eventos');
        $('#btn-guardar-comunicado').text(esEvento ? 'Guardar actividad' : 'Guardar Comunicado');
        $('#comunicado-titulo-label').text(esEvento ? 'Título de la actividad' : 'Título del Comunicado');
    }

    $('#comunicado_categoria').on('change', actualizarCamposEvento);
    $('#comunicado_fecha_evento').on('change input', actualizarCamposEvento);

    $('#btn-nuevo-evento').on('click', function(e) {
        e.preventDefault();
        resetFormulario();
        var $eventCategory = $('#comunicado_categoria option[data-slug="eventos"]');
        if (!$eventCategory.length) {
            alert('No existe la categoría Eventos. Créala desde “Gestionar Categorías” y vuelve a intentarlo.');
            return;
        }
        $('#form-comunicado-titulo').text('Crear Nuevo Evento');
        $('#comunicado_categoria').val($eventCategory.val());
        actualizarCamposEvento();
        $formContainer.slideDown();
        $('html, body').animate({ scrollTop: $formContainer.offset().top - 50 }, 300);
    });

    // Editar
    function editarComunicado(item) {
        resetFormulario();
        
        $('#comunicado_id').val(item.id);
        $('#comunicado_titulo').val(item.title);
        $('#comunicado_categoria').val(item.category_id);
        var esEvento = $('#comunicado_categoria option:selected').data('slug') === 'eventos' || item.fecha_evento !== '';
        $('#form-comunicado-titulo').text(esEvento ? 'Editar actividad o evento' : 'Editar Comunicado');
        
        // jQuery .val() strips some html encoding if we are not careful, but for a simple textarea it works ok.
        $('#comunicado_contenido').val(item.content);
        $('#comunicado_fecha_evento').val(item.fecha_evento);
        $('#comunicado_tipo_evento').val(item.tipo_evento || 'ACADEMICO');
        $('#comunicado_lugar_evento').val(item.lugar_evento || 'I.E. Rafael Pombo');
        actualizarCamposEvento();
        $('#comunicado_pdf_url').val(item.pdf_url);
        if (item.es_destacado == '1') {
            $('#comunicado_es_destacado').prop('checked', true);
        }

        if (item.imagen_id && item.imagen_url) {
            $('#comunicado_imagen_id').val(item.imagen_id);
            $('#comunicado_imagen_preview').html('<img src="' + item.imagen_url + '" style="max-width:100%; max-height:100%; object-fit:cover;">');
            $('#remove_comunicado_imagen_btn').show();
        }
        
        $formContainer.slideDown();
        // Scroll hasta el form
        $('html, body').animate({ scrollTop: $formContainer.offset().top - 50 }, 300);
    }

    // Guardar (Crear o Actualizar)
    $('#btn-guardar-comunicado').click(function(e) {
        e.preventDefault();
        var id = $('#comunicado_id').val();
        var titulo = $('#comunicado_titulo').val();
        
        if (titulo.trim() === '') {
            alert('El título es obligatorio.');
            return;
        }

        $('#comunicado-spinner').addClass('is-active');
        $('#btn-guardar-comunicado').prop('disabled', true);

        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_save_comunicado',
            nonce: edusiteco_ajax.nonce,
            id: id,
            titulo: titulo,
            categoria_id: $('#comunicado_categoria').val(),
            contenido: $('#comunicado_contenido').val(),
            fecha_evento: $('#comunicado_fecha_evento').val(),
            tipo_evento: $('#comunicado_tipo_evento').val(),
            lugar_evento: $('#comunicado_lugar_evento').val(),
            pdf_url: $('#comunicado_pdf_url').val(),
            es_destacado: $('#comunicado_es_destacado').is(':checked') ? '1' : '0',
            imagen_id: $('#comunicado_imagen_id').val()
        }, function(response) {
            $('#comunicado-spinner').removeClass('is-active');
            $('#btn-guardar-comunicado').prop('disabled', false);
            
            if (response.success) {
                $formContainer.slideUp();
                cargarComunicados();
            } else {
                alert('Error al guardar: ' + response.data);
            }
        });
    });

    // Eliminar
    function eliminarComunicado(id) {
        $.post(edusiteco_ajax.ajax_url, {
            action: 'edusiteco_delete_comunicado',
            nonce: edusiteco_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                cargarComunicados();
            } else {
                alert('Error al eliminar: ' + response.data);
            }
        });
    }
});
</script>
