<div class="container">
	<div class="row justify-content-center">
		<div class="col-sm-6">
			<div class="card card-body">
				<h2><?php print_lang('gestionnaire_de_r_initialisation_de_mot_de_passe'); ?></h2>
				<hr />	
				<h4 class="animated bounce text-success">
					<i class="material-icons">check_circle</i> <?php print_lang('votre_mot_de_passe_a_t_r_initialis_'); ?>
				</h4>
				<hr />
			</div>
			<br />
			<a href="<?php print_link(""); ?>" class="btn btn-info"><?php print_lang('cliquez_ici_pour_vous_identifier'); ?></a>
			<?php 
				if(DEVELOPMENT_MODE){ 
			?>
				<div class="text-muted">To edit the email template, browse to :- <i>app/view/partials/passwordmanager/password_reset_completed.php</i></div>
			<?php 
				} 
			?>
		</div>
	</div>
</div>
	