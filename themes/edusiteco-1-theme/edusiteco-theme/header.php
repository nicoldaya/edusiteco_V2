<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-background-light dark:bg-background-dark font-plus-jakarta'); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'edusiteco'); ?></a>

		<header class="w-full">
			<!-- Top Bar - Gobierno de Colombia -->
			<div class="bg-gov-top px-4 sm:px-6 lg:px-8">
				<div class="max-w-7xl mx-auto flex items-center justify-between h-12">
					<a href="https://www.gov.co" rel="noopener noreferrer" target="_blank">
						<img alt="Logo Gov.co" class="h-6"
							src="<?php echo get_theme_file_uri("assets/img/govco.png"); ?>" />
					</a>
					<div class="hidden md:flex items-center space-x-6">
						<?php
						// Obtener el enlace a la página de transparencia
						$transparency_page = get_page_by_path('transparencia-y-acceso-a-la-informacion-publica');
						$transparency_url = $transparency_page ? get_permalink($transparency_page->ID) : '#';

						$participes_page = get_page_by_path('participa');
						$participes_url = $transparency_page ? get_permalink($participes_page->ID) : '#';
						?>
						<a class="text-white text-sm font-medium hover:underline"
							href="<?php echo esc_url($transparency_url); ?>">Transparencia</a>
						<a class="text-white text-sm font-medium hover:underline"
							href="<?php echo esc_url($participes_url); ?>" target="_blank"
							rel="noopener noreferrer">Participa</a>
						<button id="theme-toggle"
							class="p-2 rounded-full flex justify-center items-center text-white bg-white/10 hover:bg-white/20 transition-colors"
							aria-label="Cambiar tema" title="Cambiar tema">
							<svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8Z" stroke="currentColor"
									stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<svg class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.7" />
								<path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"
									stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
							</svg>
						</button>
					</div>
					<div class="md:hidden">
						<button class="text-white p-2 bg-gray-100 hover:bg-gray-300 transition-colors"
							id="mobile-menu-button">
							<img class="w-6" src="<?php echo get_theme_file_uri("assets/svg/menu-burger.svg") ?>"
								alt="<?php echo esc_attr_e('Menu ITA', 'edusiteco'); ?>" />
						</button>
					</div>
				</div>
			</div>

			<!-- Mobile Menu (Top) -->
			<div class="hidden md:hidden bg-gov-top backdrop-blur-sm" id="mobile-menu">
				<div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
					<a class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10"
						href="<?php echo esc_url($transparency_url); ?>">Transparencia</a>
					<a class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10"
						href="https://www.gov.co/participa/" target="_blank" rel="noopener noreferrer">Participa</a>
					<a class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10"
						href="#">Directorio</a>
				</div>
			</div>

			<!-- Main Header -->
			<?php
			$has_header_image = get_header_image() ? true : false;
			$header_wrapper_class = "bg-background-light dark:bg-background-dark border-b-[8px] border-brand-warning";

			if ($has_header_image) {
				$header_wrapper_class .= " has-header-image";
				$style_header = 'style="background-image: url(' . esc_url(get_header_image()) . '); background-size: cover; background-position: center;"';
			} else {
				$style_header = '';
				$header_wrapper_class .= " no-header-image";
			}
			?>
			<div class="<?php echo esc_attr($header_wrapper_class); ?>" <?php echo $style_header; ?>>
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="flex items-center justify-between <?= ($has_header_image) ? "h-56" : "h-20" ?>">
						<div class="flex items-center">
							<?php
							$title_class = $has_header_image
								? "text-white drop-shadow-lg"
								: "text-text-light dark:text-text-dark";
							$edusiteco_description = get_bloginfo('description', 'display');
							?>
							<div class="header-branding">
								<a class="header-brand-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
									<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/escudo-rafael-pombo.png'); ?>"
										alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
								</a>
								<div class="header-brand-copy">
									<?php if (is_front_page() && is_home()): ?>
										<h1 class="site-title font-quartzo-bold">
											<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"
												class="<?= $title_class; ?> hover:text-brand-primary transition-colors">
												<?php bloginfo('name'); ?>
											</a>
										</h1>
									<?php else: ?>
										<p class="site-title font-quartzo-bold">
											<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"
												class="<?= $title_class; ?> hover:text-brand-primary transition-colors">
												<?php bloginfo('name'); ?>
											</a>
										</p>
									<?php endif; ?>
									<?php if ($edusiteco_description || is_customize_preview()): ?>
										<p class="site-description <?= $title_class; ?>">
											<?php echo esc_html($edusiteco_description); ?>
										</p>
									<?php endif; ?>
								</div>
							</div>

							<!-- Desktop Navigation -->
							<nav class="hidden lg:flex lg:ml-5 lg:space-x-0 header-navigation">
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'menu-1',
										'menu_class' => 'flex space-x-8',
										'container' => false,
										'items_wrap' => '%3$s',
										'fallback_cb' => false,
										'walker' => new Custom_Nav_Walker()
									)
								);

								// Menú por defecto si no hay menú configurado
								if (!has_nav_menu('menu-1')) {
									edusiteco_default_menu();
								}
								?>
							</nav>
						</div>

						<div class="flex items-center space-x-4">
							<!-- Search Bar -->
							<div class="hidden md:block relative">
								<?php get_search_form(); ?>
							</div>

							<!-- Mobile Menu Button -->
							<div class="lg:hidden">
								<button
									class="p-2 rounded-md transition-colors <?php echo $has_header_image ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 text-text-light dark:text-text-dark'; ?>"
									id="main-mobile-menu-button">
									<img class="w-6"
										src="<?php echo get_theme_file_uri('assets/svg/menu-burger.svg') ?>"
										alt="<?php echo esc_attr_e('Menu Principal', 'edusiteco'); ?>" />
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Mobile Main Menu -->
				<div class="hidden lg:hidden border-t border-border-light dark:border-border-dark <?php echo $has_header_image ? 'bg-white/10 backdrop-blur-md' : 'bg-background-light dark:bg-background-dark'; ?>"
					id="main-mobile-menu">
					<div class="pt-0 pb-4 space-y-1">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_class' => 'space-y-1',
								'container' => false,
								'items_wrap' => '<div class="space-y-0">%3$s</div>',
								'fallback_cb' => false,
								'walker' => new Mobile_Nav_Walker()
							)
						);

						// Menú móvil por defecto
						if (!has_nav_menu('menu-1')) {
							edusiteco_default_mobile_menu();
						}
						?>

						<!-- Mobile Search -->
						<div class="p-4 md:hidden border-t border-border-light dark:border-border-dark <?php echo $has_header_image ? 'bg-white/10' : ''; ?>">
							<div class="relative">
								<?php get_search_form(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>