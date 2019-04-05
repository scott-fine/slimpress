<?php get_header(); ?>

	<main>
		<!-- section -->
		<section class="middle">

			<h1 class="title">
				<?php echo bloginfo('name'); ?>
			</h1>
			<h1 class="tagline"><?php bloginfo('description'); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</section>
	</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
