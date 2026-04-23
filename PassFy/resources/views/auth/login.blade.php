<div id="modal-login" class="modal-overlay">
    <section class="secao-login">
        <i class="close"><span class="material-symbols-outlined">close</span></i>
        <h1>Que bom te ver de novo!</h1>
    <div class="tipo-login">
        <button type="button" class="login-type-btn type-btn active" data-type="cliente">Sou Cliente</button>
        <button type="button" class="login-type-btn type-btn" data-type="organizadora">Sou Organizadora</button>
        <button type="button" class="login-type-btn type-btn" data-type="usuario">Sou Usuário</button>
    </div>
    <form class="form-login" id="form-login" method="POST" action="{{ route('login.cliente') }}">
        @csrf
        <div id="input-dinamico-login" class="input-login"></div>
        <div class="input-login">
            <label for="login-password">Senha</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="login-password" placeholder="Digite a senha" required>
                <i class="fa fa-eye" id="toggle-password"></i>
            </div>
        </div>
        <div class="check-remember">
            <div class="checkbox-wrapper-14">
                <input id="s1-14" type="checkbox" class="switch">
                <label for="s1-14">Lembre-se de mim</label>
            </div>
            <a href="#">Esqueceu sua senha?</a>
        </div>
        <button type="submit" id="login-btn">Entrar</button>
        <div id="loginError" style="display: none; color: red;"></div>
        <button id="google-login-btn"><i class="fa-brands fa-google"></i>Faça login com o Google</button>
        <p>Não tem uma Conta? <a id="register-link" href="{{ route('register.cliente') }}">Cadastre-se agora!</a></p>
    </form>
    </section>
</div>