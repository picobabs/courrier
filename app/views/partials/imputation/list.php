<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("imputation/add");
$can_edit = ACL::is_allowed("imputation/edit");
$can_view = ACL::is_allowed("imputation/view");
$can_delete = ACL::is_allowed("imputation/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data From Controller
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="grid" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <h5 class="record-title"><?php print_lang('gestion_des_imputations'); ?></h5>
                </div>
                <div class="col-sm-4 ">
                    <a  class="btn btn-primary" href="<?php print_link("imputation/add?idcourrier=". $_GET['idcourrier']) ?>">
                        <?php print_lang('nouvelle_imputation'); ?> 
                    </a>
                </div>
                <div class="col-md-12 comp-grid">
                    <a  class="btn btn-primary" href="<?php print_link("courrier") ?>">
                        <?php print_lang('retour'); ?> 
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class=" animated fadeIn page-content">
                        <div id="imputation-list-records">
                            <?php
                            if(!empty($records)){
                            ?>
                            <div id="page-report-body">
                                <div class="row sm-gutters page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <!--record-->
                                    <?php
                                    $counter = 0;
                                    foreach($records as $data){
                                    $rec_id = (!empty($data['idimputation']) ? urlencode($data['idimputation'] ?? '') : null);
                                    $counter++;
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="arrondic p-2 mb-3 animated bounceIn">
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-flatpickr="{altFormat: 'Y-m-d', enableTime: false, minDate: '', maxDate: ''}" 
                                                    data-value="<?php echo $data['date_imputation'] ?? '' ; ?>" 
                                                    data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                    data-name="date_imputation" 
                                                    data-title="Entrer Date Imputation" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="flatdatetimepicker" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('date_imputation'); ?>:  
                                                    </span>
                                                    <?php echo $data['date_imputation'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-value="<?php echo $data['delai_traitement'] ?? '' ; ?>" 
                                                    data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                    data-name="delai_traitement" 
                                                    data-title="Entrer Delai Traitement" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="text" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('delai_traitement_jours_'); ?>:  
                                                    </span>
                                                    <?php echo $data['delai_traitement'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="mb-2">  <?php Html :: page_link_file($data['ftraite']); ?></div>
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                    data-name="instruction" 
                                                    data-title="Entrer Instruction" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="textarea" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('instructions'); ?>:  
                                                    </span>
                                                    <?php echo $data['instruction'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-value="<?php echo $data['etat_imputation_etat_imputation'] ?? '' ; ?>" 
                                                    data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("etat_imputation/editfield/" . urlencode($data['idetatimputation'] ?? '')); ?>" 
                                                    data-name="etat_imputation" 
                                                    data-title="Entrer Etat Imputation" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="text" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('etat_imputation_'); ?>:  
                                                    </span>
                                                    <?php echo $data['etat_imputation_etat_imputation'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-value="<?php echo $data['user_identification'] ?? '' ; ?>" 
                                                    data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("user/editfield/" . urlencode($data['iduser'] ?? '')); ?>" 
                                                    data-name="identification" 
                                                    data-title="Entrer Identification" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="text" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('destinataire'); ?>:  
                                                    </span>
                                                    <?php echo $data['user_identification'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="mb-2">  <?php Html :: page_img($data['user_avatar'],50,50,1); ?></div>
                                            <div class="mb-2">  
                                                <span <?php if($can_edit){ ?> data-flatpickr="{ enableTime: false, minDate: '', maxDate: ''}" 
                                                    data-value="<?php echo $data['date_limite_traite'] ?? '' ; ?>" 
                                                    data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                    data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                    data-name="date_limite_traite" 
                                                    data-title="Entrer Date Limite Traite" 
                                                    data-placement="left" 
                                                    data-toggle="click" 
                                                    data-type="flatdatetimepicker" 
                                                    data-mode="popover" 
                                                    data-showbuttons="left" 
                                                    class="is-editable" <?php } ?>>
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('date_limite_traite'); ?>:  
                                                    </span>
                                                    <?php echo $data['date_limite_traite'] ?? '' ; ?> 
                                                </span>
                                            </div>
                                            <div class="td-btn">
                                                <?php if($can_edit){ ?>
                                                <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('modifier_cet_enregistrement'); ?>" href="<?php print_link("imputation/edit/$rec_id"); ?>">
                                                    <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="<?php print_lang('supprimer_cet_enregistrement'); ?>" href="<?php print_link("imputation/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
                                                    <i class="material-icons">clear</i>
                                                    <?php print_lang('effacer'); ?>
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    }
                                    ?>
                                    <!--endrecord-->
                                </div>
                                <div class="row sm-gutters search-data" id="search-data-<?php echo $page_element_id; ?>"></div>
                                <div>
                                </div>
                            </div>
                            <?php
                            if($show_footer == true){
                            ?>
                            <div class=" border-top mt-2">
                                <div class="row justify-content-center">    
                                    <div class="col-md-auto">   
                                    </div>
                                    <div class="col">   
                                        <?php
                                        if($show_pagination == true){
                                        $pager = new Pagination($total_records, $record_count);
                                        $pager->route = $this->route;
                                        $pager->show_page_count = true;
                                        $pager->show_record_count = true;
                                        $pager->show_page_limit =true;
                                        $pager->limit_count = $this->limit_count;
                                        $pager->show_page_number_list = true;
                                        $pager->pager_link_range=5;
                                        $pager->render();
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            }
                            else{
                            ?>
                            <div class="text-muted  animated bounce p-3">
                                <h4><i class="material-icons">block</i> <?php print_lang('aucun_enregistrement_trouv_'); ?></h4>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
