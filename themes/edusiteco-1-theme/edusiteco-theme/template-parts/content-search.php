<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package edusiteco
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result-card mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800'); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			edusiteco_posted_on();
			edusiteco_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php edusiteco_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<a class="search-result-card__link mt-4 inline-flex items-center rounded-lg bg-brand-primary-600 px-5 py-3 font-semibold text-white transition-colors hover:bg-brand-primary-700 focus:outline-none focus:ring-2 focus:ring-brand-primary-500 focus:ring-offset-2"
		href="<?php echo esc_url( get_permalink() ); ?>">
		<?php esc_html_e( 'Abrir página', 'edusiteco' ); ?>
	</a>

	<footer class="entry-footer">
		<?php edusiteco_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
