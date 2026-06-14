@extends('layouts.app')

@section('title', 'Administração - Usuários')

@section('content')
<section class="admin-section">
    <div class="admin-header">
        <h1><i class="fa-solid fa-users"></i> Administração de Usuários</h1>
        <p>Visualize todos os usuários cadastrados no sistema</p>
    </div>

    <div class="admin-tabs">
        <button class="tab-btn active" data-tab="clientes">Clientes</button>
        <button class="tab-btn" data-tab="organizadoras">Organizadoras</button>
    </div>

    {{-- Tabela de Clientes --}}
    <div id="tab-clientes" class="tab-content active">
        <div class="table-header">
            <h2><i class="fa-solid fa-user"></i> Clientes</h2>
            <span class="total-count">Total: {{ $clientes->count() }}</span>
        </div>
        
        @if($clientes->count() > 0)
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->idCliente }}</td>
                            <td><i class="fa-solid fa-user"></i> {{ $cliente->nomeCliente }}</td>
                            <td><i class="fa-solid fa-envelope"></i> {{ $cliente->emailCliente }}</td>
                            <td>{{ $cliente->cpfCliente }}</td>
                            <td>{{ $cliente->telefoneCliente }}</td>
                            <td>{{ $cliente->cidade->nomeCidade ?? '-' }}/{{ $cliente->cidade->ufCidade ?? '-' }}</td>
                            <td><span class="status-ativo">Ativo</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="nenhum-registro">
                <i class="fa-solid fa-user-slash"></i>
                <p>Nenhum cliente cadastrado.</p>
            </div>
        @endif
    </div>

    {{-- Tabela de Organizadoras --}}
    <div id="tab-organizadoras" class="tab-content">
        <div class="table-header">
            <h2><i class="fa-solid fa-building"></i> Organizadoras</h2>
            <span class="total-count">Total: {{ $organizadoras->count() }}</span>
        </div>
        
        @if($organizadoras->count() > 0)
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizadoras as $org)
                        <tr>
                            <td>{{ $org->idOrg }}</td>
                            <td><i class="fa-solid fa-building"></i> {{ $org->nomeOrg }}</td>
                            <td><i class="fa-solid fa-envelope"></i> {{ $org->emailOrg }}</td>
                            <td>{{ $org->cnpjOrg }}</td>
                            <td>{{ $org->telefoneOrg }}</td>
                            <td>{{ $org->cidade->nomeCidade ?? '-' }}/{{ $org->cidade->ufCidade ?? '-' }}</td>
                            <td><span class="status-ativo">Ativo</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="nenhum-registro">
                <i class="fa-solid fa-building-circle-xmark"></i>
                <p>Nenhuma organizadora cadastrada.</p>
            </div>
        @endif
    </div>

    <div class="admin-footer">
        <a href="{{ route('home') }}" class="btn-voltar">
            <i class="fa-solid fa-arrow-left"></i> Voltar para o site
        </a>
    </div>
</section>

<script>
    // Abas
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.dataset.tab;
            
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(`tab-${tabId}`).classList.add('active');
        });
    });
</script>
@endsection