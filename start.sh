#!/usr/bin/env bash
set -e

echo "=========================================="
echo "DEMARRAGE DE L'APPLICATION DIRM v3"
echo "=========================================="

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données (10 secondes)..."
sleep 10

# Vérifier si la base de données est accessible
if [ -z "$DATABASE_URL" ]; then
    echo "⚠️  DATABASE_URL n'est pas définie"
    exit 1
else
    echo "✅ DATABASE_URL configurée: ${DATABASE_URL:0:50}..."
fi

# Créer les dossiers nécessaires
echo "📁 Création des dossiers nécessaires..."
mkdir -p var/cache var/log var/sessions public/uploads
chmod -R 777 var public/uploads 2>/dev/null || true

# Vider le cache avant les migrations
echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod --no-debug 2>/dev/null || true
php bin/console cache:warmup --env=prod --no-debug 2>/dev/null || true

# Créer le schéma de la base de données si nécessaire
echo "📦 Création/mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force --env=prod || echo "⚠️  Schema update échoué, tentative avec migrations..."

# Exécuter les migrations
echo "📦 Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod --allow-no-migration || echo "⚠️  Erreur lors des migrations"

# Vérifier si des utilisateurs existent
echo "👥 Vérification des utilisateurs..."
USER_COUNT=$(php bin/console doctrine:query:sql "SELECT COUNT(*) as count FROM user" --env=prod 2>/dev/null | grep -oE '[0-9]+' | head -1 || echo "0")

echo "📊 Nombre d'utilisateurs trouvés: $USER_COUNT"

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "🔄 Aucun utilisateur trouvé, chargement des fixtures..."
    php bin/console doctrine:fixtures:load --no-interaction --env=prod || echo "⚠️  Erreur lors du chargement des fixtures"
    echo "✅ Fixtures chargées!"
else
    echo "✅ Des utilisateurs existent déjà, fixtures ignorées"
fi

# Démarrer le serveur PHP
echo "=========================================="
echo "🌐 Démarrage du serveur PHP sur le port $PORT..."
echo "=========================================="
php -S 0.0.0.0:$PORT -t public
