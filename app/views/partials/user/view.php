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
                    <h4 class="record-title"><?php print_lang('vue_user'); ?></h4>
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
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
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
                                        <th class="title"> <?php print_lang('emailuser'); ?>: </th>
                                        <td class="value"> <?php echo $data['emailuser'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_role_id">
                                        <th class="title"> <?php print_lang('user_role_id'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_role_id'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-niveau_imputation">
                                        <th class="title"> <?php print_lang('niveau_imputation'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/user_niveau_imputation_option_list'); ?>' 
                                                data-value="<?php echo $data['niveau_imputation'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['iduser'] ?? '' ?>" 
                                                data-url="<?php print_link("user/editfield/" . urlencode($data['iduser'] ?? '')); ?>" 
                                                data-name="niveau_imputation" 
                                                data-title="Sélectionnez une valeur" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['niveau_imputation'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-direction">
                                        <th class="title"> <?php print_lang('direction'); ?>: </th>
                                        <td class="value"> <?php echo $data['direction'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-division">
                                        <th class="title"> <?php print_lang('division'); ?>: </th>
                                        <td class="value"> <?php echo $data['division'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-roles_role_id">
                                        <th class="title"> <?php print_lang('roles_role_id'); ?>: </th>
                                        <td class="value"> <?php echo $data['roles_role_id'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-roles_role_name">
                                        <th class="title"> <?php print_lang('roles_role_name'); ?>: </th>
                                        <td class="value"> <?php echo $data['roles_role_name'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-direction_iddirection">
                                        <th class="title"> <?php print_lang('direction_iddirection'); ?>: </th>
                                        <td class="value"> <?php echo $data['direction_iddirection'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-direction_direction">
                                        <th class="title"> <?php print_lang('direction_direction'); ?>: </th>
                                        <td class="value"> <?php echo $data['direction_direction'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-division_iddivision">
                                        <th class="title"> <?php print_lang('division_iddivision'); ?>: </th>
                                        <td class="value"> <?php echo $data['division_iddivision'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-division_direction">
                                        <th class="title"> <?php print_lang('division_direction'); ?>: </th>
                                        <td class="value"> <?php echo $data['division_direction'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-division_division">
                                        <th class="title"> <?php print_lang('division_division'); ?>: </th>
                                        <td class="value"> <?php echo $data['division_division'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-niveau_imputation_idniveau">
                                        <th class="title"> <?php print_lang('niveau_imputation_idniveau'); ?>: </th>
                                        <td class="value"> <?php echo $data['niveau_imputation_idniveau'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-niveau_imputation_niveau_imputation">
                                        <th class="title"> <?php print_lang('niveau_imputation_niveau_imputation'); ?>: </th>
                                        <td class="value"> <?php echo $data['niveau_imputation_niveau_imputation'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-online">
                                        <th class="title"> <?php print_lang('online'); ?>: </th>
                                        <td class="value"> <?php echo $data['online'] ?? '' ; ?></td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <div class="dropup export-btn-holder mx-1">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">save</i> <?php print_lang('exportation'); ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                    <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                        <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                        </a>
                                        <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                        <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                            <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                            </a>
                                            <?php $export_word_link = $this->set_current_page_link(array('format' => 'word')); ?>
                                            <a class="dropdown-item export-link-btn" data-format="word" href="<?php print_link($export_word_link); ?>" target="_blank">
                                                <img src="<?php print_link('assets/images/doc.png') ?>" class="mr-2" /> WORD
                                                </a>
                                                <?php $export_csv_link = $this->set_current_page_link(array('format' => 'csv')); ?>
                                                <a class="dropdown-item export-link-btn" data-format="csv" href="<?php print_link($export_csv_link); ?>" target="_blank">
                                                    <img src="<?php print_link('assets/images/csv.png') ?>" class="mr-2" /> CSV
                                                    </a>
                                                    <?php $export_excel_link = $this->set_current_page_link(array('format' => 'excel')); ?>
                                                    <a class="dropdown-item export-link-btn" data-format="excel" href="<?php print_link($export_excel_link); ?>" target="_blank">
                                                        <img src="<?php print_link('assets/images/xsl.png') ?>" class="mr-2" /> EXCEL
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if($can_edit){ ?>
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("user/edit/$rec_id"); ?>">
                                                    <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("user/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
                                                    <i class="material-icons">clear</i> <?php print_lang('effacer'); ?>
                                                </a>
                                                <?php } ?>
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
