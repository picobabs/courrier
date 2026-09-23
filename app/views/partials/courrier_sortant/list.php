<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("courrier_sortant/add");
$can_edit = ACL::is_allowed("courrier_sortant/edit");
$can_view = ACL::is_allowed("courrier_sortant/view");
$can_delete = ACL::is_allowed("courrier_sortant/delete");
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
                    <h4 class="record-title"><?php print_lang('courrier_sortant'); ?></h4>
                </div>
                <div class="col-sm-3 ">
                    <?php if($can_add){ ?>
                    <a  class="btn btn btn-primary my-1" href="<?php print_link("courrier_sortant/add") ?>">
                        <i class="material-icons">add</i>                               
                        <?php print_lang('ajouter_un_nouveau'); ?> 
                    </a>
                    <?php } ?>
                </div>
                <div class="col-sm-4 ">
                    <form  class="search" action="<?php print_link('courrier_sortant'); ?>" method="get">
                        <div class="input-group">
                            <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="<?php print_lang('chercher'); ?>" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 comp-grid">
                        <div class="">
                            <!-- Page bread crumbs components-->
                            <?php
                            if(!empty($field_name) || !empty($_GET['search'])){
                            ?>
                            <hr class="sm d-block d-sm-none" />
                            <nav class="page-header-breadcrumbs mt-2" aria-label="breadcrumb">
                                <ul class="breadcrumb m-0 p-1">
                                    <?php
                                    if(!empty($field_name)){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('courrier_sortant'); ?>">
                                            <i class="material-icons">arrow_back</i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <?php echo (get_value("tag") ? get_value("tag")  :  make_readable($field_name)); ?>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold">
                                        <?php echo (get_value("label") ? get_value("label")  :  make_readable(urldecode($field_value))); ?>
                                    </li>
                                    <?php 
                                    }   
                                    ?>
                                    <?php
                                    if(get_value("search")){
                                    ?>
                                    <li class="breadcrumb-item">
                                        <a class="text-decoration-none" href="<?php print_link('courrier_sortant'); ?>">
                                            <i class="material-icons">arrow_back</i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item text-capitalize">
                                        <?php print_lang('chercher'); ?>
                                    </li>
                                    <li  class="breadcrumb-item active text-capitalize font-weight-bold"><?php echo get_value("search"); ?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <!--End of Page bread crumbs components-->
                            <?php
                            }
                            ?>
                        </div>
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
                    <div class="col-sm-2 comp-grid">
                        <form method="get" action="<?php print_link($current_page) ?>" class="form filter-form">
                            <div class="card mb-3">
                                <div class="card-header h4 h4">Destinataire</div>
                                <div class="p-2">
                                    <select   name="courrier_sortant_destinataire" class="form-control custom ">
                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                        <?php 
                                        $courrier_sortant_destinataire_options = $comp_model -> courrier_sortant_courrier_sortantdestinataire_option_list();
                                        if(!empty($courrier_sortant_destinataire_options)){
                                        foreach($courrier_sortant_destinataire_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        $selected = $this->set_field_selected('courrier_sortant_destinataire',$value);
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
                            <hr />
                            <div class="form-group text-center">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-10 comp-grid">
                        <?php $this :: display_page_errors(); ?>
                        <div class="filter-tags mb-2">
                            <?php
                            if(!empty(get_value('courrier_sortant_destinataire'))){
                            ?>
                            <div class="filter-chip card bg-light">
                                <b>Courrier Sortant Destinataire :</b> 
                                <?php 
                                if(get_value('courrier_sortant_destinatairelabel')){
                                echo get_value('courrier_sortant_destinatairelabel');
                                }
                                else{
                                echo get_value('courrier_sortant_destinataire');
                                }
                                $remove_link = unset_get_value('courrier_sortant_destinataire', $this->route->page_url);
                                ?>
                                <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                    &times;
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div  class=" animated fadeIn page-content">
                            <div id="courrier_sortant-list-records">
                                <?php
                                if(!empty($records)){
                                ?>
                                <div id="page-report-body">
                                    <div class="row sm-gutters page-data" id="page-data-<?php echo $page_element_id; ?>">
                                        <!--record-->
                                        <?php
                                        $counter = 0;
                                        foreach($records as $data){
                                        $rec_id = (!empty($data['idcourrier']) ? urlencode($data['idcourrier'] ?? '') : null);
                                        $counter++;
                                        ?>
                                        <div class="col-sm-6">
                                            <div class="bg-light p-2 mb-3 animated bounceIn">
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['nature_courrier_nature'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("nature_courrier/editfield/" . urlencode($data['idnature'] ?? '')); ?>" 
                                                        data-name="nature" 
                                                        data-title="Entrer Nature" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('nature_courrier'); ?>:  
                                                        </span>
                                                        <?php echo $data['nature_courrier_nature'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['numero_courrier'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                        data-name="numero_courrier" 
                                                        data-title="Entrer Numero Courrier" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('numero_courrier'); ?>:  
                                                        </span>
                                                        <?php echo $data['numero_courrier'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-source='<?php 
                                                        $dependent_field = (!empty($data['destinataire']) ? urlencode($data['destinataire'] ?? '') : null);
                                                        print_link('api/json/courrier_sortant_en_reponse_courrier_numero_option_list/'.$dependent_field); 
                                                        ?>' 
                                                        data-value="<?php echo $data['en_reponse_courrier_numero'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                        data-name="en_reponse_courrier_numero" 
                                                        data-title="Sélectionnez une valeur" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="select" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('en_r_ponse_au_courrier_numero'); ?>:  
                                                        </span>
                                                        <?php echo $data['en_reponse_courrier_numero'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('date_saisie'); ?>:  
                                                    </span>
                                                <?php echo $data['date_saisie'] ?? '' ; ?></div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-flatpickr="{ enableTime: false, minDate: '', maxDate: ''}" 
                                                        data-value="<?php echo $data['date_courrier'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                        data-name="date_courrier" 
                                                        data-title="Entrer Date Courrier" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="flatdatetimepicker" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('date_courrier'); ?>:  
                                                        </span>
                                                        <?php echo $data['date_courrier'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-flatpickr="{ enableTime: false, minDate: '', maxDate: ''}" 
                                                        data-value="<?php echo $data['date_envoi'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                        data-name="date_envoi" 
                                                        data-title="Entrer Date Envoi" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="flatdatetimepicker" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('date_envoi'); ?>:  
                                                        </span>
                                                        <?php echo $data['date_envoi'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['expediteur_expediteur'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("expediteur/editfield/" . urlencode($data['idexpediteur'] ?? '')); ?>" 
                                                        data-name="expediteur" 
                                                        data-title="Entrer Identité Expediteur" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('destinataire'); ?>:  
                                                        </span>
                                                        <?php echo $data['expediteur_expediteur'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['intitule'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                        data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                        data-name="intitule" 
                                                        data-title="Entrer Intitule" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('intitule'); ?>:  
                                                        </span>
                                                        <?php echo $data['intitule'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div><?php echo nettoyer_html($data['objet'] ?? ''); ?></div>
                                                <div class="mb-2">  <?php Html :: page_link_file($data['fichier']); ?></div>
                                                <div class="td-btn">
                                                    <?php if($can_view){ ?>
                                                    <a class="btn btn-sm btn-success has-tooltip" title="<?php print_lang('voir_l_enregistrement'); ?>" href="<?php print_link("courrier_sortant/view/$rec_id"); ?>">
                                                        <i class="material-icons">visibility</i> <?php print_lang('vue'); ?>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if($can_edit){ ?>
                                                    <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('modifier_cet_enregistrement'); ?>" href="<?php print_link("courrier_sortant/edit/$rec_id"); ?>">
                                                        <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if($can_delete){ ?>
                                                    <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="<?php print_lang('supprimer_cet_enregistrement'); ?>" href="<?php print_link("courrier_sortant/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
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
