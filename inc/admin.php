<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('admin_notices', 'centro_servizi_maybe_show_acf_notice');

function centro_servizi_maybe_show_acf_notice(): void
{
    if (function_exists('get_field')) {
        return;
    }

    if (! current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="notice notice-warning">
        <p>Il tema Centro Servizi funziona anche senza ACF, ma i campi personalizzati saranno disattivati finche il plugin ACF Free non viene attivato.</p>
    </div>
    <?php
}
