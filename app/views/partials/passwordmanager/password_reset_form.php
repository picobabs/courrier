<div class="container">
	<h3><?php print_lang('gestionnaire_de_r_initialisation_de_mot_de_passe'); ?></h3>
	<hr />
	<div class="row">
		<div class="col-sm-6">
			<?php $page_link = $this->set_current_page_link(); ?>
			<form method="post" action="<?php print_link($page_link); ?>">
				<?php Html::csrf_token(); ?>
				<?php 
					$this :: display_page_errors();			
				?>
				<div class="form-group">
					<label><?php print_lang('nouveau_mot_de_passe'); ?></label>
					<input placeholder="Your New Password" required value="" class="form-control default" name="password" id="txtpass" type="password" />
				</div>
				<div class="form-group">
					<label><?php print_lang('confirmer_le_nouveau_mot_de_passe'); ?></label>
					<input placeholder="Confirm Password" required class="form-control default" name="cpassword" id="txtcpass" type="password" />
				</div>
				<div class="mt-2 "><button  class="btn btn-success" type="submit"><?php print_lang('changer_le_mot_de_passe'); ?></button></div>
			</form>
		</div>
	</div>
</div>
