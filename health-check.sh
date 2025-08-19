#!/bin/bash

# Script de vérification de la santé du système Chocolatey CMS
# Usage: ./health-check.sh

set -e

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m'

# Fonctions utilitaires
print_header() {
    echo -e "${PURPLE}"
    echo "================================================================"
    echo "           🏥 CHOCOLATEY CMS HEALTH CHECK 🏥"
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

# Variables globales
ERRORS=0
WARNINGS=0
CHECKS=0

# Fonction pour incrémenter les compteurs
check_result() {
    local status=$1
    local message="$2"
    
    CHECKS=$((CHECKS + 1))
    
    case $status in
        "ok")
            print_success "$message"
            ;;
        "warning")
            print_warning "$message"
            WARNINGS=$((WARNINGS + 1))
            ;;
        "error")
            print_error "$message"
            ERRORS=$((ERRORS + 1))
            ;;
    esac
}

# Test de connectivité HTTP
test_http() {
    local url="$1"
    local name="$2"
    local timeout="${3:-5}"
    
    if curl -s --max-time $timeout "$url" >/dev/null 2>&1; then
        check_result "ok" "$name accessible sur $url"
        return 0
    else
        check_result "error" "$name non accessible sur $url"
        return 1
    fi
}

# Test de connectivité avec contenu
test_http_content() {
    local url="$1"
    local expected="$2"
    local name="$3"
    
    local response=$(curl -s --max-time 5 "$url" 2>/dev/null || echo "ERROR")
    
    if [[ "$response" == *"$expected"* ]]; then
        check_result "ok" "$name retourne le contenu attendu"
        return 0
    else
        check_result "error" "$name ne retourne pas le contenu attendu"
        return 1
    fi
}

# Vérification des conteneurs Docker
check_containers() {
    print_info "🐳 Vérification des conteneurs Docker..."
    
    local containers=("chocolatey_app" "chocolatey_nginx" "chocolatey_mysql" "chocolatey_redis")
    
    for container in "${containers[@]}"; do
        if docker ps --format "table {{.Names}}" | grep -q "^$container$"; then
            local status=$(docker inspect --format='{{.State.Health.Status}}' "$container" 2>/dev/null || echo "no-healthcheck")
            local running=$(docker inspect --format='{{.State.Running}}' "$container" 2>/dev/null || echo "false")
            
            if [ "$running" = "true" ]; then
                if [ "$status" = "healthy" ] || [ "$status" = "no-healthcheck" ]; then
                    check_result "ok" "Conteneur $container en cours d'exécution"
                else
                    check_result "warning" "Conteneur $container en cours d'exécution mais statut santé: $status"
                fi
            else
                check_result "error" "Conteneur $container arrêté"
            fi
        else
            check_result "error" "Conteneur $container introuvable"
        fi
    done
}

# Vérification des services web
check_web_services() {
    print_info "🌐 Vérification des services web..."
    
    # Application principale
    test_http "http://localhost:8080" "Application Chocolatey"
    test_http "http://localhost:8080/health" "Health check application"
    
    # phpMyAdmin
    test_http "http://localhost:8081" "phpMyAdmin"
    
    # Redis Commander
    test_http "http://localhost:8082" "Redis Commander"
    
    # MailHog
    test_http "http://localhost:8025" "MailHog"
}

