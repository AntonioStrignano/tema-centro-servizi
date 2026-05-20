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
$subtitle = $homepage_subtitle !== ''
	? $homepage_subtitle
	: 'Una scuola dell\'infanzia accogliente, sicura e piena di esperienze per crescere insieme.';

$contacts_map = [];
foreach ($contacts_raw as $contact) {
	$type = isset($contact['type']) ? trim((string) $contact['type']) : '';
	$value = isset($contact['value']) ? trim((string) $contact['value']) : '';
	if ($type === '' || $value === '') {
		continue;
	}
	if (! isset($contacts_map[$type])) {
		$contacts_map[$type] = $value;
	}
}

$address_value = $contacts_map['indirizzo'] ?? $contacts_map['address'] ?? '';
$phone_value = $contacts_map['telefono'] ?? $contacts_map['phone'] ?? '';
$email_value = $contacts_map['email'] ?? '';

$phone_href = $phone_value !== '' ? preg_replace('/[^0-9\+]/', '', $phone_value) : '';
$email_href = sanitize_email($email_value);

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

$intro_content = '';
if (have_posts()) {
    while (have_posts()) {
        the_post();
        $intro_content = trim((string) get_the_content());
    }
    rewind_posts();
}

$la_nostra_scuola_excerpt = '';
$la_nostra_scuola_items = [];
if ($la_nostra_scuola_page instanceof WP_Post) {
    $la_nostra_scuola_content = get_page($la_nostra_scuola_page->ID);
    if ($la_nostra_scuola_content instanceof WP_Post) {
        $la_nostra_scuola_excerpt = trim(
            $la_nostra_scuola_content->post_excerpt !== ''
                ? $la_nostra_scuola_content->post_excerpt
                : wp_trim_words((string) $la_nostra_scuola_content->post_content, 30)
        );
    }

    $acf_items = get_field('la_nostra_scuola', $la_nostra_scuola_page->ID);
    if (is_array($acf_items)) {
        $la_nostra_scuola_items = $acf_items;
    }
}

$attivita_speciali_page = get_page_by_path('attivita-speciali');
$attivita_speciali_intro = '';
$attivita_speciali_cards = [];

if ($attivita_speciali_page instanceof WP_Post) {
	$attivita_speciali_intro = trim(
		$attivita_speciali_page->post_excerpt !== ''
			? (string) $attivita_speciali_page->post_excerpt
			: wp_trim_words((string) $attivita_speciali_page->post_content, 32)
	);

	$attivita_speciali_items = get_field('attivita_speciali', $attivita_speciali_page->ID);
	if (is_array($attivita_speciali_items)) {
		foreach (array_slice($attivita_speciali_items, 0, 4) as $item) {
			$item_title = isset($item['titolo']) ? trim((string) $item['titolo']) : '';
			$item_text = isset($item['paragrafo']) ? trim((string) $item['paragrafo']) : '';
			$item_image = isset($item['immagine']) && is_array($item['immagine']) ? $item['immagine'] : null;

			if ($item_title === '') {
				continue;
			}

			$attivita_speciali_cards[] = [
				'title' => $item_title,
				'text' => $item_text,
				'image_url' => $item_image['url'] ?? '',
				'image_alt' => $item_title,
				'url' => get_permalink($attivita_speciali_page->ID),
			];
		}
	}
}
?>
<!-- DEBUG: Homepage Font Debug -->
<?php
if (is_user_logged_in() && current_user_can('manage_options')) {
	$typo_json = get_option('centro_servizi_typography', '{}');
	$typo = json_decode($typo_json, true) ?: [];
	echo '<!-- TYPOGRAPHY SAVED: ' . esc_html(json_encode($typo, JSON_PRETTY_PRINT)) . ' -->';
}
?>

