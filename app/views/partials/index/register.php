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
                    <h4 class="record-title"><?php print_lang('enregistrement_de_l_utilisateur'); ?></h4>
                </div>
                <div class="col-sm-6 comp-grid">
                    <div class="">
                        <div class="text-center">
                            <?php print_lang('vous_avez_d_j_un_compte_'); ?>  <a class="btn btn-primary" href="<?php print_link('') ?>"> <?php print_lang('s_identifier'); ?></a>
                        </div>
                    </div>
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
                        <form id="user-userregister-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("index/register?csrf_token=$csrf_token") ?>" method="post">
                            <!--[main-form-start]-->
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
                                                        <input id="ctrl-password"  value="<?php  echo $this->set_field_value('password',""); ?>" type="password" placeholder="<?php print_lang('entrer_password'); ?>"  required="" name="password"  class="form-control  password password-strength" />
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
                                                            <label class="control-label" for="emailuser"><?php print_lang('emailuser'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input id="ctrl-emailuser"  value="<?php  echo $this->set_field_value('emailuser',""); ?>" type="email" placeholder="<?php print_lang('entrer_emailuser'); ?>"  required="" name="emailuser"  data-url="api/json/user_emailuser_value_exist/" data-loading-msg="<?php print_lang('v_rifier_les_disponibilit_s_'); ?>" data-available-msg="<?php print_lang('disponible'); ?>" data-unavailable-msg="<?php print_lang('indisponible'); ?>" class="form-control  ctrl-check-duplicate" />
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
                                                                    <div class="dropzone required" input="#ctrl-avatar" fieldname="avatar"    data-multiple="false" dropmsg="<?php print_lang('choisissez_des_fichiers_ou_glissez_d_posez_les_fichiers_t_l_charger'); ?>"    btntext="<?php print_lang('feuilleter'); ?>" extensions=".jpg,.png,.gif,.jpeg" filesize="3" maximum="1">
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
                                                                    <label class="control-label" for="date_deleted"><?php print_lang('date_deleted'); ?> <span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="input-group">
                                                                        <input id="ctrl-date_deleted" class="form-control datepicker  datepicker" required="" value="<?php  echo $this->set_field_value('date_deleted',""); ?>" type="datetime"  name="date_deleted" placeholder="<?php print_lang('entrer_date_deleted'); ?>" data-enable-time="true" data-min-date="" data-max-date="" data-date-format="Y-m-d H:i:S" data-alt-format="F j, Y - H:i" data-inline="false" data-no-calendar="false" data-mode="single" /> 
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group ">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <label class="control-label" for="is_deleted"><?php print_lang('is_deleted'); ?> <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <div class="">
                                                                            <input id="ctrl-is_deleted"  value="<?php  echo $this->set_field_value('is_deleted',""); ?>" type="text" placeholder="<?php print_lang('entrer_is_deleted'); ?>"  required="" name="is_deleted"  class="form-control " />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <label class="control-label" for="user_role_id"><?php print_lang('user_role_id'); ?> <span class="text-danger">*</span></label>
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
                                                                            <label class="control-label" for="niveau_imputation"><?php print_lang('niveau_imputation'); ?> <span class="text-danger">*</span></label>
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <div class="">
                                                                                <input id="ctrl-niveau_imputation"  value="<?php  echo $this->set_field_value('niveau_imputation',""); ?>" type="number" placeholder="<?php print_lang('entrer_niveau_imputation'); ?>" step="1"  required="" name="niveau_imputation"  class="form-control " />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <label class="control-label" for="direction"><?php print_lang('direction'); ?> <span class="text-danger">*</span></label>
                                                                            </div>
                                                                            <div class="col-sm-8">
                                                                                <div class="">
                                                                                    <input id="ctrl-direction"  value="<?php  echo $this->set_field_value('direction',""); ?>" type="number" placeholder="<?php print_lang('entrer_direction'); ?>" step="1"  required="" name="direction"  class="form-control " />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group ">
                                                                            <div class="row">
                                                                                <div class="col-sm-4">
                                                                                    <label class="control-label" for="division"><?php print_lang('division'); ?> <span class="text-danger">*</span></label>
                                                                                </div>
                                                                                <div class="col-sm-8">
                                                                                    <div class="">
                                                                                        <input id="ctrl-division"  value="<?php  echo $this->set_field_value('division',""); ?>" type="number" placeholder="<?php print_lang('entrer_division'); ?>" step="1"  required="" name="division"  class="form-control " />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group ">
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        <label class="control-label" for="online"><?php print_lang('online'); ?> <span class="text-danger">*</span></label>
                                                                                    </div>
                                                                                    <div class="col-sm-8">
                                                                                        <div class="">
                                                                                            <input id="ctrl-online"  value="<?php  echo $this->set_field_value('online',""); ?>" type="number" placeholder="<?php print_lang('entrer_online'); ?>" step="1"  required="" name="online"  class="form-control " />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--[main-form-end]-->
                                                                            <div class="form-group form-submit-btn-holder text-center mt-3">
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
                                                