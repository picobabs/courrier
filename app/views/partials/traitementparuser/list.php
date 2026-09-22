<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("traitementparuser/add");
$can_edit = ACL::is_allowed("traitementparuser/edit");
$can_view = ACL::is_allowed("traitementparuser/view");
$can_delete = ACL::is_allowed("traitementparuser/delete");
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
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="arrondi p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <h5 class="record-title"><?php print_lang('traitement_des_imputations_par_destinataire'); ?></h5>
                </div>
                <div class="col-sm-4 ">
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
                                    <a class="text-decoration-none" href="<?php print_link('traitementparuser'); ?>">
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
                                    <a class="text-decoration-none" href="<?php print_link('traitementparuser'); ?>">
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
                        <div id="traitementparuser-list-records">
                            <div id="page-report-body" class="table-responsive">
                                <table class="table  table-striped table-sm text-left">
                                    <thead class="table-header bg-light">
                                        <tr>
                                            <th class="td-sno">#</th>
                                            <th  class="td-user"> <?php print_lang('destinataire'); ?></th>
                                            <th  class="td-user_avatar"> <?php print_lang('photo'); ?></th>
                                            <th  class="td-Nouvelle_imputation"> <?php print_lang('imputations_non_encore_trait_es'); ?></th>
                                            <th  class="td-Traitement_en_cours"> <?php print_lang('imputations_en_cours_de_traitement'); ?></th>
                                            <th  class="td-Imputation_retournee"> <?php print_lang('imputations_trait_es_et_retourn_es'); ?></th>
                                            <th  class="td-Total"> <?php print_lang('total'); ?></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    if(!empty($records)){
                                    ?>
                                    <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                        <!--record-->
                                        <?php
                                        $counter = 0;
                                        $sum_of_Nouvelle_imputation = 0;
                                        $sum_of_Traitement_en_cours = 0;
                                        $sum_of_Imputation_retournee = 0;
                                        $sum_of_Total = 0;
                                        foreach($records as $data){
                                        $rec_id = (!empty($data['iduser']) ? urlencode($data['iduser'] ?? '') : null);
                                        $counter++;
                                        $sum_of_Nouvelle_imputation = $sum_of_Nouvelle_imputation + $data['Nouvelle_imputation'];
                                        $sum_of_Traitement_en_cours = $sum_of_Traitement_en_cours + $data['Traitement_en_cours'];
                                        $sum_of_Imputation_retournee = $sum_of_Imputation_retournee + $data['Imputation_retournee'];
                                        $sum_of_Total = $sum_of_Total + $data['Total'];
                                        ?>
                                        <tr>
                                            <th class="td-sno"><?php echo $counter; ?></th>
                                            <td class="td-user"> <?php echo $data['user'] ?? '' ; ?></td>
                                            <td class="td-user_avatar"><?php Html :: page_img($data['user_avatar'],50,50,1); ?></td>
                                            <td class="td-Nouvelle_imputation"> <?php echo $data['Nouvelle_imputation'] ?? '' ; ?></td>
                                            <td class="td-Traitement_en_cours"> <?php echo $data['Traitement_en_cours'] ?? '' ; ?></td>
                                            <td class="td-Imputation_retournee"> <?php echo $data['Imputation_retournee'] ?? '' ; ?></td>
                                            <td class="td-Total"> <?php echo $data['Total'] ?? '' ; ?></td>
                                        </tr>
                                        <?php 
                                        }
                                        ?>
                                        <!--endrecord-->
                                    </tbody>
                                    <tbody class="search-data" id="search-data-<?php echo $page_element_id; ?>"></tbody>
                                    <tfoot><tr><th></th><th>Total</th><th></th><th><?php echo $sum_of_Nouvelle_imputation;  ?></th><th><?php echo $sum_of_Traitement_en_cours;  ?></th><th><?php echo $sum_of_Imputation_retournee;  ?></th><th><?php echo $sum_of_Total;  ?></th></tr></tfoot>
                                    <?php
                                    }
                                    ?>
                                </table>
                                <?php 
                                if(empty($records)){
                                ?>
                                <h4 class="bg-light text-center border-top text-muted animated bounce  p-3">
                                    <i class="material-icons">block</i> <?php print_lang('aucun_enregistrement_trouv_'); ?>
                                </h4>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            if( $show_footer && !empty($records)){
                            ?>
                            <div class=" border-top mt-2">
                                <div class="row justify-content-center">    
                                    <div class="col-md-auto justify-content-center">    
                                        <div class="p-3 d-flex justify-content-between">    
                                        </div>
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
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
