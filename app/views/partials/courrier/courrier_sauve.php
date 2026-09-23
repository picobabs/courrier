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
                    <h5 class="record-title"><?php print_lang('gestion_du_courrier'); ?></h5>
                </div>
                <div class="col-sm-3 ">
                    <?php if($can_add){ ?>
                    <a  class="btn btn btn-primary my-1" href="<?php print_link("courrier/add") ?>">
                        <i class="material-icons">add</i>                               
                        <?php print_lang('ajouter_un_nouveau'); ?> 
                    </a>
                    <?php } ?>
                </div>
                <div class="col-sm-4 ">
                    <form  class="search" action="<?php print_link('courrier'); ?>" method="get">
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
                    <div class="col-md-12 comp-grid">
                        <?php $this :: display_page_errors(); ?>
                        <div  class=" animated fadeIn page-content">
                            <div id="courrier-courrier_sauve-records">
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
                                                <div><?php echo nettoyer_html($data['objet'] ?? ''); ?></div>
                                                <div class="mb-2">  <?php Html :: page_link_file($data['fichier']); ?></div>
                                                <div class="mb-2">  
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('etat_etat'); ?>:  
                                                    </span>
                                                <?php echo $data['etat_etat'] ?? '' ; ?></div>
                                                <div class="mb-2">  <a href="<?php print_link("imputation/list?idcourrier=" . urlencode($data['idcourrier'] ?? '')) ?>">
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('imputations'); ?>:  
                                                    </span>
                                                <?php echo $data['Imputation'] ?? '' ; ?></a></div>
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
                                                <div class="td-btn">
                                                    <?php if($can_edit){ ?>
                                                    <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('modifier_cet_enregistrement'); ?>" href="<?php print_link("courrier/edit/$rec_id"); ?>">
                                                        <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
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
