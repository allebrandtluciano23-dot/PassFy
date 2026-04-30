<header>
		<a href="{{ route('home') }}"><img class="logo-header" src="{{ asset('images/logo.png') }}"></a>
		<ul class="menu-header">
			@if(Auth::guard('cliente')->check())
				{{-- Header para Cliente --}}
				<li>
					<a href="#"><i class="fa-solid fa-shopping-cart"></i> Carrinho</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-ticket"></i> Meus Ingressos</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-wallet"></i> Carteira (R$ {{ number_format(Auth::guard('cliente')->user()->carteiraDigital->saldo ?? 0, 2, ',', '.') }})</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-user"></i> Perfil </a>
				</li>
				<li>
					<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="fa-solid fa-sign-out-alt"></i> Sair
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>
			@elseif(Auth::guard('organizadora')->check())
				{{-- Header para Organizadora --}}
				<li>
					<a href="#"><i class="fa-regular fa-square-plus"></i> Criar Evento</a>
				</li>
				<li>
					<a href="#"><i class="fa-regular fa-calendar"></i> Meus Eventos</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-chart-line"></i> Relatórios</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-building"></i> Perfil </a>
				</li>
				<li>
					<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="fa-solid fa-sign-out-alt"></i> Sair
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>
			@elseif(Auth::guard('usuario')->check())
				{{-- Header para Usuário Admin --}}
				<li>
					<a href="#"><i class="fa-solid fa-cog"></i> Administração</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-users"></i> Gerenciar Usuários</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-chart-bar"></i> Dashboard</a>
				</li>
				<li>
					<a href="#"><i class="fa-solid fa-user-shield"></i> {{ Auth::guard('usuario')->user()->nomeUsuario }}</a>
				</li>
				<li>
					<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="fa-solid fa-sign-out-alt"></i> Sair
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>
			@else
				{{-- Header para Visitante (não autenticado) --}}
				<li>
					<a href="#"><i class="fa-regular fa-square-plus"></i> Criar Eventos</a>
				</li>
				<li>
					<a href="#"><i class="fa-regular fa-calendar"></i> Eventos</a>
				</li>
				<li>
					<a href="#" id="btn-login"><i class="fa-solid fa-user-plus"></i> Entrar</a>
				</li>
			@endif
		</ul>
	</header>