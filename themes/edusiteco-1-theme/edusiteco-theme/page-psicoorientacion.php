<?php
/**
 * Template Name: Psicoorientación
 * Description: Página para Psicoorientación integrada con el Plugin Edusiteco.
 */

get_header();

$o = get_option('info_colegio_settings');
$o = is_array($o) ? $o : array();

$hero_title = !empty($o['psico_hero_titulo']) ? $o['psico_hero_titulo'] : get_the_title();
$saludo = $o['psico_saludo'] ?? 'Aquí estamos para escucharte. Este es un espacio seguro para ayudarte con tus emociones, tus estudios y tu futuro. No estás solo en tu proceso.';

$default_areas = array(
    array('titulo' => 'Tus Emociones', 'texto' => '¿Te sientes triste, con ansiedad o problemas en casa? Hablemos.', 'icono' => $o['psico_s1_icono'] ?? '', 'material_icon' => 'favorite'),
    array('titulo' => 'Tus Estudios', 'texto' => 'Te ayudamos a organizar tu tiempo y a decidir qué carrera estudiar cuando te gradúes.', 'icono' => $o['psico_s2_icono'] ?? '', 'material_icon' => 'school'),
    array('titulo' => 'Convivencia', 'texto' => 'Ayuda para resolver conflictos con compañeros o detener el acoso escolar (bullying).', 'icono' => $o['psico_s3_icono'] ?? '', 'material_icon' => 'handshake'),
    array('titulo' => 'Para Papás', 'texto' => 'Orientación y consejos para que las familias apoyen a sus hijos.', 'icono' => $o['psico_s4_icono'] ?? '', 'material_icon' => 'family_restroom'),
);
foreach ($default_areas as $i => &$default_area) {
    $number = $i + 1;
    $default_area['titulo'] = $o["psico_s{$number}_titulo"] ?? $default_area['titulo'];
    $default_area['texto'] = $o["psico_s{$number}_texto"] ?? $default_area['texto'];
}
unset($default_area);
$areas = isset($o['psico_areas']) && is_array($o['psico_areas']) ? $o['psico_areas'] : $default_areas;

// Cita
$cita_foto    = $o['psico_foto'] ?? '';
$cita_horario = $o['psico_horario'] ?? 'Atendemos de Lunes a Viernes de 8:00 am a 12:00 pm en la oficina del primer piso.';
$cita_url     = $o['psico_cita_url'] ?? '#';

// Botiquín
$emergencias = $o['psico_emergencias'] ?? 'Si tienes una emergencia fuera del colegio o te sientes en peligro, llama a la línea 141 del ICBF (apoyo a menores) o a la línea local de salud mental.';
?>

