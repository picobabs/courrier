<?php
$comp_model = new SharedController;
$current_page = $this->set_current_page_link();

// Tuiles de synthese : libelle, valeur, lien, couleur, icone
$tuiles = array(
    array('Courriers enregistres', $comp_model->getcount_courriers(),          'courrier/',                        'alert-primary', 'mail'),
    array('Recus ce mois-ci',      $comp_model->getcount_courriers_du_mois(),  'courrier/',                        'alert-success', 'date_range'),
    array('Courriers en cours',    $comp_model->getcount_courriers_en_cours(), 'courrier/',                        'alert-warning', 'hourglass_empty'),
    array('Imputations en attente',$comp_model->getcount_imputationsenattente(),'imputations_en_attente/',         'alert-info',    'pending_actions'),
    array('Imputations en retard', $comp_model->getcount_imputationenretard(), 'imputation_en_retard/',            'alert-danger',  'report_problem'),
    array('Expediteurs',           $comp_model->getcount_expediteur(),         'expediteur/',                      'alert-secondary','account_balance'),
    array('Utilisateurs',          $comp_model->getcount_gestiondesutilisateurs(),'user/',                         'alert-dark',    'perm_identity'),
);

// Graphiques : titre, donnees, type, identifiant
$graphiques = array(
    array("Etat de traitement du courrier", $comp_model->barchart_h6etatdetraitementducourrierh6(), 'bar',      'g_etat_courrier'),
    array("Courriers recus par mois",       $comp_model->barchart_courrier_par_mois(),              'line',     'g_courrier_mois'),
    array("Courrier par type",              $comp_model->piechart_h6courrierpartypeh6(),            'pie',      'g_courrier_type'),
    array("Courrier par sens",              $comp_model->piechart_h6courrierparsensh6(),            'pie',      'g_courrier_sens'),
    array("Etat des imputations",           $comp_model->barchart_h6etatdetraitementimputationsh6(),'bar',      'g_etat_imputation'),
    array("Courrier par direction",         $comp_model->barchart_courrier_par_direction(),         'bar',      'g_courrier_direction'),
);
?>
<div class="page-content">
    <div class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h4><?php print_lang('le_tableau_de_bord'); ?></h4>
                    <small class="text-muted">Statistiques du courrier</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <?php foreach($tuiles as $t){ list($titre, $valeur, $lien, $couleur, $icone) = $t; ?>
            <div class="col-md-3 col-sm-6 comp-grid mb-2">
                <a class="animated fadeIn record-count alert <?php echo $couleur; ?>" href="<?php print_link($lien) ?>">
                    <div class="row">
                        <div class="col-2"><i class="material-icons"><?php echo $icone; ?></i></div>
                        <div class="col-10">
                            <div class="flex-column justify-content align-center">
                                <div class="title"><?php echo htmlspecialchars($titre, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                        <h4 class="value"><strong><?php echo (int) $valeur; ?></strong></h4>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>

        <div class="row">
            <?php foreach($graphiques as $g){
                list($titre, $chartdata, $type, $id) = $g;
                $labels  = (!empty($chartdata['labels'])) ? array_values($chartdata['labels']) : array();
                $valeurs = (!empty($chartdata['datasets'][0])) ? array_values($chartdata['datasets'][0]) : array();
            ?>
            <div class="col-lg-6 comp-grid mb-3">
                <div class="card p-3">
                    <h6 class="mb-0"><?php echo htmlspecialchars($titre, ENT_QUOTES, 'UTF-8'); ?></h6>
                    <hr />
                    <?php if(empty($labels)){ ?>
                        <p class="text-muted text-center my-4">Aucune donnee a afficher pour le moment.</p>
                    <?php } else { ?>
                    <canvas id="<?php echo $id; ?>"></canvas>
                    <script>
                    $(function(){
                        var donnees = {
                            labels: <?php echo json_encode($labels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                            datasets: [{
                                label: <?php echo json_encode($titre, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                                backgroundColor: [<?php foreach($labels as $l){ echo "'".random_color(0.8)."',"; } ?>],
                                borderColor: '<?php echo random_color(0.9); ?>',
                                borderWidth: <?php echo ($type === 'line') ? 2 : 1; ?>,
                                fill: <?php echo ($type === 'line') ? 'false' : 'true'; ?>,
                                data: <?php echo json_encode(array_map('intval', $valeurs)); ?>
                            }]
                        };
                        new Chart(document.getElementById('<?php echo $id; ?>'), {
                            type: '<?php echo $type; ?>',
                            data: donnees,
                            options: {
                                responsive: true,
                                legend: { display: <?php echo ($type === 'pie') ? 'true' : 'false'; ?> },
                                scales: <?php echo ($type === 'pie') ? '{}' : '{ yAxes: [{ ticks: { beginAtZero: true } }] }'; ?>
                            }
                        });
                    });
                    </script>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
