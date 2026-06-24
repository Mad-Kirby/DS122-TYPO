const btn = document.querySelector(".btn-submit");
const input_nome = document.querySelector("#nome");
const input_senha = document.querySelector("#senha");

const validarNome = () => {
    const regex = /^[_. -]+(?!.*[a-zA-Z0-9])$/;
    const entrada = input_nome.value;

    let msg = "";
    input_nome.nextElementSibling.textContent = "";

    if ( !entrada )
        msg = "Campo obrigatório, não deve estar vazio!" ;
    else if ( !(entrada.length >= 3 && entrada.length <= 16) 
        || regex.test(entrada) )
        msg = "Nome inválido!" ;
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
    const regex = /^[a-zA-Z0-9_. -]*$/g;
    const entrada = e.target.value;
    if ( !regex.test(entrada) || entrada.length > 16 ) {
        e.target.value = entrada.slice(0, entrada.length - 1);
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
    if ( !validarNome() || !validarSenha() ) 
        e.preventDefault();
});