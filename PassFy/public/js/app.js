// ----------------------------------Login-----------------------------------

const modal = document.getElementById('modal-login');
const btnLogin = document.getElementById('btn-login');
const closeBtn = document.querySelector('.close');

// Abre modal de login ao clicar no botão "Entrar"
if (btnLogin && modal) {
    btnLogin.addEventListener('click', function(e) {
        e.preventDefault();  // Evita redirecionar
        modal.classList.add('open');
    });
}

// Fechar ao clicar no X do modal
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

// Muda visibilidade da senha
const togglePassword = document.getElementById('toggle-password');
if (togglePassword) {
    togglePassword.addEventListener('click', function() {
        const passwordInput = document.getElementById('login-password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
}

// Seleção do tipo de login (cliente, organizadora, usuario)
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
			<input type="text" name="username" placeholder="Username">
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

// Seleção do tipo de cadastro (cliente, organizadora)
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

let tipoEscolhidoCadastro = 'cliente';
const selecTipoCadastro = document.getElementById('tipo-cadastro');
const formCadastro = document.getElementById('form-cadastro');
const campoDinamicoCadastro = document.getElementById('input-dinamico-cadastro');
const cepInput = document.querySelector('input[name="cep"]');
const cidadeDisplay = document.getElementById('city_display');
const ufDisplay = document.getElementById('state_display');
const cidadeHidden = document.getElementById('city_hidden');
const ufHidden = document.getElementById('state_hidden');
const cepStatus = document.getElementById('cep-status');

if (campoDinamicoCadastro && formCadastro) {
    // Buscar CEP
    if (cepInput) {
        cepInput.addEventListener('blur', function() {
            const cep = this.value.trim();
            
            // Validar CEP (8 dígitos)
            if (cep.length !== 8 || !/^\d{8}$/.test(cep)) {
                cepStatus.textContent = 'CEP deve conter 8 dígitos';
                cepStatus.style.color = 'red';
                cepStatus.style.display = 'block';
                cidadeInput.value = '';
                ufInput.value = '';
                return;
            }

            cepStatus.textContent = 'Buscando...';
            cepStatus.style.color = '#FFA500';
            cepStatus.style.display = 'block';

            // Buscar CEP
            fetch(`/api/cidade/search-by-cep?cep=${cep}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cidadeDisplay.value = data.nomeCidade;
                        ufDisplay.value = data.ufCidade;
                        cidadeHidden.value = data.nomeCidade;
                        ufHidden.value = data.ufCidade;
                        cepStatus.textContent = `✓ Cidade encontrada (${data.source === 'database' ? 'banco' : 'API'})`;
                        cepStatus.style.color = 'green';
                    } else {
                        cidadeDisplay.value = '';
                        ufDisplay.value = '';
                        cidadeHidden.value = '';
                        ufHidden.value = '';
                        cepStatus.textContent = `✗ ${data.message}`;
                        cepStatus.style.color = 'red';
                    }
                })
                .catch(error => {
                    cepStatus.textContent = 'Erro ao buscar CEP';
                    cepStatus.style.color = 'red';
                    console.error('Erro:', error);
                });
        });
    }

    // Atualizar campos ao mudar o tipo
    document.querySelectorAll('.register-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.register-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            tipoEscolhidoCadastro = this.dataset.type;
            campoDinamicoCadastro.innerHTML = registerConfig[tipoEscolhidoCadastro].fields;
            formCadastro.action = registerConfig[tipoEscolhidoCadastro].route;
        });
    });

    // Inicializar com cliente
    campoDinamicoCadastro.innerHTML = registerConfig.cliente.fields;
    formCadastro.action = registerConfig.cliente.route;

    // Enviar formulário
    formCadastro.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cepValue = cepInput ? cepInput.value.trim() : '';
        const cityValue = cidadeHidden ? cidadeHidden.value.trim() : '';
        const stateValue = ufHidden ? ufHidden.value.trim() : '';

        if (!cepValue || cepValue.length !== 8 || !/^\d{8}$/.test(cepValue)) {
            cepStatus.textContent = 'Por favor, digite um CEP válido (8 dígitos).';
            cepStatus.style.color = 'red';
            cepStatus.style.display = 'block';
            cepInput.focus();
            return;
        }

        if (!cityValue || !stateValue) {
            cepStatus.textContent = 'Por favor, aguarde a validação do CEP antes de enviar.';
            cepStatus.style.color = 'red';
            cepStatus.style.display = 'block';
            return;
        }

        const formData = new FormData(formCadastro);
        
        fetch(formCadastro.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            return response.json().then(data => ({ status: response.status, body: data }));
        })
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                window.location.href = body.redirect || '/';
            } else {
                const errorMessage = body.message || 'Erro ao processar o cadastro.';
                document.getElementById('registerError').textContent = errorMessage;
                document.getElementById('registerError').style.display = 'block';
                if (body.errors) {
                    console.error('Erros de validação:', body.errors);
                }
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('registerError').textContent = 'Erro ao processar cadastro. Tente novamente.';
            document.getElementById('registerError').style.display = 'block';
        });
    });
}