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

$contact_chunks = [];
if (isset($contacts_map['address'])) {
    $contact_chunks[] = 'Indirizzo: ' . $contacts_map['address'];
}
if (isset($contacts_map['phone'])) {
    $contact_chunks[] = 'Tel: ' . $contacts_map['phone'];
}
if (isset($contacts_map['email'])) {
    $contact_chunks[] = 'Email: ' . antispambot($contacts_map['email']);
}
if (isset($contacts_map['pec'])) {
    $contact_chunks[] = 'PEC: ' . antispambot($contacts_map['pec']);
}

$feedback_url = $accessibility_feedback_url !== ''
    ? $accessibility_feedback_url
    : 'https://example.com/google-form-accessibilita';
?>
<footer class="site-footer" id="footer-sito" role="contentinfo">
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

    <p><?php echo esc_html($company_display); ?></p>
    <?php if ($legal_address !== '') : ?>
        <p><?php echo nl2br(esc_html($legal_address)); ?></p>
    <?php endif; ?>
    <?php if (! empty($legal_chunks)) : ?>
        <p><?php echo esc_html(implode(' | ', $legal_chunks)); ?></p>
    <?php endif; ?>
    <?php if (! empty($contact_chunks)) : ?>
        <p><?php echo esc_html(implode(' | ', $contact_chunks)); ?></p>
    <?php endif; ?>
    <?php if ($footer_text !== '') : ?>
        <p><?php echo nl2br(esc_html($footer_text)); ?></p>
    <?php endif; ?>

    <ul>
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
            <li><a href="<?php echo esc_url(get_permalink($accessibility_page)); ?>">Dichiarazione di Accessibilita</a></li>
        <?php endif; ?>
        <?php if ($obiettivi_page instanceof WP_Post) : ?>
            <li><a href="<?php echo esc_url(get_permalink($obiettivi_page)); ?>">Obiettivi di Accessibilita</a></li>
        <?php endif; ?>
    </ul>

    <p>
        <a href="<?php echo esc_url($feedback_url); ?>" rel="noopener noreferrer" target="_blank">
            Segnala un problema di accessibilita <span class="sr-only">(apre in nuova finestra)</span>
        </a>
    </p>
    <p>Powered by Centro Servizi</p>
    <p>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
