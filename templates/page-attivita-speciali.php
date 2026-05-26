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
				<div class="attivita-speciali-grid">
					<?php foreach ($attivita_speciali as $attivita) :
						$titolo = isset($attivita['titolo']) ? (string) $attivita['titolo'] : '';
						$immagine = isset($attivita['immagine']) && is_array($attivita['immagine'])
							? $attivita['immagine']
							: null;
						$paragrafo = isset($attivita['paragrafo']) ? (string) $attivita['paragrafo'] : '';

						if ($titolo === '') {
							continue;
						}
						?>
					<div class="attivita-speciali__item">
						<?php if ($immagine !== null) : ?>
						<figure class="attivita-speciali__figure">
							<img
								src="<?php echo esc_url((string) $immagine['url']); ?>"
								alt="<?php echo esc_attr($titolo); ?>"
								class="attivita-speciali__img"
								loading="lazy"
							/>
						</figure>
						<?php endif; ?>

						<div class="attivita-speciali__content">
							<h2 class="attivita-speciali__titolo"><?php echo esc_html($titolo); ?></h2>

							<?php if ($paragrafo !== '') : ?>
							<div class="attivita-speciali__paragrafo">
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
