<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$accessibility_page  = get_page_by_path('dichiarazione-accessibilita');
$obiettivi_page      = get_page_by_path('obiettivi-accessibilita');
$whistleblowing_url  = trim((string) get_option('centro_servizi_url_whistleblowing', ''));
$footer_text         = trim((string) get_option('centro_servizi_footer_text', ''));

$legal_company_name  = trim((string) get_option('centro_servizi_legal_company_name', ''));
$legal_address       = trim((string) get_option('centro_servizi_legal_address', ''));
$legal_vat           = trim((string) get_option('centro_servizi_legal_vat', ''));
$legal_fiscal_code   = trim((string) get_option('centro_servizi_legal_fiscal_code', ''));
$legal_mecc          = trim((string) get_option('centro_servizi_legal_mecc', ''));
$legal_rea           = trim((string) get_option('centro_servizi_legal_rea', ''));
$accessibility_feedback_url = trim((string) get_option('centro_servizi_accessibility_feedback_url', ''));

$contacts_raw = centro_servizi_get_contacts();
$contacts_map = [];
foreach ($contacts_raw as $contact) {
    $type = isset($contact['type']) ? (string) $contact['type'] : '';
    $value = isset($contact['value']) ? trim((string) $contact['value']) : '';
    if ($type === '' || $value === '' || isset($contacts_map[$type])) {
        continue;
    }
    $contacts_map[$type] = $value;
}

$company_display = $legal_company_name !== '' ? $legal_company_name : get_bloginfo('name');
$legal_chunks = [];
if ($legal_vat !== '') {
    $legal_chunks[] = 'P.IVA ' . $legal_vat;
}
if ($legal_fiscal_code !== '') {
    $legal_chunks[] = 'Cod. Fiscale ' . $legal_fiscal_code;
}
if ($legal_mecc !== '') {
    $legal_chunks[] = 'Cod. Mecc. ' . $legal_mecc;
}
if ($legal_rea !== '') {
    $legal_chunks[] = 'REA ' . $legal_rea;
}

$contact_items = [];
if (isset($contacts_map['address'])) {
    $contact_items[] = [
        'label' => 'Indirizzo',
        'value' => $contacts_map['address'],
        'href'  => '',
    ];
}
if (isset($contacts_map['phone'])) {
    $contact_items[] = [
        'label' => 'Telefono',
        'value' => $contacts_map['phone'],
        'href'  => 'tel:' . preg_replace('/[^+\d]/', '', $contacts_map['phone']),
    ];
}
if (isset($contacts_map['email'])) {
    $contact_items[] = [
        'label' => 'Email',
        'value' => antispambot($contacts_map['email']),
        'href'  => 'mailto:' . antispambot($contacts_map['email']),
    ];
}
if (isset($contacts_map['pec'])) {
    $contact_items[] = [
        'label' => 'PEC',
        'value' => antispambot($contacts_map['pec']),
        'href'  => 'mailto:' . antispambot($contacts_map['pec']),
    ];
}

$feedback_url = $accessibility_feedback_url !== ''
    ? $accessibility_feedback_url
    : ($accessibility_page instanceof WP_Post
        ? get_permalink($accessibility_page)
        : home_url('/dichiarazione-accessibilita/'));
?>
<footer class="site-footer" id="footer-sito" role="contentinfo">
    <div class="site-footer__inner">
        <div class="site-footer__grid">
            <section class="site-footer__brand-block" aria-label="Identita sito">
                <p class="site-footer__eyebrow">Scuola dell'infanzia</p>
                <h2 class="site-footer__title"><?php echo esc_html($company_display); ?></h2>

                <?php if ($legal_address !== '') : ?>
                    <p class="site-footer__lead"><?php echo nl2br(esc_html($legal_address)); ?></p>
                <?php endif; ?>

                <?php if ($footer_text !== '') : ?>
                    <p class="site-footer__lead"><?php echo nl2br(esc_html($footer_text)); ?></p>
                <?php elseif (get_bloginfo('description') !== '') : ?>
                    <p class="site-footer__lead"><?php echo esc_html(get_bloginfo('description')); ?></p>
                <?php endif; ?>

                <p class="site-footer__feedback">
                    <a href="<?php echo esc_url($feedback_url); ?>" rel="noopener noreferrer" target="_blank">
                        Segnala un problema di accessibilità <span class="sr-only">(apre in nuova finestra)</span>
                    </a>
                </p>
            </section>

            <section class="site-footer__column" aria-label="Navigazione footer">
                <h3 class="site-footer__heading">Esplora</h3>
                <nav aria-label="Menu footer">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'wp_page_menu',
                        'menu_class'     => 'menu',
                    ]);
                    ?>
                </nav>
            </section>

            <section class="site-footer__column" aria-label="Contatti">
                <h3 class="site-footer__heading">Contatti</h3>
                <?php if ($contact_items !== []) : ?>
                    <ul class="site-footer__list">
                        <?php foreach ($contact_items as $contact_item) : ?>
                            <li>
                                <span class="site-footer__label"><?php echo esc_html($contact_item['label']); ?></span>
                                <?php if ($contact_item['href'] !== '') : ?>
                                    <a href="<?php echo esc_url($contact_item['href']); ?>"><?php echo esc_html($contact_item['value']); ?></a>
                                <?php else : ?>
                                    <span><?php echo esc_html($contact_item['value']); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <section class="site-footer__column" aria-label="Link legali">
                <h3 class="site-footer__heading">Trasparenza e tutela</h3>
                <ul class="site-footer__list">
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie</a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/')); ?>">Amministrazione Trasparente</a></li>
                    <?php if ($whistleblowing_url !== '') : ?>
                        <li>
                            <a href="<?php echo esc_url($whistleblowing_url); ?>" target="_blank" rel="noopener noreferrer">
                                Whistleblowing <span class="sr-only">(apre in nuova finestra)</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($accessibility_page instanceof WP_Post) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($accessibility_page)); ?>">Dichiarazione di accessibilità</a></li>
                    <?php endif; ?>
                    <?php if ($obiettivi_page instanceof WP_Post) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($obiettivi_page)); ?>">Obiettivi di accessibilità</a></li>
                    <?php endif; ?>
                </ul>
            </section>
        </div>

        <div class="site-footer__bottom">
            <div class="site-footer__meta">
                <?php if (! empty($legal_chunks)) : ?>
                    <p><?php echo esc_html(implode(' | ', $legal_chunks)); ?></p>
                <?php endif; ?>
                <p>Sito scolastico istituzionale</p>
            </div>
            <p class="site-footer__copyright">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
