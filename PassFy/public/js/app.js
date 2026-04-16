const modal = document.getElementById('modal-login');
const btnLogin = document.getElementById('btn-login');
const closeBtn = document.querySelector('.close');

if (btnLogin && modal) {
    btnLogin.addEventListener('click', function(e) {
        e.preventDefault();  // Evita redirecionar
        modal.classList.add('open');
    });
}

// Fechar ao clicar no X
if (closeBtn && modal) {
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('open');
    });
}

// Fechar ao clicar fora do modal
window.addEventListener('click', function(e) {
    if (modal && e.target === modal) {
        modal.classList.remove('open');
    }
});

const loginConfig = {
    cliente: {
        route: '/login/cliente',
        fields: `
            <label>Email</label>
			<input type="text" name="email" placeholder="exemplo@email.com">
        `
    },
    organizadora: {
        route: '/login/organizadora',
        fields: `
            <label>CNPJ</label>
			<input type="text" name="cnpj" placeholder="Digite o CNPJ">
        `
    },
    usuario: {
        route: '/login/usuario',
        fields: `
            <label>Username</label>
			<input type="text" name="username" placeholder="username">
        `
    }
};

let tipoEscolhido = 'cliente';
const loginForm = document.getElementById('form-login');
const campoDinamicoLogin = document.getElementById('input-dinamico-login');
const registerLink = document.getElementById('register-link');

if (campoDinamicoLogin && loginForm) {
    document.querySelectorAll('.login-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.login-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            tipoEscolhido = this.dataset.type;
            campoDinamicoLogin.innerHTML = loginConfig[tipoEscolhido].fields;
            loginForm.action = loginConfig[tipoEscolhido].route;
            if (registerLink) {
                registerLink.href = tipoEscolhido === 'organizadora'
                    ? '/register/organizadora'
                    : '/register/cliente';
            }
        });
    });

    // Inicializar com cliente
    campoDinamicoLogin.innerHTML = loginConfig.cliente.fields;
    loginForm.action = loginConfig.cliente.route;

    // Enviar formulário
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        
        fetch(loginForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/';
            } else {
                // Mostrar erro
                document.getElementById('loginError').textContent = data.message;
                document.getElementById('loginError').style.display = 'block';
            }
        });
    });
}

const registerConfig = {
    cliente: {
        route: '/register/cliente',
        fields: `
            <label>CPF</label>
			<input type="text" name="cpf" placeholder="CPF">
        `
    },
    organizadora: {
        route: '/register/organizadora',
        fields: `
            <label>CNPJ</label>
			<input type="text" name="cnpj" placeholder="CNPJ">
        `
    }
};

// Cadastro
let tipoEscolhidoCadastro = 'cliente';
const selecTipoCadastro = document.getElementById('tipo-cadastro');
const formCadastro = document.getElementById('form-cadastro');
const campoDinamicoCadastro = document.getElementById('input-dinamico-cadastro');

if (selecTipoCadastro && campoDinamicoCadastro && formCadastro) {
    // Atualizar campos ao mudar o tipo
    selecTipoCadastro.addEventListener('change', function() {
        tipoEscolhidoCadastro = this.value;
        campoDinamicoCadastro.innerHTML = registerConfig[tipoEscolhidoCadastro].fields;
        formCadastro.action = registerConfig[tipoEscolhidoCadastro].route;
    });

    // Inicializar com cliente
    campoDinamicoCadastro.innerHTML = registerConfig.cliente.fields;
    formCadastro.action = registerConfig.cliente.route;

    // Enviar formulário
    formCadastro.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(formCadastro);
        
        fetch(formCadastro.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/';
            } else {
                document.getElementById('registerError').textContent = data.message;
                document.getElementById('registerError').style.display = 'block';
            }
        });
    });
}