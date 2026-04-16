<div id="modal-login" class="modal-overlay">
    <section class="secao-login">
        <p class="close">X</p>
        <h1>Que bom te ver de novo!</h1>
    <div class="tipo-login">
        <button type="button" class="login-type-btn active" data-type="cliente">Sou Cliente</button>
        <button type="button" class="login-type-btn" data-type="organizadora">Sou Organizadora</button>
        <button type="button" class="login-type-btn" data-type="usuario">Sou Usuário</button>
    </div>
    <form class="form-login" id="form-login" method="POST" action="{{ route('login.cliente') }}">
        @csrf
        <div id="input-dinamico-login" class="input-login"></div>
        <div class="input-login">
            <label>Senha</label>
            <input type="password" name="password" placeholder="Digite a senha" required>
        </div>
        <div class="check-remember">
            <div class="checkbox-wrapper">
                <input type="checkbox" name="remember" unchecked>
                <label>Lembre-se de mim</label>
            </div>
            <a href="#">Esqueceu sua senha?</a>
        </div>
        <button type="submit">Entrar</button>
        <div id="loginError" style="display: none; color: red;"></div>
        <button>Faça login com o Google</button>
        <p>Não tem uma Conta? <a id="register-link" href="{{ route('register.cliente') }}">Cadastre-se agora!</a></p>
    </form>
    </section>
</div>