<?php get_header(); ?>

<main id="primary" class="site-main">
    <article class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>

                <div class="flex flex-col md:flex-row gap-10">
                    
                    <!-- Columna Izquierda (Foto) -->
                    <div class="w-full md:w-1/3 flex justify-center md:justify-start items-start">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('large', ['class' => 'w-64 h-64 md:w-full md:h-auto aspect-square object-cover rounded-full shadow-lg border-4 border-white dark:border-gray-800']); ?>
                        <?php else: ?>
                            <div class="w-64 h-64 md:w-full md:h-auto aspect-square bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 shadow-lg border-4 border-white dark:border-gray-800">
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Columna Derecha (Información) -->
                    <div class="w-full md:w-2/3 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg p-6 md:p-10 border border-gray-200 dark:border-gray-700">
                        
                        <!-- Encabezado -->
                        <header class="mb-6">
                            <?php the_title('<h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">', '</h1>'); ?>
                            
                            <?php 
                            $asignatura = get_post_meta(get_the_ID(), 'profesor_asignatura', true);
                            if ($asignatura): 
                            ?>
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm font-bold rounded-full">
                                        <?php echo esc_html($asignatura); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Miembro del equipo desde: <?php echo get_the_date('Y'); ?>
                            </div>
                        </header>

                        <!-- Línea separadora -->
                        <hr class="border-gray-200 dark:border-gray-700 mb-8">

                        <!-- Descripción / Biografía -->
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 text-justify">
                            <?php the_content(); ?>
                        </div>

                    </div>
                </div>

                <!-- Comunicados del Profesor -->
                <?php
                $teacher_user_id = get_post_meta(get_the_ID(), '_teacher_user_id', true);
                if ($teacher_user_id) :
                    $args = [
                        'post_type'      => 'comunicado',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'meta_query'     => [
                            [
                                'key'   => '_teacher_user_id',
                                'value' => $teacher_user_id,
                            ]
                        ],
                    ];
                    $teacher_posts = new WP_Query($args);
                    if ($teacher_posts->have_posts()) :
                ?>
                        <section class="mt-16 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                Comunicados del profesor
                            </h2>
                            <ul class="space-y-4">
                                <?php while ($teacher_posts->have_posts()) : $teacher_posts->the_post(); ?>
                                    <li class="bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition duration-300">
                                        <a href="<?php the_permalink(); ?>" class="text-lg font-bold text-brand-primary hover:text-brand-secondary transition">
                                            <?php the_title(); ?>
                                        </a>
                                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <?php echo get_the_date(); ?>
                                        </p>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </section>
                <?php
                        wp_reset_postdata();
                    endif;
                endif;
                ?>

                <!-- Botón Volver -->
                <footer class="mt-12">
                    <?php
                    $profesores_page = get_page_by_path('profesores');
                    $profesores_url = $profesores_page ? get_permalink($profesores_page->ID) : home_url('/profesores/');
                    ?>
                    <a href="<?php echo esc_url($profesores_url); ?>" class="inline-flex items-center gap-2 text-brand-primary hover:text-brand-secondary font-bold transition-all group">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Profesores
                    </a>
                </footer>

        <?php endwhile; endif; ?>
    </article>
</main>

<?php get_footer(); ?>