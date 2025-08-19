@extends('admin.layout')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Accessible stats cards with WCAG compliance -->
<div class="row mb-4" role="region" aria-labelledby="stats-heading">
    <div class="visually-hidden">
        <h2 id="stats-heading">Statistiques du système</h2>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card" role="region" aria-labelledby="total-users-title">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h2 mb-0" id="total-users-title">
                            <span class="visually-hidden">Total des </span>{{ $stats->total_users }}
                        </h3>
                        <p class="card-text mb-0">Utilisateurs total</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-users fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card" role="region" aria-labelledby="online-users-title">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h2 mb-0" id="online-users-title">
                            {{ $stats->online_users }}
                        </h3>
                        <p class="card-text mb-0">En ligne</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-circle text-success fa-2x" aria-hidden="true" title="Utilisateurs connectés"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card" role="region" aria-labelledby="banned-users-title">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h2 mb-0" id="banned-users-title">
                            {{ $stats->banned_users }}
                        </h3>
                        <p class="card-text mb-0">Utilisateurs bannis</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-ban text-danger fa-2x" aria-hidden="true" title="Utilisateurs bannis"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card" role="region" aria-labelledby="articles-title">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h2 mb-0" id="articles-title">
                            {{ $stats->total_articles }}
                        </h3>
                        <p class="card-text mb-0">Articles publiés</p>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-newspaper fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-chart-line"></i> Activité récente</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Bienvenue dans le panel d'administration Chocolatey CMS.
                    Utilisez le menu de gauche pour naviguer entre les différentes sections.
                </div>
                
                <h6>Actions rapides</h6>
                <div class="btn-group" role="group">
                    <a href="/admin/users" class="btn btn-outline-primary">
                        <i class="fas fa-users"></i> Gérer les utilisateurs
                    </a>
                    <a href="/admin/articles" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Nouvel article
                    </a>
                    <a href="/admin/settings" class="btn btn-outline-warning">
                        <i class="fas fa-cog"></i> Paramètres
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-server"></i> Informations système</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Version PHP:</strong> {{ PHP_VERSION }}
                </div>
                <div class="mb-2">
                    <strong>Framework:</strong> Lumen {{ app()->version() }}
                </div>
                <div class="mb-2">
                    <strong>Environnement:</strong> {{ app()->environment() }}
                </div>
                <div class="mb-2">
                    <strong>Serveur:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertes</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning alert-sm">
                    <small><i class="fas fa-clock"></i> Pensez à faire des sauvegardes régulières de la base de données.</small>
                </div>
                @if($stats['banned_users'] > 0)
                <div class="alert alert-danger alert-sm">
                    <small><i class="fas fa-ban"></i> {{ $stats['banned_users'] }} utilisateur(s) actuellement banni(s).</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection