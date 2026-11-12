<?php

/**
 * Template Name: Profesores
 * Description: Página que muestra el directorio de profesores con un diseño profesional.
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php 
    $o = get_option('info_colegio_settings');
    ?>
    <!-- 🌟 HERO -->
    <section class="relative h-[50vh] bg-cover bg-center flex items-center justify-center text-white"
        style="background-image: linear-gradient(rgba(51, 102, 204, 0.85), rgba(51, 102, 204, 0.9)), url('<?php echo esc_url(!empty($o['profesores']['hero_image']) ? $o['profesores']['hero_image'] : get_template_directory_uri() . '/assets/images/hero-simbolos.jpg'); ?>');">

        <!-- Contenido -->
        <div class="relative z-10 text-center px-6 animate-fade-in-up">
            <h1 class="text-4xl md:text-6xl font-bold font-display mb-4 tracking-tight">
                <?php echo esc_html(!empty($o['profesores']['titulo']) ? $o['profesores']['titulo'] : 'Nuestro Equipo Docente'); ?>
            </h1>

            <p class="text-lg md:text-2xl opacity-90 max-w-2xl mx-auto">
                <?php echo esc_html(!empty($o['profesores']['subtitulo']) ? $o['profesores']['subtitulo'] : 'Conoce a los profesionales comprometidos con la formación de nuestros estudiantes'); ?>
            </p>
        </div>
    </section>

    <!-- 📚 LISTADO DE PROFESORES -->
    <section class="profesores-list-section px-6 py-16">
        <div class="max-w-7xl mx-auto">

        <?php
        $args = [
            'post_type' => 'profesor',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ];

        $profesores = new WP_Query($args);

        if ($profesores->have_posts()):
        ?>

            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg p-4 md:p-8 border border-gray-200 dark:border-gray-700">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                <?php while ($profesores->have_posts()): $profesores->the_post(); ?>

                    <div class="p-4">
                        <article class="bg-white dark:bg-background-dark rounded-2xl shadow-md hover:shadow-xl 
                            transition-shadow duration-300 overflow-hidden group animate-fade-in-up">

                            <!-- Imagen -->
                            <div class="relative h-56 overflow-hidden">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium', [
                                         'class' => 'w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500'
                                    ]); ?>
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                        Sin foto
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Contenido -->
                            <div class="p-5 text-center">

                                <!-- Nombre -->
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    <?php the_title(); ?>
                                </h3>

                                <!-- Materia -->
                                <?php
                                $subject = get_post_meta(get_the_ID(), 'profesor_asignatura', true);
                                ?>

                                <?php if ($subject): ?>
                                    <p class="text-sm text-brand-primary font-medium mb-2">
                                        <?= esc_html($subject); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (has_excerpt() || get_the_content()): ?>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        <?= wp_trim_words(get_the_content(), 20, '...'); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Botón -->
                                <a
                                    href="<?php the_permalink(); ?>"
                                    class="inline-flex items-center gap-2 text-sm text-brand-primary 
                   hover:text-brand-secondary font-semibold transition-colors">
                                    Ver perfil
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                        viewBox="0 0 24 24" fill="none">
                                        <path d="M5 12H19M12 5L19 12L12 19"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    </div>


                <?php endwhile; ?>

              </div>
            </div>

        <?php
        else:
            echo '<div class="bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700"><p class="text-center text-lg text-gray-600 dark:text-gray-300">No hay profesores registrados aún.</p></div>';
        endif;

        wp_reset_postdata();
        ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>