# Vérification des bases de données
check_databases() {
    print_info "🗄️  Vérification des bases de données..."
    
    # Test MySQL
    if docker-compose exec -T mysql mysqladmin ping -h localhost -u chocolatey -pchocolatey_password >/dev/null 2>&1; then
        check_result "ok" "MySQL répond aux requêtes"
        
        # Test de connexion à la base Chocolatey
        if docker-compose exec -T mysql mysql -u chocolatey -pchocolatey_password -e "USE chocolatey; SELECT COUNT(*) FROM users;" >/dev/null 2>&1; then
            check_result "ok" "Base de données Chocolatey accessible"
        else
            check_result "error" "Base de données Chocolatey inaccessible"
        fi
    else
        check_result "error" "MySQL ne répond pas"
    fi
    
    # Test Redis
    if docker-compose exec -T redis redis-cli ping >/dev/null 2>&1; then
        check_result "ok" "Redis répond aux requêtes"
        
        # Test d'écriture/lecture Redis
        if docker-compose exec -T redis redis-cli set healthcheck "ok" >/dev/null 2>&1 && \
           docker-compose exec -T redis redis-cli get healthcheck | grep -q "ok"; then
            check_result "ok" "Redis lecture/écriture fonctionnelle"
            docker-compose exec -T redis redis-cli del healthcheck >/dev/null 2>&1
        else
            check_result "error" "Redis lecture/écriture défaillante"
        fi
    else
        check_result "error" "Redis ne répond pas"
    fi
}

