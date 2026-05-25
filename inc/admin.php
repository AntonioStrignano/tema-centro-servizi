<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_find_git_repository_root(string $start_path): string
{
    $path = wp_normalize_path($start_path);

    while ($path !== '' && $path !== '/' && $path !== '.') {
        if (file_exists($path . '/.git')) {
            return $path;
        }

        $parent = dirname($path);

        if ($parent === $path) {
            break;
        }

        $path = wp_normalize_path($parent);
    }

    return '';
}

function centro_servizi_exec_git_command(string $repo_root, string $command): string
{
    if (! function_exists('shell_exec')) {
        return '';
    }

    $full_command = sprintf('git -C %s %s 2>/dev/null', escapeshellarg($repo_root), $command);
    $output = shell_exec($full_command);

    if (! is_string($output)) {
        return '';
    }

    return trim($output);
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

function centro_servizi_get_release_info_meta(): array
{
    $meta = [
        'branch' => 'main',
        'commit_short' => '-',
        'commit_datetime' => '-',
    ];

    $repo_root = centro_servizi_find_git_repository_root(get_template_directory());

    if ($repo_root === '') {
        return $meta;
    }

    $branch = centro_servizi_exec_git_command($repo_root, 'rev-parse --abbrev-ref HEAD');
    if ($branch !== '' && $branch !== 'HEAD') {
        $meta['branch'] = sanitize_text_field($branch);
    }

    $subject = centro_servizi_exec_git_command($repo_root, 'log -1 --pretty=%s');
    if ($subject !== '') {
        $meta['commit_short'] = sanitize_text_field(centro_servizi_shorten_commit_subject($subject));
    }

    $timestamp_raw = centro_servizi_exec_git_command($repo_root, 'log -1 --pretty=%ct');
    $timestamp = (int) $timestamp_raw;
    if ($timestamp > 0) {
        $meta['commit_datetime'] = wp_date('Y-m-d H:i', $timestamp);
    }

    return $meta;
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
