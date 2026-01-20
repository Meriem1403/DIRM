#!/bin/bash
set -e

echo "🚀 Démarrage de l'application DIRM..."

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
sleep 5

# Vérifier si la base de données est accessible
if [ -z "$DATABASE_URL" ]; then
    echo "⚠️  DATABASE_URL n'est pas définie"
else
    echo "✅ DATABASE_URL configurée"
fi

# Exécuter les migrations
echo "📦 Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || echo "⚠️  Erreur lors des migrations (peut être normal si déjà à jour)"

# Vérifier si les utilisateurs existent, sinon charger les fixtures
echo "👥 Vérification des utilisateurs..."
# Tenter de charger les fixtures (ne fera rien si déjà chargées)
php bin/console doctrine:fixtures:load --no-interaction --env=prod --append || echo "⚠️  Fixtures déjà chargées ou erreur"

# Vider le cache
echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod --no-debug || true

# Créer les dossiers nécessaires
echo "📁 Création des dossiers nécessaires..."
mkdir -p var/cache var/log public/uploads
chmod -R 777 var public/uploads || true

# Démarrer le serveur PHP
echo "🌐 Démarrage du serveur PHP sur le port $PORT..."
php -S 0.0.0.0:$PORT -t public
