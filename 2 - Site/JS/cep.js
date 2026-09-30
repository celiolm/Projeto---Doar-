// Formatação automática do CEP
const cepInput = document.getElementById('cep');
        
cepInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.slice(0, 5) + '-' + value.slice(5, 8);
    }
    e.target.value = value;
});

// Função para buscar CEP na API ViaCEP
async function buscarCep() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    const btnBuscar = document.getElementById('btnBuscarCep');
    
    if (cep.length !== 8) {
        alert('Por favor, digite um CEP válido com 8 números');
        return;
    }

    // Mostrar loading no botão
    const originalText = btnBuscar.innerHTML;
    btnBuscar.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Buscando...';
    btnBuscar.disabled = true;

    try {
        // Chamada para a API ViaCEP
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();

        if (data.erro) {
            alert('CEP não encontrado. Verifique o número digitado.');
            return;
        }

        // Preencher os campos com os dados retornados
        document.getElementById('rua').value = data.logradouro || '';
        document.getElementById('bairro').value = data.bairro || '';
        document.getElementById('cidade').value = data.localidade || '';
        document.getElementById('uf').value = data.uf || '';
        
        // Se tiver complemento, preencher
        if (data.complemento) {
            document.getElementById('complemento').value = data.complemento;
        }

        // Foco automático no campo número após buscar
        document.getElementById('numero').focus();
        
        // Pequeno feedback visual
        Object.assign(document.getElementById('rua').style, { 
            borderColor: '#4caf50', 
            transition: '0.3s' 
        });
        setTimeout(() => {
            document.getElementById('rua').style.borderColor = '#e0e0e0';
        }, 2000);

    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        alert('Erro ao buscar CEP. Verifique sua conexão com a internet.');
    } finally {
        // Restaurar botão
        btnBuscar.innerHTML = originalText;
        btnBuscar.disabled = false;
    }
}

// Buscar automaticamente ao pressionar Enter no campo CEP
cepInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        buscarCep();
    }
});

// Evento do botão de buscar
document.getElementById('btnBuscarCep').addEventListener('click', buscarCep);

// Validação de senha no submit
document.getElementById('formCadastro').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar-senha').value;
    
    if (senha !== confirmarSenha) {
        alert('As senhas não coincidem!');
        return;
    }
    
    if (senha.length < 6) {
        alert('A senha deve ter no mínimo 6 caracteres');
        return;
    }
    
    // Se chegou aqui, todos os campos estão OK
    alert('Cadastro realizado com sucesso!\nBem-vindo ao Doar+');
    // Aqui você pode redirecionar para a página de login
    // window.location.href = 'login.html';
});

// Máscara para CPF
const cpfInput = document.getElementById('cpf');
cpfInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    
    if (value.length > 9) {
        value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9) + '-' + value.slice(9, 11);
    } else if (value.length > 6) {
        value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9);
    } else if (value.length > 3) {
        value = value.slice(0, 3) + '.' + value.slice(3, 6);
    }
    e.target.value = value;
});

// Máscara para Telefone
const telefoneInput = document.getElementById('telefone');
telefoneInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    
    if (value.length > 10) {
        value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7, 11);
    } else if (value.length > 6) {
        value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 6) + '-' + value.slice(6, 10);
    } else if (value.length > 2) {
        value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 6);
    } else if (value.length > 0) {
        value = '(' + value;
    }
    e.target.value = value;
});