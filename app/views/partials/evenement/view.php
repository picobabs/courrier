<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("evenement/add");
$can_edit = ACL::is_allowed("evenement/edit");
$can_view = ACL::is_allowed("evenement/view");
$can_delete = ACL::is_allowed("evenement/delete");
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
                    <h4 class="record-title"><?php print_lang('vue_evenement'); ?></h4>
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
                        $rec_id = (!empty($data['idevenement']) ? urlencode($data['idevenement'] ?? '') : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-date_evenement">
                                        <th class="title"> <?php print_lang('date_evenement'); ?>: </th>
                                        <td class="value"> <?php echo $data['date_evenement'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-userid">
                                        <th class="title"> <?php print_lang('userid'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['userid'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idevenement'] ?? '' ?>" 
                                                data-url="<?php print_link("evenement/editfield/" . urlencode($data['idevenement'] ?? '')); ?>" 
                                                data-name="userid" 
                                                data-title="Entrer Userid" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="number" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['userid'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-action">
                                        <th class="title"> <?php print_lang('action'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['action'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idevenement'] ?? '' ?>" 
                                                data-url="<?php print_link("evenement/editfield/" . urlencode($data['idevenement'] ?? '')); ?>" 
                                                data-name="action" 
                                                data-title="Entrer Action" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['action'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-numero_courrier">
                                        <th class="title"> <?php print_lang('numero_courrier'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['numero_courrier'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idevenement'] ?? '' ?>" 
                                                data-url="<?php print_link("evenement/editfield/" . urlencode($data['idevenement'] ?? '')); ?>" 
                                                data-name="numero_courrier" 
                                                data-title="Entrer Numero Courrier" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['numero_courrier'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-destinataire">
                                        <th class="title"> <?php print_lang('destinataire'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['destinataire'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idevenement'] ?? '' ?>" 
                                                data-url="<?php print_link("evenement/editfield/" . urlencode($data['idevenement'] ?? '')); ?>" 
                                                data-name="destinataire" 
                                                data-title="Entrer Destinataire" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="number" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['destinataire'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-user_iduser">
                                        <th class="title"> <?php print_lang('user_iduser'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_iduser'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_identification">
                                        <th class="title"> <?php print_lang('user_identification'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_identification'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_login">
                                        <th class="title"> <?php print_lang('user_login'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_login'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_password">
                                        <th class="title"> <?php print_lang('user_password'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_password'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_emailuser">
                                        <th class="title"> <?php print_lang('user_emailuser'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_emailuser'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_avatar">
                                        <th class="title"> <?php print_lang('user_avatar'); ?>: </th>
                                        <td class="value"><?php Html :: page_img($data['user_avatar'],400,400,1); ?></td>
                                    </tr>
                                    <tr  class="td-user_date_deleted">
                                        <th class="title"> <?php print_lang('user_date_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_date_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_is_deleted">
                                        <th class="title"> <?php print_lang('user_is_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_is_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_user_role_id">
                                        <th class="title"> <?php print_lang('user_user_role_id'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_user_role_id'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_niveau_imputation">
                                        <th class="title"> <?php print_lang('user_niveau_imputation'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_niveau_imputation'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_direction">
                                        <th class="title"> <?php print_lang('user_direction'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_direction'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_division">
                                        <th class="title"> <?php print_lang('user_division'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_division'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user_online">
                                        <th class="title"> <?php print_lang('user_online'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_online'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_iduser">
                                        <th class="title"> <?php print_lang('user2_iduser'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_iduser'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_identification">
                                        <th class="title"> <?php print_lang('user2_identification'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_identification'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_login">
                                        <th class="title"> <?php print_lang('user2_login'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_login'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_password">
                                        <th class="title"> <?php print_lang('user2_password'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_password'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_emailuser">
                                        <th class="title"> <?php print_lang('user2_emailuser'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_emailuser'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_avatar">
                                        <th class="title"> <?php print_lang('user2_avatar'); ?>: </th>
                                        <td class="value"><?php Html :: page_img($data['user2_avatar'],400,400,1); ?></td>
                                    </tr>
                                    <tr  class="td-user2_date_deleted">
                                        <th class="title"> <?php print_lang('user2_date_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_date_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_is_deleted">
                                        <th class="title"> <?php print_lang('user2_is_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_is_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_user_role_id">
                                        <th class="title"> <?php print_lang('user2_user_role_id'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_user_role_id'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_niveau_imputation">
                                        <th class="title"> <?php print_lang('user2_niveau_imputation'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_niveau_imputation'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_direction">
                                        <th class="title"> <?php print_lang('user2_direction'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_direction'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_division">
                                        <th class="title"> <?php print_lang('user2_division'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_division'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-user2_online">
                                        <th class="title"> <?php print_lang('user2_online'); ?>: </th>
                                        <td class="value"> <?php echo $data['user2_online'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-idcourrier">
                                        <th class="title"> <?php print_lang('idcourrier'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['idcourrier'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idevenement'] ?? '' ?>" 
                                                data-url="<?php print_link("evenement/editfield/" . urlencode($data['idevenement'] ?? '')); ?>" 
                                                data-name="idcourrier" 
                                                data-title="Entrer Idcourrier" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="number" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['idcourrier'] ?? '' ; ?> 
                                            </span>
                                        </td>
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
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("evenement/edit/$rec_id"); ?>">
                                                    <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("evenement/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
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
