const validarNome = () => {
    const regex = /^[_. -]+(?!.*[a-zA-Z0-9])$/;
    const entrada = input_nome.value;
    const erro_nome = document.querySelector("#erro-nome");

    let msg = "";
    erro_nome.textContent = "";

    if ( !entrada )
        msg = "Campo obrigatório, não deve estar vazio!" ;
    else if ( !(entrada.length >= 3 && entrada.length <= 16) 
        || regex.test(entrada) )
        msg = "Nome inválido!" ;
    else return true;

    erro_nome.textContent = msg;
    return false;
}

const validarEmail = () => {
    const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
    const entrada = input_email.value;
    const erro_email = document.querySelector("#erro-email")

    let msg = "";
    erro_email.textContent = "";

    if ( !entrada )
        msg = "Campo obrigatório, não deve estar vazio!" ;
    else if ( !regex.test(entrada) )
        msg = "Endereço de email inválido!" ;
    else return true;   

    erro_email.textContent = msg;
    return false;
}

const btn = document.querySelector(".btn-submit");
const input_nome = document.querySelector("#nome");
const input_email = document.querySelector("#email");

input_nome.addEventListener("input", (e) => {
    const regex = /^[a-zA-Z0-9_. -]*$/g;
    const entrada = e.target.value;
    if ( !regex.test(entrada) || entrada.length > 16 ) {
        e.target.value = entrada.slice(0, entrada.length - 1);
    }
});

btn.addEventListener("click", (e) => {
    let correto = validarNome();
    correto = validarEmail() && correto;

    if ( !correto ) e.preventDefault();
});