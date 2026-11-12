<h2 style="margin-bottom:10px;">Identidad Institucional</h2>
<p style="margin-bottom:20px;">Completa la información principal del colegio. Cada sección tiene una breve guía para ayudarte.</p>

<h2>Configuración del Encabezado (Hero)</h2>
<p>Personaliza el título y subtítulo que aparecen en la parte superior de la página de Misión, Visión y Valores.</p>
<?php
$o = get_option('info_colegio_settings');
$mision_imagen = $o['mision_imagen'] ?? '';
$plugin_asset_url = plugin_dir_url(__DIR__ . '/../Edusiteco.php') . 'assets/img/';
$mision_imagen_default = $plugin_asset_url . 'mision-institucional.png';
$vision_imagen = $o['vision_imagen'] ?? '';
$vision_imagen_default = $plugin_asset_url . 'vision-institucional.png';
$valores_imagen = $o['valores_imagen'] ?? '';
?>
<table class="form-table">
    <tr>
        <th scope="row">Título Principal</th>
        <td>
            <input type="text" name="info_colegio_settings[identidad_hero_titulo]" value="<?php echo esc_attr($o['identidad_hero_titulo'] ?? 'Misión, Visión y Valores'); ?>" class="regular-text" style="width:100%;">
        </td>
    </tr>
    <tr>
        <th scope="row">Subtítulo / Descripción</th>
        <td>
            <textarea name="info_colegio_settings[identidad_hero_subtitulo]" rows="2" class="regular-text" style="width:100%;"><?php echo esc_textarea($o['identidad_hero_subtitulo'] ?? 'La esencia que guía nuestro camino educativo'); ?></textarea>
        </td>
    </tr>
</table>

<hr style="margin: 30px 0;">

