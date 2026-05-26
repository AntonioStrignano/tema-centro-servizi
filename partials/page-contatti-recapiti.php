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

if ($contacts === [] && $maps_embed_url === '') {
    return;
}
?>
<section class="contatti-recapiti" aria-labelledby="contatti-recapiti-title">
    <h2 id="contatti-recapiti-title" class="contatti-recapiti__title"><?php esc_html_e('Recapiti', 'centro-servizi'); ?></h2>

    <div class="contatti-recapiti__panel">
        <?php if ($contacts !== []) : ?>
        <dl class="contatti-recapiti__list">
            <?php foreach ($contacts as $contact) :
                if (! is_array($contact)) {
                    continue;
                }

                $label = trim((string) ($contact['label'] ?? ''));
                $type = trim((string) ($contact['type'] ?? ''));
                $value = trim((string) ($contact['value'] ?? ''));
                $href = trim((string) ($contact['href'] ?? ''));

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
                <div class="contatti-recapiti__row">
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
                </div>
            <?php endforeach; ?>
        </dl>
        <?php endif; ?>

        <?php if ($maps_embed_url !== '') : ?>
        <div class="contatti-recapiti__map" aria-label="<?php esc_attr_e('Mappa sede', 'centro-servizi'); ?>">
            <iframe
                src="<?php echo esc_url($maps_embed_url); ?>"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
                title="<?php esc_attr_e('Mappa sede', 'centro-servizi'); ?>"
            ></iframe>
        </div>
        <?php endif; ?>
    </div>
</section>
