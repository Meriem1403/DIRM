# 🚀 Guide de déploiement - Projet DIRM

## ⚠️ Important : Netlify n'est pas adapté pour Symfony

Netlify est conçu pour les sites statiques et les fonctions serverless. Votre projet Symfony nécessite :
- Un serveur PHP qui tourne en continu
- Une base de données MySQL
- Un environnement d'exécution complet

## 📋 Options de déploiement recommandées

### Option 1 : Railway (Recommandé - Simple et gratuit au début)

Railway est une excellente option pour déployer Symfony avec MySQL.

#### Étapes de déploiement :

1. **Créer un compte sur Railway** : https://railway.app

2. **Créer un nouveau projet** depuis GitHub

3. **Ajouter les services nécessaires** :
   - Service PHP (depuis votre repo GitHub)
   - Service MySQL (depuis le template)

4. **Configurer les variables d'environnement** dans Railway :
   ```env
   APP_ENV=prod
   APP_SECRET=your_secret_key_here
   DATABASE_URL=mysql://user:password@host:3306/database_name
   MAILER_DSN=smtp://localhost:1025
   ```

5. **Créer un fichier `railway.json`** à la racine :
   ```json
   {
     "build": {
       "builder": "NIXPACKS"
     },
     "deploy": {
       "startCommand": "php -S 0.0.0.0:$PORT -t public"
     }
   }
   ```

6. **Créer un fichier `Procfile`** (pour Railway) :
   ```
   web: php -S 0.0.0.0:$PORT -t public
   ```

### Option 2 : Heroku (Classique mais payant maintenant)

1. Installer Heroku CLI
2. Créer une application Heroku
3. Ajouter le buildpack PHP
4. Configurer les variables d'environnement
5. Déployer avec `git push heroku main`

### Option 3 : Clever Cloud (Recommandé pour la France)

Plateforme française spécialisée dans le déploiement d'applications PHP/Symfony.

1. Créer un compte sur https://www.clever-cloud.com
2. Créer une application PHP
3. Ajouter une base de données MySQL
4. Configurer les variables d'environnement
5. Connecter votre repository GitHub

### Option 4 : DigitalOcean App Platform

1. Créer un compte DigitalOcean
2. Créer une nouvelle App depuis GitHub
3. Sélectionner PHP comme runtime
4. Ajouter une base de données MySQL
5. Configurer les variables d'environnement

### Option 5 : AWS Elastic Beanstalk

Pour des déploiements plus complexes et scalables.

## 🔧 Préparation du projet pour le déploiement

### 1. Créer un fichier `.env.production` (exemple)

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=your_production_secret_here

DATABASE_URL="mysql://user:password@host:3306/database_name?serverVersion=8.0"

MAILER_DSN=smtp://smtp.example.com:587?encryption=tls&auth_mode=login&username=user&password=pass

# MongoDB (si utilisé)
MONGODB_URI=mongodb://user:password@host:27017/database_name
```

### 2. Optimiser pour la production

```bash
# Installer les dépendances sans dev
composer install --no-dev --optimize-autoloader

# Compiler les assets
php bin/console cache:clear --env=prod
yarn encore production

# Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create --env=prod
php bin/console doctrine:migrations:migrate --env=prod --no-interaction
```

### 3. Créer un fichier `.htaccess` pour Apache (si nécessaire)

Le fichier devrait déjà exister dans `public/.htaccess` pour Symfony.

## 📝 Fichiers de configuration à créer

Voir les fichiers ci-dessous pour chaque plateforme.
