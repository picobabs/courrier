# Gestion du courrier – ANASER

Application web PHP/MySQL de gestion du courrier arrivée et départ : saisie, imputation aux directions et divisions, suivi du traitement, relances, états et exports (PDF, Excel, Word).

## Prérequis

- PHP 7.4 ou 8.x avec les extensions `mysqli`, `pdo_mysql`, `mbstring`, `gd`, `dom`
- MySQL ou MariaDB
- Apache avec `mod_rewrite` (MAMP, XAMPP, WAMP ou un serveur Linux)
- [Composer](https://getcomposer.org/)

## Installation

```bash
git clone https://github.com/picobabs/courrier.git courrieranaser
cd courrieranaser
composer install              # installe Dompdf dans vendor/
cp config.example.php config.php
```

1. Ouvrez `config.php` et renseignez l'accès à la base (`DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`). Si vous voulez envoyer des e-mails, renseignez aussi les paramètres SMTP.
2. Importez la base de données dans MySQL. Le fichier `.sql` n'est pas versionné : il faut le conserver ailleurs.
3. Donnez au serveur web les droits d'écriture sur `uploads/`, `logs/` et `helpers/cache/`.
4. Ouvrez `http://localhost/courrieranaser/` dans le navigateur.

En production, mettez `DEVELOPMENT_MODE` à `false` dans `config.php`.

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
