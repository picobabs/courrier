<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("courrier/add");
$can_edit = ACL::is_allowed("courrier/edit");
$can_view = ACL::is_allowed("courrier/view");
$can_delete = ACL::is_allowed("courrier/delete");
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
                    <h5 class="record-title"><?php print_lang('recherche_du_courrier'); ?></h5>
                </div>
                <div class="col-sm-3 ">
                </div>
                <div class="col-sm-4 ">
                    <form method="get" action="<?php print_link($current_page) ?>" class="form filter-form">
                        <form  class="search" action="<?php print_link('courrier/recherche'); ?>" method="get">
                            <div class="input-group">
                                <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="<?php print_lang(''); ?>" />
                                    <div class="input-group-append">
                                        <button class="btn btn-primary"><i class="material-icons">search</i></button>
                                    </div>
                                </div>
                            </form>
                            <hr />
                            <div class="form-group text-center">
                                <button class="btn btn-primary">Filter</button>
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
                                        <a class="text-decoration-none" href="<?php print_link('courrier'); ?>">
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
                                        <a class="text-decoration-none" href="<?php print_link('courrier'); ?>">
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
                    <div class="col-sm-3 comp-grid">
                        <form method="get" action="<?php print_link($current_page) ?>" class="form filter-form">
                            <div class="card mb-3">
                                <div class="card-header h4 h4"><h5>Date Reception</h5></div>
                                <div class="p-2">
                                    <input class="form-control datepicker"  value="<?php echo $this->set_field_value('courrier_date_reception') ?>" type="datetime"  name="courrier_date_reception" placeholder="<?php print_lang(''); ?>" data-enable-time="" data-date-format="Y-m-d" data-alt-format="M j, Y" data-inline="false" data-no-calendar="false" data-mode="range" />
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header h4 h4"><h5>Expediteur</h5></div>
                                    <div class="p-2">
                                        <select   name="courrier_expediteur" class="form-control custom ">
                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                            <?php 
                                            $courrier_expediteur_options = $comp_model -> courrier_courrierexpediteur_option_list();
                                            if(!empty($courrier_expediteur_options)){
                                            foreach($courrier_expediteur_options as $option){
                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                            $selected = $this->set_field_selected('courrier_expediteur',$value);
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
                                <div class="card mb-3">
                                    <div class="card-header h4 h4"><h5> Etat courrier </h5></div>
                                    <div class="p-2">
                                        <select   name="courrier_etat" class="form-control custom ">
                                            <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                            <?php 
                                            $courrier_etat_options = $comp_model -> courrier_courrieretat_option_list();
                                            if(!empty($courrier_etat_options)){
                                            foreach($courrier_etat_options as $option){
                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                            $selected = $this->set_field_selected('courrier_etat',$value);
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
                        <div class="col-sm-9 comp-grid">
                            <?php $this :: display_page_errors(); ?>
                            <div class="filter-tags mb-2">
                                <?php
                                if(!empty($_GET['courrier_date_reception'])){
                                ?>
                                <div class="filter-chip card bg-light">
                                    <b>Courrier Date Reception :</b> 
                                    <?php
                                    $date_val = get_value('courrier_date_reception');
                                    $formated_date = "";
                                    if(str_contains('-to-', $date_val)){
                                    //if value is a range date
                                    $vals = explode('-to-' , str_replace(' ' , '' , $date_val));
                                    $startdate = $vals[0];
                                    $enddate = $vals[1];
                                    $formated_date = format_date($startdate, 'jS F, Y') . ' <span class="text-muted">&#10148;</span> ' . format_date($enddate, 'jS F, Y');
                                    }
                                    elseif(str_contains(',', $date_val)){
                                    //multi date values
                                    $vals = explode(',' , str_replace(' ' , '' , $date_val));
                                    $formated_arrs = array_map(function($date){return format_date($date, 'jS F, Y');}, $vals);
                                    $formated_date = implode(' <span class="text-info">&#11161;</span> ', $formated_arrs);
                                    }
                                    else{
                                    $formated_date = format_date($date_val, 'jS F, Y');
                                    }
                                    echo  $formated_date;
                                    $remove_link = unset_get_value('courrier_date_reception', $this->route->page_url);
                                    ?>
                                    <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                        &times;
                                    </a>
                                </div>
                                <?php
                                }
                                ?>
                                <?php
                                if(!empty(get_value('courrier_expediteur'))){
                                ?>
                                <div class="filter-chip card bg-light">
                                    <b>Courrier Expediteur :</b> 
                                    <?php 
                                    if(get_value('courrier_expediteurlabel')){
                                    echo get_value('courrier_expediteurlabel');
                                    }
                                    else{
                                    echo get_value('courrier_expediteur');
                                    }
                                    $remove_link = unset_get_value('courrier_expediteur', $this->route->page_url);
                                    ?>
                                    <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                        &times;
                                    </a>
                                </div>
                                <?php
                                }
                                ?>
                                <?php
                                if(!empty(get_value('courrier_etat'))){
                                ?>
                                <div class="filter-chip card bg-light">
                                    <b>Courrier Etat :</b> 
                                    <?php 
                                    if(get_value('courrier_etatlabel')){
                                    echo get_value('courrier_etatlabel');
                                    }
                                    else{
                                    echo get_value('courrier_etat');
                                    }
                                    $remove_link = unset_get_value('courrier_etat', $this->route->page_url);
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
                                <div id="courrier-recherche-records">
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
                                                <div class="arrondic p-2 mb-3 animated bounceIn">
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('numero_courrier'); ?>:  
                                                        </span>
                                                    <?php echo $data['numero_courrier'] ?? '' ; ?></div>
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('date_saisie'); ?>:  
                                                        </span>
                                                    <?php echo $data['date_saisie'] ?? '' ; ?></div>
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('date_courrier'); ?>:  
                                                        </span>
                                                    <?php echo $data['date_courrier'] ?? '' ; ?></div>
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('date_reception'); ?>:  
                                                        </span>
                                                    <?php echo $data['date_reception'] ?? '' ; ?></div>
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('intitule'); ?>:  
                                                        </span>
                                                    <?php echo $data['intitule'] ?? '' ; ?></div>
                                                    <div><?php echo $data['objet'] ?? '' ; ?></div>
                                                    <div class="mb-2">  <?php Html :: page_link_file($data['fichier']); ?></div>
                                                    <div class="mb-2">  
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('etatcourrier'); ?>:  
                                                        </span>
                                                    <?php echo $data['etat_etat'] ?? '' ; ?></div>
                                                    <div class="mb-2">  <a href="<?php print_link("evenement/suivi?idcourrier=" . urlencode($data['idcourrier'] ?? '')) ?>"><?php echo $data['suivi'] ?? '' ; ?></a></div>
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
