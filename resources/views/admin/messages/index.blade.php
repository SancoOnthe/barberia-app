@extends('layouts.admin')
@section('title', 'Mensajes - Bandeja de Entrada')
@section('header-title', 'Mensajes')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-envelope"></i> Bandeja de Entrada</h2>
        
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise"></i> Recargar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table admin-table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Remitente</th>
                    <th>Email</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Recibido</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr class="{{ !$msg->read ? 'table-warning' : '' }}">
                    <td class="fw-bold">{{ $msg->name }}</td>
                    <td><a href="mailto:{{ $msg->email }}" class="text-decoration-none">{{ $msg->email }}</a></td>
                    <td>{{ $msg->subject }}</td>
                    
                    <td title="{{ $msg->body }}">
                        {{ Str::limit($msg->body, 50) }}
                    </td>
                    
                    <td class="small text-muted">
                        {{ $msg->created_at->format('d/m/Y H:i') }}
                        <br>
                        <small>{{ $msg->created_at->diffForHumans() }}</small>
                    </td>
                    
                    <td>
                        @if($msg->read)
                            <span class="badge bg-success">Leído</span>
                        @else
                            <span class="badge bg-danger">No Leído</span>
                        @endif
                    </td>
                    
                    <td>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.messages.toggle', $msg->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $msg->read ? 'btn-outline-secondary' : 'btn-primary' }}" 
                                    title="{{ $msg->read ? 'Marcar como no leído' : 'Marcar como leído' }}">
                                    <i class="bi bi-envelope{{ $msg->read ? '-open' : '' }}"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-mailbox fs-1 d-block mb-3"></i>
                        <p>No tienes mensajes nuevos.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection
