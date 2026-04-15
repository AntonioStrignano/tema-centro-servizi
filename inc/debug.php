<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_is_bureaucratic_context(): bool
{
    return is_post_type_archive(['trasparenza', 'area-famiglie', 'area-personale'])
        || is_singular(['trasparenza', 'area-famiglie', 'area-personale'])
        || is_tax(['contenutiammtrasp', 'annoscolastico'])
        || is_page('amministrazione-trasparente');
}

function centro_servizi_get_debug_context(): array
{
    $template = centro_servizi_get_relative_theme_path(centro_servizi_get_current_template_path());
    $partial = 'partials/footer.php';
    $deploy_file = get_template_directory() . '/assets/deploy-meta.php';
    $deployed_at = 'non disponibile';

    if (file_exists($deploy_file)) {
        $timestamp = filemtime($deploy_file);

        if (is_int($timestamp) && $timestamp > 0) {
            $deployed_at = wp_date('d/m/Y H:i:s', $timestamp);
        }
    }

    return [
        'template' => $template !== '' ? $template : 'template non rilevato',
        'partial' => $partial,
        'deployed_at' => $deployed_at,
    ];
}

function centro_servizi_get_current_template_path(): string
{
    global $template;

    if (is_string($template) && $template !== '') {
        return $template;
    }

    return '';
}

function centro_servizi_get_relative_theme_path(string $path): string
{
    $theme_dir = wp_normalize_path(get_template_directory()) . '/';
    $path = wp_normalize_path($path);

    if ($path === '') {
        return '';
    }

    if (str_starts_with($path, $theme_dir)) {
        return substr($path, strlen($theme_dir));
    }

    return basename($path);
}
