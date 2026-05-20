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
$trasparenza_archive_url = get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/');

$attivita_posts = new WP_Query([
    'post_type'      => 'attivita',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);

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
?>
<main class="site-main home-main home-vitrine" id="contenuto-principale" role="main">
	<section class="site-section home-vitrine__hero" aria-labelledby="home-hero-title">
		<div class="site-section__inner home-vitrine__hero-inner">
			<p class="home-vitrine__eyebrow">Scuola dell'infanzia paritaria</p>
			<h1 id="home-hero-title" class="home-vitrine__title"><?php echo esc_html($title); ?></h1>
			<p class="home-vitrine__subtitle"><?php echo esc_html($subtitle); ?></p>
			<div class="home-vitrine__cta-row">
				<a class="home-vitrine__button home-vitrine__button--primary" href="<?php echo esc_url($contatti_page_url); ?>">Prenota un colloquio</a>
				<a class="home-vitrine__button home-vitrine__button--ghost" href="<?php echo esc_url($trasparenza_archive_url); ?>">Informazioni utili</a>
			</div>
		</div>
	</section>

	<section class="site-section home-vitrine__pillars" aria-labelledby="home-pillars-title">
		<div class="site-section__inner">
			<h2 id="home-pillars-title" class="home-vitrine__section-title">Crescere bene, ogni giorno</h2>
			<div class="home-vitrine__pillars-grid">
				<article class="home-vitrine__pillar-card">
					<h3>Accoglienza</h3>
					<p>Ambienti sereni e ritmi rispettosi dei bambini, con attenzione ai bisogni di ogni famiglia.</p>
				</article>
				<article class="home-vitrine__pillar-card">
					<h3>Didattica attiva</h3>
					<p>Laboratori, gioco e scoperta per sviluppare autonomia, linguaggio, creativita e relazioni.</p>
				</article>
				<article class="home-vitrine__pillar-card">
					<h3>Inclusione</h3>
					<p>Progetti personalizzati e collaborazione educativa costante con genitori e territorio.</p>
				</article>
			</div>
		</div>
	</section>

	<?php if ($intro_content !== '') : ?>
		<section class="site-section home-vitrine__intro" aria-labelledby="home-intro-title">
			<div class="site-section__inner home-vitrine__intro-inner">
				<h2 id="home-intro-title" class="home-vitrine__section-title">Chi siamo</h2>
				<div class="entry-content home-vitrine__intro-content">
					<?php echo wp_kses_post(apply_filters('the_content', $intro_content)); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="site-section home-vitrine__attivita" aria-labelledby="home-attivita-title">
		<div class="site-section__inner">
			<div class="home-vitrine__section-head">
				<h2 id="home-attivita-title" class="home-vitrine__section-title">Attivita in evidenza</h2>
				<a class="home-vitrine__text-link" href="<?php echo esc_url(get_post_type_archive_link('attivita') ?: home_url('/attivita/')); ?>">Vedi tutte le attivita</a>
			</div>

			<div class="home-vitrine__attivita-grid">
				<?php if ($attivita_posts->have_posts()) : ?>
					<?php while ($attivita_posts->have_posts()) : $attivita_posts->the_post(); ?>
						<article class="home-vitrine__attivita-card">
							<?php if (has_post_thumbnail()) : ?>
								<div class="home-vitrine__attivita-media">
									<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr('Apri attivita: ' . get_the_title()); ?>">
										<?php the_post_thumbnail('large', ['loading' => 'lazy']); ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="home-vitrine__attivita-body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html(wp_trim_words((string) get_the_excerpt(), 22)); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<article class="home-vitrine__attivita-card home-vitrine__attivita-card--empty">
						<div class="home-vitrine__attivita-body">
							<h3>Nuove attivita in arrivo</h3>
							<p>Pubblicheremo presto i prossimi laboratori e i progetti educativi dell'anno scolastico.</p>
						</div>
					</article>
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

	<section class="site-section home-vitrine__info" aria-labelledby="home-info-title">
		<div class="site-section__inner">
			<div class="home-vitrine__section-head">
				<h2 id="home-info-title" class="home-vitrine__section-title">Informazioni pratiche</h2>
			</div>

			<div class="home-vitrine__info-grid">
				<div class="home-vitrine__info-column">
					<?php if ($orari_posts->have_posts()) : ?>
						<?php while ($orari_posts->have_posts()) : $orari_posts->the_post(); ?>
							<article class="home-vitrine__info-card">
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html(wp_trim_words((string) get_the_excerpt(), 20)); ?></p>
								<a class="home-vitrine__text-link" href="<?php the_permalink(); ?>">Leggi gli orari</a>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>

					<?php if ($calendario_posts->have_posts()) : ?>
						<?php while ($calendario_posts->have_posts()) : $calendario_posts->the_post(); ?>
							<article class="home-vitrine__info-card">
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html(wp_trim_words((string) get_the_excerpt(), 20)); ?></p>
								<a class="home-vitrine__text-link" href="<?php the_permalink(); ?>">Leggi il calendario</a>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</div>

				<aside class="home-vitrine__contact-box" aria-label="Contatti rapidi">
					<h3>Contatti rapidi</h3>
					<ul>
						<?php if ($address_value !== '') : ?>
							<li><strong>Indirizzo:</strong> <?php echo esc_html($address_value); ?></li>
						<?php endif; ?>
						<?php if ($phone_value !== '') : ?>
							<li>
								<strong>Telefono:</strong>
								<?php if ($phone_href !== '') : ?>
									<a href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_value); ?></a>
								<?php else : ?>
									<?php echo esc_html($phone_value); ?>
								<?php endif; ?>
							</li>
						<?php endif; ?>
						<?php if ($email_value !== '') : ?>
							<li>
								<strong>Email:</strong>
								<?php if ($email_href !== '') : ?>
									<a href="mailto:<?php echo esc_attr($email_href); ?>"><?php echo esc_html($email_value); ?></a>
								<?php else : ?>
									<?php echo esc_html($email_value); ?>
								<?php endif; ?>
							</li>
						<?php endif; ?>
					</ul>
					<a class="home-vitrine__button home-vitrine__button--primary" href="<?php echo esc_url($contatti_page_url); ?>">Vai alla pagina contatti</a>
				</aside>
			</div>
		</div>
	</section>

	<?php get_template_part('partials/strip-servizi'); ?>
</main>
<?php
get_footer();
