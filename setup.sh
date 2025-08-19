#!/bin/bash

# Script d'installation et de démarrage pour Chocolatey CMS
# Usage: ./setup.sh [clean|start|stop|restart|logs|status]

set -e

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Fonctions utilitaires
print_header() {
    echo -e "${PURPLE}"
    echo "================================================================"
    echo "               🍫 CHOCOLATEY CMS DOCKER SETUP 🍫"
    echo "================================================================"
    echo -e "${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Vérification des prérequis
check_requirements() {
    print_info "Vérification des prérequis..."
    
    if ! command -v docker &> /dev/null; then
        print_error "Docker n'est pas installé. Veuillez installer Docker Desktop."
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose n'est pas installé. Veuillez installer Docker Compose."
        exit 1
    fi
    
    if ! docker info &> /dev/null; then
        print_error "Docker n'est pas démarré. Veuillez démarrer Docker Desktop."
        exit 1
    fi
    
    print_success "Tous les prérequis sont satisfaits"
}

# Installation propre
clean_install() {
    print_header
    print_info "🧹 Installation propre de Chocolatey CMS..."
    
    check_requirements
    
    # Arrêt et suppression des conteneurs existants
    print_info "Arrêt des conteneurs existants..."
    docker-compose down -v --remove-orphans 2>/dev/null || true
    
    # Nettoyage des images orphelines
    print_info "Nettoyage des images Docker..."
    docker system prune -f 2>/dev/null || true
    
    # Suppression des volumes de données (ATTENTION: perte de données)
    print_warning "Suppression des volumes de données existants..."
    docker volume rm chocolatey-2500_mysql_data chocolatey-2500_redis_data 2>/dev/null || true
    
    # Copie du fichier .env pour Docker
    if [ ! -f .env ]; then
        print_info "Création du fichier de configuration .env..."
        cp .env.docker .env
    fi
    
    # Construction et démarrage des services
    print_info "Construction des images Docker..."
    docker-compose build --no-cache
    
    print_info "Démarrage des services..."
    docker-compose up -d
    
    # Attente que tous les services soient prêts
    print_info "Attente du démarrage des services..."
    sleep 30
    
    # Vérification de la santé des services
    print_info "Vérification de la santé des services..."
    
    # Test MySQL
    if docker-compose exec -T mysql mysqladmin ping -h localhost -u chocolatey -pchocolatey_password &>/dev/null; then
        print_success "MySQL est opérationnel"
    else
        print_error "MySQL ne répond pas"
    fi
    
    # Test Redis
    if docker-compose exec -T redis redis-cli ping &>/dev/null; then
        print_success "Redis est opérationnel"
    else
        print_error "Redis ne répond pas"
    fi
    
    # Test Application
    if curl -s http://localhost:8080/health &>/dev/null; then
        print_success "Application web est opérationnelle"
    else
        print_warning "Application web pas encore prête (peut prendre quelques minutes)"
    fi
    
    print_success "Installation terminée avec succès !"
    show_access_info
}

# Démarrage simple
start() {
    print_header
    print_info "🚀 Démarrage de Chocolatey CMS..."
    
    check_requirements
    
    if [ ! -f .env ]; then
        cp .env.docker .env
        print_info "Fichier .env créé"
    fi
    
    docker-compose up -d
    print_success "Services démarrés"
    show_access_info
}

# Arrêt
stop() {
    print_info "🛑 Arrêt de Chocolatey CMS..."
    docker-compose down
    print_success "Services arrêtés"
}

# Redémarrage
restart() {
    print_info "🔄 Redémarrage de Chocolatey CMS..."
    stop
    sleep 5
    start
}

# Affichage des logs
show_logs() {
    print_info "📋 Affichage des logs..."
    docker-compose logs -f
}

# Statut des services
show_status() {
    print_info "📊 Statut des services:"
    echo ""
    docker-compose ps
    echo ""
    
    # Vérification des ports
    print_info "🌐 Vérification des services web:"
    
    services=(
        "http://localhost:8080|Application principale"
        "http://localhost:8081|phpMyAdmin"
        "http://localhost:8082|Redis Commander"
        "http://localhost:8025|MailHog"
    )
    
    for service in "${services[@]}"; do
        url=$(echo $service | cut -d'|' -f1)
        name=$(echo $service | cut -d'|' -f2)
        
        if curl -s --max-time 3 $url/health &>/dev/null || curl -s --max-time 3 $url &>/dev/null; then
            print_success "$name: Accessible sur $url"
        else
            print_warning "$name: Non accessible sur $url"
        fi
    done
}

# Informations d'accès
show_access_info() {
    echo ""
    print_info "🌐 URLs d'accès:"
    echo -e "   ${GREEN}Application principale:${NC} http://localhost:8080"
    echo -e "   ${GREEN}phpMyAdmin:${NC}          http://localhost:8081"
    echo -e "   ${GREEN}Redis Commander:${NC}     http://localhost:8082"
    echo -e "   ${GREEN}MailHog (emails):${NC}    http://localhost:8025"
    echo ""
    print_info "🔑 Identifiants par défaut:"
    echo -e "   ${GREEN}Admin:${NC} admin / admin123"
    echo -e "   ${GREEN}Demo:${NC}  demo / demo123"
    echo -e "   ${GREEN}MySQL:${NC} chocolatey / chocolatey_password"
    echo ""
    print_info "📚 Commandes utiles:"
    echo -e "   ${GREEN}Logs:${NC}      ./setup.sh logs"
    echo -e "   ${GREEN}Statut:${NC}    ./setup.sh status"
    echo -e "   ${GREEN}Arrêt:${NC}     ./setup.sh stop"
    echo -e "   ${GREEN}Restart:${NC}   ./setup.sh restart"
    echo ""
}

# Commandes de développement
dev_commands() {
    echo ""
    print_info "🛠️  Commandes de développement:"
    echo ""
    echo "# Accès au conteneur de l'application"
    echo "docker-compose exec app bash"
    echo ""
    echo "# Exécution de Composer"
    echo "docker-compose exec app composer install"
    echo ""
    echo "# Accès à MySQL"
    echo "docker-compose exec mysql mysql -u chocolatey -pchocolatey_password chocolatey"
    echo ""
    echo "# Accès à Redis CLI"
    echo "docker-compose exec redis redis-cli"
    echo ""
    echo "# Vider le cache Redis"
    echo "docker-compose exec redis redis-cli FLUSHALL"
    echo ""
    echo "# Voir les logs d'un service spécifique"
    echo "docker-compose logs -f app"
    echo "docker-compose logs -f nginx"
    echo "docker-compose logs -f mysql"
    echo ""
}

# Menu d'aide
show_help() {
    print_header
    echo "Usage: ./setup.sh [COMMAND]"
    echo ""
    echo "Commandes disponibles:"
    echo "  clean     Installation propre (supprime toutes les données)"
    echo "  start     Démarrage des services"
    echo "  stop      Arrêt des services"
    echo "  restart   Redémarrage des services"
    echo "  logs      Affichage des logs en temps réel"
    echo "  status    Statut des services"
    echo "  dev       Affichage des commandes de développement"
    echo "  help      Affichage de cette aide"
    echo ""
    echo "Si aucune commande n'est spécifiée, 'start' sera exécuté."
}

# Script principal
main() {
    case "${1:-start}" in
        "clean")
            clean_install
            ;;
        "start")
            start
            ;;
        "stop")
            stop
            ;;
        "restart")
            restart
            ;;
        "logs")
            show_logs
            ;;
        "status")
            show_status
            ;;
        "dev")
            dev_commands
            ;;
        "help"|"-h"|"--help")
            show_help
            ;;
        *)
            print_error "Commande inconnue: $1"
            show_help
            exit 1
            ;;
    esac
}

# Vérification que le script est exécuté depuis le bon répertoire
if [ ! -f "docker-compose.yml" ]; then
    print_error "Ce script doit être exécuté depuis le répertoire racine du projet (où se trouve docker-compose.yml)"
    exit 1
fi

# Exécution du script principal
main "$@"