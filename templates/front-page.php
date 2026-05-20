<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$homepage_title = trim((string) get_option('centro_servizi_homepage_title', ''));
$homepage_subtitle = trim((string) get_option('centro_servizi_homepage_subtitle', ''));
$contacts_json = (string) get_option('centro_servizi_contacts', '[]');
$contacts_raw = json_decode($contacts_json, true);
$contacts_raw = is_array($contacts_raw) ? $contacts_raw : [];

$title = $homepage_title !== '' ? $homepage_title : get_bloginfo('name');
$subtitle = $homepage_subtitle;

$la_nostra_scuola_page = get_page_by_path('la-nostra-scuola');
$orari_posts = new WP_Query([
    'post_type'      => 'trasparenza',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'contenutiammtrasp',
            'field'    => 'slug',
            'terms'    => 'orari-funz',
            'operator' => 'IN',
        ],
    ],
    'no_found_rows'  => true,
]);

$calendario_posts = new WP_Query([
    'post_type'      => 'trasparenza',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'contenutiammtrasp',
            'field'    => 'slug',
            'terms'    => 'calendario',
            'operator' => 'IN',
        ],
    ],
    'no_found_rows'  => true,
]);

$contatti_page_url = home_url('/contatti/');
$trasparenza_archive_url = get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/');
?>
<main class="site-main home-main" id="contenuto-principale" role="main">

	<!-- ─── 1. HERO ─── -->
	<section class="site-section home-hero" aria-labelledby="home-hero-title">
		<div class="home-hero__inner">
			<h1 id="home-hero-title" class="home-hero__title"><?php echo esc_html($title); ?></h1>

			<?php if ($subtitle !== '') : ?>
				<p class="home-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
			<?php endif; ?>

			<div class="home-hero__actions">
				<a class="home-button" href="<?php echo esc_url($contatti_page_url); ?>">Contattaci</a>
			</div>
		</div>
		<div class="home-shape home-shape--bottom" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
				<path d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"/>
			</svg>
		</div>
	</section>

	<!-- ─── INTRO CONTENT ─── -->
	<?php while (have_posts()) : the_post(); ?>
		<?php $content = trim((string) get_the_content()); ?>
		<?php if ($content !== '') : ?>
			<section class="site-section home-intro" aria-labelledby="home-intro-title">
				<h2 id="home-intro-title">Chi siamo</h2>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>

	<!-- ─── 2. LA NOSTRA SCUOLA ─── -->
	<?php if ($la_nostra_scuola_page) : ?>
		<section class="site-section home-la-nostra-scuola" aria-labelledby="home-lns-title">
			<div class="home-la-nostra-scuola__inner">
				<h2 id="home-lns-title"><?php echo esc_html($la_nostra_scuola_page->post_title); ?></h2>

				<?php
				$lns_content = get_page($la_nostra_scuola_page->ID);
				$lns_excerpt = trim($lns_content->post_excerpt !== '' ? $lns_content->post_excerpt : wp_trim_words($lns_content->post_content, 30));
				?>

				<?php if ($lns_excerpt !== '') : ?>
					<p class="home-la-nostra-scuola__excerpt"><?php echo esc_html($lns_excerpt); ?></p>
				<?php endif; ?>

				<?php
				$lns_items = get_field('la_nostra_scuola', $la_nostra_scuola_page->ID) ?: [];
				if (! empty($lns_items) && is_array($lns_items)) : ?>
					<div class="home-la-nostra-scuola__gallery">
						<?php foreach (array_slice($lns_items, 0, 3) as $index => $item) :
							$immagine = isset($item['immagine']) && is_array($item['immagine'])
								? $item['immagine']
								: null;
							if ($immagine === null) {
								continue;
							}
							$titolo = isset($item['titolo']) ? (string) $item['titolo'] : 'Item ' . ($index + 1);
							?>
						<figure class="home-la-nostra-scuola__item">
							<img
								src="<?php echo esc_url((string) $immagine['url']); ?>"
								alt="<?php echo esc_attr($titolo); ?>"
								loading="lazy"
							/>
							<figcaption><?php echo esc_html($titolo); ?></figcaption>
						</figure>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="home-la-nostra-scuola__actions">
					<a href="<?php echo esc_url(get_permalink($la_nostra_scuola_page->ID)); ?>" class="home-button">
						Scopri di più
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- ─── 3. CONTATTI RAPIDI ─── -->
	<section class="site-section home-contacts" aria-labelledby="home-contacts-title">
		<div class="home-shape home-shape--top" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" height="100%" viewBox="0 0 283.5 27.8" preserveAspectRatio="xMidYMax slice">
				<path d="M0 0v1.4c.6.7 1.1 1.4 1.4 2 2 3.8 2.2 6.6 1.8 10.8-.3 3.3-2.4 9.4 0 12.3 1.7 2 3.7 1.4 4.6-.9 1.4-3.8-.7-8.2-.6-12 .1-3.7 3.2-5.5 6.9-4.9 4 .6 4.8 4 4.9 7.4.1 1.8-1.1 7 0 8.5.6.8 1.6 1.2 2.4.5 1.4-1.1.1-5.4.1-6.9.1-3.7.3-8.6 4.1-10.5 5-2.5 6.2 1.6 5.4 5.6-.4 1.7-1 9.2 2.9 6.3 1.5-1.1.7-3.5.5-4.9-.4-2.4-.4-4.3 1-6.5.9-1.4 2.4-3.1 4.2-3 2.4.1 2.7 2.2 4 3.7 1.5 1.8 1.8 2.2 3 .1 1.1-1.9 1.2-2.8 3.6-3.3 1.3-.3 4.8-1.4 5.9-.5 1.5 1.1.6 2.8.4 4.3-.2 1.1-.6 4 1.8 3.4 1.7-.4-.3-4.1.6-5.6 1.3-2.2 5.8-1.4 7 .5 1.3 2.1.5 5.8.1 8.1s-1.2 5-.6 7.4c1.3 5.1 4.4.9 4.3-2.4-.1-4.4-2-8.8-.5-13 .9-2.4 4.6-6.6 7.7-4.5 2.7 1.8.5 7.8.2 10.3-.2 1.7-.8 4.6.2 6.2.9 1.4 2 1.5 2.6-.3.5-1.5-.9-4.5-1-6.1-.2-1.7-.4-3.7.2-5.4 1.8-5.6 3.5 2.4 6.3.6 1.4-.9 4.3-9.4 6.1-3.1.6 2.2-1.3 7.8.7 8.9 4.2 2.3 1.5-7.1 2.2-8 3.1-4 4.7 3.8 6.1 4.1 3.1.7 2.8-7.9 8.1-4.5 1.7 1.1 2.9 3.3 3.2 5.2.4 2.2-1 4.5-.6 6.6 1 4.3 4.4 1.5 4.4-1.7 0-2.7-3-8.3 1.4-9.1 4.4-.9 7.3 3.5 7.8 6.9.3 2-1.5 10.9 1.3 11.3 4.1.6-3.2-15.7 4.8-15.8 4.7-.1 2.8 4.1 3.9 6.6 1 2.4 2.1 1 2.3-.8.3-1.9-.9-3.2 1.3-4.3 5.9-2.9 5.9 5.4 5.5 8.5-.3 2-1.7 8.4 2 8.1 6.9-.5-2.8-16.9 4.8-18.7 4.7-1.2 6.1 3.6 6.3 7.1.1 1.7-1.2 8.1.6 9.1 3.5 2 1.9-7 2-8.4.2-4 1.2-9.6 6.4-9.8 4.7-.2 3.2 4.6 2.7 7.5-.4 2.2 1.3 8.6 3.8 4.4 1.1-1.9-.3-4.1-.3-6 0-1.7.4-3.2 1.3-4.6 1-1.6 2.9-3.5 5.1-2.9 2.5.6 2.3 4.1 4.1 4.9 1.9.8 1.6-.9 2.3-2.1 1.2-2.1 2.1-2.1 4.4-2.4 1.4-.2 3.6-1.5 4.9-.5 2.3 1.7-.7 4.4.1 6.5.6 1.5 2.1 1.7 2.8.3.7-1.4-1.1-3.4-.3-4.8 1.4-2.5 6.2-1.2 7.2 1 2.3 4.8-3.3 12-.2 16.3 3 4.1 3.9-2.8 3.8-4.8-.4-4.3-2.1-8.9 0-13.1 1.3-2.5 5.9-5.7 7.9-2.4 2 3.2-1.3 9.8-.8 13.4.5 4.4 3.5 3.3 2.7-.8-.4-1.9-2.4-10 .6-11.1 3.7-1.4 2.8 7.2 6.5.4 2.2-4.1 4.9-3.1 5.2 1.2.1 1.5-.6 3.1-.4 4.6.2 1.9 1.8 3.7 3.3 1.3 1-1.6-2.6-10.4 2.9-7.3 2.6 1.5 1.6 6.5 4.8 2.7 1.3-1.5 1.7-3.6 4-3.7 2.2-.1 4 2.3 4.8 4.1 1.3 2.9-1.5 8.4.9 10.3 4.2 3.3 3-5.5 2.7-6.9-.6-3.9 1-7.2 5.5-5 4.1 2.1 4.3 7.7 4.1 11.6 0 .8-.6 9.5 2.5 5.2 1.2-1.7-.1-7.7.1-9.6.3-2.9 1.2-5.5 4.3-6.2 4.5-1 7.7 1.5 7.4 5.8-.2 3.5-1.8 7.7-.5 11.1 1 2.7 3.6 2.8 5 .2 1.6-3.1 0-8.3-.4-11.6-.4-4.2-.2-7 1.8-10.8 0 0-.1.1-.1.2-.2.4-.3.7-.4.8v.1c-.1.2-.1.2 0 0v-.1l.4-.8c0-.1.1-.1.1-.2.2-.4.5-.8.8-1.2V0H0zM282.7 3.4z"/>
			</svg>
		</div>

		<h2 id="home-contacts-title">Contattaci</h2>

		<div class="home-contacts__grid">
			<?php
			$contact_types = [
				'indirizzo'  => 'Indirizzo',
				'telefono'   => 'Telefono',
				'email'      => 'Email',
			];

			foreach ($contact_types as $type => $label) :
				$value = '';
				foreach ($contacts_raw as $contact) {
					if (isset($contact['type']) && $contact['type'] === $type) {
						$value = trim((string) $contact['value']);
						break;
					}
				}

				if ($value === '') {
					continue;
				}
				?>
				<article class="home-contact-card">
					<h3 class="home-contact-card__title"><?php echo esc_html($label); ?></h3>
					<p class="home-contact-card__value">
						<?php
						if ($type === 'email') {
							?><a href="mailto:<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></a><?php
						} elseif ($type === 'telefono') {
							?><a href="tel:<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></a><?php
						} else {
							echo esc_html($value);
						}
						?>
					</p>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="home-contacts__actions">
			<a href="<?php echo esc_url($contatti_page_url); ?>" class="home-button">Vai alla pagina contatti</a>
		</div>

		<div class="home-shape home-shape--bottom" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
				<path d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"/>
			</svg>
		</div>
	</section>

	<!-- ─── 4. ORARI & CALENDARIO ─── -->
	<section class="site-section home-orari-calendario" aria-labelledby="home-orari-title">
		<h2 id="home-orari-title">Orari & Calendario</h2>

		<div class="home-orari-calendario__grid">
			<!-- ORARI -->
			<?php if ($orari_posts->have_posts()) : ?>
				<?php while ($orari_posts->have_posts()) : $orari_posts->the_post(); ?>
					<article class="home-orari-calendario__card">
						<h3><?php the_title(); ?></h3>
						<div class="home-orari-calendario__excerpt">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="home-link">Visualizza</a>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<!-- CALENDARIO -->
			<?php if ($calendario_posts->have_posts()) : ?>
				<?php while ($calendario_posts->have_posts()) : $calendario_posts->the_post(); ?>
					<article class="home-orari-calendario__card">
						<h3><?php the_title(); ?></h3>
						<div class="home-orari-calendario__excerpt">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="home-link">Visualizza</a>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>

		<div class="home-orari-calendario__actions">
			<a href="<?php echo esc_url($trasparenza_archive_url); ?>" class="home-button">Amministrazione trasparente</a>
		</div>
	</section>

	<!-- ─── 5. STRIP SERVIZI ─── -->
	<?php get_template_part('partials/strip-servizi'); ?>

</main>
<?php
get_footer();
