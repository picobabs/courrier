<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h5 class="record-title"><?php print_lang('fiche_user'); ?></h5>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form id="user-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("user/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="identification"><?php print_lang('identification'); ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-identification"  value="<?php  echo $this->set_field_value('identification',""); ?>" type="text" placeholder="<?php print_lang('entrer_identification'); ?>"  required="" name="identification"  class="form-control " />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="login"><?php print_lang('login'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input id="ctrl-login"  value="<?php  echo $this->set_field_value('login',""); ?>" type="text" placeholder="<?php print_lang('entrer_login'); ?>"  required="" name="login"  data-url="api/json/user_login_value_exist/" data-loading-msg="<?php print_lang('v_rifier_les_disponibilit_s_'); ?>" data-available-msg="<?php print_lang('disponible'); ?>" data-unavailable-msg="<?php print_lang('indisponible'); ?>" class="form-control  ctrl-check-duplicate" />
                                                        <div class="check-status"></div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="password"><?php print_lang('password'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input id="ctrl-password"  value="<?php  echo $this->set_field_value('password',""); ?>" type="password" placeholder="<?php print_lang('entrer_password'); ?>" maxlength="255"  required="" name="password"  class="form-control  password password-strength" />
                                                            <div class="input-group-append cursor-pointer btn-toggle-password">
                                                                <span class="input-group-text"><i class="material-icons">visibility</i></span>
                                                            </div>
                                                        </div>
                                                        <div class="password-strength-msg">
                                                            <small class="font-weight-bold"><?php print_lang('devrait_contenir'); ?></small>
                                                            <small class="length chip">6 <?php print_lang('caract_res_minimum'); ?></small>
                                                            <small class="caps chip"><?php print_lang('lettre_capitale'); ?></small>
                                                            <small class="number chip"><?php print_lang('nombre'); ?></small>
                                                            <small class="special chip"><?php print_lang('symbole'); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="confirm_password"><?php print_lang('confirm_password'); ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input id="ctrl-password-confirm" data-match="#ctrl-password"  class="form-control password-confirm " type="password" name="confirm_password" required placeholder="<?php print_lang('confirm_password'); ?>" />
                                                                <div class="input-group-append cursor-pointer btn-toggle-password">
                                                                    <span class="input-group-text"><i class="material-icons">visibility</i></span>
                                                                </div>
                                                                <div class="invalid-feedback">
                                                                    Password does not match
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="emailuser"><?php print_lang('adresse_email'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input id="ctrl-emailuser"  value="<?php  echo $this->set_field_value('emailuser',""); ?>" type="email" placeholder="<?php print_lang('entrer_adresse_email'); ?>"  required="" name="emailuser"  data-url="api/json/user_emailuser_value_exist/" data-loading-msg="<?php print_lang('v_rifier_les_disponibilit_s_'); ?>" data-available-msg="<?php print_lang('disponible'); ?>" data-unavailable-msg="<?php print_lang('indisponible'); ?>" class="form-control  ctrl-check-duplicate" />
                                                                    <div class="check-status"></div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="avatar"><?php print_lang('avatar'); ?> <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <div class="dropzone required" input="#ctrl-avatar" fieldname="avatar"    data-multiple="false" dropmsg="<?php print_lang('choose_files_or_drag_and_drop_files_to_upload'); ?>"    btntext="<?php print_lang('feuilleter'); ?>" extensions=".jpg,.png,.gif,.jpeg" filesize="3" maximum="1">
                                                                        <input name="avatar" id="ctrl-avatar" required="" class="dropzone-input form-control" value="<?php  echo $this->set_field_value('avatar',""); ?>" type="text"  />
                                                                            <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                                            <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label class="control-label" for="user_role_id"><?php print_lang('profil'); ?> <span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="">
                                                                        <select required=""  id="ctrl-user_role_id" name="user_role_id"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                            <?php 
                                                                            $user_role_id_options = $comp_model -> user_user_role_id_option_list();
                                                                            if(!empty($user_role_id_options)){
                                                                            foreach($user_role_id_options as $option){
                                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                            $selected = $this->set_field_selected('user_role_id',$value, "");
                                                                            ?>
                                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                            <?php
                                                                            }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label class="control-label" for="niveau_imputation"><?php print_lang('niveau_imputation'); ?> </label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="">
                                                                        <select id="ctrl-niveau_imputation" name="niveau_imputation"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                            <?php 
                                                                            $niveau_imputation_options = $comp_model -> user_niveau_imputation_option_list();
                                                                            if(!empty($niveau_imputation_options)){
                                                                            foreach($niveau_imputation_options as $option){
                                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                            $selected = $this->set_field_selected('niveau_imputation',$value, "");
                                                                            ?>
                                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                            <?php
                                                                            }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label class="control-label" for="direction"><?php print_lang('direction'); ?> </label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="">
                                                                        <select id="ctrl-direction" data-load-select-options="division" name="direction"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                            <?php 
                                                                            $direction_options = $comp_model -> user_direction_option_list();
                                                                            if(!empty($direction_options)){
                                                                            foreach($direction_options as $option){
                                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                            $selected = $this->set_field_selected('direction',$value, "");
                                                                            ?>
                                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                                <?php echo $label; ?>
                                                                            </option>
                                                                            <?php
                                                                            }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label class="control-label" for="division"><?php print_lang('division'); ?> </label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="">
                                                                        <select id="ctrl-division" data-load-path="<?php print_link('api/json/user_division_option_list') ?>" name="division"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-submit-btn-holder text-center mt-3">
                                                        <div class="form-ajax-status"></div>
                                                        <button class="btn btn-primary" type="submit">
                                                            <?php print_lang('soumettre'); ?>
                                                            <i class="material-icons">send</i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

<script>
// Rattachement hierarchique : Direction et Division ne s'affichent que pour
// les profils que l'organigramme rattache effectivement a une structure.
//
//   aucun rattachement   Directeur General, Secretaire General,
//                        Coordonnateur (une cellule ne depend pas d'une
//                        direction), Administrateur, Chef bureau courrier
//   direction seulement  Directeur
//   direction + division Chef de Division, Destinataire, Saisie
//
// Les libelles sont compares sans casse ni accents, et par correspondance
// partielle : "Chef de Division / Cellule" reconnait bien "chef de division".
// Un profil inconnu affiche les deux champs, ce qui est le comportement le
// moins surprenant.
$(function () {
    var SANS_RATTACHEMENT = [
        'directeur general', 'secretaire general',
        'coordonnateur', 'coordinateur',
        'administrator', 'administrateur',
        'chef bureau courrier'
    ];
    var DIRECTION_SEULE = ['directeur'];
    var AVEC_DIVISION   = ['chef de division', 'chefs de division', 'destinataire', 'saisie'];

    function normaliser(t) {
        t = (t || '').toString().trim().toLowerCase();
        return t.replace(/[\u00e0\u00e2\u00e4]/g, 'a').replace(/[\u00e9\u00e8\u00ea\u00eb]/g, 'e')
                .replace(/[\u00ee\u00ef]/g, 'i').replace(/[\u00f4\u00f6]/g, 'o')
                .replace(/[\u00f9\u00fb\u00fc]/g, 'u').replace(/\u00e7/g, 'c')
                .replace(/\s+/g, ' ');
    }

    function contient(libelle, liste) {
        for (var i = 0; i < liste.length; i++) {
            if (libelle.indexOf(liste[i]) !== -1) { return true; }
        }
        return false;
    }

    var $profil    = $('#ctrl-user_role_id');
    var $direction = $('#ctrl-direction');
    var $division  = $('#ctrl-division');
    if (!$profil.length || !$direction.length || !$division.length) { return; }

    var $blocDirection = $direction.closest('.form-group');
    var $blocDivision  = $division.closest('.form-group');

    function appliquer() {
        var libelle = normaliser($profil.find('option:selected').text());

        // L'ordre compte : "chef de division" contient "division" et non
        // "directeur", mais "directeur general" contient "directeur".
        // On teste donc du plus specifique au plus general.
        var direction, division;
        if (contient(libelle, SANS_RATTACHEMENT)) {
            direction = false; division = false;
        } else if (contient(libelle, AVEC_DIVISION)) {
            direction = true;  division = true;
        } else if (contient(libelle, DIRECTION_SEULE)) {
            direction = true;  division = false;
        } else {
            direction = true;  division = true;
        }

        // On vide un champ avant de le masquer : sans cela une valeur choisie
        // puis devenue invisible partirait quand meme a l'enregistrement.
        if (!direction) { $direction.val(''); }
        if (!division)  { $division.val(''); }

        $blocDirection.toggle(direction);
        $blocDivision.toggle(division);
    }

    $profil.on('change', appliquer);
    appliquer();
});
</script>
