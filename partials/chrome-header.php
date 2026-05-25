<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}
?>
<header class="site-header" id="top" role="banner">
    <div class="site-branding">
        <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>
        <p><?php bloginfo('description'); ?></p>
    </div>

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
</header>