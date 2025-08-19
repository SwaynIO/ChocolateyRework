#!/bin/bash

# Script de démarrage pour le conteneur Chocolatey CMS
set -e

echo "🚀 Démarrage de Chocolatey CMS..."

# Vérification des permissions
echo "📁 Vérification des permissions..."
chown -R chocolatey:chocolatey /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Attente de la base de données
echo "🔄 Attente de la base de données MySQL..."
until php -r "
try {
    \$pdo = new PDO('mysql:host=mysql;dbname=chocolatey', 'chocolatey', 'chocolatey_password');
    echo 'Database connected successfully';
    exit(0);
} catch (PDOException \$e) {
    echo 'Database connection failed: ' . \$e->getMessage();
    exit(1);
}
" 2>/dev/null; do
    echo "⏳ Connexion à la base de données..."
    sleep 5
done

echo "✅ Base de données connectée !"

# Attente de Redis
echo "🔄 Attente de Redis..."
until php -r "
try {
    \$redis = new Redis();
    \$redis->connect('redis', 6379);
    \$redis->ping();
    echo 'Redis connected successfully';
    exit(0);
} catch (Exception \$e) {
    echo 'Redis connection failed: ' . \$e->getMessage();
    exit(1);
}
" 2>/dev/null; do
    echo "⏳ Connexion à Redis..."
    sleep 3
done

echo "✅ Redis connecté !"

# Génération de la clé d'application si nécessaire
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp .env.docker .env
fi

# Vérification et génération de la clé APP_KEY
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    echo "🔑 Génération de la clé d'application..."
    echo "APP_KEY=$(openssl rand -base64 32)" >> .env
fi

# Installation/mise à jour des dépendances Composer
echo "📦 Installation des dépendances..."
su chocolatey -c "composer install --no-dev --optimize-autoloader --no-interaction"

# Nettoyage du cache
echo "🧹 Nettoyage du cache..."
su chocolatey -c "rm -rf storage/framework/cache/data/*"
su chocolatey -c "rm -rf storage/framework/sessions/*"
su chocolatey -c "rm -rf storage/framework/views/*"

# Optimisation Composer pour production
echo "⚡ Optimisation Composer..."
su chocolatey -c "composer dump-autoload --optimize"

# Vérification de la santé de l'application
echo "🔍 Vérification de la santé de l'application..."
php -v
php -m | grep -E "(pdo_mysql|redis|gd|mbstring|zip)"

echo "✨ Chocolatey CMS prêt !"
echo "🌐 Application disponible sur http://localhost:8080"
echo "🗄️  phpMyAdmin disponible sur http://localhost:8081"
echo "🔴 Redis Commander disponible sur http://localhost:8082"
echo "📧 MailHog disponible sur http://localhost:8025"

# Démarrage de PHP-FPM
echo "🎯 Démarrage de PHP-FPM..."
exec php-fpm