<div class="accordion-container">

    <!-- Sección 1: Misión -->
    <div class="accordion-item">
        <button type="button" class="accordion-header">Misión</button>
        <div class="accordion-content">
            <p>Describe la <strong>razón de ser del colegio</strong>, lo que busca lograr con su comunidad educativa.</p>
            <div style="margin:15px 0; padding:15px; border:1px solid #ddd; border-radius:6px;">
                <p><strong>Imagen horizontal de Misión</strong></p>
                <input type="hidden" id="mision-imagen-url" name="info_colegio_settings[mision_imagen]" value="<?php echo esc_url($mision_imagen); ?>">
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <img id="mision-imagen-preview" src="<?php echo esc_url($mision_imagen ?: $mision_imagen_default); ?>" alt="Vista previa de la imagen de Misión" style="display:block; width:min(100%, 360px); height:200px; border-radius:8px; object-fit:cover; border:1px solid #ddd; background:#fff;">
                    <button type="button" class="button" id="seleccionar-imagen-mision">Seleccionar imagen</button>
                    <button type="button" class="button" id="quitar-imagen-mision">Usar imagen predeterminada</button>
                </div>
                <p class="description">Esta imagen se muestra en formato horizontal junto al texto de Misión.</p>
            </div>
            <textarea name="info_colegio_settings[mision]" rows="4" class="large-text" placeholder="Ej: Formar ciudadanos íntegros con valores humanos y competencias académicas."><?php echo esc_textarea($o['mision'] ?? ''); ?></textarea>
            <br><br>
            <p><strong>Resumen de Misión (para la página de inicio):</strong></p>
            <textarea name="info_colegio_settings[mision_resumen]" rows="2" class="large-text" placeholder="Ej: Breve resumen de la misión..."><?php echo esc_textarea($o['mision_resumen'] ?? ''); ?></textarea>
        </div>
    </div>

    <!-- Sección 2: Visión -->
    <div class="accordion-item">
        <button type="button" class="accordion-header">Visión</button>
        <div class="accordion-content">
            <p>Explica <strong>hacia dónde quiere llegar el colegio</strong> en el futuro o qué busca lograr a largo plazo.</p>
            <div style="margin:15px 0; padding:15px; border:1px solid #ddd; border-radius:6px;">
                <p><strong>Imagen horizontal de Visión</strong></p>
                <input type="hidden" id="vision-imagen-url" name="info_colegio_settings[vision_imagen]" value="<?php echo esc_url($vision_imagen); ?>">
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <img id="vision-imagen-preview" src="<?php echo esc_url($vision_imagen ?: $vision_imagen_default); ?>" alt="Vista previa de la imagen de Visión" style="display:block; width:min(100%, 360px); height:200px; border-radius:8px; object-fit:cover; border:1px solid #ddd; background:#fff;">
                    <button type="button" class="button" id="seleccionar-imagen-vision">Seleccionar imagen</button>
                    <button type="button" class="button" id="quitar-imagen-vision">Usar imagen predeterminada</button>
                </div>
                <p class="description">Esta imagen se muestra en formato horizontal junto al texto de Visión.</p>
            </div>
            <textarea name="info_colegio_settings[vision]" rows="4" class="large-text" placeholder="Ej: Ser una institución reconocida por su excelencia educativa y compromiso social."><?php echo esc_textarea($o['vision'] ?? ''); ?></textarea>
            <br><br>
            <p><strong>Resumen de Visión (para la página de inicio):</strong></p>
            <textarea name="info_colegio_settings[vision_resumen]" rows="2" class="large-text" placeholder="Ej: Breve resumen de la visión..."><?php echo esc_textarea($o['vision_resumen'] ?? ''); ?></textarea>
        </div>
    </div>

    <!-- Sección 3: Valores Institucionales -->
    <div class="accordion-item">
        <button type="button" class="accordion-header">Valores Institucionales</button>
        <div class="accordion-content">
            <p>Agrega los valores que guían las acciones de la institución. Por ejemplo: respeto, honestidad, responsabilidad.</p>
            <div style="margin:15px 0; padding:15px; border:1px solid #ddd; border-radius:6px;">
                <p><strong>Imagen horizontal de Nuestros Valores</strong></p>
                <input type="hidden" id="valores-imagen-url" name="info_colegio_settings[valores_imagen]" value="<?php echo esc_url($valores_imagen); ?>">
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <img id="valores-imagen-preview" src="<?php echo esc_url($valores_imagen); ?>" alt="Vista previa de la imagen de Valores" style="display:<?php echo $valores_imagen ? 'block' : 'none'; ?>; width:min(100%, 360px); height:200px; border-radius:8px; object-fit:cover; border:1px solid #ddd; background:#fff;">
                    <button type="button" class="button" id="seleccionar-imagen-valores">Seleccionar imagen</button>
                    <button type="button" class="button" id="quitar-imagen-valores">Quitar imagen</button>
                </div>
                <p class="description">La imagen se presenta en una franja horizontal de ancho completo sobre la sección de valores.</p>
            </div>

            <p><strong>Resumen de Valores (para la página de inicio):</strong></p>
            <textarea name="info_colegio_settings[valores_resumen]" rows="2" class="large-text" placeholder="Ej: Breve resumen de los valores..."><?php echo esc_textarea($o['valores_resumen'] ?? ''); ?></textarea>
            <br><br>

            <div id="valores-container">
                <?php
                $valores = isset($o['valores']) && is_array($o['valores']) ? $o['valores'] : [['nombre' => '', 'descripcion' => '']];
                foreach ($valores as $index => $valor) :
                ?>
                    <div class="valor-item" style="margin-bottom:15px; border:1px solid #ddd; padding:10px; border-radius:6px;">
                        <label><strong>Nombre del Valor:</strong></label><br>
                        <input type="text" name="info_colegio_settings[valores][<?php echo $index; ?>][nombre]" value="<?php echo esc_attr($valor['nombre'] ?? ''); ?>" class="regular-text" placeholder="Ej: Respeto"><br><br>
                        <label><strong>Descripción:</strong></label><br>
                        <textarea name="info_colegio_settings[valores][<?php echo $index; ?>][descripcion]" rows="2" class="large-text" placeholder="Ej: Promovemos relaciones basadas en el respeto mutuo."><?php echo esc_textarea($valor['descripcion'] ?? ''); ?></textarea><br>
                        <button type="button" class="button remove-valor">Eliminar</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button button-secondary" id="add-valor">+ Agregar Valor</button>
        </div>
    </div>
</div>

