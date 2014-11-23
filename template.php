<?php get_template_part( 'partials/head' ); ?>

	<body <?php body_class(); ?>>

		<!--[if lt IE 8]><div class="alert alert-warning"><?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience. <a class="close" data-dismiss="alert" href="#">&times;</a>', SIMPLE_THEME_SLUG); ?></div><![endif]-->

		<?php
			do_action('get_header');
			get_template_part( 'partials/header' );
		?>

		<main role="main" id="main">

			<?php get_template_part( 'partials/hero' ); ?>

			<?php
			// checking for custom templates
			if ( simple_display_custom_template() ) : // was !empty ?>

				<?php do_action('simple_grid_before'); ?>

					<?php do_action('simple_archives_before'); ?>

						<?php
							if ( ! is_front_page() ) :
								get_template_part( 'partials/breadcrumbs' );
							endif;
						?>

						<?php include simple_template_path(); ?>

						<?php simple_pagination( array('next_text'=>'Load More') ); ?>

					<?php do_action('simple_archives_after'); ?>

				<?php do_action('simple_grid_after'); ?>

				<?php else : ?>
					<?php include simple_template_path(); ?>
				<?php endif; ?>

			<div id="push-footer"></div><!-- sticky footer helper -->

		</main>

<?php get_template_part( 'partials/foot' ); ?>