// ---------------------------------- Configurações ---------------------------------
const loginConfig = {
    cliente: {
        route: '/login/cliente',
        fields: `<label>Email</label><input type="text" name="email" placeholder="exemplo@email.com">`
    },
    organizadora: {
        route: '/login/organizadora',
        fields: `<label>CNPJ</label><input type="text" name="cnpj" placeholder="Digite o CNPJ">`
    },
    usuario: {
        route: '/login/usuario',
        fields: `<label>Username</label><input type="text" name="username" placeholder="Username">`
    }
};

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

// ---------------------------------- Utilitários ---------------------------------
function mostrarErro(elementId, mensagem, cor = 'red') {
    const el = document.getElementById(elementId);
    if (el) {
        el.textContent = mensagem;
        el.style.display = 'block';
        el.style.color = cor;
    }
}

function esconderErro(elementId) {
    const el = document.getElementById(elementId);
    if (el) el.style.display = 'none';
}

// ---------------------------------- Modal de Login ---------------------------------
const modal = document.getElementById('modal-login');
const btnLogin = document.getElementById('btn-login');
const visitorCriarEvento = document.getElementById('visitor-criar-evento');
const closeBtn = document.querySelector('.close');

function abrirModal(e) {
    if (e) e.preventDefault();
    if (modal) modal.classList.add('open');
}

function fecharModal() {
    if (modal) modal.classList.remove('open');
}

if (btnLogin && modal) btnLogin.addEventListener('click', abrirModal);
if (visitorCriarEvento && modal) visitorCriarEvento.addEventListener('click', abrirModal);
if (closeBtn && modal) closeBtn.addEventListener('click', fecharModal);
window.addEventListener('click', (e) => { if (modal && e.target === modal) fecharModal(); });

// ---------------------------------- Toggle Senha ---------------------------------
const togglePassword = document.getElementById('toggle-password');
if (togglePassword) {
    togglePassword.addEventListener('click', function() {
        const passwordInput = document.getElementById('login-password');
        if (passwordInput) {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        }
    });
}

// ---------------------------------- Login Dinâmico ---------------------------------
const loginForm = document.getElementById('form-login');
const campoDinamicoLogin = document.getElementById('input-dinamico-login');
const registerLink = document.getElementById('register-link');

function initLogin() {
    if (!campoDinamicoLogin || !loginForm) return;

    document.querySelectorAll('.login-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.login-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tipo = this.dataset.type;
            campoDinamicoLogin.innerHTML = loginConfig[tipo].fields;
            loginForm.action = loginConfig[tipo].route;
            if (registerLink) {
                registerLink.href = tipo === 'organizadora' ? '/register/organizadora' : '/register/cliente';
            }
        });
    });

    campoDinamicoLogin.innerHTML = loginConfig.cliente.fields;
    loginForm.action = loginConfig.cliente.route;

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
                mostrarErro('loginError', data.message);
            }
        })
        .catch(() => mostrarErro('loginError', 'Erro ao fazer login. Tente novamente.'));
    });
}

// ---------------------------------- Cadastro Dinâmico ---------------------------------
const formCadastro = document.getElementById('form-registrar-cliente');
const campoDinamicoCadastro = document.getElementById('input-dinamico-cadastro');
const cepInput = document.querySelector('input[name="cep"]');
const cepBtn = document.getElementById('btn-buscar-cep');

function initCadastro() {
    if (!campoDinamicoCadastro || !formCadastro) return;

    document.querySelectorAll('.register-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.register-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tipo = this.dataset.type;
            campoDinamicoCadastro.innerHTML = registerConfig[tipo].fields;
            formCadastro.action = registerConfig[tipo].route;
        });
    });

    campoDinamicoCadastro.innerHTML = registerConfig.cliente.fields;
    formCadastro.action = registerConfig.cliente.route;

    formCadastro.addEventListener('submit', function(e) {
        e.preventDefault();

        const cepValue = cepInput ? cepInput.value.trim() : '';
        if (!/^\d{8}$/.test(cepValue)) {
            mostrarErro('cep-status', 'Digite um CEP válido (8 dígitos).', 'red');
            return;
        }

        const ufSelect = document.querySelector('select[name="state"]');
        const cidadeSelect = document.querySelector('select[name="city"]');
        if (!ufSelect?.value || !cidadeSelect?.value) {
            mostrarErro('cep-status', 'Selecione a UF e a cidade (ou aguarde o carregamento do CEP).', 'red');
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
                mostrarErro('registerError', data.message || 'Erro ao cadastrar.');
            }
        })
        .catch(() => mostrarErro('registerError', 'Erro interno. Tente novamente.'));
    });

    if (cepInput && cepBtn) {
        cepBtn.addEventListener('click', buscarCep);
    }
}

