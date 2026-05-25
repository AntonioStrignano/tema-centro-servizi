<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$brand_name = (string) get_bloginfo('name');
$brand_description = trim((string) get_bloginfo('description'));

$phone_contact = function_exists('centro_servizi_get_contact_by_type')
    ? centro_servizi_get_contact_by_type('phone')
    : null;

$email_contact = function_exists('centro_servizi_get_contact_by_type')
    ? centro_servizi_get_contact_by_type('email')
    : null;

if (! is_array($email_contact) || $email_contact === []) {
    $email_contact = function_exists('centro_servizi_get_contact_by_type')
        ? centro_servizi_get_contact_by_type('pec')
        : null;
}

$phone_value = is_array($phone_contact) ? trim((string) ($phone_contact['value'] ?? '')) : '';
$phone_href = $phone_value !== '' ? 'tel:' . preg_replace('/[^+\d]/', '', $phone_value) : '';

$email_value = is_array($email_contact) ? trim((string) ($email_contact['value'] ?? '')) : '';
$email_href = $email_value !== '' ? 'mailto:' . antispambot($email_value) : '';

$contacts_page = get_page_by_path('contatti');
$contact_cta_url = '';

if ($contacts_page instanceof WP_Post) {
    $contact_cta_url = (string) get_permalink($contacts_page);
} elseif ($email_href !== '') {
    $contact_cta_url = $email_href;
} elseif ($phone_href !== '') {
    $contact_cta_url = $phone_href;
}

$initials = '';
$brand_tokens = preg_split('/\s+/', wp_strip_all_tags($brand_name)) ?: [];

foreach ($brand_tokens as $brand_token) {
    $brand_token = trim((string) $brand_token);

    if ($brand_token === '') {
        continue;
    }

    $initial = function_exists('mb_substr') ? mb_substr($brand_token, 0, 1) : substr($brand_token, 0, 1);
    $initials .= function_exists('mb_strtoupper') ? mb_strtoupper($initial) : strtoupper($initial);

    if (strlen($initials) >= 2) {
        break;
    }
}

if ($initials === '') {
    $initials = 'CS';
}
?>
<header class="site-header" id="top" role="banner">
    <div class="site-header__top">
        <div class="site-header__brand">
            <div class="site-header__logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a class="site-header__logo-mark" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($brand_name); ?>">
                        <span aria-hidden="true"><?php echo esc_html($initials); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="site-branding">
                <p class="site-branding__overline">Portale servizi scolastici</p>
                <p class="site-branding__title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($brand_name); ?></a></p>
                <?php if ($brand_description !== '') : ?>
                    <p class="site-branding__description"><?php echo esc_html($brand_description); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="site-header__utility" aria-label="Contatti rapidi">
            <?php if ($phone_href !== '') : ?>
                <a class="site-header__utility-link" href="<?php echo esc_url($phone_href); ?>">
                    <span class="site-header__utility-label">Telefono</span>
                    <span class="site-header__utility-value"><?php echo esc_html($phone_value); ?></span>
                </a>
            <?php endif; ?>

            <?php if ($email_href !== '') : ?>
                <a class="site-header__utility-link" href="<?php echo esc_url($email_href); ?>">
                    <span class="site-header__utility-label">Email</span>
                    <span class="site-header__utility-value"><?php echo esc_html(antispambot($email_value)); ?></span>
                </a>
            <?php endif; ?>

            <?php if ($contact_cta_url !== '') : ?>
                <a class="site-header__cta" href="<?php echo esc_url($contact_cta_url); ?>">
                    Contatta la segreteria
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="site-header__bottom">
        <nav class="site-navigation" id="navigazione-principale" role="navigation" aria-label="Menu principale">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => 'wp_page_menu',
                'menu_class'     => 'menu',
            ]);
            ?>
        </nav>

        <div class="site-search">
            <?php get_search_form(); ?>
        </div>
    </div>
</header>