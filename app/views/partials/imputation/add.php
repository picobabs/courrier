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
                    <h5 class="record-title"><?php print_lang('imputer'); ?></h5>
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
                        <form id="imputation-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("imputation/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="date_imputation"><?php print_lang('date_imputation'); ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-date_imputation" class="form-control datepicker  datepicker"  required="" value="<?php  echo $this->set_field_value('date_imputation',date_now()); ?>" type="datetime" name="date_imputation" placeholder="<?php print_lang('entrer_date_imputation'); ?>" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="Y-m-d" data-inline="false" data-no-calendar="false" data-mode="single" />
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
                                                <label class="control-label" for="date_limite_traite"><?php print_lang('date_limite_traite'); ?> </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input required="required" id="ctrl-date_limite_traite" class="form-control datepicker  datepicker"  value="<?php  echo $this->set_field_value('date_limite_traite',date('Y-m-d', strtotime('+8day'))); ?>" type="datetime" name="date_limite_traite" placeholder="<?php print_lang('entrer_date_limite_traite'); ?>" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="ctrl-delai_traitement"  value="<?php  echo $this->set_field_value('delai_traitement', Circuit::DELAI_DEFAUT); ?>" type="hidden" placeholder="<?php print_lang('entrer_delai_traitement'); ?>"  required="" name="delai_traitement"  class="form-control " />
                                            <input id="ctrl-idcourrier"  value="<?php  echo $this->set_field_value('idcourrier',""); ?>" type="hidden" placeholder="<?php print_lang('entrer_code_courrier'); ?>"  name="idcourrier"  class="form-control " />
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="iduser"><?php print_lang('destinataire'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <select required=""  id="ctrl-iduser" name="iduser"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                    <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                    <?php 
                                                                    $iduser_options = $comp_model -> imputation_iduser_option_list();
                                                                    if(!empty($iduser_options)){
                                                                    foreach($iduser_options as $option){
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $selected = $this->set_field_selected('iduser',$value, "");
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
                                                            <label class="control-label" for="instruction"><?php print_lang('instruction'); ?> </label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <textarea placeholder="<?php print_lang('entrer_instruction'); ?>" id="ctrl-instruction"  rows="5" name="instruction" class=" form-control"><?php  echo $this->set_field_value('instruction',""); ?></textarea>
                                                                <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="etat_traitement"><?php print_lang('etat_traitement'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <select required=""  id="ctrl-etat_traitement" name="etat_traitement"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                                    <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                                    <?php 
                                                                    $etat_traitement_options = $comp_model -> imputation_etat_traitement_option_list();
                                                                    if(!empty($etat_traitement_options)){
                                                                    foreach($etat_traitement_options as $option){
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $selected = $this->set_field_selected('etat_traitement',$value, Circuit::IMPUTATION_NOUVELLE);
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
                                                <input id="ctrl-niveau"  value="<?php  echo $this->set_field_value('niveau',""); ?>" type="hidden" placeholder="<?php print_lang('entrer_niveau'); ?>"  name="niveau"  class="form-control " />
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
