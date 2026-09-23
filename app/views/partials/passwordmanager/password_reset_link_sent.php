<div class="container">
	<h3><?php print_lang('gestionnaire_de_r_initialisation_de_mot_de_passe'); ?></h3>
	<hr />
	<div class="">
		<h4 class="text-info bold">
			<i class="material-icons">email</i> <?php print_lang('un_message_a_t_envoy_votre_email_veuillez_suivre_le_lien_pour_r_initialiser_votre_mot_de_passe'); ?>
		</h4>
		<?php
		if (DEVELOPMENT_MODE) {
			?>
			<div class="text-muted">
				To edit this file, browse to :- <i>app/view/partials/passwordmanager/password_reset_link_sent.php</i>
			</div>
		<?php
		}
		?>
	</div>
	<hr />
	<a href="<?php print_link(""); ?>" class="btn btn-info"><?php print_lang('cliquez_ici_pour_vous_identifier'); ?></a>
	<?php
	// Le contenu du message (avec le lien de reinitialisation) etait affiche ici
	// en mode developpement : n'importe qui pouvait alors saisir l'adresse d'un
	// autre utilisateur et prendre le controle de son compte. Supprime.
	?>
</div>