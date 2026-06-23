const searches = window.location.search.substring(1).split("&");
let arrSearches = new Array();
searches.forEach( (elem) => {
    const aux = elem.split("=");
    arrSearches[ aux[0] ] = aux[1];
})

if ( arrSearches.step == "senha" ) {
    /* === cadastro step senha === */
    const validarSenha = () => {
        const regex = /^(?=.*?[0-9])(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[^0-9A-Za-z]).{8,}$/u;
        const entrada = input_senha.value;

        // essas mensagens vou tirar depois, só para deixar a base funcionando por enquanto
        let msg;
        if ( !entrada ) msg = "Senha não deve ser vazia!";
        else if ( !regex.test(entrada) ) 
            msg = "Senha fraca, atente-se aos requisitos!";
        else return true;

        alert(msg);
        return false;
    }

    const validarConfirmacao = () => {
        const error_msg = input_confirmacao.nextElementSibling;
        error_msg.textContent = "";

        if (input_senha.value === input_confirmacao.value)
            return true;
        
        error_msg.textContent = "Confirmação de senha não confere!";
        return false;
    }

    const btn = document.querySelector("#btn-cadastro");
    const input_senha = document.querySelector("#senha");
    const input_confirmacao = document.querySelector("#confirmar-senha");

    /* adicionar permissão para ver a senha de ambos os campos --> olhinho <o> */

    btn.addEventListener("click", (e) => {
        const correto = validarSenha() && validarConfirmacao();

        if ( !correto ) e.preventDefault();
    });
}
else {
    /* === cadastro: nome e email === */
    const validarNome = () => {
        const regex = /^[_. -]+(?!.*[a-zA-Z0-9])$/;
        const entrada = input_nome.value;

        let msg = "";
        input_nome.nextElementSibling.textContent = "";

        if ( !entrada )
            msg = "Campo obrigatório, não deve estar vazio!" ;
        else if ( !(entrada.length >= 3 && entrada.length <= 16) )
            msg = "Nome deve ter entre 3 e 16 caracteres!" ;
        else if ( regex.test(entrada) )
            msg = "Nome não pode conter somente ponto, hífen, underline e espaços" ;
        else return true;

        input_nome.nextElementSibling.textContent = msg;
        return false;
    }

    const validarEmail = () => {
        const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
        const entrada = input_email.value;

        let msg = "";
        input_email.nextElementSibling.textContent = "";

        if ( !entrada )
            msg = "Campo obrigatório, não deve estar vazio!" ;
        else if ( !regex.test(entrada) )
            msg = "Endereço de email inválido!" ;
        else return true;   

        input_email.nextElementSibling.textContent = msg;
        return false;
    }

    const btn = document.querySelector("#btn-continuar");
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
}