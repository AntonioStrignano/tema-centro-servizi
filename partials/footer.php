<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$accessibility_page = get_page_by_path('dichiarazione-accessibilita');
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

    <p>Ragione sociale | P.IVA | Codice Meccanografico</p>
    <p>Contatti: indirizzo, telefono, email, PEC</p>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a></li>
        <li><a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie</a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('trasparenza') ?: home_url('/amministrazione-trasparente/')); ?>">Amministrazione Trasparente</a></li>
        <?php if ($accessibility_page instanceof WP_Post) : ?>
            <li><a href="<?php echo esc_url(get_permalink($accessibility_page)); ?>">Dichiarazione di Accessibilita</a></li>
        <?php endif; ?>
    </ul>
    <p><a href="https://example.com/google-form-accessibilita" rel="noopener noreferrer" target="_blank">Segnala un problema di accessibilita <span class="sr-only">(apre in nuova finestra)</span></a></p>
    <p>Powered by Centro Servizi</p>
    <p>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
