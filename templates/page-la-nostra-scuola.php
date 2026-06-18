<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');

if (have_posts()) {
    the_post();
    $la_nostra_scuola = get_field('la_nostra_scuola') ?: [];
    ?>

	<main id="contenuto-principale" class="site-main" role="main">

		<article <?php post_class('site-section'); ?>>
			<div class="site-section__inner">
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>

				<?php if (get_the_content()) : ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<?php endif; ?>

				<?php if (! empty($la_nostra_scuola) && is_array($la_nostra_scuola)) : ?>
					<?php get_template_part('partials/page-sezioni-grid', null, [
						'items' => $la_nostra_scuola,
						'grid_class' => 'la-nostra-scuola-grid',
						'item_class' => 'la-nostra-scuola__item',
						'figure_class' => 'la-nostra-scuola__figure',
						'image_class' => 'la-nostra-scuola__img',
						'content_class' => 'la-nostra-scuola__content',
						'title_class' => 'la-nostra-scuola__titolo',
						'paragraph_class' => 'la-nostra-scuola__paragrafo',
					]); ?>
				<?php endif; ?>
			</div>
		</article>

	</main>

	<?php
} else {
    get_template_part('templates/404');
}

get_template_part('partials/footer');
