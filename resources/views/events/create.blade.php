@extends('layouts.app')

@section('title', 'Créer un événement')

@push('styles')
<style>
    .ticket-type-row {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }

    .remove-ticket-type {
        color: #dc3545;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Navigation</h5>
                    <div class="list-group">
                        <a href="{{ route('dashboard.organizer') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.organizer') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('dashboard.events') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.events') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt me-2"></i>Mes événements
                        </a>
                        <a href="{{ route('events.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('events.create') ? 'active' : '' }}">
                            <i class="fas fa-plus me-2"></i>Créer un événement
                        </a>
                        <a href="{{ route('dashboard.statistics') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.statistics') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-user-edit me-2"></i>Mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Créer un nouvel événement</h2>

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data"
                        id="event-form">
                        @csrf

                        <!-- Informations de base de l'événement -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Titre de l'événement <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5"
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="venue" class="form-label">Lieu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue"
                                    name="venue" value="{{ old('venue') }}" required>
                                @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Date et heure de début <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local"
                                    class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                    name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Date et heure de fin <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local"
                                    class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                    name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="draft" {{ old('status')==='draft' ? 'selected' : '' }}>Brouillon
                                    </option>
                                    <option value="published" {{ old('status')==='published' ? 'selected' : '' }}>Publié
                                    </option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Image de l'événement</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                    name="image" accept="image/*">
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section des types de billets -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Types de billets</h4>
                            <button type="button" class="btn btn-success" id="add-ticket-type">
                                <i class="fas fa-plus me-1"></i> Ajouter un type de billet
                            </button>
                        </div>

                        <div id="ticket-types-container">
                            <!-- Les types de billets seront ajoutés ici dynamiquement -->
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard.events') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Créer l'événement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let ticketTypeIndex = 0;

        function addTicketType() {
            const container = document.getElementById('ticket-types-container');
            const ticketTypeHtml = `
            <div class="ticket-type-row" data-index="${ticketTypeIndex}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="ticket_types[${ticketTypeIndex}][name]" class="form-label">Nom du type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ticket_types[${ticketTypeIndex}][name]" placeholder="ex: Standard, VIP" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="ticket_types[${ticketTypeIndex}][price]" class="form-label">Prix (fcfa) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="ticket_types[${ticketTypeIndex}][price]" min="0" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="ticket_types[${ticketTypeIndex}][total_quantity]" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="ticket_types[${ticketTypeIndex}][total_quantity]" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ticket_types[${ticketTypeIndex}][description]" class="form-label">Description</label>
                        <input type="text" class="form-control" name="ticket_types[${ticketTypeIndex}][description]" placeholder="Avantages de ce type de billet">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-ticket-type" onclick="removeTicketType(${ticketTypeIndex})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="ticket_types[${ticketTypeIndex}][sort_order]" value="${ticketTypeIndex}">
            </div>
        `;

            container.insertAdjacentHTML('beforeend', ticketTypeHtml);
            ticketTypeIndex++;
        }

        window.removeTicketType = function (index) {
            const row = document.querySelector(`[data-index="${index}"]`);
            if (row) {
                row.remove();
            }
        };

        document.getElementById('add-ticket-type').addEventListener('click', addTicketType);

        // Ajouter un type de billet par défaut
        addTicketType();
    });
</script>
@endpush
    