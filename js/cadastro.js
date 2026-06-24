const searches = window.location.search.substring(1).split("&");
let arrSearches = new Array();
searches.forEach( (elem) => {
    const aux = elem.split("=");
    arrSearches[ aux[0] ] = aux[1];
})

if ( arrSearches.step == "senha" ) {
    /* === cadastro step senha === */
    const validarSenha = () => {
        /* expressão regular para verificar se a senha possui ao menos um digito (0-9), 
        uma letra maiúscula e minúscula, um caractere especial diferente de espaço e
        possua 8 ou mais caracteres, e que todos os caracteres façam parte
        desta classe -> [\dA-Za-z@$!%*?&_-] */
        const regex = /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\dA-Za-z ])[\dA-Za-z@$!%*?&_-]{8,}$/;
        const entrada = input_senha.value;

        if ( !entrada || !regex.test(entrada) ) 
            return false;
        
        return true;
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
    const requisitos_senha = document.querySelectorAll(".req-senha li");

    // impedindo a entrada de caracteres indesejados
    input_senha.addEventListener("input", (e) => {
        const regex = /^[\dA-Za-z@$!%*?&_-]*$/g;
        const entrada = e.target.value;
        if ( !regex.test(entrada) ) {
            e.target.value = entrada.slice(0, entrada.length - 1);
        }
    });
    input_confirmacao.addEventListener("input", (e) => {
        const regex = /^[\dA-Za-z@$!%*?&_-]*$/g;
        const entrada = e.target.value;
        if ( !regex.test(entrada) ) {
            e.target.value = entrada.slice(0, entrada.length - 1);
        }
    });

    // indica na tela quais requisitos de senha foram atingidos ou não
    input_senha.addEventListener("input", (e) => {
        const entrada = e.target.value;
        const regex = [
            /(?=^.{8,}$)/g, // minimo de 8 caracteres
            /[A-Z]/g,
            /[a-z]/g,
            /\d/g, // digitos, equivale a [0-9]
            // caracteres especiais, desde que seja um dos identificados no segundo colchete
            /(?=.*[^\dA-Za-z ])[@$!%*?&_-]/g 
        ];

        // testa para cada requisito e altera a classe css que possui
        regex.forEach( (elem, index) => {
            if( !elem.test(entrada) ) {
                requisitos_senha[index].classList.remove("correct");
                requisitos_senha[index].classList.add("error");
            } else { 
                requisitos_senha[index].classList.remove("error");
                requisitos_senha[index].classList.add("correct");   
            }
        });
    });

    btn.addEventListener("click", (e) => {
        const correto = validarSenha() && validarConfirmacao();

        if ( !correto ) e.preventDefault();
    });
}
else {
    /* === cadastro: nome e email === */
    const validarNome = () => {
        // varifica se o nome eh composto somente por caracteres não alfanumericos
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
        /* expressão regular para validar a estrutura de um email, 
        sequencia de 1 ou mais caracteres alfanumericos, seguido de um @ e
        uma ou mais sequencias de caracteres seguida de um . 
        finalizada por uma sequencia de 2 a 4 caracteres alfanumericos*/
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