# Vérification des performances
check_performance() {
    print_info "⚡ Vérification des performances..."
    
    # Test de temps de réponse de l'application
    local response_time=$(curl -w "%{time_total}" -s -o /dev/null http://localhost:8080/ 2>/dev/null || echo "999")
    
    if (( $(echo "$response_time < 2.0" | bc -l) )); then
        check_result "ok" "Temps de réponse acceptable: ${response_time}s"
    elif (( $(echo "$response_time < 5.0" | bc -l) )); then
        check_result "warning" "Temps de réponse lent: ${response_time}s"
    else
        check_result "error" "Temps de réponse très lent: ${response_time}s"
    fi
    
    # Vérification de l'utilisation mémoire des conteneurs
    local memory_usage=$(docker stats --no-stream --format "table {{.Container}}\t{{.MemPerc}}" | grep -E "(chocolatey_|CONTAINER)" | tail -n +2)
    
    while IFS=$'\t' read -r container mem_perc; do
        local mem_value=$(echo "$mem_perc" | sed 's/%//')
        if (( $(echo "$mem_value < 80" | bc -l) )); then
            check_result "ok" "Mémoire $container: $mem_perc"
        elif (( $(echo "$mem_value < 90" | bc -l) )); then
            check_result "warning" "Mémoire $container élevée: $mem_perc"
        else
            check_result "error" "Mémoire $container critique: $mem_perc"
        fi
    done <<< "$memory_usage"
}

# Vérification de la sécurité
check_security() {
    print_info "🔒 Vérification de la sécurité..."
    
    # Vérification que les ports ne sont pas exposés publiquement (localhost only)
    local exposed_ports=("8080" "8081" "8082" "8025" "3306" "6379")
    
    for port in "${exposed_ports[@]}"; do
        if netstat -tuln 2>/dev/null | grep -q ":$port.*127.0.0.1\|:$port.*0.0.0.0"; then
            if netstat -tuln 2>/dev/null | grep ":$port" | grep -q "0.0.0.0"; then
                check_result "warning" "Port $port exposé sur toutes les interfaces (0.0.0.0)"
            else
                check_result "ok" "Port $port exposé uniquement en local"
            fi
        fi
    done
    
    # Vérification des fichiers sensibles
    if [ -f ".env" ]; then
        if grep -q "APP_DEBUG=true" .env; then
            check_result "warning" "Mode debug activé (normal en développement)"
        fi
        
        if grep -q "APP_KEY=.*" .env && ! grep -q "APP_KEY=$" .env; then
            check_result "ok" "Clé d'application configurée"
        else
            check_result "error" "Clé d'application manquante"
        fi
    else
        check_result "error" "Fichier .env manquant"
    fi
}

# Vérification des logs
check_logs() {
    print_info "📋 Vérification des logs..."
    
    # Recherche d'erreurs dans les logs récents
    local error_count=$(docker-compose logs --since="1h" 2>/dev/null | grep -i error | wc -l)
    local warning_count=$(docker-compose logs --since="1h" 2>/dev/null | grep -i warning | wc -l)
    
    if [ "$error_count" -eq 0 ]; then
        check_result "ok" "Aucune erreur dans les logs de la dernière heure"
    elif [ "$error_count" -lt 5 ]; then
        check_result "warning" "$error_count erreur(s) dans les logs de la dernière heure"
    else
        check_result "error" "$error_count erreurs dans les logs de la dernière heure"
    fi
    
    if [ "$warning_count" -lt 10 ]; then
        check_result "ok" "$warning_count avertissement(s) dans les logs"
    else
        check_result "warning" "$warning_count avertissements dans les logs"
    fi
}

# Vérification de l'espace disque
check_disk_space() {
    print_info "💾 Vérification de l'espace disque..."
    
    # Espace disque du système
    local disk_usage=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
    
    if [ "$disk_usage" -lt 80 ]; then
        check_result "ok" "Espace disque système: ${disk_usage}% utilisé"
    elif [ "$disk_usage" -lt 90 ]; then
        check_result "warning" "Espace disque système faible: ${disk_usage}% utilisé"
    else
        check_result "error" "Espace disque système critique: ${disk_usage}% utilisé"
    fi
    
    # Espace utilisé par Docker
    local docker_size=$(docker system df --format "table {{.Size}}" | tail -n +2 | head -1 || echo "0B")
    print_info "Espace utilisé par Docker: $docker_size"
}

# Résumé final
print_summary() {
    echo ""
    echo -e "${PURPLE}================================================================${NC}"
    echo -e "${PURPLE}                        RÉSUMÉ${NC}"
    echo -e "${PURPLE}================================================================${NC}"
    echo ""
    
    echo -e "📊 ${BLUE}Vérifications effectuées:${NC} $CHECKS"
    
    if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
        echo -e "🎉 ${GREEN}Système en parfait état !${NC}"
        echo -e "✨ ${GREEN}Toutes les vérifications sont passées avec succès.${NC}"
    elif [ $ERRORS -eq 0 ]; then
        echo -e "😊 ${YELLOW}Système globalement sain avec quelques avertissements.${NC}"
        echo -e "⚠️  ${YELLOW}Avertissements:${NC} $WARNINGS"
    else
        echo -e "🚨 ${RED}Problèmes détectés nécessitant une attention.${NC}"
        echo -e "❌ ${RED}Erreurs:${NC} $ERRORS"
        echo -e "⚠️  ${YELLOW}Avertissements:${NC} $WARNINGS"
    fi
    
    echo ""
    
    if [ $ERRORS -gt 0 ]; then
        echo -e "${RED}Actions recommandées:${NC}"
        echo "• Vérifiez les logs: docker-compose logs"
        echo "• Redémarrez les services: ./setup.sh restart"
        echo "• En cas de problème persistant: ./setup.sh clean"
        echo ""
        exit 1
    elif [ $WARNINGS -gt 0 ]; then
        echo -e "${YELLOW}Recommandations:${NC}"
        echo "• Surveillez les métriques de performance"
        echo "• Vérifiez les logs régulièrement"
        echo ""
        exit 0
    else
        echo -e "${GREEN}Le système fonctionne parfaitement ! 🚀${NC}"
        echo ""
        exit 0
    fi
}

# Script principal
main() {
    print_header
    
    # Vérification que Docker Compose est disponible
    if ! command -v docker-compose &> /dev/null; then
        print_error "docker-compose n'est pas installé ou pas dans le PATH"
        exit 1
    fi
    
    # Vérification que nous sommes dans le bon répertoire
    if [ ! -f "docker-compose.yml" ]; then
        print_error "docker-compose.yml introuvable. Exécutez ce script depuis le répertoire racine du projet."
        exit 1
    fi
    
    # Exécution des vérifications
    check_containers
    check_web_services
    check_databases
    check_performance
    check_security
    check_logs
    check_disk_space
    
    # Affichage du résumé
    print_summary
}

# Installation de bc si nécessaire (pour les calculs flottants)
if ! command -v bc &> /dev/null; then
    print_warning "bc n'est pas installé. Certaines vérifications de performance seront ignorées."
fi

# Exécution du script principal
main "$@"