<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php if( $show_header == true ){ ?>
    <div class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h4 class="record-title">Dupliquer un profil</h4>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="">
        <div class="container">
            <div class="row">
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div class="bg-light p-3 animated fadeIn page-content">
                        <p class="text-muted">
                            Le nouveau profil reprend l'integralite des droits du profil modele :
                            permissions d'ecran, acces en affichage, acces en selection et types de
                            courrier visibles. Vous pourrez les ajuster ensuite.
                        </p>
                        <form id="roles-dupliquer-form" role="form" class="form page-form form-horizontal needs-validation" action="<?php print_link("roles/dupliquer?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="ctrl-role_modele">Profil modele <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select required="required" id="ctrl-role_modele" name="role_modele" class="custom-select">
                                                <option value="">Selectionnez le profil a copier</option>
                                                <?php
                                                $rec = $this->set_field_value('role_modele', '');
                                                $options = $comp_model -> user_user_role_id_option_list();
                                                if(!empty($options)){
                                                    foreach($options as $option){
                                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                                        $selected = ( $value == $rec ? 'selected' : null );
                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'); ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="ctrl-role_name">Nom du nouveau profil <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="ctrl-role_name" value="<?php echo $this->set_field_value('role_name',""); ?>" type="text" placeholder="Exemple : Coordonnateur de cellule" required="required" name="role_name" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-submit-btn-holder text-center mt-3">
                                    <div class="form-ajax-status"></div>
                                    <button class="btn btn-primary" type="submit">
                                        Creer le profil <i class="material-icons">send</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
