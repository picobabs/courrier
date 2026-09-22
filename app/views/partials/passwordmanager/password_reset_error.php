<div class="container">
	<h3><?php print_lang('gestionnaire_de_r_initialisation_de_mot_de_passe'); ?></h3>
	
	<div class="card card-body mt-4 animated bounce">
		<h3 class="text-danger bold"><?php print_lang('votre_r_initialisation_du_mot_de_passe_n_est_pas_termin_e'); ?></h3>
		<div class="text-muted"><?php print_lang('echec_de_la_r_initialisation_du_mot_de_passe'); ?></div>
		<hr />
		<div class="text-success">
			<?php print_lang('s_il_vous_pla_t_vous_pouvez_essayer_de_r_initialiser_votre_mot_de_passe_en_suivant_ces_tapes'); ?>
			<br />
			<br />
			<a href="<?php print_link("passwordmanager/") ?>" class="btn btn-primary"><?php print_lang('r_initialiser_le_mot_de_passe'); ?></a>
			
			<a href="<?php print_link(""); ?>" class="btn btn-info"><?php print_lang('cliquez_ici_pour_vous_identifier'); ?></a>
		</div>
	</div>
</div>
