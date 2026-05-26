<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$contacts = function_exists('centro_servizi_get_homepage_contacts')
    ? centro_servizi_get_homepage_contacts()
    : [];

$maps_embed_url = function_exists('centro_servizi_get_homepage_map_embed_url')
    ? centro_servizi_get_homepage_map_embed_url()
    : '';

$page_id = get_queried_object_id();
$page_title = $page_id > 0 ? get_the_title($page_id) : (string) __('Contatti', 'centro-servizi');
$page_content = $page_id > 0 ? (string) get_post_field('post_content', $page_id) : '';

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <div class="site-section">
        <div class="site-section__inner">
        <h1><?php echo esc_html($page_title); ?></h1>

        <?php if ($maps_embed_url !== '') : ?>
        <div class="contatti-mappa">
            <iframe
                src="<?php echo esc_url($maps_embed_url); ?>"
                width="100%"
                height="400"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="<?php esc_attr_e('Mappa sede', 'centro-servizi'); ?>"
            ></iframe>
        </div>
        <?php endif; ?>

        <?php if ($contacts !== []) : ?>
        <div class="contatti-lista">
            <h2>Recapiti</h2>
            <dl class="contatti-dl">
                <?php foreach ($contacts as $contact) :
                    if (! is_array($contact)) {
                        continue;
                    }

                    $type  = trim((string) ($contact['type'] ?? ''));
                    $label = trim((string) ($contact['label'] ?? ''));
                    $value = trim((string) ($contact['value'] ?? ''));
                    $href  = trim((string) ($contact['href'] ?? ''));

                    if ($value === '') {
                        continue;
                    }

                    if ($label === '') {
                        $label = ucfirst($type !== '' ? $type : 'Contatto');
                    }

                    $is_external = ! empty($contact['external']);
                    $display_value = ($type === 'email' || $type === 'pec')
                        ? antispambot($value)
                        : $value;
                ?>
                    <dt><?php echo esc_html($label); ?></dt>
                    <dd>
                        <?php if ($href !== '') : ?>
                            <a href="<?php echo esc_url($href); ?>"<?php echo $is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo esc_html($display_value); ?>
                                <?php if ($is_external) : ?><span class="sr-only"><?php esc_html_e('(apre in nuova finestra)', 'centro-servizi'); ?></span><?php endif; ?>
                            </a>
                        <?php else : ?>
                            <?php echo nl2br(esc_html($display_value)); ?>
                        <?php endif; ?>
                    </dd>
                <?php endforeach; ?>
            </dl>
        </div>
        <?php endif; ?>

        <?php if (trim($page_content) !== '') : ?>
        <div class="contatti-contenuto entry-content">
            <?php echo apply_filters('the_content', $page_content); ?>
        </div>
        <?php endif; ?>
        </div>
    </div>
</main>
<?php
get_template_part('partials/footer');
