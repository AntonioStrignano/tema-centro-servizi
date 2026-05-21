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
$la_nostra_scuola_url = $la_nostra_scuola_page instanceof WP_Post
    ? get_permalink($la_nostra_scuola_page->ID)
    : home_url('/la-nostra-scuola/');
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

$custom_logo_id = (int) get_theme_mod('custom_logo');
$hero_logo_url = $custom_logo_id > 0 ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
$hero_logo_alt = $title !== '' ? $title : get_bloginfo('name');

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
	<section class="site-section home-vitrine__hero" aria-labelledby="home-hero-title" style="background-image: url('https://demo.pro06.it/wp-content/uploads/2026/04/WhatsApp-Image-2026-03-31-at-09.54.37-8.webp'); background-position: center center; background-size: cover; background-repeat: no-repeat;">
		<div class="site-section__inner home-vitrine__hero-inner">
			<?php if (is_string($hero_logo_url) && $hero_logo_url !== '') : ?>
				<p class="home-vitrine__logo-wrap">
					<img class="home-vitrine__logo" src="<?php echo esc_url($hero_logo_url); ?>" alt="<?php echo esc_attr($hero_logo_alt); ?>" loading="eager" decoding="async" />
				</p>
			<?php endif; ?>
			<h1 id="home-hero-title" class="home-vitrine__title"><?php echo esc_html($title); ?></h1>
			<p class="home-vitrine__subtitle"><?php echo esc_html($subtitle); ?></p>
			<div class="home-vitrine__cta-row">
				<a class="home-vitrine__button home-vitrine__button--primary" href="<?php echo esc_url($contatti_page_url); ?>">Prenota un colloquio</a>
				<a class="home-vitrine__button home-vitrine__button--ghost" href="<?php echo esc_url((string) $la_nostra_scuola_url); ?>">Scopri la scuola</a>
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

	<!-- Chi Siamo / Valori -->
<section class="py-section-padding-mobile md:py-section-padding-desktop bg-surface-cream">
    <div class="px-4 md:px-gutter max-w-container-max mx-auto">
        <div class="grid lg:grid-cols-2 gap-16 items-center md:grid-cols-4 justify-items-center max-w-5xl mx-auto">
            <div>
                <span class="font-label-caps text-label-caps text-tertiary mb-4 block">CHI SIAMO</span>
                <h2 class="font-headline-md text-headline-md text-primary mb-6">Educhiamo con amore, guidiamo con esperienza.</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    La nostra scuola paritaria è un luogo di crescita armoniosa dove la curiosità naturale dei bambini viene alimentata attraverso il gioco, l'esplorazione e la relazione. Crediamo in un'educazione che valorizzi l'unicità di ogni individuo in un contesto comunitario solido.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:grid-cols-4 justify-items-center max-w-5xl mx-auto">
                <div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
                    <div class="w-12 h-12 bg-secondary-container rounded-full flex items-center justify-center mx-auto mb-4 text-secondary">
                        <span class="material-symbols-outlined" data-icon="volunteer_activism">volunteer_activism</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Accoglienza</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Un clima familiare e sereno.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
                    <div class="w-12 h-12 bg-primary-fixed rounded-full flex items-center justify-center mx-auto mb-4 text-primary">
                        <span class="material-symbols-outlined" data-icon="trending_up">trending_up</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Crescita</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Sviluppo cognitivo e motorio.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl editorial-shadow text-center">
                    <div class="w-12 h-12 bg-tertiary-fixed rounded-full flex items-center justify-center mx-auto mb-4 text-tertiary">
                        <span class="material-symbols-outlined" data-icon="groups">groups</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Comunità</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Condivisione con le famiglie.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mini Gallery -->
<section class="py-section-padding-mobile md:py-section-padding-desktop">
    <div class="px-4 md:px-gutter max-w-container-max mx-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="font-label-caps text-label-caps text-secondary mb-4 block">GLI SPAZI</span>
                <h2 class="font-headline-md text-headline-md text-primary">Un ambiente su misura per i piccoli.</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-4">
                <div class="aspect-[4/5] rounded-3xl overflow-hidden">
                    <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdh5na1rpTmHcMqxT0pqpJreVVjX2XS1eSsDT7NPTYV6FYXCWHTAD25SD4ql1gj2yO8oY5Q1TtRDy3NoTJorVUeC8eraSQwzkpUC79-6ln1_Zbo2WjM0ylhQGn0e3ksBzHAdeU2hJ9lhIckBTZTC7Mo0qBLG2k9FLLb8yZK7ZqbyFJHJ-peO3oR9zlQgh1ooYlsJ7ab0UAtdayfFRSqcz2TH4shcg-IP8r64HU6IlVPicTqBYL7rkvXeRkDNWLK7wZ7jngL0XjH394" alt="Aula polifunzionale">
                </div>
                <p class="font-label-caps text-label-caps text-primary px-2">Aula polifunzionale</p>
            </div>
            <div class="space-y-4 md:mt-12">
                <div class="aspect-[4/5] rounded-3xl overflow-hidden">
                    <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHNMPLe6BN65k37pNZ9tykZJzzu88rx4kZymtD_mPGqkNc9mtps3I4PbyXYx5tkh2ZTktBf6wpT0BozIpAdm3tM5ddJxhrGNhwbKS6uLmZkGTzzVE-upi0eFp2Q7UidHrB1XBu_movCtJcSgA3_T6SFgKmVKjPrO3X0X5Q0JD-IPe-HH2xObuJ8LZT8yDp8_qHys7HEigViRe-mDuXNiwYpgjXKh5Ywia0YmbYlo7UmJZI1nCa3vXVYehnfxGcAGQuKpGZSxpb4htF" alt="Giardino esterno">
                </div>
                <p class="font-label-caps text-label-caps text-primary px-2">Giardino esterno</p>
            </div>
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
