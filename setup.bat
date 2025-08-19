@echo off
setlocal enabledelayedexpansion

REM Script d'installation et de démarrage pour Chocolatey CMS (Windows)
REM Usage: setup.bat [clean|start|stop|restart|logs|status]

REM Couleurs pour Windows (limitées)
set "RED=[91m"
set "GREEN=[92m"
set "YELLOW=[93m"
set "BLUE=[94m"
set "PURPLE=[95m"
set "NC=[0m"

REM Fonction pour afficher l'en-tête
:print_header
echo.
echo %PURPLE%================================================================%NC%
echo %PURPLE%               🍫 CHOCOLATEY CMS DOCKER SETUP 🍫%NC%
echo %PURPLE%================================================================%NC%
echo.
goto :eof

REM Fonction pour afficher un succès
:print_success
echo %GREEN%✅ %~1%NC%
goto :eof

REM Fonction pour afficher une info
:print_info
echo %BLUE%ℹ️  %~1%NC%
goto :eof

REM Fonction pour afficher un avertissement
:print_warning
echo %YELLOW%⚠️  %~1%NC%
goto :eof

REM Fonction pour afficher une erreur
:print_error
echo %RED%❌ %~1%NC%
goto :eof

REM Vérification des prérequis
:check_requirements
call :print_info "Vérification des prérequis..."

docker --version >nul 2>&1
if errorlevel 1 (
    call :print_error "Docker n'est pas installé. Veuillez installer Docker Desktop."
    exit /b 1
)

docker-compose --version >nul 2>&1
if errorlevel 1 (
    call :print_error "Docker Compose n'est pas installé. Veuillez installer Docker Compose."
    exit /b 1
)

docker info >nul 2>&1
if errorlevel 1 (
    call :print_error "Docker n'est pas démarré. Veuillez démarrer Docker Desktop."
    exit /b 1
)

call :print_success "Tous les prérequis sont satisfaits"
goto :eof

REM Installation propre
:clean_install
call :print_header
call :print_info "🧹 Installation propre de Chocolatey CMS..."

call :check_requirements
if errorlevel 1 exit /b 1

call :print_info "Arrêt des conteneurs existants..."
docker-compose down -v --remove-orphans >nul 2>&1

call :print_info "Nettoyage des images Docker..."
docker system prune -f >nul 2>&1

call :print_warning "Suppression des volumes de données existants..."
docker volume rm chocolatey-2500_mysql_data chocolatey-2500_redis_data >nul 2>&1

if not exist .env (
    call :print_info "Création du fichier de configuration .env..."
    copy .env.docker .env >nul
)

call :print_info "Construction des images Docker..."
docker-compose build --no-cache

call :print_info "Démarrage des services..."
docker-compose up -d

call :print_info "Attente du démarrage des services..."
timeout /t 30 >nul

call :print_success "Installation terminée avec succès !"
call :show_access_info
goto :eof

REM Démarrage simple
:start
call :print_header
call :print_info "🚀 Démarrage de Chocolatey CMS..."

call :check_requirements
if errorlevel 1 exit /b 1

if not exist .env (
    copy .env.docker .env >nul
    call :print_info "Fichier .env créé"
)

docker-compose up -d
call :print_success "Services démarrés"
call :show_access_info
goto :eof

REM Arrêt
:stop
call :print_info "🛑 Arrêt de Chocolatey CMS..."
docker-compose down
call :print_success "Services arrêtés"
goto :eof

REM Redémarrage
:restart
call :print_info "🔄 Redémarrage de Chocolatey CMS..."
call :stop
timeout /t 5 >nul
call :start
goto :eof

REM Affichage des logs
:show_logs
call :print_info "📋 Affichage des logs..."
docker-compose logs -f
goto :eof

REM Statut des services
:show_status
call :print_info "📊 Statut des services:"
echo.
docker-compose ps
echo.
goto :eof

REM Informations d'accès
:show_access_info
echo.
call :print_info "🌐 URLs d'accès:"
echo    %GREEN%Application principale:%NC% http://localhost:8080
echo    %GREEN%phpMyAdmin:%NC%          http://localhost:8081
echo    %GREEN%Redis Commander:%NC%     http://localhost:8082
echo    %GREEN%MailHog (emails):%NC%    http://localhost:8025
echo.
call :print_info "🔑 Identifiants par défaut:"
echo    %GREEN%Admin:%NC% admin / admin123
echo    %GREEN%Demo:%NC%  demo / demo123
echo    %GREEN%MySQL:%NC% chocolatey / chocolatey_password
echo.
call :print_info "📚 Commandes utiles:"
echo    %GREEN%Logs:%NC%      setup.bat logs
echo    %GREEN%Statut:%NC%    setup.bat status
echo    %GREEN%Arrêt:%NC%     setup.bat stop
echo    %GREEN%Restart:%NC%   setup.bat restart
echo.
goto :eof

REM Menu d'aide
:show_help
call :print_header
echo Usage: setup.bat [COMMAND]
echo.
echo Commandes disponibles:
echo   clean     Installation propre (supprime toutes les données)
echo   start     Démarrage des services
echo   stop      Arrêt des services
echo   restart   Redémarrage des services
echo   logs      Affichage des logs en temps réel
echo   status    Statut des services
echo   help      Affichage de cette aide
echo.
echo Si aucune commande n'est spécifiée, 'start' sera exécuté.
goto :eof

REM Script principal
if not exist docker-compose.yml (
    call :print_error "Ce script doit être exécuté depuis le répertoire racine du projet"
    exit /b 1
)

set "command=%~1"
if "%command%"=="" set "command=start"

if "%command%"=="clean" (
    call :clean_install
) else if "%command%"=="start" (
    call :start
) else if "%command%"=="stop" (
    call :stop
) else if "%command%"=="restart" (
    call :restart
) else if "%command%"=="logs" (
    call :show_logs
) else if "%command%"=="status" (
    call :show_status
) else if "%command%"=="help" (
    call :show_help
) else if "%command%"=="-h" (
    call :show_help
) else if "%command%"=="--help" (
    call :show_help
) else (
    call :print_error "Commande inconnue: %command%"
    call :show_help
    exit /b 1
)

endlocal