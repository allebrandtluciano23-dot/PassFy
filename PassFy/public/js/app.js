// ----------------------------------Login-----------------------------------

const modal = document.getElementById('modal-login');
const btnLogin = document.getElementById('btn-login');
const closeBtn = document.querySelector('.close');
const visitorCriarEvento = document.getElementById('visitor-criar-evento');

// Abre modal de login ao clicar no botão "Entrar"
if (btnLogin && modal) {
    btnLogin.addEventListener('click', function(e) {
        e.preventDefault();  // Evita redirecionar
        modal.classList.add('open');
    });
}

if (visitorCriarEvento && modal) {
    visitorCriarEvento.addEventListener('click', function(e) {
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
        fields: `<label>CPF</label><input type="text" name="cpf" placeholder="CPF">`
    },
    organizadora: {
        route: '/register/organizadora',
        fields: `<label>CNPJ</label><input type="text" name="cnpj" placeholder="CNPJ">`
    }
};

let tipoEscolhidoCadastro = 'cliente';
const selecTipoCadastro = document.getElementById('tipo-cadastro');
const formCadastro = document.getElementById('form-registrar-cliente');
const campoDinamicoCadastro = document.getElementById('input-dinamico-cadastro');
const cepInput = document.querySelector('input[name="cep"]');
const cepStatus = document.getElementById('cep-status');
const cepBtn = document.getElementById('btn-buscar-cep');

if (campoDinamicoCadastro && formCadastro) {
    // Buscar CEP
    if (cepInput && cepBtn) {
        cepBtn.addEventListener('click', function() {
            const cep = cepInput.value.trim();
            if (!/^\d{8}$/.test(cep)) {
                cepStatus.textContent = 'CEP deve conter 8 dígitos';
                cepStatus.style.color = 'red';
                return;
            }
            cepStatus.textContent = 'Buscando...';
            cepStatus.style.color = '#FFA500';
            fetch(`/api/cidade/buscar-por-cep?cep=${cep}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ufSelect = document.getElementById('uf_select');
                        const cidadeSelect = document.getElementById('cidade_select');
                        ufSelect.value = data.ufCidade;
                        ufSelect.dispatchEvent(new Event('change'));
                        // Aguarda carregar as cidades e seleciona
                        const interval = setInterval(() => {
                            if (cidadeSelect.options.length > 1) {
                                cidadeSelect.value = data.idCidade;
                                clearInterval(interval);
                                cepStatus.textContent = 'Cidade e UF preenchidas!';
                                cepStatus.style.color = 'green';
                            }
                        }, 200);
                    } else {
                        alert(data.message);
                        document.getElementById('uf_select').value = '';
                        document.getElementById('cidade_select').innerHTML = '<option value="">Selecione uma UF primeiro</option>';
                        document.getElementById('cidade_select').disabled = true;
                        cepStatus.textContent = 'CEP não encontrado';
                        cepStatus.style.color = 'red';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    cepStatus.textContent = 'Erro ao buscar CEP';
                    cepStatus.style.color = 'red';
                });
        });
    }

    // Alternar entre cliente/organizadora
    document.querySelectorAll('.register-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.register-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            tipoEscolhidoCadastro = this.dataset.type;
            campoDinamicoCadastro.innerHTML = registerConfig[tipoEscolhidoCadastro].fields;
            formCadastro.action = registerConfig[tipoEscolhidoCadastro].route;
        });
    });

    // Inicializar
    campoDinamicoCadastro.innerHTML = registerConfig.cliente.fields;
    formCadastro.action = registerConfig.cliente.route;

    // Enviar formulário
    formCadastro.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validar CEP
        const cepValue = cepInput ? cepInput.value.trim() : '';
        if (!/^\d{8}$/.test(cepValue)) {
            cepStatus.textContent = 'Digite um CEP válido (8 dígitos).';
            cepStatus.style.color = 'red';
            return;
        }

        // Validar UF e cidade selecionadas
        const ufSelecionada = ufSelect ? ufSelect.value : '';
        const cidadeSelecionada = cidadeSelect ? cidadeSelect.value : '';
        if (!ufSelecionada || !cidadeSelecionada) {
            cepStatus.textContent = 'Selecione a UF e a cidade (ou aguarde o carregamento do CEP).';
            cepStatus.style.color = 'red';
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/';
            } else {
                const errorDiv = document.getElementById('registerError');
                errorDiv.textContent = data.message || 'Erro ao cadastrar.';
                errorDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('registerError').textContent = 'Erro interno. Tente novamente.';
            document.getElementById('registerError').style.display = 'block';
        });
    });
}

// Atualizar cidades ao mudar a UF
document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.querySelector('select[name="state"]');
    const cidadeSelect = document.querySelector('select[name="city"]');
    
    if (ufSelect && cidadeSelect) {
        ufSelect.addEventListener('change', function() {
            const uf = this.value;
            if (!uf) {
                cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
                cidadeSelect.disabled = true;
                return;
            }
            cidadeSelect.disabled = true;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            fetch(`/cidades/${uf}`)
                .then(response => response.json())
                .then(cidades => {
                    cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
                    if (cidades.length === 0) {
                        cidadeSelect.innerHTML += '<option disabled>Nenhuma cidade encontrada</option>';
                    } else {
                        cidades.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.id;
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                    }
                    cidadeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                    cidadeSelect.disabled = true;
                });
        });
    }
});

const fileInput = document.getElementById('image');
const fileName = document.getElementById('fileName');
const filePreview = document.getElementById('filePreview');
const previewImage = document.getElementById('previewImage');

fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        // Mostrar nome do arquivo
        fileName.textContent = this.files[0].name;
        
        // Mostrar prévia
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            filePreview.style.display = 'block';
        }
        reader.readAsDataURL(this.files[0]);
    } else {
        fileName.textContent = '';
        filePreview.style.display = 'none';
    }
});

let loteCount = 0;

// Template de um lote
function getLoteTemplate(index) {
    return `
        <div class="lote-card" data-lote-id="${index}">
            <div class="lote-header">
                <h4>Lote ${index}</h4>
                <button type="button" class="btn-remove-lote" onclick="removerLote(${index})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="lote-fields">
                <div class="lote-field">
                    <label>Nome do Lote *</label>
                    <input type="text" 
                           name="lotes[${index}][nomeLote]" 
                           placeholder="Ex: Pista, VIP, Camarote" 
                           required>
                </div>
                <div class="lote-field">
                    <label>Quantidade Total *</label>
                    <input type="number" 
                           name="lotes[${index}][quantidadeTotal]" 
                           placeholder="Número de ingressos" 
                           min="1" 
                           required>
                </div>
                <div class="lote-field">
                    <label>Valor do Ingresso (R$) *</label>
                    <input type="number" 
                           name="lotes[${index}][valorIngresso]" 
                           placeholder="0,00" 
                           step="0.50" 
                           min="0" 
                           required>
                </div>
            </div>
        </div>
    `;
}

// Adicionar novo lote
function adicionarLote() {
    loteCount++;
    const container = document.getElementById('lotes-container');
    const novoLote = document.createElement('div');
    novoLote.innerHTML = getLoteTemplate(loteCount);
    container.appendChild(novoLote.firstElementChild);
    
    // Scroll suave para o novo lote
    const novoElemento = document.querySelector(`[data-lote-id="${loteCount}"]`);
    if (novoElemento) {
        novoElemento.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Remover lote
function removerLote(id) {
    const lote = document.querySelector(`[data-lote-id="${id}"]`);
    if (lote) {
        lote.remove();
    }
}

// Event listener para o botão de adicionar
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-lote-btn');
    if (addBtn) {
        addBtn.addEventListener('click', adicionarLote);
    }
});