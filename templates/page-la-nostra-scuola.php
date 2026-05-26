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
				<div class="la-nostra-scuola-grid">
					<?php foreach ($la_nostra_scuola as $sezione) :
						$titolo = isset($sezione['titolo']) ? (string) $sezione['titolo'] : '';
						$immagine = isset($sezione['immagine']) && is_array($sezione['immagine'])
							? $sezione['immagine']
							: null;
						$paragrafo = isset($sezione['paragrafo']) ? (string) $sezione['paragrafo'] : '';

						if ($titolo === '') {
							continue;
						}
						?>
					<div class="la-nostra-scuola__item">
						<?php if ($immagine !== null) : ?>
						<figure class="la-nostra-scuola__figure">
							<img
								src="<?php echo esc_url((string) $immagine['url']); ?>"
								alt="<?php echo esc_attr($titolo); ?>"
								class="la-nostra-scuola__img"
								loading="lazy"
							/>
						</figure>
						<?php endif; ?>

						<div class="la-nostra-scuola__content">
							<h2 class="la-nostra-scuola__titolo"><?php echo esc_html($titolo); ?></h2>

							<?php if ($paragrafo !== '') : ?>
							<div class="la-nostra-scuola__paragrafo">
								<?php echo wp_kses_post(wpautop($paragrafo)); ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<?php
					endforeach;
					?>
				</div>
				<?php endif; ?>
			</div>
		</article>

	</main>

	<?php
} else {
    get_template_part('templates/404');
}

get_template_part('partials/footer');
