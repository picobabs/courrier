<?php
$comp_model = new SharedController;
$page_element_id = "edit-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title"><?php print_lang('traitement_imputation'); ?></h4>
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
                        <form novalidate  id="" role="form" enctype="multipart/form-data"  class="form page-form form-horizontal needs-validation" action="<?php print_link("imputation/edit_traitement/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
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
                                                    $rec = $data['etat_traitement'];
                                                    $etat_traitement_options = $comp_model -> imputation_etat_traitement_option_list();
                                                    if(!empty($etat_traitement_options)){
                                                    foreach($etat_traitement_options as $option){
                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                    $selected = ( $value == $rec ? 'selected' : null );
                                                    ?>
                                                    <option 
                                                        <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
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
                                <input id="ctrl-idcourrier"  value="<?php  echo $data['idcourrier'] ?? '' ; ?>" type="hidden" placeholder="<?php print_lang('entrer_idcourrier'); ?>" list="idcourrier_list"  required="" name="idcourrier"  class="form-control " />
                                    <datalist id="idcourrier_list">
                                        <?php 
                                        $idcourrier_options = $comp_model -> imputation_idcourrier_option_list();
                                        if(!empty($idcourrier_options)){
                                        foreach($idcourrier_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        ?>
                                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                        <?php
                                        }
                                        }
                                        ?>
                                    </datalist>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="iduser"><?php print_lang('destinataire'); ?> </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <select  disabled id="ctrl-iduser" name="iduser"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                        <?php
                                                        $rec = $data['iduser'];
                                                        $iduser_options = $comp_model -> imputation_iduser_option_list_2();
                                                        if(!empty($iduser_options)){
                                                        foreach($iduser_options as $option){
                                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                                        $selected = ( $value == $rec ? 'selected' : null );
                                                        ?>
                                                        <option 
                                                            <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
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
                                                <label class="control-label" for="ftraite"><?php print_lang('fichier_traite'); ?> </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <div class="dropzone " input="#ctrl-ftraite" fieldname="ftraite"    data-multiple="false" dropmsg="<?php print_lang('choose_files_or_drag_and_drop_files_to_upload'); ?>"    btntext="<?php print_lang('feuilleter'); ?>" filesize="3" maximum="1">
                                                        <input name="ftraite" id="ctrl-ftraite" class="dropzone-input form-control" value="<?php  echo $data['ftraite'] ?? '' ; ?>" type="text"  />
                                                            <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                            <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <?php Html :: uploaded_files_list($data['ftraite'], '#ctrl-ftraite'); ?>
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
                                                        <textarea placeholder="<?php print_lang('entrer_instruction'); ?>" id="ctrl-instruction"  readonly rows="5" name="instruction" class=" form-control"><?php  echo $data['instruction'] ?? '' ; ?></textarea>
                                                        <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-ajax-status"></div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-primary" type="submit">
                                            <?php print_lang('r_viser'); ?>
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
