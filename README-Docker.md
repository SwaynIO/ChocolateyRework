# 🍫 Chocolatey CMS - Environment Docker

Environnement Docker complet pour le développement et les tests locaux de Chocolatey CMS.

## 🚀 Installation Rapide

### Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/install/) (inclus avec Docker Desktop)
- Git

### 🎯 Installation en une commande

```bash
# Linux/macOS
./setup.sh clean

# Windows
setup.bat clean
```

## 📋 Services Inclus

| Service | Port | URL | Description |
|---------|------|-----|-------------|
| **Application** | 8080 | http://localhost:8080 | CMS Chocolatey principal |
| **phpMyAdmin** | 8081 | http://localhost:8081 | Interface de gestion MySQL |
| **Redis Commander** | 8082 | http://localhost:8082 | Interface de gestion Redis |
| **MailHog** | 8025 | http://localhost:8025 | Capture d'emails (SMTP: 1025) |

## 🔑 Identifiants par Défaut

### Application
- **Administrateur**: `admin` / `admin123`
- **Utilisateur demo**: `demo` / `demo123`

### Base de données
- **Host**: `mysql` (depuis les conteneurs) / `localhost:3306` (depuis l'hôte)
- **Database**: `chocolatey`
- **Username**: `chocolatey`
- **Password**: `chocolatey_password`
- **Root password**: `root_password`

### Redis
- **Host**: `redis` (depuis les conteneurs) / `localhost:6379` (depuis l'hôte)
- **Password**: aucun (mode développement)

## 📜 Commandes Disponibles

### Linux/macOS (`./setup.sh`)

```bash
# Installation propre (supprime toutes les données)
./setup.sh clean

# Démarrage normal
./setup.sh start

# Arrêt
./setup.sh stop

# Redémarrage
./setup.sh restart

# Affichage des logs en temps réel
./setup.sh logs

# Statut des services
./setup.sh status

# Commandes de développement
./setup.sh dev

# Aide
./setup.sh help
```

### Windows (`setup.bat`)

```cmd
# Installation propre
setup.bat clean

# Démarrage normal
setup.bat start

# Arrêt
setup.bat stop

# Redémarrage
setup.bat restart

# Logs
setup.bat logs

# Statut
setup.bat status

# Aide
setup.bat help
```

## 🛠️ Développement

### Accès aux conteneurs

```bash
# Application PHP
docker-compose exec app bash

# Base de données MySQL
docker-compose exec mysql mysql -u chocolatey -pchocolatey_password chocolatey

# Redis CLI
docker-compose exec redis redis-cli

# Nginx
docker-compose exec nginx sh
```

### Gestion du cache

```bash
# Vider le cache Redis
docker-compose exec redis redis-cli FLUSHALL

# Vider le cache PHP OpCache
docker-compose exec app php -r "opcache_reset();"

# Redémarrer l'application
docker-compose restart app
```

### Logs spécifiques

```bash
# Logs de l'application
docker-compose logs -f app

# Logs Nginx
docker-compose logs -f nginx

# Logs MySQL
docker-compose logs -f mysql

# Logs Redis
docker-compose logs -f redis
```

### Composer et dépendances

```bash
# Installation des dépendances
docker-compose exec app composer install

# Mise à jour des dépendances
docker-compose exec app composer update

# Optimisation de l'autoloader
docker-compose exec app composer dump-autoload --optimize
```

## 🗄️ Gestion des Données

### Volumes persistants

Les données sont stockées dans des volumes Docker persistants :
- `chocolatey-2500_mysql_data` : Données MySQL
- `chocolatey-2500_redis_data` : Données Redis

### Sauvegarde

```bash
# Sauvegarde MySQL
docker-compose exec mysql mysqldump -u chocolatey -pchocolatey_password chocolatey > backup.sql

# Restauration MySQL
docker-compose exec -T mysql mysql -u chocolatey -pchocolatey_password chocolatey < backup.sql
```

### Reset complet

```bash
# ATTENTION: Supprime toutes les données !
docker-compose down -v
docker volume rm chocolatey-2500_mysql_data chocolatey-2500_redis_data
./setup.sh clean
```

## ⚡ Performances

### Optimisations incluses

- **PHP OpCache** : Cache d'opcode activé
- **Redis** : Cache applicatif et sessions
- **Nginx** : Compression Gzip, cache statique
- **MySQL** : Configuration optimisée pour le développement

### Monitoring

```bash
# Utilisation des ressources
docker stats

# Espace disque des volumes
docker system df

# Nettoyage des ressources inutilisées
docker system prune -f
```

## 🔧 Configuration

### Variables d'environnement

Le fichier `.env` est automatiquement créé depuis `.env.docker`. Vous pouvez le modifier selon vos besoins :

```bash
# Éditer la configuration
nano .env

# Redémarrer pour appliquer les changements
./setup.sh restart
```

### Configuration personnalisée

- **PHP** : `docker/php/local.ini`
- **Nginx** : `docker/nginx/chocolatey.conf`
- **MySQL** : `docker/mysql/my.cnf`
- **Redis** : `docker/redis/redis.conf`

## 🐛 Dépannage

### Problèmes courants

#### Port déjà utilisé
```bash
# Vérifier les ports utilisés
netstat -tulpn | grep :8080
lsof -i :8080

# Changer le port dans docker-compose.yml
```

#### Conteneur qui ne démarre pas
```bash
# Vérifier les logs
docker-compose logs [service_name]

# Forcer la reconstruction
docker-compose build --no-cache [service_name]
```

#### Base de données inaccessible
```bash
# Vérifier le statut MySQL
docker-compose exec mysql mysqladmin ping

# Redémarrer MySQL
docker-compose restart mysql
```

#### Permissions de fichiers
```bash
# Corriger les permissions
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```

### Nettoyage complet

```bash
# Arrêt et nettoyage complet
docker-compose down -v --remove-orphans
docker system prune -a -f --volumes
docker volume prune -f
```

## 📚 Documentation

- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [MySQL Configuration](https://dev.mysql.com/doc/refman/8.0/en/)
- [Redis Configuration](https://redis.io/topics/config)

## 🆘 Support

En cas de problème :

1. Vérifiez les logs : `./setup.sh logs`
2. Vérifiez le statut : `./setup.sh status`
3. Essayez un redémarrage : `./setup.sh restart`
4. En dernier recours : `./setup.sh clean`

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Nginx       │────│   PHP-FPM       │────│     MySQL       │
│   (Port 8080)   │    │  (Chocolatey)   │    │   (Port 3306)   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │
                                │
                       ┌─────────────────┐
                       │     Redis       │
                       │   (Port 6379)   │
                       └─────────────────┘
```

### Réseau Docker

Tous les services communiquent via le réseau `chocolatey_network` avec résolution DNS automatique entre conteneurs.

---

**🍫 Chocolatey CMS - Optimisé pour 2025**