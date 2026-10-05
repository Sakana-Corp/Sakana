// Busca o endereço pelo CEP (ViaCEP, com BrasilAPI como alternativa) e preenche o formulário.
(function () {
    const cepInput = document.getElementById('cep');
    if (!cepInput) return;

    const status = document.getElementById('cepStatus');
    const campos = {
        logradouro: document.getElementById('logradouro'),
        bairro: document.getElementById('bairro'),
        cidade: document.getElementById('cidade'),
        uf: document.getElementById('uf'),
        numero: document.getElementById('numero')
    };
    let ultimoCep = '';

    function mostrarStatus(texto, tipo) {
        if (!status) return;
        status.textContent = texto;
        status.className = 'form-hint' + (tipo ? ' form-hint-' + tipo : '');
    }

    async function buscarViaCep(cep) {
        const resp = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        if (!resp.ok) throw new Error('viacep_indisponivel');
        const dados = await resp.json();
        if (dados.erro) return null;
        return {
            logradouro: dados.logradouro,
            bairro: dados.bairro,
            cidade: dados.localidade,
            uf: dados.uf
        };
    }

    async function buscarBrasilApi(cep) {
        const resp = await fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`);
        if (resp.status === 404) return null;
        if (!resp.ok) throw new Error('brasilapi_indisponivel');
        const dados = await resp.json();
        return {
            logradouro: dados.street,
            bairro: dados.neighborhood,
            cidade: dados.city,
            uf: dados.state
        };
    }

    async function buscarEndereco(cep) {
        try {
            return await buscarViaCep(cep);
        } catch (e) {
            // ViaCEP fora do ar: tenta a BrasilAPI.
            return await buscarBrasilApi(cep);
        }
    }

    async function consultarCep() {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) {
            ultimoCep = '';
            cepInput.setCustomValidity('');
            mostrarStatus('', '');
            return;
        }
        if (cep === ultimoCep) return;
        ultimoCep = cep;

        mostrarStatus('Buscando endereço...', '');

        try {
            const endereco = await buscarEndereco(cep);

            // Ignora respostas atrasadas se o usuário já trocou o CEP.
            if (cep !== cepInput.value.replace(/\D/g, '')) return;

            if (!endereco) {
                cepInput.setCustomValidity('CEP não encontrado.');
                mostrarStatus('CEP não encontrado.', 'erro');
                return;
            }

            cepInput.setCustomValidity('');
            campos.logradouro.value = endereco.logradouro || '';
            campos.bairro.value = endereco.bairro || '';
            campos.cidade.value = endereco.cidade || '';
            campos.uf.value = endereco.uf || '';
            mostrarStatus('Endereço preenchido automaticamente.', 'ok');

            // CEPs gerais de cidade não trazem logradouro: o usuário completa manualmente.
            (endereco.logradouro ? campos.numero : campos.logradouro).focus();
        } catch (e) {
            // Sem conexão com as APIs: libera o preenchimento manual.
            cepInput.setCustomValidity('');
            mostrarStatus('Não foi possível consultar o CEP. Preencha o endereço manualmente.', 'erro');
        }
    }

    cepInput.addEventListener('input', consultarCep);
    cepInput.addEventListener('blur', consultarCep);
})();
