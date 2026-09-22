<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("user/add");
$can_edit = ACL::is_allowed("user/edit");
$can_view = ACL::is_allowed("user/view");
$can_delete = ACL::is_allowed("user/delete");
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
                    <h4 class="record-title"><?php print_lang('gestion_des_utilisateurs'); ?></h4>
                </div>
                <div class="col-sm-3 ">
                    <?php if($can_add){ ?>
                    <a  class="btn btn btn-primary my-1" href="<?php print_link("user/add") ?>">
                        <i class="material-icons">add</i>                               
                        <?php print_lang('ajouter_un_nouveau'); ?> 
                    </a>
                    <?php } ?>
                </div>
                <div class="col-sm-4 ">
                    <form  class="search" action="<?php print_link('user'); ?>" method="get">
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
                                        <a class="text-decoration-none" href="<?php print_link('user'); ?>">
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
                                        <a class="text-decoration-none" href="<?php print_link('user'); ?>">
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
                                <div class="card-header h4 h4">Niveau Imputation</div>
                                <div class="p-2">
                                    <select   name="user_niveau_imputation" class="form-control custom ">
                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                        <?php 
                                        $user_niveau_imputation_options = $comp_model -> user_userniveau_imputation_option_list();
                                        if(!empty($user_niveau_imputation_options)){
                                        foreach($user_niveau_imputation_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        $selected = $this->set_field_selected('user_niveau_imputation',$value);
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
                                <div class="card-header h4 h4">Direction</div>
                                <div class="p-2">
                                    <select   name="user_direction" class="form-control custom ">
                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                        <?php 
                                        $user_direction_options = $comp_model -> user_userdirection_option_list();
                                        if(!empty($user_direction_options)){
                                        foreach($user_direction_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        $selected = $this->set_field_selected('user_direction',$value);
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
                                <div class="card-header h4 h4">Profil</div>
                                <div class="p-2">
                                    <select   name="user_user_role_id" class="form-control custom ">
                                        <option value=""><?php print_lang('s_lectionnez_une_valeur'); ?></option>
                                        <?php 
                                        $user_user_role_id_options = $comp_model -> user_useruser_role_id_option_list();
                                        if(!empty($user_user_role_id_options)){
                                        foreach($user_user_role_id_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        $selected = $this->set_field_selected('user_user_role_id',$value);
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
                            if(!empty(get_value('user_niveau_imputation'))){
                            ?>
                            <div class="filter-chip card bg-light">
                                <b>User Niveau Imputation :</b> 
                                <?php 
                                if(get_value('user_niveau_imputationlabel')){
                                echo get_value('user_niveau_imputationlabel');
                                }
                                else{
                                echo get_value('user_niveau_imputation');
                                }
                                $remove_link = unset_get_value('user_niveau_imputation', $this->route->page_url);
                                ?>
                                <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                    &times;
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                            <?php
                            if(!empty(get_value('user_direction'))){
                            ?>
                            <div class="filter-chip card bg-light">
                                <b>User Direction :</b> 
                                <?php 
                                if(get_value('user_directionlabel')){
                                echo get_value('user_directionlabel');
                                }
                                else{
                                echo get_value('user_direction');
                                }
                                $remove_link = unset_get_value('user_direction', $this->route->page_url);
                                ?>
                                <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                    &times;
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                            <?php
                            if(!empty(get_value('user_user_role_id'))){
                            ?>
                            <div class="filter-chip card bg-light">
                                <b>User User Role Id :</b> 
                                <?php 
                                if(get_value('user_user_role_idlabel')){
                                echo get_value('user_user_role_idlabel');
                                }
                                else{
                                echo get_value('user_user_role_id');
                                }
                                $remove_link = unset_get_value('user_user_role_id', $this->route->page_url);
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
                            <div id="user-list-records">
                                <?php
                                if(!empty($records)){
                                ?>
                                <div id="page-report-body">
                                    <div class="row sm-gutters page-data" id="page-data-<?php echo $page_element_id; ?>">
                                        <!--record-->
                                        <?php
                                        $counter = 0;
                                        foreach($records as $data){
                                        $rec_id = (!empty($data['iduser']) ? urlencode($data['iduser'] ?? '') : null);
                                        $counter++;
                                        ?>
                                        <div class="col-sm-4">
                                            <div class="arrondi  p-2 mb-3 animated bounceIn">
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['identification'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
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
                                                            <?php print_lang('identification'); ?>:  
                                                        </span>
                                                        <?php echo $data['identification'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['roles_role_name'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                        data-url="<?php print_link("roles/editfield/" . urlencode($data['role_id'] ?? '')); ?>" 
                                                        data-name="role_name" 
                                                        data-title="Entrer Profil" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('profil'); ?>:  
                                                        </span>
                                                        <?php echo $data['roles_role_name'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['direction_direction'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                        data-url="<?php print_link("direction/editfield/" . urlencode($data['iddirection'] ?? '')); ?>" 
                                                        data-name="direction" 
                                                        data-title="Entrer Direction" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('direction_'); ?>:  
                                                        </span>
                                                        <?php echo $data['direction_direction'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['division_division'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                        data-url="<?php print_link("division/editfield/" . urlencode($data['iddivision'] ?? '')); ?>" 
                                                        data-name="division" 
                                                        data-title="Entrer Division" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('division_cellule'); ?>:  
                                                        </span>
                                                        <?php echo $data['division_division'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['login'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                        data-url="<?php print_link("user/editfield/" . urlencode($data['iduser'] ?? '')); ?>" 
                                                        data-name="login" 
                                                        data-title="Entrer Login" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('login'); ?>:  
                                                        </span>
                                                        <?php echo $data['login'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <div class="mb-2">  <a href="<?php print_link("mailto:$data[emailuser]") ?>">
                                                    <span class="font-weight-light text-muted ">
                                                        <?php print_lang('emailuser'); ?>:  
                                                    </span>
                                                <?php echo $data['emailuser'] ?? '' ; ?></a></div>
                                                <div class="mb-2">  <?php Html :: page_img($data['avatar'],50,50,1); ?></div>
                                                <div class="mb-2">  
                                                    <span <?php if($can_edit){ ?> data-value="<?php echo $data['niveau_imputation_niveau_imputation'] ?? '' ; ?>" 
                                                        data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                        data-url="<?php print_link("niveau_imputation/editfield/" . urlencode($data['idniveau'] ?? '')); ?>" 
                                                        data-name="niveau_imputation" 
                                                        data-title="Entrer Niveau Imputation" 
                                                        data-placement="left" 
                                                        data-toggle="click" 
                                                        data-type="text" 
                                                        data-mode="popover" 
                                                        data-showbuttons="left" 
                                                        class="is-editable" <?php } ?>>
                                                        <span class="font-weight-light text-muted ">
                                                            <?php print_lang('niveau_imputation_'); ?>:  
                                                        </span>
                                                        <?php echo $data['niveau_imputation_niveau_imputation'] ?? '' ; ?> 
                                                    </span>
                                                </div>
                                                <?php if ( $data['online'] ==1)  { ?>
                                                En ligne <i class="material-icons " style="color:rgba(68, 244, 18, 0.8)">mouse</i>
                                                <?php } else { ?>
                                                Déconnecté  <i class="material-icons " style="color:rgba(223, 67, 15, 0.8)">mouse</i>
                                                <?php } ?>
                                                <div class="td-btn">
                                                    <?php if($can_edit){ ?>
                                                    <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('modifier_cet_enregistrement'); ?>" href="<?php print_link("user/edit/$rec_id"); ?>">
                                                        <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if($can_delete){ ?>
                                                    <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="<?php print_lang('supprimer_cet_enregistrement'); ?>" href="<?php print_link("user/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
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
