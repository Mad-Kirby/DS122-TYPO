const btn = document.querySelector(".btn-submit");
const input_nome = document.querySelector("#nome");
const input_senha = document.querySelector("#senha");

const validarIdentificador = () => {
    const entrada = input_nome.value.trim();

    let msg = "";
    input_nome.nextElementSibling.textContent = "";

    if (!entrada)
        msg = "Campo obrigatório, não deve estar vazio!";
    else if (entrada.length > 255)
        msg = "Nome ou e-mail muito longo!";
    else return true;

    input_nome.nextElementSibling.textContent = msg;
    return false;
}

const validarSenha = () => {
    let msg = "";

    input_senha.nextElementSibling.textContent = "";
    if ( !input_senha.value )
        msg = "Campo obrigatório, não deve estar vazio!";
    else return true;

    input_senha.nextElementSibling.textContent = msg;
    return false;
}

input_nome.addEventListener("input", (e) => {
    const entrada = e.target.value;

    if (entrada.length > 255) {
        e.target.value = entrada.slice(0, 255);
    }
});

input_senha.addEventListener("input", (e) => {
    const regex = /^[\dA-Za-z@$!%*?&_-]*$/g;
    const entrada = e.target.value;
    if ( !regex.test(entrada) ) {
        e.target.value = entrada.slice(0, entrada.length - 1);
    }
});

btn.addEventListener("click", (e) => {
    if (!validarIdentificador() || !validarSenha()) 
        e.preventDefault();
});