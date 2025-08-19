@extends('admin.layout')

@section('title', 'Détails utilisateur')
@section('page-title', 'Détails de l\'utilisateur')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-user"></i> Informations utilisateur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="/habbo-imaging/avatarimage?figure={{ $user->look }}&size=l&direction=2&head_direction=2" 
                             alt="Avatar" class="img-fluid mb-3">
                        <div class="mb-2">
                            @if($user->online)
                                <span class="badge bg-success"><i class="fas fa-circle"></i> En ligne</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-circle"></i> Hors ligne</span>
                            @endif
                        </div>
                        @if($user->isBanned)
                            <span class="badge bg-danger"><i class="fas fa-ban"></i> Banni</span>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">ID:</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td><strong>{{ $user->username }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->mail }}</td>
                            </tr>
                            <tr>
                                <th>Rank:</th>
                                <td>
                                    <span class="badge bg-{{ $user->rank >= 7 ? 'danger' : ($user->rank >= 6 ? 'warning' : 'secondary') }}">
                                        Rank {{ $user->rank }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Motto:</th>
                                <td>{{ $user->motto ?? 'Aucun' }}</td>
                            </tr>
                            <tr>
                                <th>Genre:</th>
                                <td>{{ $user->gender === 'M' ? 'Homme' : 'Femme' }}</td>
                            </tr>
                            <tr>
                                <th>Dernière connexion:</th>
                                <td>
                                    @if($user->last_login)
                                        {{ date('d/m/Y H:i:s', $user->last_login) }}
                                    @else
                                        <span class="text-muted">Jamais</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ date('d/m/Y H:i:s', $user->account_created) }}</td>
                            </tr>
                            <tr>
                                <th>IP actuelle:</th>
                                <td><code>{{ $user->ip_current }}</code></td>
                            </tr>
                            <tr>
                                <th>IP d'inscription:</th>
                                <td><code>{{ $user->ip_register }}</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-coins"></i> Devises</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h4 mb-0">{{ number_format($user->credits ?? 0) }}</div>
                            <small class="text-muted">Credits</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h4 mb-0">{{ number_format($user->pixels ?? 0) }}</div>
                            <small class="text-muted">Pixels</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="h4 mb-0">{{ number_format($user->points ?? 0) }}</div>
                            <small class="text-muted">Points</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if($user->isBanned && $user->banDetails)
        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0"><i class="fas fa-ban"></i> Informations de bannissement</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th>Raison:</th>
                        <td>{{ $user->banDetails->reason }}</td>
                    </tr>
                    <tr>
                        <th>Banni le:</th>
                        <td>{{ date('d/m/Y H:i:s', $user->banDetails->timestamp) }}</td>
                    </tr>
                    @if($user->banDetails->expires)
                    <tr>
                        <th>Expire le:</th>
                        <td>{{ date('d/m/Y H:i:s', $user->banDetails->expires) }}</td>
                    </tr>
                    @else
                    <tr>
                        <th>Durée:</th>
                        <td><span class="text-danger">Permanent</span></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-tools"></i> Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="editUser({{ $user->id }})">
                        <i class="fas fa-edit"></i> Modifier l'utilisateur
                    </button>
                    @if($user->isBanned)
                        <button class="btn btn-success" onclick="unbanUser({{ $user->id }})">
                            <i class="fas fa-check"></i> Débannir
                        </button>
                    @else
                        @if($user->rank < 7)
                        <button class="btn btn-danger" onclick="banUser({{ $user->id }})">
                            <i class="fas fa-ban"></i> Bannir
                        </button>
                        @endif
                    @endif
                    <a href="/admin/users" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include modals from users page -->
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" id="editUserId" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" value="{{ $user->username }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" value="{{ $user->mail }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rank</label>
                        <select class="form-select" id="editRank" required>
                            <option value="1" {{ $user->rank == 1 ? 'selected' : '' }}>1 - Utilisateur</option>
                            <option value="2" {{ $user->rank == 2 ? 'selected' : '' }}>2 - VIP</option>
                            <option value="3" {{ $user->rank == 3 ? 'selected' : '' }}>3 - Premium</option>
                            <option value="4" {{ $user->rank == 4 ? 'selected' : '' }}>4 - Moderateur</option>
                            <option value="5" {{ $user->rank == 5 ? 'selected' : '' }}>5 - Super Mod</option>
                            <option value="6" {{ $user->rank == 6 ? 'selected' : '' }}>6 - Staff</option>
                            <option value="7" {{ $user->rank == 7 ? 'selected' : '' }}>7 - Admin</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Credits</label>
                            <input type="number" class="form-control" id="editCredits" value="{{ $user->credits ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pixels</label>
                            <input type="number" class="form-control" id="editPixels" value="{{ $user->pixels ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Points</label>
                            <input type="number" class="form-control" id="editPoints" value="{{ $user->points ?? 0 }}" min="0">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Motto</label>
                        <input type="text" class="form-control" id="editMotto" value="{{ $user->motto }}" maxlength="255">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ban User Modal -->
<div class="modal fade" id="banUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bannir l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="banUserForm">
                <div class="modal-body">
                    <input type="hidden" id="banUserId" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label class="form-label">Raison du bannissement</label>
                        <textarea class="form-control" id="banReason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expire le (optionnel)</label>
                        <input type="datetime-local" class="form-control" id="banExpires">
                        <small class="form-text text-muted">Laissez vide pour un bannissement permanent</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Bannir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editUser(userId) {
    $('#editUserModal').modal('show');
}

function banUser(userId) {
    $('#banUserModal').modal('show');
}

function unbanUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir débannir cet utilisateur ?')) {
        $.ajax({
            url: '/admin/users/' + userId + '/unban',
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                showAlert('Erreur lors du débannissement', 'danger');
            }
        });
    }
}

$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    const userId = $('#editUserId').val();
    const data = {
        username: $('#editUsername').val(),
        mail: $('#editEmail').val(),
        rank: $('#editRank').val(),
        credits: $('#editCredits').val(),
        pixels: $('#editPixels').val(),
        points: $('#editPoints').val(),
        motto: $('#editMotto').val()
    };

    $.ajax({
        url: '/admin/users/' + userId,
        type: 'PUT',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                $('#editUserModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('Erreur lors de la mise à jour', 'danger');
        }
    });
});

$('#banUserForm').on('submit', function(e) {
    e.preventDefault();
    const userId = $('#banUserId').val();
    const data = {
        reason: $('#banReason').val(),
        expires: $('#banExpires').val() ? Math.floor(new Date($('#banExpires').val()).getTime() / 1000) : null
    };

    $.ajax({
        url: '/admin/users/' + userId + '/ban',
        type: 'POST',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                $('#banUserModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('Erreur lors du bannissement', 'danger');
        }
    });
});
</script>
@endsection