<?php
/**
 * MODELE DE CONFIGURATION
 *
 * Copiez ce fichier sous le nom config.php et renseignez les valeurs propres
 * a votre installation. config.php n'est pas versionne : il contient le mot
 * de passe de la base de donnees.
 *
 *     cp config.example.php config.php
 *
 * Les constantes metier plus bas (profils, echelons, ecrans par defaut)
 * decrivent le circuit du courrier ANASER : adaptez-les a votre organisation.
 */
define("DEFAULT_TIMEZONE", "Africa/Dakar"); // set php date functions timezone
define("DEVELOPMENT_MODE" , true);// set to false when in production

// return full path of application directory
define("ROOT", str_replace("\\", "/", dirname(__FILE__)) . "/");

// return the application directory name.
define("ROOT_DIR_NAME", basename(ROOT));

define("SITE_NAME", "Gestion du courrier - ANASER");


// Get Site Address Dynamically
$site_addr = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);

//Must end with /
$site_addr = rtrim($site_addr, "/\\") . "/";

// Can Be Set Manually Like "http://localhost/mysite/".
define("SITE_ADDR", $site_addr);

define("APP_ID", "a037c01a7b3cfe8d0df2ea3cac48c0bc");

// Application Default Color (Mostly Used By Mobile)
define("META_THEME_COLOR", "#000000");

//Application resource access status
define("AUTHORIZED", 200);
define("UNAUTHORIZED", 401);
define("NOROLE", 404);
define("FORBIDDEN", 403);

// Application Files and Directories 
define("IMG_DIR",  "assets/images/");
define("FONTS_DIR",  "assets/fonts/");
define("SITE_FAVICON", IMG_DIR . "favicon.png");
define("SITE_LOGO", IMG_DIR . "logo.png");

define("CSS_DIR", SITE_ADDR . "assets/css/");
define("JS_DIR", SITE_ADDR . "assets/js/");

define("APP_DIR", "app/");
define("SYSTEM_DIR", "system/");
define("HELPERS_DIR", "helpers/");
define("LIBS_DIR", "libs/");
define("LANGS_DIR", "languages/");
define("MODELS_DIR", APP_DIR . "models/");
define("CONTROLLERS_DIR", APP_DIR . "controllers/");
define("VIEWS_DIR", APP_DIR . "views/");
define("LAYOUTS_DIR", VIEWS_DIR . "layouts/");
define("PAGES_DIR", VIEWS_DIR . "partials/");
define("AUDIT_LOGS_DIR", "logs/");

// File Upload Directories 
define("UPLOAD_DIR", "uploads/");
define("UPLOAD_FILE_DIR", UPLOAD_DIR . "files/");
define("UPLOAD_IMG_DIR", UPLOAD_DIR . "photos/");
define("MAX_UPLOAD_FILESIZE", trim(ini_get("upload_max_filesize")));

// First page to see after user login 
define("HOME_PAGE", "Home");
define("DEFAULT_PAGE", "index"); //Default Controller Class
define("DEFAULT_PAGE_ACTION", "index"); //Default Controller Action
define("DEFAULT_LAYOUT", LAYOUTS_DIR . "main_layout.php");
define("DEFAULT_LANGUAGE", "french"); //Default Language

// Page Meta Information
define("META_AUTHOR", "ANASER");

// Coordonnees du service informatique, affichees sur les pages d'erreur,
// la page de contact et l'en-tete des rapports imprimes.
define("SUPPORT_EMAIL", "cii@anaser.sn");
define("SUPPORT_PHONE", "+221773183202");
define("SITE_WEBSITE", "www.anaser.sn");

// Profils qui recoivent le courrier a leur niveau hierarchique puis l'imputent
// a l'echelon inferieur : le Directeur et le Coordonnateur ont exactement le
// meme comportement dans le circuit des imputations.
// Le Coordonnateur n'y figure PAS : il traite le courrier lui-meme et ne
// l'impute a personne, c'est un destinataire terminal.
// On raisonne sur le LIBELLE du profil et non sur son numero, car celui-ci est
// attribue automatiquement a la creation. Les libelles doivent correspondre
// exactement a ceux de l'ecran Parametrage > Les profils (la casse est ignoree).
// Pour ajouter un profil equivalent, ajoutez simplement son libelle ici.
define("ROLES_NIVEAU_HIERARCHIQUE", "Assistante du Directeur General,Directeur General,Secretaire General,Directeur,Coordonnateur");

// Profils qui voient l'INTEGRALITE du circuit d'un courrier, sans etre limites
// a leur propre echelon. Le Secretaire General a besoin de cette vue d'ensemble
// puisqu'il oriente le courrier vers les directions et les cellules et doit en
// suivre le traitement en aval.
define("ROLES_VUE_TRANSVERSALE", "Secretaire General");

