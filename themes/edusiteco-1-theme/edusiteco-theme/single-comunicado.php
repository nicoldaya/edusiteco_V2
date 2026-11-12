<?php get_header(); ?>

<article class=" md:w-[70%] lg:max-w-[800px] mx-auto px-6 py-16">
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>
            <div class="comunicado-surface bg-gray-50 dark:bg-gray-800 text-text-light dark:text-text-dark">
            <h1 class="comunicado-title text-4xl md:text-5xl font-extrabold mb-4 text-text-light dark:text-text-dark leading-tight">
                <?php the_title(); ?>
            </h1>

            <div class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Publicado el <?php echo get_the_date(); ?>
            </div>

            <?php if (has_post_thumbnail()): ?>
                <div class="mb-6">
                    <?php the_post_thumbnail('large', ['class' => 'rounded-xl shadow-lg w-full h-auto']); ?>
                </div>
            <?php endif; ?>

            <?php
            $fecha_evento = get_post_meta(get_the_ID(), '_fecha_evento', true);
            $pdf_url = get_post_meta(get_the_ID(), '_pdf_url', true);
            ?>

            <?php if ($fecha_evento || $pdf_url) : ?>

                <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-border-dark shadow-sm rounded-2xl p-6 mb-8 w-full flex flex-wrap gap-6 items-center justify-between">
                    <?php if ($fecha_evento): ?>
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4 rounded-xl">
                            <div class="bg-blue-100 dark:bg-gray-700 p-2 rounded-lg text-blue-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Fecha del Evento</p>
                                <p class="text-lg font-bold text-text-light dark:text-text-dark mt-0.5 whitespace-nowrap">
                                    <?php echo esc_html($fecha_evento); ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($pdf_url): ?>
                        <a
                            href="<?php echo esc_url($pdf_url); ?>"
                            target="_blank"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-medium shadow-md transition-all hover:shadow-lg whitespace-nowrap"
                            title="<?php _e('Descargar comunicado (PDF)', 'edusiteco') ?>"
                            aria-label="Descargar comunicado (PDF)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Descargar PDF</span>
                        </a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
            <div class="comunicado-panel">
                <div class="prose prose-lg max-w-none comunicado-panel__content text-text-light dark:text-text-dark">
                    <?php the_content(); ?>
                </div>
            </div>
            </div>

            <?php
            $categories = get_the_category();
            $archive_link = '';
            $texto = '';

            if (!empty($categories)) {
                $primary_category = $categories[0];
                $archive_link = get_category_link($primary_category->term_id);
                $texto = 'Volver a ' . $primary_category->name;
            }
            ?>

            <?php if ($archive_link): ?>
                <div class="mt-10">
                    <a
                        href="<?php echo esc_url($archive_link); ?>"
                        class="group text-blue-600 font-medium flex items-center gap-2">
                        <svg class="w-8 group-hover:stroke-blue-800 transition-colors" viewBox="-2.4 -2.4 28.80 28.80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12H18M6 12L11 7M6 12L11 17"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        <span class="group-hover:text-blue-800 group-hover:underline transition-colors">
                            <?php echo esc_html($texto); ?>
                        </span>
                    </a>
                </div>
            <?php endif; ?>
    <?php endwhile;
    endif; ?>
</article>

<?php get_footer(); ?>