@extends('admin.layout')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/users" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Rechercher</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Username, email ou ID">
            </div>
            <div class="col-md-2">
                <label for="rank" class="form-label">Rank</label>
                <select class="form-select" id="rank" name="rank">
                    <option value="">Tous</option>
                    <option value="1" {{ request('rank') == '1' ? 'selected' : '' }}>1 - Utilisateur</option>
                    <option value="2" {{ request('rank') == '2' ? 'selected' : '' }}>2 - VIP</option>
                    <option value="3" {{ request('rank') == '3' ? 'selected' : '' }}>3 - Premium</option>
                    <option value="4" {{ request('rank') == '4' ? 'selected' : '' }}>4 - Moderateur</option>
                    <option value="5" {{ request('rank') == '5' ? 'selected' : '' }}>5 - Super Mod</option>
                    <option value="6" {{ request('rank') == '6' ? 'selected' : '' }}>6 - Staff</option>
                    <option value="7" {{ request('rank') == '7' ? 'selected' : '' }}>7 - Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="banned" class="form-label">Statut</label>
                <select class="form-select" id="banned" name="banned">
                    <option value="">Tous</option>
                    <option value="0" {{ request('banned') == '0' ? 'selected' : '' }}>Actifs</option>
                    <option value="1" {{ request('banned') == '1' ? 'selected' : '' }}>Bannis</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="/admin/users" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="fas fa-users"></i> Liste des utilisateurs</h5>
        <span class="badge bg-primary">{{ $users->total() }} utilisateurs</span>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Rank</th>
                        <th>Credits</th>
                        <th>Statut</th>
                        <th>Dernière connexion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="user-avatar">
                                <img src="/habbo-imaging/avatarimage?figure={{ $user->look }}&size=s&direction=2&head_direction=2" 
                                     alt="Avatar" style="width: 32px; height: 32px;">
                            </div>
                        </td>
                        <td>
                            <strong>{{ $user->username }}</strong>
                            @if($user->online)
                                <span class="badge bg-success ms-1">En ligne</span>
                            @endif
                        </td>
                        <td>{{ $user->mail }}</td>
                        <td>
                            <span class="badge bg-{{ $user->rank >= 7 ? 'danger' : ($user->rank >= 6 ? 'warning' : 'secondary') }}">
                                Rank {{ $user->rank }}
                            </span>
                        </td>
                        <td>{{ number_format($user->credits ?? 0) }}</td>
                        <td>
                            @if($user->isBanned)
                                <span class="badge bg-danger"><i class="fas fa-ban"></i> Banni</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-check"></i> Actif</span>
                            @endif
                        </td>
                        <td>
                            @if($user->last_login)
                                {{ date('d/m/Y H:i', $user->last_login) }}
                            @else
                                <span class="text-muted">Jamais</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/{{ $user->id }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-warning" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->isBanned)
                                    <button type="button" class="btn btn-outline-success" onclick="unbanUser({{ $user->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @else
                                    @if($user->rank < 7)
                                    <button type="button" class="btn btn-outline-danger" onclick="banUser({{ $user->id }})">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucun utilisateur trouvé avec ces critères.</p>
        </div>
        @endif
    </div>
</div>

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
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rank</label>
                        <select class="form-select" id="editRank" required>
                            <option value="1">1 - Utilisateur</option>
                            <option value="2">2 - VIP</option>
                            <option value="3">3 - Premium</option>
                            <option value="4">4 - Moderateur</option>
                            <option value="5">5 - Super Mod</option>
                            <option value="6">6 - Staff</option>
                            <option value="7">7 - Admin</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Credits</label>
                            <input type="number" class="form-control" id="editCredits" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pixels</label>
                            <input type="number" class="form-control" id="editPixels" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Points</label>
                            <input type="number" class="form-control" id="editPoints" min="0">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Motto</label>
                        <input type="text" class="form-control" id="editMotto" maxlength="255">
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
                    <input type="hidden" id="banUserId">
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
    // You would fetch user data here via AJAX
    $('#editUserId').val(userId);
    $('#editUserModal').modal('show');
}

function banUser(userId) {
    $('#banUserId').val(userId);
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
                    location.reload();
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
                location.reload();
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
                location.reload();
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