// Profils disposant d'un acces integral a l'application. Le controle des droits
// ecran par ecran ne s'applique pas a eux : un administrateur ne doit jamais
// pouvoir se retrouver bloque hors de sa propre application faute d'une ligne
// manquante dans la table des permissions.
define("ROLES_ADMINISTRATEUR", "Administrator,Administrateur");

// Profils du sommet de l'organigramme : ils voient tous les ecrans sans
// dependre de la table des permissions. Le Secretaire General doit avoir
// une vue integrale du circuit, le Directeur General en est l'origine.
// Separer les intitules par une virgule. Casse et accents indifferents.
define("ROLES_ACCES_INTEGRAL", "Directeur General,Secretaire General");

// Socle de droits attribue a tout nouveau profil, et applique a tout profil
// dont la table des permissions est restee vide. Il couvre le circuit d'un
// agent qui recoit une imputation, la traite et la retourne : ni parametrage,
// ni gestion des utilisateurs, ni suppression.
// Format : "page/action", separes par des virgules.
define("ECRANS_PAR_DEFAUT", "courrier/list,courrier/view,courrier/recherche,courrier/courrier_sauve,imputation/list,imputation/traitement_imputation,imputation/edit_traitement,imputation/imputationstraitees,imputations_en_attente/list,imputation_en_retard/list,evenement/list,evenement/suivi,traitementparexpediteur/list,traitementparuser/list,user/accountedit,user/accountview");

// Profils qui voient l'ensemble du courrier sans y etre personnellement
// imputes : le bureau du courrier, qui enregistre les plis avant toute
// imputation et doit pouvoir les suivre. Les profils de supervision
// (ROLES_ADMINISTRATEUR, ROLES_ACCES_INTEGRAL, ROLES_VUE_TRANSVERSALE)
// beneficient deja de cette vue complete.
// Tout autre profil ne voit que les courriers qui lui sont imputes.
define("ROLES_VUE_COMPLETE_COURRIER", "Chef bureau courrier,Saisie");

// Ecrans garantis aux profils du bureau du courrier, quel que soit le
// contenu des tables de droits. Orienter le courrier est leur fonction :
// leur acces a l'imputation ne doit pas dependre d'un parametrage qu'on
// peut oublier de faire.
define("ECRANS_BUREAU_COURRIER", "imputation/add,imputation/list,imputation/view,courrier/courriersaimputer,imputation/imputationstraitees");

// Profil auquel tout courrier entrant est transmis des son enregistrement
// par le bureau du courrier. Designe par son intitule, casse et accents
// indifferents : changer de titulaire ne demande aucune modification du
// code, il suffit d'affecter le profil au bon utilisateur.
// Laisser vide pour desactiver la transmission automatique.
define("PROFIL_RECEPTION_COURRIER", "Directeur Général");
define("META_DESCRIPTION", "Gestion du Courrier");
define("META_KEYWORDS", "courrier");
define("META_VIEWPORT", "width=device-width, initial-scale=1.0");
define("PAGE_CHARSET", "UTF-8");

// Email Configuration Default Settings
define("USE_SMTP",false);
define("SMTP_USERNAME", "");
define("SMTP_PASSWORD", "");
define("SMTP_HOST", "");
define("SMTP_PORT", "");

//Default Email Sender Details. Please set this even if you are not using SMTP
define("DEFAULT_EMAIL", "");
define("DEFAULT_EMAIL_ACCOUNT_NAME", "");

// Database Configuration Settings
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "MOT_DE_PASSE_A_RENSEIGNER"); // MAMP default MySQL password
define("DB_NAME", "courrier2022");
define("DB_TYPE", "mysql");
define("DB_PORT", "8889"); // MAMP default MySQL port
define("DB_CHARSET", "utf8");

define("MAX_RECORD_COUNT", 20); //Default Max Records to Retrieve  per Page
define("ORDER_TYPE", "DESC");  //Default Order Type

// Active User Profile Details
define('USER_ID',(isset($_SESSION[APP_ID.'user_data']) ? $_SESSION[APP_ID.'user_data']['iduser'] : null ));
define('USER_NAME',(isset($_SESSION[APP_ID.'user_data']) ? $_SESSION[APP_ID.'user_data']['login'] : null ));
define('USER_EMAIL',(isset($_SESSION[APP_ID.'user_data']) ? $_SESSION[APP_ID.'user_data']['emailuser'] : null ));
define('USER_PHOTO',(isset($_SESSION[APP_ID.'user_data']) ? $_SESSION[APP_ID.'user_data']['avatar'] : null ));
define('USER_ROLE',(isset($_SESSION[APP_ID.'user_data']) ? $_SESSION[APP_ID.'user_data']['user_role_id'] : null ));