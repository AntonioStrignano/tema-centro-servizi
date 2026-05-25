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
        || is_page('amministrazione-trasparente')
        || centro_servizi_is_legal_page_context();
}

function centro_servizi_is_legal_page_context(): bool
{
    if (! is_page()) {
        return false;
    }

    if (function_exists('centro_servizi_get_legal_page_slugs')) {
        $slug = (string) get_post_field('post_name', get_queried_object_id());
        return in_array($slug, centro_servizi_get_legal_page_slugs(), true);
    }

    return is_page([
        'privacy-policy',
        'cookie-policy',
        'dichiarazione-accessibilita',
        'whistleblowing',
        'obiettivi-accessibilita',
        'amministrazione-trasparente',
        'la-nostra-scuola',
    ]);
}

function centro_servizi_get_asset_version(string $absolute_path): string
{
    $timestamp = file_exists($absolute_path) ? filemtime($absolute_path) : false;

    if (is_int($timestamp) && $timestamp > 0) {
        return (string) $timestamp;
    }

    return (string) wp_get_theme()->get('Version');
}

function centro_servizi_read_css_file(string $absolute_path): string
{
    if (! file_exists($absolute_path) || ! is_readable($absolute_path)) {
        return '';
    }

    $contents = file_get_contents($absolute_path);

    if (! is_string($contents)) {
        return '';
    }

    return trim($contents);
}

function centro_servizi_get_theme_stylesheets(): array
{
    $stylesheets = [
        [
            'label' => 'assets/css/site.css',
            'path' => get_template_directory() . '/assets/css/site.css',
            'url' => get_template_directory_uri() . '/assets/css/site.css',
        ],
    ];

    if (centro_servizi_is_bureaucratic_context()) {
        $stylesheets[] = [
            'label' => 'assets/css/area-burocratica.css',
            'path' => get_template_directory() . '/assets/css/area-burocratica.css',
            'url' => get_template_directory_uri() . '/assets/css/area-burocratica.css',
        ];
    }

    $stylesheets[] = [
        'label' => 'assets/css/site-header.css',
        'path' => get_template_directory() . '/assets/css/site-header.css',
        'url' => get_template_directory_uri() . '/assets/css/site-header.css',
    ];

    $stylesheets[] = [
        'label' => 'assets/css/site-footer.css',
        'path' => get_template_directory() . '/assets/css/site-footer.css',
        'url' => get_template_directory_uri() . '/assets/css/site-footer.css',
    ];

    $resolved = [];

    foreach ($stylesheets as $stylesheet) {
        if (centro_servizi_read_css_file($stylesheet['path']) === '') {
            continue;
        }

        $stylesheet['version'] = centro_servizi_get_asset_version($stylesheet['path']);
        $stylesheet['href'] = add_query_arg('ver', $stylesheet['version'], $stylesheet['url']);
        $resolved[] = $stylesheet;
    }

    return $resolved;
}

function centro_servizi_get_theme_inline_css_bundle(): string
{
    $chunks = [];

    foreach (centro_servizi_get_theme_stylesheets() as $stylesheet) {
        $contents = centro_servizi_read_css_file($stylesheet['path']);

        if ($contents === '') {
            continue;
        }

        $chunks[] = sprintf(
            '/* %1$s @ %2$s */' . "\n" . '%3$s',
            $stylesheet['label'],
            $stylesheet['version'],
            $contents
        );
    }

    return implode("\n\n", $chunks);
}

function centro_servizi_get_css_loading_mode(): string
{
    return 'link-only';
}
