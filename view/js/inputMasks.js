// Formata o CPF enquanto o usuário digita, adicionando pontos e hífen.
function formatCpf(value) {
    const digits = value.replace(/\D/g, '').slice(0, 11);

    if (digits.length <= 3) return digits;
    if (digits.length <= 6) return `${digits.slice(0, 3)}.${digits.slice(3)}`;
    if (digits.length <= 9) return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
    return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
}

// Confere os dois dígitos verificadores do CPF (mesma regra do InputValidator no PHP).
function isValidCpf(value) {
    const cpf = value.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

    for (let pos = 9; pos <= 10; pos++) {
        let soma = 0;
        for (let i = 0; i < pos; i++) {
            soma += Number(cpf[i]) * (pos + 1 - i);
        }
        const digito = (soma * 10) % 11 % 10;
        if (digito !== Number(cpf[pos])) return false;
    }
    return true;
}

// Formata o CEP no padrão 00000-000.
function formatCep(value) {
    const digits = value.replace(/\D/g, '').slice(0, 8);
    return digits.length > 5 ? `${digits.slice(0, 5)}-${digits.slice(5)}` : digits;
}

// Converte os dígitos digitados em um valor monetário no formato brasileiro.
function formatCurrency(value) {
    const digits = value.replace(/\D/g, '');
    if (!digits) return '';

    return (Number(digits) / 100).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Aguarda o carregamento da página antes de buscar e configurar os campos.
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        // Reaplica a máscara a cada alteração no campo de CPF.
        // Também valida os dígitos verificadores e bloqueia o envio se o CPF for inválido.
        const cpfStatus = document.getElementById('cpfStatus');
        cpfInput.addEventListener('input', function() {
            cpfInput.value = formatCpf(cpfInput.value);

            const completo = cpfInput.value.length === 14;
            const valido = completo && isValidCpf(cpfInput.value);
            cpfInput.setCustomValidity(completo && !valido ? 'CPF inválido: dígitos verificadores não conferem.' : '');

            if (cpfStatus) {
                cpfStatus.textContent = completo ? (valido ? 'CPF válido.' : 'CPF inválido.') : '';
                cpfStatus.className = 'form-hint' + (completo ? (valido ? ' form-hint-ok' : ' form-hint-erro') : '');
            }
        });
    }

    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function() {
            cepInput.value = formatCep(cepInput.value);
        });
    }

    const currencyInput = document.getElementById('valorProduto');
    if (currencyInput) {
        // Reaplica a máscara a cada alteração no campo de valor.
        currencyInput.addEventListener('input', function() {
            currencyInput.value = formatCurrency(currencyInput.value);
        });
    }
});