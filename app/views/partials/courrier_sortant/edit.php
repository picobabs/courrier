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
                    <h4 class="record-title"><?php print_lang('modifier'); ?></h4>
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
                        <form novalidate  id="" role="form" enctype="multipart/form-data"  class="form page-form form-horizontal needs-validation" action="<?php print_link("courrier_sortant/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
                                <input id="ctrl-sens"  value="<?php  echo $data['sens'] ?? '' ; ?>" type="hidden" placeholder="<?php print_lang('entrer_sens'); ?>"  required="" name="sens"  class="form-control " />
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="nature"><?php print_lang('nature'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <select required=""  id="ctrl-nature" name="nature"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                        <?php
                                                        $rec = $data['nature'];
                                                        $nature_options = $comp_model -> courrier_sortant_nature_option_list();
                                                        if(!empty($nature_options)){
                                                        foreach($nature_options as $option){
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
                                                <label class="control-label" for="numero_courrier"><?php print_lang('numero_courrier'); ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input id="ctrl-numero_courrier"  value="<?php  echo $data['numero_courrier'] ?? '' ; ?>" type="text" placeholder="<?php print_lang('entrer_numero_courrier'); ?>"  required="" name="numero_courrier"  class="form-control " />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="destinataire"><?php print_lang('destinataire'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <select required=""  id="ctrl-destinataire" data-load-select-options="en_reponse_courrier_numero" name="destinataire"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                                            <?php
                                                            $rec = $data['destinataire'];
                                                            $destinataire_options = $comp_model -> courrier_sortant_destinataire_option_list();
                                                            if(!empty($destinataire_options)){
                                                            foreach($destinataire_options as $option){
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
                                                    <label class="control-label" for="en_reponse_courrier_numero"><?php print_lang('en_r_ponse_au_courrier_numero'); ?> </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <select  id="ctrl-en_reponse_courrier_numero" data-load-path="<?php print_link('api/json/courrier_sortant_en_reponse_courrier_numero_option_list') ?>" name="en_reponse_courrier_numero"  placeholder="<?php print_lang('s_lectionnez_une_valeur'); ?>"    class="custom-select" >
                                                            <?php
                                                            $rec = $data['en_reponse_courrier_numero'];
                                                            $en_reponse_courrier_numero_options = $comp_model -> courrier_sortant_en_reponse_courrier_numero_option_list($data['destinataire']);
                                                            if(!empty($en_reponse_courrier_numero_options)){
                                                            foreach($en_reponse_courrier_numero_options as $option){
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
                                                    <label class="control-label" for="date_courrier"><?php print_lang('date_courrier'); ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input id="ctrl-date_courrier" class="form-control datepicker  datepicker"  required="" value="<?php  echo $data['date_courrier'] ?? '' ; ?>" type="datetime" name="date_courrier" placeholder="<?php print_lang('entrer_date_courrier'); ?>" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
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
                                                        <label class="control-label" for="date_envoi"><?php print_lang('date_envoi'); ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input id="ctrl-date_envoi" class="form-control datepicker  datepicker"  required="" value="<?php  echo $data['date_envoi'] ?? '' ; ?>" type="datetime" name="date_envoi" placeholder="<?php print_lang('entrer_date_envoi'); ?>" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="F j, Y" data-inline="false" data-no-calendar="false" data-mode="single" />
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
                                                            <label class="control-label" for="intitule"><?php print_lang('intitule'); ?> <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input id="ctrl-intitule"  value="<?php  echo $data['intitule'] ?? '' ; ?>" type="text" placeholder="<?php print_lang('entrer_intitule'); ?>"  required="" name="intitule"  class="form-control " />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="objet"><?php print_lang('objet'); ?> <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <textarea placeholder="<?php print_lang('entrer_objet'); ?>" id="ctrl-objet"  required="" rows="5" name="objet" class="htmleditor form-control"><?php  echo htmlspecialchars(nettoyer_html($data['objet'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                                    <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="fichier"><?php print_lang('fichier'); ?> <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <div class="dropzone required" input="#ctrl-fichier" fieldname="fichier"    data-multiple="false" dropmsg="<?php print_lang('choisissez_des_fichiers_ou_glissez_d_posez_les_fichiers_t_l_charger'); ?>"    btntext="<?php print_lang('feuilleter'); ?>" filesize="3" maximum="1">
                                                                        <input name="fichier" id="ctrl-fichier" required="" class="dropzone-input form-control" value="<?php  echo $data['fichier'] ?? '' ; ?>" type="text"  />
                                                                            <!--<div class="invalid-feedback animated bounceIn text-center"><?php print_lang('veuillez_choisir_un_fichier'); ?></div>-->
                                                                            <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php Html :: uploaded_files_list($data['fichier'], '#ctrl-fichier'); ?>
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
