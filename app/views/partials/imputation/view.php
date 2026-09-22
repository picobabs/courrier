<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("imputation/add");
$can_edit = ACL::is_allowed("imputation/edit");
$can_view = ACL::is_allowed("imputation/view");
$can_delete = ACL::is_allowed("imputation/delete");
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
                    <h4 class="record-title"><?php print_lang('vue_imputation'); ?></h4>
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
                        $rec_id = (!empty($data['idimputation']) ? urlencode($data['idimputation'] ?? '') : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-date_imputation">
                                        <th class="title"> <?php print_lang('date_imputation'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['date_imputation'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-delai_traitement">
                                        <th class="title"> <?php print_lang('delai_traitement'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['delai_traitement'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-etat_traitement">
                                        <th class="title"> <?php print_lang('etat_traitement'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/imputation_etat_traitement_option_list'); ?>' 
                                                data-value="<?php echo $data['etat_traitement'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                data-name="etat_traitement" 
                                                data-title="Sélectionnez une valeur" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['etat_traitement'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-ftraite">
                                        <th class="title"> <?php print_lang('ftraite'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['ftraite'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                data-name="ftraite" 
                                                data-title="Feuilleter..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['ftraite'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-user_user_role_id">
                                        <th class="title"> <?php print_lang('user_user_role_id'); ?>: </th>
                                        <td class="value"> <?php echo $data['user_user_role_id'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_idcourrier">
                                        <th class="title"> <?php print_lang('courrier_idcourrier'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_idcourrier'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_date_courrier">
                                        <th class="title"> <?php print_lang('courrier_date_courrier'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_date_courrier'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_date_reception">
                                        <th class="title"> <?php print_lang('courrier_date_reception'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_date_reception'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_expediteur">
                                        <th class="title"> <?php print_lang('courrier_expediteur'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_expediteur'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_intitule">
                                        <th class="title"> <?php print_lang('courrier_intitule'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_intitule'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_objet">
                                        <th class="title"> <?php print_lang('courrier_objet'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_objet'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_fichier">
                                        <th class="title"> <?php print_lang('courrier_fichier'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_fichier'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_armoire">
                                        <th class="title"> <?php print_lang('courrier_armoire'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_armoire'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_rangee">
                                        <th class="title"> <?php print_lang('courrier_rangee'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_rangee'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_boite">
                                        <th class="title"> <?php print_lang('courrier_boite'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_boite'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_sens">
                                        <th class="title"> <?php print_lang('courrier_sens'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_sens'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_nature">
                                        <th class="title"> <?php print_lang('courrier_nature'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_nature'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_date_deleted">
                                        <th class="title"> <?php print_lang('courrier_date_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_date_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_is_deleted">
                                        <th class="title"> <?php print_lang('courrier_is_deleted'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_is_deleted'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-courrier_etat">
                                        <th class="title"> <?php print_lang('courrier_etat'); ?>: </th>
                                        <td class="value"> <?php echo $data['courrier_etat'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-expediteur_idexpediteur">
                                        <th class="title"> <?php print_lang('expediteur_idexpediteur'); ?>: </th>
                                        <td class="value"> <?php echo $data['expediteur_idexpediteur'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-expediteur_expediteur">
                                        <th class="title"> <?php print_lang('expediteur_expediteur'); ?>: </th>
                                        <td class="value"> <?php echo $data['expediteur_expediteur'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-expediteur_cellulaire">
                                        <th class="title"> <?php print_lang('expediteur_cellulaire'); ?>: </th>
                                        <td class="value"> <?php echo $data['expediteur_cellulaire'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-instruction">
                                        <th class="title"> <?php print_lang('instruction'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['instruction'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-etat_imputation_idetatimputation">
                                        <th class="title"> <?php print_lang('etat_imputation_idetatimputation'); ?>: </th>
                                        <td class="value"> <?php echo $data['etat_imputation_idetatimputation'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-etat_imputation_etat_imputation">
                                        <th class="title"> <?php print_lang('etat_imputation_etat_imputation'); ?>: </th>
                                        <td class="value"> <?php echo $data['etat_imputation_etat_imputation'] ?? '' ; ?></td>
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
                                    <tr  class="td-date_limite_traite">
                                        <th class="title"> <?php print_lang('date_limite_traite'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['date_limite_traite'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-niveau">
                                        <th class="title"> <?php print_lang('niveau'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['niveau'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idimputation'] ?? '' ?>" 
                                                data-url="<?php print_link("imputation/editfield/" . urlencode($data['idimputation'] ?? '')); ?>" 
                                                data-name="niveau" 
                                                data-title="Entrer Niveau" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['niveau'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-origine">
                                        <th class="title"> <?php print_lang('origine'); ?>: </th>
                                        <td class="value"> <?php echo $data['origine'] ?? '' ; ?></td>
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
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("imputation/edit/$rec_id"); ?>">
                                                    <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("imputation/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
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
