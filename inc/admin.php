<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Dati statici rapidi mostrati nella admin bar.
 * Aggiorna qui branch/commit/data quando fai un rilascio.
 */
function centro_servizi_get_admin_debug_static_meta(): array
{
    return [
        'branch' => 'chore/theme-risanamento',
        'commit_short' => 'manuale',
        'commit_datetime' => '2026-05-25 00:00',
    ];
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

function centro_servizi_get_current_git_branch(): string
{
    if (! function_exists('shell_exec')) {
        return '';
    }

    $repo_root = centro_servizi_find_git_repository_root(get_template_directory());

    if ($repo_root === '') {
        return '';
    }

    $command = sprintf('git -C %s rev-parse --abbrev-ref HEAD 2>/dev/null', escapeshellarg($repo_root));
    $output = shell_exec($command);

    if (! is_string($output)) {
        return '';
    }

    $branch = trim($output);

    if ($branch === '' || $branch === 'HEAD') {
        return '';
    }

    return sanitize_text_field($branch);
}

function centro_servizi_get_latest_git_commit_meta(): array
{
    if (! function_exists('shell_exec')) {
        return [];
    }

    $repo_root = centro_servizi_find_git_repository_root(get_template_directory());

    if ($repo_root === '') {
        return [];
    }

    $subject_command = sprintf('git -C %s log -1 --pretty=%%s 2>/dev/null', escapeshellarg($repo_root));
    $timestamp_command = sprintf('git -C %s log -1 --pretty=%%ct 2>/dev/null', escapeshellarg($repo_root));

    $subject_output = shell_exec($subject_command);
    $timestamp_output = shell_exec($timestamp_command);

    if (! is_string($subject_output) || ! is_string($timestamp_output)) {
        return [];
    }

    $subject = trim($subject_output);
    $timestamp = (int) trim($timestamp_output);

    if ($subject === '' || $timestamp <= 0) {
        return [];
    }

    $subject_length = function_exists('mb_strlen') ? mb_strlen($subject) : strlen($subject);

    if ($subject_length > 18) {
        $subject_short = function_exists('mb_substr')
            ? mb_substr($subject, 0, 18)
            : substr($subject, 0, 18);
        $subject_short .= '...';
    } else {
        $subject_short = $subject;
    }

    return [
        'commit_short' => sanitize_text_field($subject_short),
        'commit_datetime' => wp_date('Y-m-d H:i', $timestamp),
    ];
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

    if (str_starts_with($normalized_template, $normalized_theme_dir)) {
        return substr($normalized_template, strlen($normalized_theme_dir));
    }

    return basename($normalized_template);
}

function centro_servizi_add_admin_bar_release_info(WP_Admin_Bar $wp_admin_bar): void
{
    if (! is_admin_bar_showing() || ! current_user_can('manage_options')) {
        return;
    }

    $meta = centro_servizi_get_admin_debug_static_meta();
    $runtime_branch = centro_servizi_get_current_git_branch();
    $runtime_commit_meta = centro_servizi_get_latest_git_commit_meta();

    if ($runtime_branch !== '') {
        $meta['branch'] = $runtime_branch;
    }

    if (isset($runtime_commit_meta['commit_short'])) {
        $meta['commit_short'] = (string) $runtime_commit_meta['commit_short'];
    }

    if (isset($runtime_commit_meta['commit_datetime'])) {
        $meta['commit_datetime'] = (string) $runtime_commit_meta['commit_datetime'];
    }

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
