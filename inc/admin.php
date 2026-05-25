<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function centro_servizi_get_admin_debug_static_meta(): array
{
    return [
        'branch' => 'main',
        'commit_short' => '-',
        'commit_datetime' => '-',
    ];
}

function centro_servizi_get_deploy_meta_admin(): array
{
    $meta_file = get_template_directory() . '/assets/deploy-meta.php';

    if (! file_exists($meta_file)) {
        return [];
    }

    $meta = require $meta_file;

    return is_array($meta) ? $meta : [];
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
    $deploy_meta = centro_servizi_get_deploy_meta_admin();

    if (function_exists('shell_exec')) {
        $repo_root = centro_servizi_find_git_repository_root(get_template_directory());

        if ($repo_root !== '') {
            $command = sprintf('git -C %s rev-parse --abbrev-ref HEAD 2>/dev/null', escapeshellarg($repo_root));
            $output = shell_exec($command);

            if (is_string($output)) {
                $branch = trim($output);

                if ($branch !== '' && $branch !== 'HEAD') {
                    return sanitize_text_field($branch);
                }
            }
        }
    }

    if (isset($deploy_meta['branch']) && is_string($deploy_meta['branch'])) {
        $fallback_branch = trim($deploy_meta['branch']);

        if ($fallback_branch !== '') {
            return sanitize_text_field($fallback_branch);
        }
    }

    return '';
}

function centro_servizi_get_short_subject(string $subject): string
{
    $subject_length = function_exists('mb_strlen') ? mb_strlen($subject) : strlen($subject);

    if ($subject_length <= 18) {
        return $subject;
    }

    $subject_short = function_exists('mb_substr')
        ? mb_substr($subject, 0, 18)
        : substr($subject, 0, 18);

    return $subject_short . '...';
}

function centro_servizi_get_latest_git_commit_meta(): array
{
    $deploy_meta = centro_servizi_get_deploy_meta_admin();

    if (function_exists('shell_exec')) {
        $repo_root = centro_servizi_find_git_repository_root(get_template_directory());

        if ($repo_root !== '') {
            $subject_command = sprintf('git -C %s log -1 --pretty=%%s 2>/dev/null', escapeshellarg($repo_root));
            $timestamp_command = sprintf('git -C %s log -1 --pretty=%%ct 2>/dev/null', escapeshellarg($repo_root));

            $subject_output = shell_exec($subject_command);
            $timestamp_output = shell_exec($timestamp_command);

            if (is_string($subject_output) && is_string($timestamp_output)) {
                $subject = trim($subject_output);
                $timestamp = (int) trim($timestamp_output);

                if ($subject !== '' && $timestamp > 0) {
                    return [
                        'commit_short' => sanitize_text_field(centro_servizi_get_short_subject($subject)),
                        'commit_datetime' => wp_date('Y-m-d H:i', $timestamp),
                    ];
                }
            }
        }
    }

    $subject_fallback = '';
    if (isset($deploy_meta['commit_title']) && is_string($deploy_meta['commit_title'])) {
        $subject_fallback = trim($deploy_meta['commit_title']);
    }

    if ($subject_fallback === '' && isset($deploy_meta['commit_hash']) && is_string($deploy_meta['commit_hash'])) {
        $subject_fallback = trim($deploy_meta['commit_hash']);
    }

    $date_fallback = '';
    if (isset($deploy_meta['deployed_at']) && is_string($deploy_meta['deployed_at'])) {
        $deployed_at = trim($deploy_meta['deployed_at']);
        $timestamp = strtotime($deployed_at);
        $date_fallback = ($timestamp !== false && $timestamp > 0)
            ? wp_date('Y-m-d H:i', $timestamp)
            : $deployed_at;
    }

    if ($subject_fallback === '' && $date_fallback === '') {
        return [];
    }

    return [
        'commit_short' => sanitize_text_field($subject_fallback !== '' ? centro_servizi_get_short_subject($subject_fallback) : '-'),
        'commit_datetime' => sanitize_text_field($date_fallback !== '' ? $date_fallback : '-'),
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

    if (strpos($normalized_template, $normalized_theme_dir) === 0) {
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