<main class="site-main home-main home-vitrine" id="contenuto-principale" role="main">
	<section class="site-section home-vitrine__hero" aria-labelledby="home-hero-title">
		<div class="site-section__inner home-vitrine__hero-inner">
			<p class="home-vitrine__eyebrow">Scuola dell'infanzia</p>
			<h1 id="home-hero-title" class="home-vitrine__title"><?php echo esc_html($title); ?></h1>
			<p class="home-vitrine__subtitle"><?php echo esc_html($subtitle); ?></p>
			<div class="home-vitrine__cta-row">
				<a class="home-vitrine__button home-vitrine__button--primary" href="<?php echo esc_url($contatti_page_url); ?>">Contattaci</a>
			</div>
		</div>
	</section>

	<section class="site-section home-vitrine__intro" aria-labelledby="home-intro-title">
		<div class="site-section__inner home-vitrine__intro-inner">
			<h2 id="home-intro-title" class="home-vitrine__section-title">Chi siamo</h2>
			<div class="entry-content home-vitrine__intro-content">
				<?php if ($intro_content !== '') : ?>
					<?php echo wp_kses_post(apply_filters('the_content', $intro_content)); ?>
				<?php else : ?>
					<p class="home-vitrine__placeholder">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer id pharetra ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur interdum, mauris id dignissim feugiat, justo erat sodales nibh, ac commodo magna est ac nibh. Suspendisse potenti. Donec molestie, lacus non congue varius, sem neque commodo sem, a scelerisque nisi neque eget orci.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ($la_nostra_scuola_page instanceof WP_Post) : ?>
		<section class="site-section home-vitrine__scuola" aria-labelledby="home-scuola-title">
			<div class="site-section__inner">
				<div class="home-vitrine__section-head">
					<h2 id="home-scuola-title" class="home-vitrine__section-title"><?php echo esc_html($la_nostra_scuola_page->post_title); ?></h2>
					<a class="home-vitrine__text-link" href="<?php echo esc_url(get_permalink($la_nostra_scuola_page->ID)); ?>">Scopri la scuola</a>
				</div>

				<?php if ($la_nostra_scuola_excerpt !== '') : ?>
					<p class="home-vitrine__scuola-excerpt"><?php echo esc_html($la_nostra_scuola_excerpt); ?></p>
				<?php endif; ?>

				<?php if (! empty($la_nostra_scuola_items)) : ?>
					<div class="home-vitrine__gallery-grid">
						<?php foreach (array_slice($la_nostra_scuola_items, 0, 4) as $index => $item) :
							$image = isset($item['immagine']) && is_array($item['immagine']) ? $item['immagine'] : null;
							if (! $image || empty($image['url'])) {
								continue;
							}
							$item_title = isset($item['titolo']) ? trim((string) $item['titolo']) : '';
							$item_title = $item_title !== '' ? $item_title : 'Ambiente educativo ' . ($index + 1);
							?>
							<figure class="home-vitrine__gallery-item">
								<img src="<?php echo esc_url((string) $image['url']); ?>" alt="<?php echo esc_attr($item_title); ?>" loading="lazy" />
								<figcaption><?php echo esc_html($item_title); ?></figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ($attivita_speciali_cards !== []) : ?>
		<section class="site-section home-vitrine__highlights" aria-labelledby="home-highlights-title">
			<div class="site-section__inner">
				<div class="home-vitrine__section-head">
					<h2 id="home-highlights-title" class="home-vitrine__section-title">Attivita in evidenza</h2>
					<?php if ($attivita_speciali_page instanceof WP_Post) : ?>
						<a class="home-vitrine__text-link" href="<?php echo esc_url(get_permalink($attivita_speciali_page->ID)); ?>">Vedi tutte le attivita speciali</a>
					<?php endif; ?>
				</div>

				<?php if ($attivita_speciali_intro !== '') : ?>
					<p class="home-vitrine__highlights-intro"><?php echo esc_html($attivita_speciali_intro); ?></p>
				<?php endif; ?>

				<div class="home-vitrine__highlights-grid">
					<?php foreach ($attivita_speciali_cards as $card) : ?>
						<article class="home-vitrine__highlight-card">
							<?php if ($card['image_url'] !== '') : ?>
								<img
									class="home-vitrine__highlight-image"
									src="<?php echo esc_url((string) $card['image_url']); ?>"
									alt="<?php echo esc_attr((string) $card['image_alt']); ?>"
									loading="lazy"
								/>
							<?php endif; ?>

							<div class="home-vitrine__highlight-content">
								<h3><?php echo esc_html((string) $card['title']); ?></h3>
								<?php if ((string) $card['text'] !== '') : ?>
									<p><?php echo esc_html(wp_trim_words((string) $card['text'], 24)); ?></p>
								<?php endif; ?>
								<a class="home-vitrine__text-link" href="<?php echo esc_url((string) $card['url']); ?>">Approfondisci</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="site-section home-vitrine__calendar" aria-labelledby="home-calendar-title">
		<div class="site-section__inner">
			<div class="home-vitrine__section-head">
				<h2 id="home-calendar-title" class="home-vitrine__section-title">Orari e calendario</h2>
			</div>

			<div class="home-vitrine__calendar-grid">
				<?php if ($orari_posts->have_posts()) : ?>
					<?php while ($orari_posts->have_posts()) : $orari_posts->the_post(); ?>
						<article class="home-vitrine__calendar-card">
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html(wp_trim_words((string) get_the_excerpt(), 24)); ?></p>
							<p class="home-vitrine__calendar-meta">
								Pubblicato: <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
								| Aggiornato: <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('d/m/Y')); ?></time>
							</p>
							<a class="home-vitrine__text-link" href="<?php the_permalink(); ?>">Leggi gli orari</a>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<article class="home-vitrine__calendar-card">
						<h3>Orari</h3>
						<p>Gli orari verranno pubblicati a breve.</p>
					</article>
				<?php endif; ?>

				<?php if ($calendario_posts->have_posts()) : ?>
					<?php while ($calendario_posts->have_posts()) : $calendario_posts->the_post(); ?>
						<article class="home-vitrine__calendar-card">
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html(wp_trim_words((string) get_the_excerpt(), 24)); ?></p>
							<p class="home-vitrine__calendar-meta">
								Pubblicato: <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
								| Aggiornato: <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('d/m/Y')); ?></time>
							</p>
							<a class="home-vitrine__text-link" href="<?php the_permalink(); ?>">Leggi il calendario</a>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<article class="home-vitrine__calendar-card">
						<h3>Calendario</h3>
						<p>Il calendario scolastico verra aggiornato a breve.</p>
					</article>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="site-section home-vitrine__contacts" aria-labelledby="home-contacts-title">
		<div class="site-section__inner">
			<div class="home-vitrine__section-head">
				<h2 id="home-contacts-title" class="home-vitrine__section-title">Contatti</h2>
			</div>

			<div class="home-vitrine__contacts-grid">
				<?php if ($address_value !== '') : ?>
					<article class="home-vitrine__contact-card">
						<h3>Indirizzo</h3>
						<p><?php echo esc_html($address_value); ?></p>
					</article>
				<?php endif; ?>

				<?php if ($phone_value !== '') : ?>
					<article class="home-vitrine__contact-card">
						<h3>Telefono</h3>
						<p>
							<?php if ($phone_href !== '') : ?>
								<a href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_value); ?></a>
							<?php else : ?>
								<?php echo esc_html($phone_value); ?>
							<?php endif; ?>
						</p>
					</article>
				<?php endif; ?>

				<?php if ($email_value !== '') : ?>
					<article class="home-vitrine__contact-card">
						<h3>Email</h3>
						<p>
							<?php if ($email_href !== '') : ?>
								<a href="mailto:<?php echo esc_attr($email_href); ?>"><?php echo esc_html($email_value); ?></a>
							<?php else : ?>
								<?php echo esc_html($email_value); ?>
							<?php endif; ?>
						</p>
					</article>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php get_template_part('partials/strip-servizi'); ?>
</main>
<?php
get_footer();
