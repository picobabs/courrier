<?php
	$data=$this->view_data;
	$user_email = $data["user_email"];
	$status = $data["status"];
?>
<div class="container">
	<?php 
		if($status==true){
			if(!empty($_GET['resend'])){
				?>
				<h4 class="text-info bold animated bounce"><i class="material-icons">email</i> <?php print_lang('la_v_rification_du_courrier_lectronique_a_t_renvoy_e'); ?></h4>
				<?php
			}
			else{
				?>
				<h4 class="text-info bold"><i class="material-icons">email</i> <?php print_lang('lien_de_v_rification_de_l_e_mail_envoy_'); ?></h4>
				<?php
			}
		?>
			<div class="text-muted"><?php print_lang('veuillez_v_rifier_votre_adresse_e_mail_en_suivant_le_lien_dans_votre_bo_te_aux_lettres'); ?></div>
			<hr />
			<div>
				<a href="<?php print_link("index/send_verify_email_link/$user_email?resend=true") ?>" class="btn btn-primary"><i class="material-icons">email</i> <?php print_lang('r_envoyer_l_email'); ?></a>
			</div>
			<?php
		}
		else{
			?>
			<div><i class="material-icons">email</i> <?php print_lang('veuillez_v_rifier_votre_adresse_e_mail_en_suivant_le_lien_dans_votre_bo_te_aux_lettres'); ?></div>
			<hr />
			<div>
				<a href="<?php print_link("index/send_verify_email_link/$user_email?resend=true") ?>" class="btn btn-primary"><i class="material-icons">email</i> <?php print_lang('r_envoyer_l_email'); ?></a>
			</div>
			<?php
		}
	?>
	<?php
		if(DEVELOPMENT_MODE){
			$mailbody = $this->view_data["mailbody"];
			?>
			<hr />
			<div class="bg-light p-4 border">
				<div class="text-danger">
					<h3>
						<b>Disclaimer:</b> You are seeing this because you published under development mode.
						<br />We understand that sending email in localhost might be problematic.
					</h3>
					<div class="text-muted">To edit the email template, browse to :- <i>app/view/partials/index/emailverify_template.html</i></div>
				</div>
				<hr />
				<?php  echo $mailbody; ?>
			</div>
			
			<?php
		}
	?>
</div>


