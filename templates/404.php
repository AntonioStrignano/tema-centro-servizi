<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

get_template_part('partials/header');
?>
<main class="site-main" id="contenuto-principale" role="main">
    <section class="site-section">
        <h1>Pagina non trovata</h1>
        <p>La risorsa richiesta non e disponibile o e stata spostata.</p>
        <p><a href="<?php echo esc_url(home_url('/')); ?>">Torna alla home</a></p>
    </section>
</main>
<?php
get_template_part('partials/footer');