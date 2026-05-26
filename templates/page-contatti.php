<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

// ============================================================================
// DATI CONTATTI E MAPPA
// ============================================================================

$contacts_json = get_option('centro_servizi_contacts', '[]');
$contacts_raw  = json_decode($contacts_json, true);
$contacts_raw  = is_array($contacts_raw) ? $contacts_raw : [];

$maps_embed_url = (string) get_option('centro_servizi_maps_embed_url', '');

// Fallback: costruisci URL embed dall'indirizzo se non c'è un URL personalizzato
if ($maps_embed_url === '') {
    $address_value = '';
    foreach ($contacts_raw as $contact) {
        if (($contact['type'] ?? '') === 'address' && ! empty($contact['value'])) {
            $address_value = (string) $contact['value'];
            break;
        }
    }
    if ($address_value !== '') {
        $maps_embed_url = 'https://www.google.com/maps?q=' . rawurlencode($address_value) . '&z=15&output=embed';
    }
}

// Etichette per tipo contatto
$contact_labels = [
    'address' => 'Sede',
    'phone'   => 'Telefono',
    'email'   => 'Email',
    'pec'     => 'PEC',
    'fax'     => 'Fax',
    'website' => 'Sito web',
    'social'  => 'Social',
];

// ============================================================================
// TEMPLATE
// ============================================================================

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <div class="site-section">
        <div class="site-section__inner">
        <h1><?php the_title(); ?></h1>

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

        <?php if (! empty($contacts_raw)) : ?>
        <div class="contatti-lista">
            <h2>Recapiti</h2>
            <dl class="contatti-dl">
                <?php foreach ($contacts_raw as $contact) :
                    $type  = (string) ($contact['type']  ?? '');
                    $label = (string) ($contact['label'] ?? ($contact_labels[$type] ?? ucfirst($type)));
                    $value = (string) ($contact['value'] ?? '');
                    if ($value === '') continue;

                    // Costruisci href per i tipi cliccabili
                    $href = '';
                    if ($type === 'email' || $type === 'pec') {
                        $href = 'mailto:' . antispambot($value);
                    } elseif ($type === 'phone' || $type === 'fax') {
                        $href = 'tel:' . preg_replace('/[^+\d]/', '', $value);
                    } elseif ($type === 'website' || $type === 'social') {
                        $href = $value;
                    }
                ?>
                    <dt><?php echo esc_html($label); ?></dt>
                    <dd>
                        <?php if ($href !== '') : ?>
                            <?php if ($type === 'email' || $type === 'pec') : ?>
                                <a href="<?php echo esc_attr($href); ?>"><?php echo esc_html(antispambot($value)); ?></a>
                            <?php elseif ($type === 'website' || $type === 'social') : ?>
                                <a href="<?php echo esc_url($href); ?>" rel="noopener noreferrer" target="_blank">
                                    <?php echo esc_html($value); ?>
                                    <span class="screen-reader-text"><?php esc_html_e('(apre in nuova finestra)', 'centro-servizi'); ?></span>
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_attr($href); ?>"><?php echo esc_html($value); ?></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php echo nl2br(esc_html($value)); ?>
                        <?php endif; ?>
                    </dd>
                <?php endforeach; ?>
            </dl>
        </div>
        <?php endif; ?>

        <?php
        // Contenuto eventuale della pagina WP (es. orari, note aggiuntive)
        while (have_posts()) :
            the_post();
            $content = get_the_content();
            if (trim($content) !== '') :
        ?>
        <div class="contatti-contenuto entry-content">
            <?php the_content(); ?>
        </div>
        <?php
            endif;
        endwhile;
        ?>
        </div>
    </div>
</main>
<?php
get_template_part('partials/footer');
