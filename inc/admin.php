<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_shorten_commit_subject(string $subject): string
{
    $max_length = 18;
    $length = function_exists('mb_strlen') ? mb_strlen($subject) : strlen($subject);

    if ($length <= $max_length) {
        return $subject;
    }

    $short = function_exists('mb_substr')
        ? mb_substr($subject, 0, $max_length)
        : substr($subject, 0, $max_length);

    return $short . '...';
}

function centro_servizi_get_release_info_defaults(): array
{
    return [
        'branch' => 'main',
        'commit_short' => '-',
        'commit_datetime' => '-',
    ];
}

function centro_servizi_parse_deploy_meta_file(string $meta_file): array
{
    $content = file_get_contents($meta_file);

    if (! is_string($content) || $content === '') {
        return [];
    }

    $keys = ['branch', 'commit_title', 'commit_hash', 'deployed_at'];
    $parsed = [];

    foreach ($keys as $key) {
        $pattern = "/'" . preg_quote($key, '/') . "'\\s*=>\\s*(['\"])((?:\\\\.|(?!\\1).)*)\\1/s";

        if (! preg_match($pattern, $content, $matches)) {
            continue;
        }

        $parsed[$key] = sanitize_text_field(stripcslashes((string) $matches[2]));
    }

    return $parsed;
}

function centro_servizi_get_release_info_meta(): array
{
    $meta = centro_servizi_get_release_info_defaults();
    $meta_file = get_template_directory() . '/assets/deploy-meta.php';

    if (! file_exists($meta_file) || ! is_readable($meta_file)) {
        return $meta;
    }

    $deploy_meta = centro_servizi_parse_deploy_meta_file($meta_file);

    if (isset($deploy_meta['branch']) && $deploy_meta['branch'] !== '') {
        $meta['branch'] = (string) $deploy_meta['branch'];
    }

    $commit_source = '';
    if (isset($deploy_meta['commit_title']) && $deploy_meta['commit_title'] !== '') {
        $commit_source = (string) $deploy_meta['commit_title'];
    } elseif (isset($deploy_meta['commit_hash']) && $deploy_meta['commit_hash'] !== '') {
        $commit_source = (string) $deploy_meta['commit_hash'];
    }

    if ($commit_source !== '') {
        $meta['commit_short'] = centro_servizi_shorten_commit_subject($commit_source);
    }

    if (isset($deploy_meta['deployed_at']) && $deploy_meta['deployed_at'] !== '') {
        $timestamp = strtotime((string) $deploy_meta['deployed_at']);

        if ($timestamp !== false && $timestamp > 0) {
            $meta['commit_datetime'] = wp_date('Y-m-d H:i', $timestamp);
        } else {
            $meta['commit_datetime'] = (string) $deploy_meta['deployed_at'];
        }
    }

    return $meta;
}

function centro_servizi_get_current_template_label(): string
{
    if (is_admin()) {
        return 'wp-admin';
    }

    global $template;

    if (! is_string($template) || $template === '') {
        return 'template non rilevato';
    }

    $normalized_template = wp_normalize_path($template);
    $normalized_theme_dir = wp_normalize_path(get_template_directory()) . '/';

    if (strpos($normalized_template, $normalized_theme_dir) === 0) {
        return substr($normalized_template, strlen($normalized_theme_dir));
    }

    return basename($normalized_template);
}

function centro_servizi_add_admin_bar_release_info($wp_admin_bar): void
{
    if (! ($wp_admin_bar instanceof WP_Admin_Bar)) {
        return;
    }

    if (! is_admin_bar_showing() || ! current_user_can('manage_options')) {
        return;
    }

    $meta = centro_servizi_get_release_info_meta();

    $template_label = centro_servizi_get_current_template_label();

    $title = sprintf(
        'Branch: %1$s | Commit: %2$s | Data: %3$s | Template: %4$s',
        $meta['branch'],
        $meta['commit_short'],
        $meta['commit_datetime'],
        $template_label
    );

    $wp_admin_bar->add_node([
        'id' => 'centro-servizi-release-info',
        'parent' => 'top-secondary',
        'title' => esc_html($title),
        'href' => false,
        'meta' => [
            'title' => 'Info tecniche tema',
        ],
    ]);
}

add_action('admin_bar_menu', 'centro_servizi_add_admin_bar_release_info', 999);