function buscarCep() {
    const cep = cepInput.value.trim();
    if (!/^\d{8}$/.test(cep)) {
        mostrarErro('cep-status', 'CEP deve conter 8 dígitos', 'red');
        return;
    }

    mostrarErro('cep-status', 'Buscando...', '#FFA500');
    fetch(`/api/cidade/buscar-por-cep?cep=${cep}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ufSelect = document.getElementById('uf_select');
                const cidadeSelect = document.getElementById('cidade_select');
                ufSelect.value = data.ufCidade;
                ufSelect.dispatchEvent(new Event('change'));
                const interval = setInterval(() => {
                    if (cidadeSelect.options.length > 1) {
                        cidadeSelect.value = data.idCidade;
                        clearInterval(interval);
                        mostrarErro('cep-status', 'Cidade e UF preenchidas!', 'green');
                    }
                }, 200);
            } else {
                alert(data.message);
                document.getElementById('uf_select').value = '';
                const cidadeSelect = document.getElementById('cidade_select');
                cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
                cidadeSelect.disabled = true;
                mostrarErro('cep-status', 'CEP não encontrado', 'red');
            }
        })
        .catch(() => mostrarErro('cep-status', 'Erro ao buscar CEP', 'red'));
}

// ---------------------------------- Carregar Cidades por UF (sem disparar change desnecessário) ---------------------------------
async function carregarCidadesPorUF(uf, cidadeIdSelecionada = null) {
    const cidadeSelect = document.getElementById('cidade_select');
    if (!cidadeSelect) return;

    if (!uf) {
        cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
        cidadeSelect.disabled = true;
        return;
    }

    cidadeSelect.disabled = true;
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';

    try {
        const response = await fetch(`/cidades/${uf}`);
        const cidades = await response.json();
        
        cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
        if (cidades.length === 0) {
            cidadeSelect.innerHTML += '<option disabled>Nenhuma cidade encontrada</option>';
        } else {
            cidades.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.id;
                option.textContent = cidade.nome;
                if (cidadeIdSelecionada && cidade.id == cidadeIdSelecionada) {
                    option.selected = true;
                }
                cidadeSelect.appendChild(option);
            });
        }
        cidadeSelect.disabled = false;
    } catch (error) {
        console.error('Erro ao carregar cidades:', error);
        cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
        cidadeSelect.disabled = true;
    }
}

// ---------------------------------- Inicializar UF e Cidade (página de edição) ---------------------------------
function initUfCidade() {
    const ufSelect = document.querySelector('select[name="state"]');
    const cidadeSelect = document.getElementById('cidade_select');
    
    if (!ufSelect || !cidadeSelect) return;

    // Evento de mudança manual da UF
    ufSelect.addEventListener('change', function() {
        carregarCidadesPorUF(this.value);
    });

    // Verificar se é página de edição (cidade já selecionada no HTML)
    const cidadeSelecionada = cidadeSelect.getAttribute('data-selected-value') || 
                               cidadeSelect.querySelector('option[selected]')?.value;
    
    const ufAtual = ufSelect.value;
    
    if (ufAtual && cidadeSelecionada) {
        // Edição: carregar cidades sem disparar evento e já selecionar a cidade
        carregarCidadesPorUF(ufAtual, cidadeSelecionada);
    } else if (ufAtual) {
        // Apenas carregar cidades para exibição (sem seleção)
        carregarCidadesPorUF(ufAtual);
    }
}

// ---------------------------------- Upload de Imagem --------------------------
const fileInput = document.getElementById('image');
const uploadLabel = document.getElementById('uploadLabel');
const fileNameSpan = document.getElementById('fileName');

function initUploadImagem() {
    if (!fileInput || !uploadLabel) return;

    // Verificar se já existe imagem (edição)
    const imagemExistente = uploadLabel.getAttribute('data-imagem-preview');
    if (imagemExistente && imagemExistente !== '') {
        uploadLabel.style.backgroundImage = `url('${imagemExistente}')`;
        uploadLabel.classList.add('has-preview');
        // Esconde ícone e texto quando tem preview
        const icon = uploadLabel.querySelector('i');
        const text = uploadLabel.querySelector('span:not(.file-name)');
        if (icon) icon.style.display = 'none';
        if (text) text.style.display = 'none';
    }

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Mostrar nome do arquivo
            if (fileNameSpan) fileNameSpan.textContent = file.name;
            
            // Ler e mostrar preview no background do label
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadLabel.style.backgroundImage = `url('${e.target.result}')`;
                uploadLabel.classList.add('has-preview');
                
                // Esconder ícone e texto
                const icon = uploadLabel.querySelector('i');
                const text = uploadLabel.querySelector('span:not(.file-name)');
                if (icon) icon.style.display = 'none';
                if (text) text.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
}

// ---------------------------------- Lotes ---------------------------------
let loteCount = 0;

function getLoteTemplate(index, loteData = null) {
    return `
        <div class="lote-card" data-lote-id="${loteData?.idLote || index}">
            <div class="lote-header">
                <h4>${loteData?.nomeLote || `Lote ${index}`}</h4>
                <button type="button" class="btn-remove-lote" onclick="removerLote(this, ${loteData?.idLote || index}, ${!!loteData})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="lote-fields">
                <div class="lote-field">
                    <label>Nome do Lote *</label>
                    <input type="text" name="lotes[${loteData?.idLote || index}][nomeLote]" value="${loteData?.nomeLote || ''}" placeholder="Ex: Pista, VIP" required>
                </div>
                <div class="lote-field">
                    <label>Quantidade Total *</label>
                    <input type="number" name="lotes[${loteData?.idLote || index}][quantidadeTotal]" value="${loteData?.quantidadeTotal || ''}" placeholder="Número de ingressos" min="1" required>
                </div>
                <div class="lote-field">
                    <label>Valor do Ingresso (R$) *</label>
                    <input type="number" name="lotes[${loteData?.idLote || index}][valorIngresso]" value="${loteData?.valorIngresso || ''}" placeholder="0,00" step="0.50" min="0" required>
                </div>
            </div>
        </div>
    `;
}

function adicionarLote() {
    loteCount++;
    const container = document.getElementById('lotes-container');
    if (container) {
        container.insertAdjacentHTML('beforeend', getLoteTemplate(loteCount));
    }
}

window.removerLote = function(button, id, isEdit = false) {
    if (isEdit) {
        if (confirm('Tem certeza que deseja remover este lote?')) {
            fetch(`/lotes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.closest('.lote-card').remove();
                } else {
                    alert('Erro ao remover lote.');
                }
            });
        }
    } else {
        button.closest('.lote-card').remove();
    }
};

function initLotes() {
    const addBtn = document.getElementById('add-lote-btn');
    if (addBtn) {
        addBtn.addEventListener('click', adicionarLote);
    }

    const container = document.getElementById('lotes-container');
    if (!container) return;

    const isEditPage = window.location.pathname.includes('/edit');
    const lotesExistentes = container.querySelectorAll('.lote-card').length;

    if (!isEditPage && lotesExistentes === 0) {
        adicionarLote();
    }
}

// ---------------------------------- Inicialização Geral ---------------------------------
document.addEventListener('DOMContentLoaded', function() {
    initLogin();
    initCadastro();
    initUfCidade();
    initUploadImagem();
    initLotes();
});