<!-- Estilos visuales -->
<style>
    .accordion-container {
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
    }

    .accordion-header {
        width: 100%;
        text-align: left;
        padding: 12px 16px;
        font-size: 16px;
        background: #f7f7f7;
        border: none;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .accordion-mision-icon {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-right: 8px;
        padding: 3px;
        border: 1px solid #ddd;
        border-radius: 50%;
        background: #fff;
        object-fit: contain;
        vertical-align: middle;
    }

    .accordion-mision-icon {
        object-fit: cover;
    }

    .accordion-header:hover {
        background: #eaeaea;
    }

    .accordion-content {
        display: none;
        padding: 15px;
        background: #fff;
    }

    .accordion-item.active .accordion-content {
        display: block;
    }
</style>

<!-- Script de acordeones y valores -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- Acordeones ---
        const accordions = document.querySelectorAll(".accordion-header");
        accordions.forEach(btn => {
            btn.addEventListener("click", () => {
                const item = btn.parentElement;
                item.classList.toggle("active");
            });
        });

        const missionImageInput = document.getElementById("mision-imagen-url");
        const missionImagePreview = document.getElementById("mision-imagen-preview");
        const selectMissionImage = document.getElementById("seleccionar-imagen-mision");
        const removeMissionImage = document.getElementById("quitar-imagen-mision");
        const defaultMissionImage = "<?php echo esc_js($mision_imagen_default); ?>";

        if (missionImageInput && selectMissionImage) {
            let missionImageFrame;

            selectMissionImage.addEventListener("click", (event) => {
                event.preventDefault();

                if (!missionImageFrame) {
                    missionImageFrame = wp.media({
                        title: "Seleccionar imagen para Misión",
                        button: { text: "Usar esta imagen" },
                        library: { type: "image" },
                        multiple: false
                    });

                    missionImageFrame.on("select", () => {
                        const attachment = missionImageFrame.state().get("selection").first().toJSON();
                        missionImageInput.value = attachment.url;
                        missionImagePreview.src = attachment.url;
                        missionImagePreview.style.display = "block";
                    });
                }

                missionImageFrame.open();
            });
        }

        if (removeMissionImage) {
            removeMissionImage.addEventListener("click", () => {
                missionImageInput.value = "";
                missionImagePreview.src = defaultMissionImage;
                missionImagePreview.style.display = "block";
            });
        }

        function bindIdentityImagePicker(key, label, defaultImage = "") {
            const input = document.getElementById(`${key}-imagen-url`);
            const preview = document.getElementById(`${key}-imagen-preview`);
            const selectButton = document.getElementById(`seleccionar-imagen-${key}`);
            const removeButton = document.getElementById(`quitar-imagen-${key}`);
            let frame;

            if (!input || !preview || !selectButton || !removeButton) return;

            selectButton.addEventListener("click", (event) => {
                event.preventDefault();

                if (!frame) {
                    frame = wp.media({
                        title: `Seleccionar imagen para ${label}`,
                        button: { text: "Usar esta imagen" },
                        library: { type: "image" },
                        multiple: false
                    });

                    frame.on("select", () => {
                        const attachment = frame.state().get("selection").first().toJSON();
                        input.value = attachment.url;
                        preview.src = attachment.url;
                        preview.style.display = "block";
                    });
                }

                frame.open();
            });

            removeButton.addEventListener("click", () => {
                input.value = "";
                if (defaultImage) {
                    preview.src = defaultImage;
                    preview.style.display = "block";
                } else {
                    preview.removeAttribute("src");
                    preview.style.display = "none";
                }
            });
        }

        bindIdentityImagePicker("vision", "Visión", "<?php echo esc_js($vision_imagen_default); ?>");
        bindIdentityImagePicker("valores", "Valores");

        // --- Valores dinámicos ---
        const container = document.getElementById("valores-container");
        const addBtn = document.getElementById("add-valor");

        addBtn.addEventListener("click", () => {
            const index = container.querySelectorAll(".valor-item").length;
            const div = document.createElement("div");
            div.className = "valor-item";
            div.style.marginBottom = "15px";
            div.style.border = "1px solid #ddd";
            div.style.padding = "10px";
            div.style.borderRadius = "6px";
            div.innerHTML = `
            <label><strong>Nombre del Valor:</strong></label><br>
            <input type="text" name="info_colegio_settings[valores][${index}][nombre]" class="regular-text" placeholder="Ej: Responsabilidad"><br><br>
            <label><strong>Descripción:</strong></label><br>
            <textarea name="info_colegio_settings[valores][${index}][descripcion]" rows="2" class="large-text" placeholder="Ej: Asumimos compromisos con disciplina y honestidad."></textarea><br>
            <button type="button" class="button remove-valor">Eliminar</button>
        `;
            container.appendChild(div);
        });

        container.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-valor")) {
                e.target.closest(".valor-item").remove();
            }
        });
    });
</script>