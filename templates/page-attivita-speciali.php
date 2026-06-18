<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');

if (have_posts()) {
    the_post();
    $attivita_speciali = get_field('attivita_speciali') ?: [];
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

				<?php if (! empty($attivita_speciali) && is_array($attivita_speciali)) : ?>
					<?php get_template_part('partials/page-sezioni-grid', null, [
						'items' => $attivita_speciali,
						'grid_class' => 'attivita-speciali-grid',
						'item_class' => 'attivita-speciali__item',
						'figure_class' => 'attivita-speciali__figure',
						'image_class' => 'attivita-speciali__img',
						'content_class' => 'attivita-speciali__content',
						'title_class' => 'attivita-speciali__titolo',
						'paragraph_class' => 'attivita-speciali__paragrafo',
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
