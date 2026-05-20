<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$azienda_url = 'https://www.scuoledinfanzia.it/new_site/index.php';

$servizi = [
    [
        'titolo'      => 'Comunicazione',
        'descrizione' => 'Comunicazione tempestiva tra scuola e famiglie: bacheca, circolari, materiali multimediali e supporto pedagogico-amministrativo.',
        'img'         => 'https://lilliputandria.it/wp-content/uploads/2022/01/comunicazione.jpg',
    ],
    [
        'titolo'      => 'Progettazione',
        'descrizione' => 'Progettazione di finanziamenti UE e MIUR, convegni e percorsi formativi per dirigenti, docenti e famiglie.',
        'img'         => 'https://lilliputandria.it/wp-content/uploads/2022/01/progettazione.jpg',
    ],
    [
        'titolo'      => 'Consulenza',
        'descrizione' => 'Consulenza pedagogica, amministrativa, contabile e gestione del personale.',
        'img'         => 'https://lilliputandria.it/wp-content/uploads/2022/01/consulenza.jpg',
    ],
    [
        'titolo'      => 'Formazione',
        'descrizione' => 'Formazione continua per docenti ed educatori: e-learning, attività culturali e seminari specializzati.',
        'img'         => 'https://lilliputandria.it/wp-content/uploads/2022/01/formazione.jpg',
    ],
];
?>
<section class="strip-servizi site-section" aria-labelledby="strip-servizi-titolo">
    <div class="site-section__inner">
        <h2 id="strip-servizi-titolo" class="strip-servizi__titolo">
            <?php esc_html_e('Servizi Centro Servizi Scuole in Rete', 'centro-servizi'); ?>
        </h2>
        <ul class="strip-servizi__lista" role="list">
            <?php foreach ($servizi as $servizio) : ?>
            <li class="strip-servizi__item">
                <a
                    class="strip-servizi__card"
                    href="<?php echo esc_url($azienda_url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php echo esc_attr($servizio['titolo'] . ' — scopri su scuoledinfanzia.it'); ?>"
                    style="background-image: url('<?php echo esc_url($servizio['img']); ?>');"
                >
                    <div class="strip-servizi__overlay">
                        <h3 class="strip-servizi__card-titolo"><?php echo esc_html($servizio['titolo']); ?></h3>
                        <p class="strip-servizi__card-desc"><?php echo esc_html($servizio['descrizione']); ?></p>
                        <span class="sr-only">(apre in nuova finestra)</span>
                    </div>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
