<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("user/add");
$can_edit = ACL::is_allowed("user/edit");
$can_view = ACL::is_allowed("user/view");
$can_delete = ACL::is_allowed("user/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title"><?php print_lang('mon_compte'); ?></h4>
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
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['iduser']) ? urlencode($data['iduser'] ?? '') : null);
                        $counter++;
                        ?>
                        <div class="bg-primary m-2 mb-4">
                            <div class="profile">
                                <div class="avatar">
                                    <?php 
                                    if(!empty(USER_PHOTO)){
                                    Html::page_img(USER_PHOTO, 100, 100); 
                                    }
                                    ?>
                                </div>
                                <h1 class="title mt-4"><?php echo $data['login'] ?? '' ; ?></h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mx-3 mb-3">
                                    <ul class="nav nav-pills flex-column text-left">
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#AccountPageView" class="nav-link active">
                                                <i class="material-icons">account_box</i> <?php print_lang('d_tail_du_compte'); ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#AccountPageEdit" class="nav-link">
                                                <i class="material-icons">edit</i> <?php print_lang('modifier_le_compte'); ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#AccountPageChangeEmail" class="nav-link">
                                                <i class="material-icons">email</i> <?php print_lang('changer_l_e_mail'); ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#AccountPageChangePassword" class="nav-link">
                                                <i class="material-icons">lock</i> <?php print_lang('r_initialiser_le_mot_de_passe'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="mb-3">
                                    <div class="tab-content">
                                        <div class="tab-pane show active fade" id="AccountPageView" role="tabpanel">
                                            <table class="table table-hover table-borderless table-striped">
                                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                                    <tr  class="td-identification">
                                                        <th class="title"> <?php print_lang('identification'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['identification'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr  class="td-login">
                                                        <th class="title"> <?php print_lang('login'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['login'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr  class="td-emailuser">
                                                        <th class="title"> <?php print_lang('adresse_email'); ?>: </th>
                                                        <td class="value"> <?php echo $data['emailuser'] ?? '' ; ?></td>
                                                    </tr>
                                                    <tr  class="td-roles_role_name">
                                                        <th class="title"> <?php print_lang('profil'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['roles_role_name'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr  class="td-niveau_imputation_niveau_imputation">
                                                        <th class="title"> <?php print_lang('niveau_imputation'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['niveau_imputation_niveau_imputation'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr  class="td-direction_direction">
                                                        <th class="title"> <?php print_lang('direction'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['direction_direction'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr  class="td-division_division">
                                                        <th class="title"> <?php print_lang('division'); ?>: </th>
                                                        <td class="value">
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
                                                                <?php echo $data['division_division'] ?? '' ; ?> 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>    
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="AccountPageEdit" role="tabpanel">
                                            <div class=" reset-grids">
                                                <?php  $this->render_page("account/edit"); ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane  fade" id="AccountPageChangeEmail" role="tabpanel">
                                            <div class=" reset-grids">
                                                <?php  $this->render_page("account/change_email"); ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="AccountPageChangePassword" role="tabpanel">
                                            <div class=" reset-grids">
                                                <?php  $this->render_page("passwordmanager"); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        else{
                        ?>
                        <!-- Empty Record Message -->
                        <div class="text-muted p-3">
                            <i class="material-icons">block</i> <?php print_lang('aucun_enregistrement_trouv_'); ?>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
