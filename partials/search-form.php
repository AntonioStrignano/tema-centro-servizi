<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$context = isset($args['context']) && is_string($args['context']) ? $args['context'] : 'default';
$show_filters = isset($args['show_filters']) ? (bool) $args['show_filters'] : true;
$form_id_prefix = isset($args['form_id_prefix']) && is_string($args['form_id_prefix']) && $args['form_id_prefix'] !== ''
    ? $args['form_id_prefix']
    : 'site-search';

$query = trim((string) get_search_query());
$selected_post_types = function_exists('centro_servizi_get_search_selected_post_types')
    ? centro_servizi_get_search_selected_post_types()
    : ['page', 'trasparenza', 'area-famiglie', 'area-personale'];
$available_post_types = function_exists('centro_servizi_get_search_post_type_map')
    ? centro_servizi_get_search_post_type_map()
    : [
        'page'           => 'Pagine',
        'trasparenza'    => 'Amministrazione Trasparente',
        'area-famiglie'  => 'Area Famiglie',
        'area-personale' => 'Area Personale',
    ];

$wrapper_classes = ['search-form', 'search-form--' . sanitize_html_class($context)];

if ($show_filters) {
    $wrapper_classes[] = 'search-form--with-filters';
}
?>
<form role="search" method="get" class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form__bar">
        <label class="sr-only" for="<?php echo esc_attr($form_id_prefix); ?>-input">Cerca nel sito</label>
        <input
            type="search"
            id="<?php echo esc_attr($form_id_prefix); ?>-input"
            class="search-form__input"
            placeholder="Cerca nel sito"
            value="<?php echo esc_attr($query); ?>"
            name="s"
        >
        <button type="submit" class="search-form__submit">Cerca</button>
    </div>

    <?php if ($show_filters) : ?>
        <fieldset class="search-form__filters">
            <legend>Filtra per tipo di contenuto</legend>

            <div class="search-form__options">
                <?php foreach ($available_post_types as $post_type => $label) : ?>
                    <label class="search-form__option">
                        <input
                            type="checkbox"
                            name="post_type[]"
                            value="<?php echo esc_attr($post_type); ?>"
                            <?php checked(in_array($post_type, $selected_post_types, true)); ?>
                        >
                        <span><?php echo esc_html($label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
    <?php else : ?>
        <?php foreach (array_keys($available_post_types) as $post_type) : ?>
            <input type="hidden" name="post_type[]" value="<?php echo esc_attr($post_type); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</form>