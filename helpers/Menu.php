<?php
/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu{
	
	
			public static $navbarsideleft = array(
		array(
			'path' => 'home', 
			'label' => 'Accueil', 
			'icon' => '<i class="material-icons ">home</i>'
		),
		
		array(
			'path' => 'courrier', 
			'label' => 'Courrier entrant', 
			'icon' => '<i class="material-icons ">fast_rewind</i>'
		),
		
		array(
			'path' => 'expediteur', 
			'label' => 'Expediteur', 
			'icon' => '<i class="material-icons ">account_balance</i>'
		),
		
		array(
			'path' => 'user', 
			'label' => 'Utilisateurs', 
			'icon' => '<i class="material-icons ">perm_identity</i>'
		),
		
		array(
			'path' => 'courrier/courriersaimputer',
			'label' => 'Courriers a imputer',
			'icon' => '<i class="material-icons ">send</i>'
		),

		array(
			'path' => 'imputation/traitement_imputation', 
			'label' => 'Traitement des imputations', 
			'icon' => '<i class="material-icons ">create</i>'
		),
		
		array(
			'path' => 'imputations_en_attente', 
			'label' => 'Imputations En Attente', 
			'icon' => '<i class="material-icons ">dialer_sip</i>'
		),
		
		array(
			'path' => 'imputation_en_retard', 
			'label' => 'Imputations En Retard', 
			'icon' => '<i class="material-icons ">access_alarms</i>'
		),
		
		array(
			'path' => 'courrier/recherche', 
			'label' => 'Recherche', 
			'icon' => '<i class="material-icons ">gps_not_fixed</i>'
		),
		
		array(
			'path' => 'evenement', 
			'label' => 'Suivi des évènements', 
			'icon' => '<i class="material-icons ">visibility</i>'
		),
		
		array(
			'path' => 'courrier_sortant', 
			'label' => 'Courrier sortant', 
			'icon' => '<i class="material-icons ">fast_forward</i>'
		)
	);
		
			public static $navbartopleft = array(
		array(
			'path' => 'droit_lister', 
			'label' => 'Paramétrage', 
			'icon' => '<i class="material-icons ">youtube_searched_for</i>',
'submenu' => array(
		array(
			'path' => 'direction', 
			'label' => 'Les Directions', 
			'icon' => '<i class="material-icons ">account_balance</i>'
		),
		
		array(
			'path' => 'division', 
			'label' => 'Les Divisions', 
			'icon' => '<i class="material-icons ">account_box</i>'
		),
		
		array(
			'path' => 'niveau_imputation', 
			'label' => 'les Niveaux imputation', 
			'icon' => '<i class="material-icons ">call_split</i>'
		),
		
		array(
			'path' => 'droit_lister', 
			'label' => 'Workflow', 
			'icon' => '<i class="material-icons ">settings_input_component</i>',
'submenu' => array(
		array(
			'path' => 'droit_lister', 
			'label' => 'Accèe en affichage', 
			'icon' => ''
		),
		
		array(
			'path' => 'droit_select', 
			'label' => 'Accès en sélection', 
			'icon' => ''
		)
	)
		),
		
		array(
			'path' => 'roles', 
			'label' => 'Les profils', 
			'icon' => '<i class="material-icons ">accessibility</i>'
		)
	)
		)
	);
		
	
	
}