<main id="primary" class="site-main">

    <!-- 🌟 HERO -->
    <section class="relative h-[50vh] bg-cover bg-center flex items-center justify-center text-white"
        style="background-image: linear-gradient(rgba(51, 102, 204, 0.85), rgba(51, 102, 204, 0.9));">
        
        <div class="relative z-10 text-center px-6 animate-fade-in-up">
            <h1 class="text-4xl md:text-6xl font-bold font-display text-white mb-4 tracking-tight">
                <?php echo esc_html($hero_title); ?>
            </h1>

            <p class="text-lg md:text-2xl opacity-90 max-w-3xl mx-auto">
                <?php echo esc_html($saludo); ?>
            </p>
        </div>
    </section>

    <div class="psicoorientacion-backdrop">
    <div class="w-full max-w-6xl mx-auto px-6 py-12 md:py-20 bg-background-light dark:bg-background-dark transition-colors duration-300">

        <!-- BLOQUE 2: SERVICIOS -->
        <section class="mb-16 md:mb-24">
            <h2 class="text-3xl font-display font-bold text-center text-text-light dark:text-white mb-10">
                ¿En qué te ayudamos?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($areas as $area) :
                    if (!is_array($area) || empty($area['titulo'])) {
                        continue;
                    }
                    $area_image = isset($area['icono']) ? $area['icono'] : '';
                    $area_icon = !empty($area['material_icon']) ? $area['material_icon'] : 'psychology';
                    ?>
                    <article class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 text-center transform transition duration-300 hover:-translate-y-2">
                        <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center mb-4 overflow-hidden">
                            <?php if ($area_image): ?>
                                <img src="<?php echo esc_url($area_image); ?>" alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="material-icons text-3xl text-primary"><?php echo esc_html($area_icon); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-xl font-bold text-text-light dark:text-white mb-3"><?php echo esc_html($area['titulo']); ?></h3>
                        <?php if (!empty($area['texto'])): ?>
                            <p class="text-gray-600 dark:text-gray-300"><?php echo esc_html($area['texto']); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- BLOQUE 3: CÓMO PEDIR CITA -->
        <section class="mb-16 md:mb-24 bg-primary/5 dark:bg-primary/10 rounded-3xl p-8 md:p-12">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <?php if ($cita_foto): ?>
                    <div class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0">
                        <img src="<?php echo esc_url($cita_foto); ?>" alt="Psicoorientador" class="w-full h-full object-cover rounded-full shadow-xl border-4 border-white dark:border-gray-800">
                    </div>
                <?php else: ?>
                    <div class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center shadow-xl border-4 border-white dark:border-gray-800">
                        <span class="material-icons text-6xl text-gray-400">person</span>
                    </div>
                <?php endif; ?>

                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-display font-bold text-text-light dark:text-white mb-4">¿Cómo pedir una cita?</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 mb-6 flex items-start md:items-center justify-center md:justify-start gap-2">
                        <span class="material-icons text-primary">schedule</span>
                        <?php echo esc_html($cita_horario); ?>
                    </p>
                    <a href="<?php echo esc_url($cita_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-primary hover:bg-secondary text-white font-bold py-4 px-8 rounded-full transition-colors duration-300 shadow-lg hover:shadow-xl text-lg">
                        <span class="material-icons">event_available</span>
                        Pedir cita aquí
                    </a>
                </div>
            </div>
        </section>

        <!-- BLOQUE 4: BOTIQUÍN DE AYUDA -->
        <section>
            <h2 class="text-3xl font-display font-bold text-center text-text-light dark:text-white mb-10 flex items-center justify-center gap-3">
                <span class="material-icons text-red-500 text-4xl">medical_services</span>
                Botiquín de Ayuda
            </h2>

            <div class="max-w-4xl mx-auto mb-16">
                <!-- Teléfonos de Emergencia -->
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-6 md:p-8 rounded-r-xl shadow-sm">
                    <h3 class="text-2xl font-bold text-red-700 dark:text-red-400 mb-4 flex items-center gap-2">
                        <span class="material-icons">warning</span>
                        Teléfonos Importantes
                    </h3>
                    <p class="text-lg text-red-800 dark:text-red-300 leading-relaxed">
                        <?php echo nl2br(esc_html($emergencias)); ?>
                    </p>
                </div>
            </div>

            <!-- Lista de Tips / Recursos -->
            <div>
                <h3 class="text-2xl font-bold text-center text-text-light dark:text-white mb-8">Recursos y Tips</h3>
                
                <?php
                $args = array(
                    'post_type'      => 'psico_recurso',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                );
                $recursos = new WP_Query($args);

                if ($recursos->have_posts()):
                ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <?php while ($recursos->have_posts()): $recursos->the_post(); 
                            $attachment_id = get_post_thumbnail_id();
                            $meta_url = get_post_meta(get_the_ID(), 'recurso_url', true);
                            
                            if ($meta_url) {
                                $file_url = $meta_url;
                            } else {
                                $file_url = $attachment_id ? wp_get_attachment_url($attachment_id) : '#';
                            }
                            
                            // Si por alguna razón wp_get_attachment_url devuelve false, aseguramos un fallback a #
                            if (!$file_url) $file_url = '#';

                            // Determinar si es imagen o documento
                            $is_image = false;
                            $icon = 'description';
                            if ($attachment_id) {
                                $mime = get_post_mime_type($attachment_id);
                                if (strpos($mime, 'image/') !== false) {
                                    $is_image = true;
                                    $icon = 'image';
                                } elseif (strpos($mime, 'pdf') !== false) {
                                    $icon = 'picture_as_pdf';
                                }
                            }
                        ?>
                            <article class="flex flex-col gap-4 bg-white dark:bg-gray-800 p-5 rounded-xl shadow border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 flex-shrink-0 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                                        <span class="material-icons text-2xl"><?php echo esc_html($icon); ?></span>
                                    </div>
                                    <h3 class="font-bold text-gray-800 dark:text-gray-200"><?php the_title(); ?></h3>
                                </div>
                                <?php if (get_the_content()): ?>
                                    <div class="text-gray-600 dark:text-gray-300"><?php echo wpautop(esc_html(get_the_content())); ?></div>
                                <?php endif; ?>
                                <?php if ($file_url !== '#'): ?>
                                    <div class="flex flex-wrap gap-3 mt-auto">
                                        <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
                                            <span class="material-icons" aria-hidden="true">open_in_new</span> Abrir archivo
                                        </a>
                                        <a href="<?php echo esc_url($file_url); ?>" download class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
                                            <span class="material-icons" aria-hidden="true">download</span> Descargar
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 dark:text-gray-400 italic">No hay recursos disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>
    </div>
</main>

<?php get_footer(); ?>
