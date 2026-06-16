<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('admin_init', 'centro_servizi_ensure_attivita_page');
add_action('admin_menu', 'centro_servizi_register_attivita_admin_menu');
add_action('admin_post_centro_servizi_save_attivita', 'centro_servizi_handle_attivita_save');

function centro_servizi_get_attivita_page(): ?WP_Post
{
    $page = get_page_by_path('attivita');

    return $page instanceof WP_Post ? $page : null;
}

function centro_servizi_get_attivita_page_id(): int
{
    $page = centro_servizi_get_attivita_page();

    return $page instanceof WP_Post ? (int) $page->ID : 0;
}

function centro_servizi_ensure_attivita_page(): void
{
    if (! current_user_can('edit_pages')) {
        return;
    }

    $page = centro_servizi_get_attivita_page();

    if ($page instanceof WP_Post) {
        if ($page->post_status !== 'publish') {
            wp_update_post([
                'ID'          => $page->ID,
                'post_status' => 'publish',
            ]);
        }

        return;
    }

    $author_id = (int) get_current_user_id();
    if ($author_id <= 0) {
        $author_id = 1;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Attività',
        'post_name'    => 'attivita',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => $author_id,
    ], true);
}

function centro_servizi_get_attivita_edit_url(): string
{
    $page = centro_servizi_get_attivita_page();

    if ($page instanceof WP_Post) {
        $edit_url = get_edit_post_link($page->ID, 'raw');
        if (is_string($edit_url) && $edit_url !== '') {
            return $edit_url;
        }
    }

    return admin_url('post-new.php?post_type=page');
}

function centro_servizi_register_attivita_admin_menu(): void
{
    add_menu_page(
        'Attività',
        'Attività',
        'edit_pages',
        'centro-servizi-attivita',
        'centro_servizi_render_attivita_admin_page',
        'dashicons-format-gallery',
        22
    );
}

function centro_servizi_get_attivita_sections(): array
{
    $page_id = centro_servizi_get_attivita_page_id();

    if ($page_id <= 0) {
        return [];
    }

    $sections = get_post_meta($page_id, 'centro_servizi_attivita_sections', true);

    if (is_array($sections) && $sections !== []) {
        return $sections;
    }

    if (function_exists('get_field')) {
        $acf_sections = get_field('attivita_sezioni', $page_id);

        if (is_array($acf_sections) && $acf_sections !== []) {
            return $acf_sections;
        }
    }

    return [];
}

function centro_servizi_sanitize_attivita_sections(array $raw_sections): array
{
    $sections = [];

    foreach ($raw_sections as $raw_section) {
        if (! is_array($raw_section)) {
            continue;
        }

        $title = sanitize_text_field((string) ($raw_section['titolo'] ?? $raw_section['title'] ?? ''));
        $caption = sanitize_textarea_field((string) ($raw_section['didascalia'] ?? $raw_section['caption'] ?? ''));
        $raw_images = $raw_section['immagini'] ?? $raw_section['images'] ?? '';

        $image_ids = [];
        if (is_array($raw_images)) {
            foreach ($raw_images as $raw_image) {
                $image_id = 0;
                if (is_array($raw_image)) {
                    $image_id = (int) ($raw_image['ID'] ?? $raw_image['id'] ?? 0);
                } else {
                    $image_id = absint($raw_image);
                }

                if ($image_id > 0) {
                    $image_ids[] = $image_id;
                }
            }
        } else {
            $image_ids = array_values(array_filter(array_map('absint', preg_split('/\s*,\s*/', trim((string) $raw_images)) ?: [])));
        }

        $image_ids = array_values(array_unique(array_filter($image_ids)));

        if ($title === '' || $image_ids === []) {
            continue;
        }

        $sections[] = [
            'titolo' => $title,
            'didascalia' => $caption,
            'immagini' => $image_ids,
        ];
    }

    return $sections;
}

