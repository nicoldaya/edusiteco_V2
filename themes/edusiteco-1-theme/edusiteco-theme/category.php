<?php get_header(); ?>

<?php
$current_category = get_queried_object();
$is_event_category = isset($current_category->slug) && $current_category->slug === 'eventos';
?>
<main id="primary" class="site-main <?php echo $is_event_category ? 'lg:max-w-[96%]' : 'lg:max-w-[80%]'; ?> mx-auto">
    <div class="<?php echo $is_event_category ? 'events-archive-panel' : 'bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg p-6 md:p-10 border border-gray-200 dark:border-gray-700 my-8'; ?>">
        <div class="max-w-7xl mx-auto">
            <header class="page-header my-8">
				<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-text-dark mb-4"><?= $current_category->name; ?></h1>
				
				<?php if ( ! empty( $current_category->description ) ) : ?>
					<div class="archive-description text-gray-600 dark:text-text-dark leading-relaxed"><?= $current_category->description; ?></div>
				<?php endif; ?>
            </header>

			

        <div class="<?php echo $is_event_category ? 'events-list' : 'grid md:grid-cols-2 lg:grid-cols-3 gap-6'; ?>">
            <?php
            $current_page = max(1, absint(get_query_var('paged')), absint(get_query_var('page')));
            $convocatorias = new WP_Query([
                'post_type' => 'comunicado',
                'posts_per_page' => $is_event_category ? 10 : 6,
                'paged' => $current_page,
                'meta_key' => $is_event_category ? '_fecha_evento' : '',
                'orderby' => $is_event_category ? 'meta_value' : 'date',
                'meta_type' => $is_event_category ? 'DATE' : '',
                'order' => $is_event_category ? 'ASC' : 'DESC',
                'meta_query' => $is_event_category ? [
                    [
                        'key' => '_fecha_evento',
                        'value' => '',
                        'compare' => '!=',
                    ],
                ] : [],
                'tax_query' => [
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $is_event_category ? array('eventos', 'comunicado', 'circulares') : $current_category->slug,
                    ],
                ],
            ]);

            if ($convocatorias->have_posts()):
                while ($convocatorias->have_posts()):
                    $convocatorias->the_post();
                    if ($is_event_category) :
                        $event_date = get_post_meta(get_the_ID(), '_fecha_evento', true);
                        $event_timestamp = strtotime($event_date);
                        $event_type = get_post_meta(get_the_ID(), '_tipo_evento', true);
                        $event_location = get_post_meta(get_the_ID(), '_lugar_evento', true);
                        $event_styles = array(
                            'ACADEMICO' => array('label' => 'ACADÉMICO', 'class' => 'event-date--academic'),
                            'REUNION DE PADRES' => array('label' => 'REUNIÓN DE PADRES', 'class' => 'event-date--parents'),
                            'EVENTO CULTURAL' => array('label' => 'EVENTO CULTURAL', 'class' => 'event-date--cultural'),
                            'JORNADA PEDAGOGICA' => array('label' => 'JORNADA PEDAGÓGICA', 'class' => 'event-date--pedagogy'),
                            'EVENTO DEPORTIVO' => array('label' => 'EVENTO DEPORTIVO', 'class' => 'event-date--sports'),
                            'EVENTO INSTITUCIONAL' => array('label' => 'EVENTO INSTITUCIONAL', 'class' => 'event-date--institutional'),
                        );
                        $event_display = isset($event_styles[$event_type]) ? $event_styles[$event_type] : array('label' => 'EVENTO', 'class' => 'event-date--academic');
                        $is_historical = get_post_meta(get_the_ID(), '_edusiteco_historic_archive', true) === '1' || $event_date < current_time('Y-m-d');
                        $event_pdf = get_post_meta(get_the_ID(), '_pdf_url', true);
                        $event_has_details = has_excerpt() || trim(get_the_content()) !== '' || $event_pdf;
                        ?>
                        <article class="event-card">
                            <time class="event-card__date <?php echo esc_attr($event_display['class']); ?>" datetime="<?php echo esc_attr($event_date); ?>">
                                <span class="event-card__month"><?php echo esc_html(strtoupper(date_i18n('M', $event_timestamp))); ?></span>
                                <span class="event-card__day"><?php echo esc_html(date_i18n('j', $event_timestamp)); ?></span>
                                <span class="event-card__weekday"><?php echo esc_html(date_i18n('D', $event_timestamp)); ?></span>
                            </time>
                            <div class="event-card__content">
                                <h2 class="event-card__title">
                                    <?php if ($event_has_details) : ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php else : ?>
                                        <?php the_title(); ?>
                                    <?php endif; ?>
                                </h2>
                                <p class="event-card__type <?php echo esc_attr($event_display['class']); ?>"><?php echo esc_html($event_display['label']); ?></p>
                                <p class="event-card__location"><span aria-hidden="true">📍</span> <?php echo esc_html($event_location ?: 'I.E. Rafael Pombo'); ?></p>
                                <?php if ($is_historical) : ?>
                                    <p class="event-card__status">ACTIVIDAD REALIZADA</p>
                                <?php endif; ?>
                                <?php if (has_excerpt() || trim(get_the_content()) !== '') : ?>
                                    <p class="event-card__description"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                                <?php endif; ?>
                                <?php if ($event_has_details) : ?>
                                    <div class="event-card__links">
                                        <a href="<?php the_permalink(); ?>">Ver información</a>
                                        <?php if ($event_pdf) : ?>
                                            <a href="<?php echo esc_url($event_pdf); ?>" target="_blank" rel="noopener noreferrer">Circular / documento</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php else : ?>
                    <article
							class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 group">
							<?php if (has_post_thumbnail()): ?>
								<div class="relative overflow-hidden">
									<img 
										src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>"
										class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105"
									/>
								</div>
							<?php else: ?>
								<div class="relative overflow-hidden">
									<!-- Fondo con SVG -->
									<div class="h-52 bg-brand-primary text-white/10 transition-transform duration-500 group-hover:scale-105">
										<svg viewBox="0 0 1000 500" fill="currentColor" class="w-full h-full">
											<circle cx="200" cy="100" r="80" opacity="0.1" />
											<circle cx="800" cy="150" r="120" opacity="0.05" />
											<circle cx="400" cy="400" r="100" opacity="0.08" />
										</svg>
									</div>
									<!-- Texto superpuesto -->
									<div class="absolute inset-0 flex items-center justify-center p-4 text-white">
										<div class="text-center">
											<span class="block text-lg font-semibold">Comunicado</span>
											<span class="block text-sm opacity-80 uppercase font-bold"><?php echo get_bloginfo('name'); ?></span>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<div class="p-6">
								<div class="flex items-center justify-between mb-3">
									<div class="flex flex-wrap gap-2">
										<?php
										$categories = get_the_category();
										if (!empty($categories)) {
											foreach ($categories as $category) {
												// Construimos manualmente el enlace para tener control total sobre las clases
												echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-xs font-medium text-brand-primary bg-brand-primary/10 px-2 py-1 rounded-full hover:bg-primary/20 transition-colors">' . esc_html($category->name) . '</a>';
											}
										}
										?>
									</div>
									<time class="text-xs text-gray-500 dark:text-gray-400" datetime="<?php echo get_the_date('c'); ?>">
										<?php echo get_the_date('j M, Y'); ?>
									</time>
								</div>
								<h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-tight group-hover:text-primary transition-colors duration-200 line-clamp-2">
									<a href="<?php the_permalink(); ?>" class="hover:underline decoration-2 decoration-primary/50">
										<?php the_title(); ?>
									</a>
								</h3>

								<p class="text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-3">
									<?php echo wp_trim_words(get_the_excerpt(), 15); ?>
								</p>

								<?php
								$fecha_evento = get_post_meta(get_the_ID(), '_fecha_evento', true);
								$pdf_url = get_post_meta(get_the_ID(), '_pdf_url', true);
								?>

								<!-- Fecha del evento si existe -->
								<?php if ($fecha_evento): ?>
									<div class="flex items-center mt-4 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
										<div class="flex-shrink-0 w-8 h-8 bg-brand-primary rounded-full flex items-center justify-center mr-3">
											<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
											</svg>
										</div>
										<div>
											<p class="text-sm font-semibold text-gray-900 dark:text-text-dark">Fecha del evento</p>
											<p class="text-sm text-gray-700 dark:text-gray-300"><?php echo esc_html(date_i18n('j \d\e F, Y', strtotime($fecha_evento))); ?></p>
										</div>
									</div>
								<?php endif; ?>

								<!-- Acciones -->
								<div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
									<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-brand-primary hover:text-brand-secondary font-semibold text-sm transition-colors group/btn">
										Leer más
										<svg class="w-4 h-4 ml-1 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
										</svg>
									</a>

									<?php if ($pdf_url): ?>
										<a href="<?php echo esc_url($pdf_url); ?>" target="_blank" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-red-600 transition-colors group/pdf" title="Descargar PDF">
											<svg class="w-5 h-5 mr-2 group-hover/pdf:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M4 4C4 3.44772 4.44772 3 5 3H14H14.5858C14.851 3 15.1054 3.10536 15.2929 3.29289L19.7071 7.70711C19.8946 7.89464 20 8.149 20 8.41421V20C20 20.5523 19.5523 21 19 21H5C4.44772 21 4 20.5523 4 20V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M20 8H15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.5 13H11V17H11.5C12.6046 17 13.5 16.1046 13.5 15C13.5 13.8954 12.6046 13 11.5 13Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15.5 17V13L17.5 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16 15H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 17L7 15.5M7 15.5L7 13L7.75 13C8.44036 13 9 13.5596 9 14.25V14.25C9 14.9404 8.44036 15.5 7.75 15.5H7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g>
											</svg>
											<span class="text-sm font-medium group-hover/pdf:font-semibold group-hover/pdf:scale-110 transition-transform">PDF</span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</article>
                    <?php endif; ?>
                <?php endwhile;
                wp_reset_postdata();
            else: ?>
                <div class="flex justify-center">
                    <p class="bg-brand-primary-100 p-4 rounded-md text-text-light dark:text-text-dark"><?php echo $is_event_category ? 'No hay eventos disponibles en este momento.' : 'No hay convocatorias disponibles en este momento.'; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- 🔹 Paginación -->
        <div class="mt-8 flex justify-center">
            <?php
            if ($convocatorias->max_num_pages > 1) {
                echo paginate_links([
                    'total' => $convocatorias->max_num_pages,
                    'current' => $current_page,
                    'mid_size' => 2,
                    'prev_text' => __('« Anterior'),
                    'next_text' => __('Siguiente »'),
                    'type' => 'list',
                ]);
            }
            ?>
        </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>