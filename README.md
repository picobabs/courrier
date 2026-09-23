# Gestion du courrier – ANASER

Application web PHP/MySQL de gestion du courrier arrivée et départ : saisie, imputation aux directions et divisions, suivi du traitement, relances, états et exports (PDF, Excel, Word).

## Prérequis

- PHP 7.4 à 8.4 avec les extensions `mysqli`, `pdo_mysql`, `mbstring`, `gd`, `dom`
- MySQL ou MariaDB
- Apache avec `mod_rewrite` (MAMP, XAMPP, WAMP ou un serveur Linux)
- [Composer](https://getcomposer.org/)

## Installation

```bash
git clone https://github.com/picobabs/courrier.git courrieranaser
cd courrieranaser
composer install              # installe Dompdf 3 dans vendor/ (génère composer.lock)
cp config.example.php config.php
```

1. Ouvrez `config.php` et renseignez l'accès à la base (`DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`). Si vous voulez envoyer des e-mails, renseignez aussi les paramètres SMTP.
2. Importez la base de données dans MySQL. Le fichier `.sql` n'est pas versionné : il faut le conserver ailleurs.
3. Donnez au serveur web les droits d'écriture sur `uploads/`, `logs/` et `helpers/cache/`.
4. Ouvrez `http://localhost/courrieranaser/` dans le navigateur.

En production, dans `config.php` :

- mettez `DEVELOPMENT_MODE` à `false`, sinon les erreurs PHP s'affichent à l'écran ;
- fixez l'adresse du site à la main, par exemple `define("SITE_ADDR", "https://courrier.anaser.sn/");`, au lieu de la déduire de l'en-tête `Host`. Sinon, un attaquant peut faire envoyer des liens de réinitialisation de mot de passe qui pointent vers son propre site.

Si vous mettez à jour une installation existante qui utilisait Dompdf 0.8, supprimez `vendor/` et `composer.lock`, puis relancez `composer install`.

## Structure

| Dossier | Contenu |
|---|---|
| `app/controllers` | Contrôleurs (courrier, imputation, utilisateurs, etc.) |
| `app/models` | Accès aux données |
| `app/views` | Gabarits et pages |
| `system` | Noyau du framework (routeur, vues, base de données) |
| `helpers`, `libs` | Fonctions utilitaires, PHPMailer, ACL, CSRF, exports |
| `assets` | CSS, JavaScript, polices, images |
| `languages` | Traductions |

## Fichiers volontairement exclus du dépôt

Les exclusions sont définies dans `.gitignore` :

- `config.php`, qui contient les mots de passe
- `vendor/`, à régénérer avec `composer install`
- `uploads/` et `logs/`, qui contiennent les données produites par l'application
- les vidéos de démonstration (`*.mp4`) et les sauvegardes de base
- les scripts d'administration temporaires (`configurer-*.php`, `migrer-base.php`, etc.), qui s'exécutent sans authentification
