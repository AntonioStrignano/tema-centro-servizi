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
$contact_cta_url = $contacts_page instanceof WP_Post
    ? (string) get_permalink($contacts_page)
    : (string) home_url('/contatti/');

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

$render_brand_mark = static function () use ($brand_name, $initials): void {
    if (has_custom_logo()) {
        the_custom_logo();
        return;
    }

    ?>
    <a class="site-header__logo-mark" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($brand_name); ?>">
        <span aria-hidden="true"><?php echo esc_html($initials); ?></span>
    </a>
    <?php
};
?>
<header class="site-header" id="top" role="banner">
    <div class="site-header__top">
        <div class="site-header__top-inner">
            <div class="site-header__utility" aria-label="Contatti rapidi">
                <?php if ($phone_href !== '') : ?>
                    <a class="site-header__utility-link" href="<?php echo esc_url($phone_href); ?>">
                        <span class="site-header__utility-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M6.6 10.8c1.4 2.8 3.7 5.1 6.6 6.6l2.2-2.2c0.3-0.3 0.7-0.4 1-0.3 1.1 0.4 2.3 0.6 3.6 0.6 0.6 0 1 0.4 1 1V20c0 0.6-0.4 1-1 1C10.6 21 3 13.4 3 4c0-0.6 0.4-1 1-1h3.5c0.6 0 1 0.4 1 1 0 1.2 0.2 2.4 0.6 3.6 0.1 0.3 0 0.7-0.3 1L6.6 10.8z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="site-header__utility-value"><?php echo esc_html($phone_value); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ($email_href !== '') : ?>
                    <a class="site-header__utility-link" href="<?php echo esc_url($email_href); ?>">
                        <span class="site-header__utility-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M4 6h16c1.1 0 2 0.9 2 2v8c0 1.1-0.9 2-2 2H4c-1.1 0-2-0.9-2-2V8c0-1.1 0.9-2 2-2zm0 2v0.4l8 4.8 8-4.8V8H4zm16 8V10.7l-7.5 4.5c-0.3 0.2-0.7 0.2-1 0L4 10.7V16h16z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="site-header__utility-value"><?php echo esc_html(antispambot($email_value)); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="site-header__main">
        <div class="site-header__main-inner">
            <button class="site-header__menu-toggle" type="button" aria-controls="site-header-navigation-panel" aria-expanded="false" data-site-header-toggle>
                <span class="site-header__menu-toggle-icon" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <span class="site-header__menu-toggle-label">Menu</span>
            </button>

            <div class="site-header__brand">
                <div class="site-header__logo">
                    <?php $render_brand_mark(); ?>
                </div>

                <div class="site-branding">
                    <p class="site-branding__title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($brand_name); ?></a></p>
                    <?php if ($brand_description !== '') : ?>
                        <p class="site-branding__description"><?php echo esc_html($brand_description); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="site-header__panel" id="site-header-navigation-panel" data-site-header-panel>
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

                <?php get_template_part('partials/search-form', null, ['context' => 'header', 'show_filters' => false, 'form_id_prefix' => 'header-search']); ?>
            </div>

            <div class="site-header__actions">
                <?php if ($contact_cta_url !== '') : ?>
                    <a class="site-header__cta" href="<?php echo esc_url($contact_cta_url); ?>">
                        Contattaci
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>