function centro_servizi_handle_attivita_save(): void
{
    if (! current_user_can('edit_pages')) {
        wp_die('Accesso negato.');
    }

    if (
        ! isset($_POST['centro_servizi_attivita_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['centro_servizi_attivita_nonce'])), 'centro_servizi_save_attivita')
    ) {
        wp_die('Verifica di sicurezza fallita.');
    }

    $page_id = centro_servizi_get_attivita_page_id();

    if ($page_id <= 0) {
        wp_die('Pagina Attività non trovata.');
    }

    $raw_sections = isset($_POST['centro_servizi_attivita_sections']) && is_array($_POST['centro_servizi_attivita_sections'])
        ? wp_unslash($_POST['centro_servizi_attivita_sections'])
        : [];

    $sections = centro_servizi_sanitize_attivita_sections($raw_sections);
    update_post_meta($page_id, 'centro_servizi_attivita_sections', $sections);

    wp_safe_redirect(add_query_arg([
        'page' => 'centro-servizi-attivita',
        'updated' => '1',
    ], admin_url('admin.php')));
    exit;
}

function centro_servizi_render_attivita_admin_page(): void
{
    if (! current_user_can('edit_pages')) {
        wp_die('Accesso negato.');
    }

    centro_servizi_ensure_attivita_page();

    wp_enqueue_media();

    $page = centro_servizi_get_attivita_page();
    $page_id = $page instanceof WP_Post ? (int) $page->ID : 0;
    $sections = centro_servizi_get_attivita_sections();
    $public_url = $page_id > 0 ? get_permalink($page_id) : home_url('/attivita/');
    ?>
    <div class="wrap centro-servizi-attivita-admin">
        <h1>Attività</h1>
        <p>Questa schermata gestisce la pagina Attività del sito come vetrina editoriale: aggiungi attività, imposta titolo, didascalia opzionale e seleziona/ordina immagini.</p>

        <p>
            <a class="button button-secondary" href="<?php echo esc_url($public_url); ?>" target="_blank" rel="noopener noreferrer">Apri pagina pubblica</a>
            <?php if ($page instanceof WP_Post) : ?>
                <a class="button button-secondary" href="<?php echo esc_url(get_edit_post_link($page->ID)); ?>">Apri editor pagina</a>
            <?php endif; ?>
        </p>

        <?php if (! empty($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p>Attività salvate.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="centro-servizi-attivita-form">
            <?php wp_nonce_field('centro_servizi_save_attivita', 'centro_servizi_attivita_nonce'); ?>
            <input type="hidden" name="action" value="centro_servizi_save_attivita" />

            <div class="centro-servizi-attivita-list" id="centro-servizi-attivita-list">
                <?php foreach ($sections as $index => $section) : ?>
                    <?php
                    $title = isset($section['titolo']) ? (string) $section['titolo'] : '';
                    $caption = isset($section['didascalia']) ? (string) $section['didascalia'] : '';
                    $images = isset($section['immagini']) ? $section['immagini'] : [];
                    if (! is_array($images)) {
                        $images = [];
                    }
                    $image_ids = array_values(array_filter(array_map('absint', $images)));
                    ?>
                    <section class="centro-servizi-attivita-item" data-section-index="<?php echo esc_attr((string) $index); ?>">
                        <?php $section_label = $title !== '' ? $title : 'Nuova attività'; ?>
                        <div class="centro-servizi-attivita-item__header">
                            <h2 data-activity-label><?php echo esc_html($section_label); ?></h2>
                            <div class="centro-servizi-attivita-item__actions">
                                <button type="button" class="button-link centro-servizi-attivita-toggle" aria-expanded="true">Comprimi</button>
                                <button type="button" class="button-link centro-servizi-attivita-move-up">Su</button>
                                <button type="button" class="button-link centro-servizi-attivita-move-down">Giù</button>
                                <button type="button" class="button-link-delete centro-servizi-attivita-remove">Rimuovi</button>
                            </div>
                        </div>

                        <div class="centro-servizi-attivita-item__body">

                        <p>
                            <label>Titolo attività<br>
                                <input type="text" name="centro_servizi_attivita_sections[<?php echo esc_attr((string) $index); ?>][titolo]" value="<?php echo esc_attr($title); ?>" class="regular-text" data-activity-title />
                            </label>
                        </p>

                        <p>
                            <label>Didascalia opzionale<br>
                                <textarea name="centro_servizi_attivita_sections[<?php echo esc_attr((string) $index); ?>][didascalia]" rows="3" class="large-text"><?php echo esc_textarea($caption); ?></textarea>
                            </label>
                        </p>

                        <div class="centro-servizi-attivita-gallery" data-gallery-wrapper>
                            <input type="hidden" name="centro_servizi_attivita_sections[<?php echo esc_attr((string) $index); ?>][immagini]" data-gallery-input value="<?php echo esc_attr(implode(',', $image_ids)); ?>" />
                            <div class="centro-servizi-attivita-gallery__preview" data-gallery-preview>
                                <?php foreach ($image_ids as $image_id) : ?>
                                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                                <?php endforeach; ?>
                            </div>
                            <p>
                                <button type="button" class="button centro-servizi-attivita-select">Seleziona immagini</button>
                                <button type="button" class="button centro-servizi-attivita-order">Ordina immagini</button>
                                <button type="button" class="button centro-servizi-attivita-clear">Svuota immagini</button>
                            </p>
                        </div>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <template id="centro-servizi-attivita-template">
                <section class="centro-servizi-attivita-item" data-section-index="__INDEX__">
                    <div class="centro-servizi-attivita-item__header">
                        <h2 data-activity-label>Nuova attività</h2>
                        <div class="centro-servizi-attivita-item__actions">
                            <button type="button" class="button-link centro-servizi-attivita-toggle" aria-expanded="true">Comprimi</button>
                            <button type="button" class="button-link centro-servizi-attivita-move-up">Su</button>
                            <button type="button" class="button-link centro-servizi-attivita-move-down">Giù</button>
                            <button type="button" class="button-link-delete centro-servizi-attivita-remove">Rimuovi</button>
                        </div>
                    </div>

                    <div class="centro-servizi-attivita-item__body">

                    <p>
                        <label>Titolo attività<br>
                            <input type="text" name="centro_servizi_attivita_sections[__INDEX__][titolo]" class="regular-text" data-activity-title />
                        </label>
                    </p>

                    <p>
                        <label>Didascalia opzionale<br>
                            <textarea name="centro_servizi_attivita_sections[__INDEX__][didascalia]" rows="3" class="large-text"></textarea>
                        </label>
                    </p>

                    <div class="centro-servizi-attivita-gallery" data-gallery-wrapper>
                        <input type="hidden" name="centro_servizi_attivita_sections[__INDEX__][immagini]" data-gallery-input value="" />
                        <div class="centro-servizi-attivita-gallery__preview" data-gallery-preview></div>
                        <p>
                            <button type="button" class="button centro-servizi-attivita-select">Seleziona immagini</button>
                            <button type="button" class="button centro-servizi-attivita-order">Ordina immagini</button>
                            <button type="button" class="button centro-servizi-attivita-clear">Svuota immagini</button>
                        </p>
                    </div>
                    </div>
                </section>
            </template>

            <p>
                <button type="button" class="button button-primary" id="centro-servizi-attivita-add">Aggiungi attività</button>
            </p>

            <?php submit_button('Salva attività', 'primary', 'submit', false); ?>
        </form>
    </div>

    <script>
    (function() {
        const list = document.getElementById('centro-servizi-attivita-list');
        const template = document.getElementById('centro-servizi-attivita-template');
        const addButton = document.getElementById('centro-servizi-attivita-add');
        let nextIndex = list ? list.querySelectorAll('.centro-servizi-attivita-item').length : 0;

        if (!list || !template || !addButton) {
            return;
        }

        const updateSectionOrderNames = () => {
            const items = list.querySelectorAll('.centro-servizi-attivita-item');
            items.forEach((item, index) => {
                item.dataset.sectionIndex = String(index);
                item.querySelectorAll('input[name], textarea[name]').forEach((field) => {
                    field.name = field.name.replace(/centro_servizi_attivita_sections\[\d+\]/, `centro_servizi_attivita_sections[${index}]`);
                });
            });
        };

        const renderPreviewFromIds = (preview, ids) => {
            if (!preview) {
                return;
            }

            if (!ids.length) {
                preview.innerHTML = '';
                return;
            }

            const html = ids.map((id) => {
                const attachment = wp.media.attachment(id);
                const data = attachment?.attributes || {};
                const src = data?.sizes?.thumbnail?.url || data?.url || '';
                return src ? `<img src="${src}" alt="" />` : '';
            }).join('');
            preview.innerHTML = html;
        };

        const openMedia = (section) => {
            const input = section.querySelector('[data-gallery-input]');
            const preview = section.querySelector('[data-gallery-preview]');

            const frame = wp.media({
                title: 'Seleziona immagini attività',
                library: { type: 'image' },
                button: { text: 'Usa immagini selezionate' },
                multiple: true
            });

            frame.on('open', () => {
                const ids = (input.value || '').split(',').map((value) => parseInt(value, 10)).filter(Boolean);
                const selection = frame.state().get('selection');
                selection.reset();
                ids.forEach((id) => {
                    const attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment);
                });
            });

            frame.on('select', () => {
                const selection = frame.state().get('selection');
                const ids = [];
                const thumbnails = [];

                selection.each((attachment) => {
                    const data = attachment.toJSON();
                    ids.push(data.id);
                    thumbnails.push(`<img src="${data.sizes && data.sizes.thumbnail ? data.sizes.thumbnail.url : data.url}" alt="" />`);
                });

                input.value = ids.join(',');
                preview.innerHTML = thumbnails.join('');
            });

            frame.open();
        };

        const openGalleryOrder = (section) => {
            const input = section.querySelector('[data-gallery-input]');
            const preview = section.querySelector('[data-gallery-preview]');
            const ids = (input?.value || '').split(',').map((value) => parseInt(value, 10)).filter(Boolean);

            if (!ids.length) {
                return;
            }

            const shortcode = `[gallery ids="${ids.join(',')}"]`;
            const frame = wp.media.gallery.edit(shortcode);

            frame.on('update', (selection) => {
                const orderedIds = [];
                selection.each((attachment) => {
                    const data = attachment.toJSON();
                    if (data.id) {
                        orderedIds.push(data.id);
                    }
                });

                if (input) {
                    input.value = orderedIds.join(',');
                }
                renderPreviewFromIds(preview, orderedIds);
            });
        };

        const bindSection = (section) => {
            const titleInput = section.querySelector('[data-activity-title]');
            const label = section.querySelector('[data-activity-label]');

            titleInput?.addEventListener('input', () => {
                if (!label) {
                    return;
                }

                const value = (titleInput.value || '').trim();
                label.textContent = value !== '' ? value : 'Nuova attività';
            });

            const toggle = section.querySelector('.centro-servizi-attivita-toggle');
            const body = section.querySelector('.centro-servizi-attivita-item__body');

            toggle?.addEventListener('click', () => {
                if (!body) {
                    return;
                }
                const isHidden = body.hasAttribute('hidden');
                if (isHidden) {
                    body.removeAttribute('hidden');
                    toggle.textContent = 'Comprimi';
                    toggle.setAttribute('aria-expanded', 'true');
                } else {
                    body.setAttribute('hidden', 'hidden');
                    toggle.textContent = 'Espandi';
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            section.querySelector('.centro-servizi-attivita-remove')?.addEventListener('click', () => {
                section.remove();
                updateSectionOrderNames();
            });

            section.querySelector('.centro-servizi-attivita-move-up')?.addEventListener('click', () => {
                const previous = section.previousElementSibling;
                if (previous) {
                    list.insertBefore(section, previous);
                    updateSectionOrderNames();
                }
            });

            section.querySelector('.centro-servizi-attivita-move-down')?.addEventListener('click', () => {
                const next = section.nextElementSibling;
                if (next) {
                    list.insertBefore(next, section);
                    updateSectionOrderNames();
                }
            });

            section.querySelector('.centro-servizi-attivita-clear')?.addEventListener('click', () => {
                const input = section.querySelector('[data-gallery-input]');
                const preview = section.querySelector('[data-gallery-preview]');
                if (input) input.value = '';
                if (preview) preview.innerHTML = '';
            });

            section.querySelector('.centro-servizi-attivita-select')?.addEventListener('click', () => {
                openMedia(section);
            });

            section.querySelector('.centro-servizi-attivita-order')?.addEventListener('click', () => {
                openGalleryOrder(section);
            });
        };

        list.querySelectorAll('.centro-servizi-attivita-item').forEach(bindSection);

        addButton.addEventListener('click', () => {
            const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex));
            const holder = document.createElement('div');
            holder.innerHTML = html.trim();
            const section = holder.firstElementChild;
            if (!section) {
                return;
            }
            bindSection(section);
            list.appendChild(section);
            nextIndex += 1;
            updateSectionOrderNames();
        });

        updateSectionOrderNames();
    })();
    </script>
    <?php
}

function centro_servizi_get_attivita_image_alt(array $image, string $section_title, int $index = 0): string
{
    $alt = trim((string) ($image['alt'] ?? ''));

    if ($alt !== '') {
        return $alt;
    }

    $section_title = trim(wp_strip_all_tags($section_title));

    if ($section_title !== '') {
        return 'bambini che fanno attività scolastica: ' . $section_title;
    }

    return 'bambini che fanno attività scolastica';
}
