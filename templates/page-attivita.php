<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');

$sections = function_exists('centro_servizi_get_attivita_sections')
    ? centro_servizi_get_attivita_sections()
    : [];
?>
<main class="site-main page-basic page-attivita" id="contenuto-principale" role="main">
    <section class="site-section page-basic__section page-attivita__section">
        <div class="site-section__inner page-basic__inner page-attivita__inner">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-basic__article page-attivita__article'); ?>>
                    <header class="page-basic__header page-attivita__header">
                        <h1 class="page-basic__title page-attivita__title"><?php the_title(); ?></h1>
                    </header>

                    <div class="entry-content page-basic__content page-attivita__content">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($sections !== []) : ?>
                        <div class="page-attivita__sections">
                            <?php foreach ($sections as $section_index => $section) : ?>
                                <?php
                                $section_title = isset($section['titolo']) ? trim((string) $section['titolo']) : '';
                                $section_caption = isset($section['didascalia']) ? trim((string) $section['didascalia']) : '';
                                $section_images = isset($section['immagini']) && is_array($section['immagini']) ? $section['immagini'] : [];

                                if ($section_title === '' || $section_images === []) {
                                    continue;
                                }
                                ?>
                                <section class="page-attivita__block">
                                    <header class="page-attivita__block-header">
                                        <h2 class="page-attivita__block-title"><?php echo esc_html($section_title); ?></h2>
                                    </header>

                                    <div class="page-attivita__gallery" role="list" aria-label="Galleria <?php echo esc_attr($section_title); ?>">
                                        <?php foreach ($section_images as $index => $image) : ?>
                                            <?php
                                            $attachment_id = 0;
                                            if (is_array($image)) {
                                                $attachment_id = (int) ($image['ID'] ?? $image['id'] ?? 0);
                                            } else {
                                                $attachment_id = absint($image);
                                            }

                                            if ($attachment_id <= 0) {
                                                continue;
                                            }

                                            $alt_text = function_exists('centro_servizi_get_attivita_image_alt')
                                                ? centro_servizi_get_attivita_image_alt($image, $section_title, (int) $index)
                                                : 'bambini che fanno attività scolastica: ' . $section_title;
                                            $full_image_url = wp_get_attachment_image_url($attachment_id, 'full');
                                            $link_title = $section_title;
                                            ?>
                                            <figure class="page-attivita__figure" role="listitem">
                                                <?php if (is_string($full_image_url) && $full_image_url !== '') : ?>
                                                    <a
                                                        class="page-attivita__lightbox thickbox"
                                                        href="<?php echo esc_url($full_image_url); ?>"
                                                        title="<?php echo esc_attr($link_title); ?>"
                                                        rel="attivita-gallery-<?php echo esc_attr((string) $section_index); ?>"
                                                    >
                                                        <?php echo wp_get_attachment_image($attachment_id, 'gallery-medium', false, [
                                                            'class' => 'page-attivita__image',
                                                            'alt' => $alt_text,
                                                            'loading' => 'lazy',
                                                        ]); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php echo wp_get_attachment_image($attachment_id, 'gallery-medium', false, [
                                                        'class' => 'page-attivita__image',
                                                        'alt' => $alt_text,
                                                        'loading' => 'lazy',
                                                    ]); ?>
                                                <?php endif; ?>
                                            </figure>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_template_part('partials/footer');
