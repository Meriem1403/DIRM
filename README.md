# 🐚 Projet DIRM - Direction Interrégionale de la Mer Méditerranée

[![Symfony](https://img.shields.io/badge/Symfony-7.3-black.svg?style=flat&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg?logo=php)](https://www.php.net/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-%5E3.0-38bdf8?logo=tailwindcss)](https://tailwindcss.com/)
[![Docker](https://img.shields.io/badge/Docker-ready-blue?logo=docker)](https://www.docker.com/)
[![MongoDB](https://img.shields.io/badge/MongoDB-%5E6.0-green?logo=mongodb)](https://www.mongodb.com/)
[![MySQL](https://img.shields.io/badge/MySQL-%5E8.0-4479A1?logo=mysql)](https://www.mysql.com/)
[![PhpStorm](https://img.shields.io/badge/IDE-PhpStorm-purple?logo=phpstorm)](https://www.jetbrains.com/phpstorm/)
[![Laragon](https://img.shields.io/badge/Local%20Server-Laragon-orange)](https://laragon.org/)
[![Tests](https://img.shields.io/badge/tests-PHPUnit-green?logo=phpunit)](https://phpunit.de/)
[![Licence](https://img.shields.io/badge/licence-Propriétaire-lightgrey)](#-licence)


Application interne de gestion des démarches administratives numériques (habilitation Cerbère, mobilité, déclaration de chantier), développée pour la DIRM Méditerranée dans un objectif de modernisation, de simplification et d’harmonisation des processus métiers.

## 🌐 Objectif

Numériser et centraliser les démarches internes des services maritimes, en garantissant :
- une traçabilité complète,
- une gestion des rôles sécurisée,
- une interface utilisateur intuitive,
- une interopérabilité entre services.

## ⚙️ Stack technique

| Domaine                   | Technologie                   |
|--------------------------|-------------------------------|
| Framework principal      | Symfony 7.3                   |
| Langage                  | PHP 8.2                       |
| Base de données          | MySQL / Doctrine ORM          |
| Données secondaires      | MongoDB (client PHP)          |
| Interface admin          | EasyAdmin 4                   |
| Frontend dynamique       | Stimulus, Webpack Encore      |
| Design UI                | Tailwind CSS                  |
| Environnement local      | Laragon                       |
| IDE                      | PhpStorm                      |
| Conteneurisation         | Docker                        |
| Outils de test           | PHPUnit, LiipFixtures         |

## 🚀 Fonctionnalités principales

- 🧾 Questionnaire dynamique multi-étapes
- 👥 Authentification sécurisée avec rôles (Agent, Chef de service, Admin)
- ✅ Validation des demandes avec statut, historique et signature
- 📎 Gestion de pièces jointes
- 📊 Page d’indicateurs administratifs
- ✉️ Notifications email (Mailpit)
- 🐳 Environnement Dockerisé

## 🚀 Installation locale

### 1. Cloner le projet

```bash
git clone https://github.com/Meriem1403/DIRM.git
cd DIRM
```

### 2. Installer les dépendances PHP et JS

```bash
composer install
yarn install 
yarn encore dev
```

### 3. Configurer l’environnement

Créer un fichier `.env.local` avec vos identifiants :
```dotenv
DATABASE_URL="mysql://root:<your_db_password>@127.0.0.1:3307/dirm?serverVersion=9.1"
MAILER_DSN=smtp://localhost:1026
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=your_secret_here
```
---
## 🧱 Générer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
---

## 🐳 Lancer le projet avec Docker

1. Cloner le projet :
   ```bash
   git clone https://github.com/votre-compte/projet-dirm.git
   cd projet-dirm   
---

- 📂 phpMyAdmin → [http://localhost:8081](http://localhost:8081)
- ✉️ Mailpit → [http://localhost:8026](http://localhost:8026)
---

## 🔐 Rôles utilisateurs

- `ROLE_AGENT` : renseigne ses formulaires
- `ROLE_CHEF_SERVICE` : validation
- `ROLE_ADMIN` : supervise tout et gère les droits

---

## 🧪 Tester

Lancer les tests automatiques :

```bash
php bin/phpunit
```

---

## 📂 Structure du projet

```
├── src/
│   ├── Controller/
│   ├── DataFixtures/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   ├── Security/
│   ├── Service/
│   └── Validator/
├── templates/
├── public/
├── migrations/
├── docker/
├── tests/
├── compose.yaml
```

---

## 🙌 Crédits

Ce projet a été réalisé dans le cadre d’une certification Studi pour la DIRM Méditerranée.  
Développé par Meriem Zahzouh, Licence CDA 2025.
