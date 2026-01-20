# 🚂 Guide de déploiement Railway - Projet DIRM

Railway est la solution la plus simple pour déployer votre application Symfony avec MySQL.

## 📋 Prérequis

- Un compte GitHub avec votre projet
- Un compte Railway (gratuit au début) : https://railway.app

## 🚀 Étapes de déploiement

### 1. Préparer votre projet

Assurez-vous que votre projet est bien commité et poussé sur GitHub.

### 2. Créer un projet sur Railway

1. Connectez-vous à Railway : https://railway.app
2. Cliquez sur **"New Project"**
3. Sélectionnez **"Deploy from GitHub repo"**
4. Autorisez Railway à accéder à votre repository
5. Sélectionnez votre repository `DIRM`

### 3. Ajouter une base de données MySQL

1. Dans votre projet Railway, cliquez sur **"+ New"**
2. Sélectionnez **"Database"** → **"MySQL"**
3. Railway créera automatiquement une base de données MySQL
4. Notez les variables d'environnement générées (elles seront automatiquement disponibles)

### 4. Configurer votre service PHP

1. Railway devrait avoir détecté automatiquement votre projet PHP
2. Si ce n'est pas le cas, ajoutez un nouveau service et sélectionnez votre repo GitHub

### 5. Configurer les variables d'environnement

Dans les paramètres de votre service PHP, ajoutez ces variables :

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=votre_secret_aleatoire_ici

# La variable DATABASE_URL sera automatiquement injectée par Railway
# Format: mysql://user:password@host:port/database

MAILER_DSN=smtp://localhost:1025
# Pour la production, configurez un vrai service SMTP :
# MAILER_DSN=smtp://smtp.gmail.com:587?encryption=tls&auth_mode=login&username=votre_email@gmail.com&password=votre_mot_de_passe
```

**Pour générer APP_SECRET** :
```bash
php -r "echo bin2hex(random_bytes(32));"
```

### 6. Configurer le build et le démarrage

Railway utilisera automatiquement les fichiers de configuration :
- `railway.json` (déjà créé)
- `.nixpacks.toml` (déjà créé)
- `Procfile` (déjà créé)

### 7. Ajouter un script de déploiement (optionnel)

Créez un fichier `railway-postbuild.sh` :

```bash
#!/bin/bash
# Script exécuté après le build

# Compiler les assets
npm run build

# Vider le cache Symfony
php bin/console cache:clear --env=prod --no-debug

# Exécuter les migrations (sera fait automatiquement au démarrage)
# php bin/console doctrine:migrations:migrate --no-interaction
```

### 8. Configurer les migrations automatiques

Dans Railway, ajoutez une commande de démarrage personnalisée :

```bash
php bin/console doctrine:migrations:migrate --no-interaction && php -S 0.0.0.0:$PORT -t public
```

Ou créez un fichier `start.sh` :

```bash
#!/bin/bash
set -e

# Attendre que la base de données soit prête
echo "Attente de la base de données..."
sleep 5

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Démarrer le serveur PHP
php -S 0.0.0.0:$PORT -t public
```

Puis dans Railway, configurez la commande de démarrage :
```
bash start.sh
```

### 9. Déployer

1. Railway détectera automatiquement les changements sur votre branche principale
2. Le déploiement se lancera automatiquement
3. Vous pouvez suivre les logs en temps réel dans l'interface Railway

### 10. Obtenir votre URL

Une fois déployé, Railway vous donnera une URL publique (ex: `https://votre-app.up.railway.app`)

## 🔧 Configuration avancée

### Variables d'environnement recommandées

```env
# Application
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=votre_secret_ici

# Base de données (injectée automatiquement par Railway)
# DATABASE_URL=mysql://user:password@host:port/database

# Mailer
MAILER_DSN=smtp://smtp.gmail.com:587?encryption=tls&auth_mode=login&username=email&password=password

# MongoDB (si utilisé)
MONGODB_URI=mongodb://user:password@host:27017/database

# Trusted proxies (pour Railway)
TRUSTED_PROXIES=*
TRUSTED_HOSTS=*.up.railway.app
```

### Domaine personnalisé

1. Dans Railway, allez dans les paramètres de votre service
2. Cliquez sur **"Settings"** → **"Networking"**
3. Ajoutez votre domaine personnalisé

## 📊 Monitoring

Railway fournit :
- Des logs en temps réel
- Des métriques de performance
- Un monitoring de la base de données

## 💰 Coûts

- **Gratuit** : $5 de crédit par mois
- **Payant** : À partir de $5/mois pour plus de ressources

## 🐛 Dépannage

### Les migrations ne s'exécutent pas

Ajoutez la commande dans Railway :
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### Erreur de permissions

Assurez-vous que les dossiers `var/` et `public/uploads/` sont accessibles en écriture.

### Erreur de connexion à la base de données

Vérifiez que la variable `DATABASE_URL` est correctement configurée et que le service MySQL est démarré.

## 📚 Ressources

- Documentation Railway : https://docs.railway.app
- Documentation Symfony Deployment : https://symfony.com/doc/current/deployment.html
