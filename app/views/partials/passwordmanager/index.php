<div class="container">
	<div>
		<h3><?php print_lang('gestionnaire_de_r_initialisation_de_mot_de_passe'); ?></h3>
		<small class="text-muted">
			<?php print_lang('s_il_vous_pla_t_fournir_l_adresse_email_valide_que_vous_avez_utilis_pour_vous_inscrire'); ?>
		</small>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-8">
			<?php 
				$this :: display_page_errors(); 
			?>
			<form method="post" action="<?php print_link("passwordmanager/postresetlink?csrf_token=" . Csrf::$token); ?>">
				<div class="row">
					<div class="col-9">
						<input value="<?php echo get_form_field_value('email'); ?>" placeholder="Enter Your Email Address" required="required" class="form-control default" name="email" type="email" />
					</div>
					<div class="col-3">
						<button class="btn btn-success" type="submit"> <?php print_lang('envoyer'); ?> <i class="material-icons">email</i></button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<br />
	<div class="text-info">
		<?php print_lang('un_lien_sera_envoy_votre_email_contenant_les_informations_dont_vous_avez_besoin_pour_votre_mot_de_passe'); ?>
	</div>
</div>




