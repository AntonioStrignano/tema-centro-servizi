<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}
?>
<ul class="skip-links">
    <?php foreach (centro_servizi_get_skip_links() as $target => $label) : ?>
        <li>
            <a href="<?php echo esc_url($target); ?>"><?php echo esc_html($label); ?></a>
        </li>
    <?php endforeach; ?>
</ul>
