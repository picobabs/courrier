<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("courrier_sortant/add");
$can_edit = ACL::is_allowed("courrier_sortant/edit");
$can_view = ACL::is_allowed("courrier_sortant/view");
$can_delete = ACL::is_allowed("courrier_sortant/delete");
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
                    <h4 class="record-title"><?php print_lang('vue_courrier_sortant'); ?></h4>
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
                        $rec_id = (!empty($data['idcourrier']) ? urlencode($data['idcourrier'] ?? '') : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-date_courrier">
                                        <th class="title"> <?php print_lang('date_courrier'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['date_courrier'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-date_envoi">
                                        <th class="title"> <?php print_lang('date_envoi'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['date_envoi'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-destinataire">
                                        <th class="title"> <?php print_lang('destinataire'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/courrier_sortant_destinataire_option_list'); ?>' 
                                                data-value="<?php echo $data['destinataire'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                data-name="destinataire" 
                                                data-title="Sélectionnez une valeur" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['destinataire'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-intitule">
                                        <th class="title"> <?php print_lang('intitule'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['intitule'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <div><?php echo nettoyer_html($data['objet'] ?? ''); ?></div>
                                    <tr  class="td-fichier">
                                        <th class="title"> <?php print_lang('fichier'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['fichier'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                data-name="fichier" 
                                                data-title="Feuilleter..." 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['fichier'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-armoire">
                                        <th class="title"> <?php print_lang('armoire'); ?>: </th>
                                        <td class="value"> <?php echo $data['armoire'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-rangee">
                                        <th class="title"> <?php print_lang('rangee'); ?>: </th>
                                        <td class="value"> <?php echo $data['rangee'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-boite">
                                        <th class="title"> <?php print_lang('boite'); ?>: </th>
                                        <td class="value"> <?php echo $data['boite'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-nature">
                                        <th class="title"> <?php print_lang('nature'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-source='<?php print_link('api/json/courrier_sortant_nature_option_list'); ?>' 
                                                data-value="<?php echo $data['nature'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                data-name="nature" 
                                                data-title="Sélectionnez une valeur" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="select" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['nature'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-numero_courrier">
                                        <th class="title"> <?php print_lang('numero_courrier'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['numero_courrier'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-date_saisie">
                                        <th class="title"> <?php print_lang('date_saisie'); ?>: </th>
                                        <td class="value"> <?php echo $data['date_saisie'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-sens">
                                        <th class="title"> <?php print_lang('sens'); ?>: </th>
                                        <td class="value">
                                            <span <?php if($can_edit){ ?> data-value="<?php echo $data['sens'] ?? '' ; ?>" 
                                                data-pk="<?php echo $data['idcourrier'] ?? '' ?>" 
                                                data-url="<?php print_link("courrier_sortant/editfield/" . urlencode($data['idcourrier'] ?? '')); ?>" 
                                                data-name="sens" 
                                                data-title="Entrer Sens" 
                                                data-placement="left" 
                                                data-toggle="click" 
                                                data-type="text" 
                                                data-mode="popover" 
                                                data-showbuttons="left" 
                                                class="is-editable" <?php } ?>>
                                                <?php echo $data['sens'] ?? '' ; ?> 
                                            </span>
                                        </td>
                                    </tr>
                                    <tr  class="td-en_reponse_courrier_numero">
                                        <th class="title"> <?php print_lang('en_reponse_courrier_numero'); ?>: </th>
                                        <td class="value">
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
                                                <?php echo $data['en_reponse_courrier_numero'] ?? '' ; ?> 
                                            </span>
                                        </td>
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
                                    <tr  class="td-nature_courrier_idnature">
                                        <th class="title"> <?php print_lang('nature_courrier_idnature'); ?>: </th>
                                        <td class="value"> <?php echo $data['nature_courrier_idnature'] ?? '' ; ?></td>
                                    </tr>
                                    <tr  class="td-nature_courrier_nature">
                                        <th class="title"> <?php print_lang('nature_courrier_nature'); ?>: </th>
                                        <td class="value"> <?php echo $data['nature_courrier_nature'] ?? '' ; ?></td>
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
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("courrier_sortant/edit/$rec_id"); ?>">
                                                    <i class="material-icons">edit</i> <?php print_lang('modifier'); ?>
                                                </a>
                                                <?php } ?>
                                                <?php if($can_delete){ ?>
                                                <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("courrier_sortant/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Êtes-vous sûr de vouloir supprimer cet enregistrement?" data-display-style="modal">
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
