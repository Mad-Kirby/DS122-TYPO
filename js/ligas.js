const searches = window.location.search.substring(1).split("&");
let arrSearches = new Array();
searches.forEach( (elem) => {
    const aux = elem.split("=");
    arrSearches[ aux[0] ] = aux[1];
})

if ( ["criar", "entrar"].includes(arrSearches.step) ) {
    let id_liga, pass_liga;
    if (arrSearches.step == "criar") {
        id_liga = "nome-liga";
        pass_liga = "palavra-chave";
    } else {
        id_liga = "nome-liga-entrada";
        pass_liga = "senha-liga";
    }
    const btn = document.querySelector(".btn");
    const liga_nome = document.querySelector(`#${id_liga}`);
    const liga_pass = document.querySelector(`#${pass_liga}`);

    const validarNomeLiga = () => {
        const regex = /^[´`^~¨ _!@#$%&?.-]+(?!.*[a-zA-Z0-9À-ÿ])$/;
        const entrada = liga_nome.value;

        let msg = "";
        liga_nome.nextElementSibling.textContent = "";

        if ( !entrada )
            msg = "Campo obrigatório, não deve estar vazio!" ;
        else if ( !(entrada.length >= 5 && entrada.length <= 32) )
            msg = "Nome da liga deve ter entre 5 e 32 caracteres!" ;
        else if ( regex.test(entrada) )
            msg = "Nome da liga não pode conter somente caracteres especiais!" ;
        else return true;

        liga_nome.nextElementSibling.textContent = msg;
        return false;
    }

    const validarPasseLiga = () => {
        const regex = /^[_!@#$%&?. -]+(?!.*[a-zA-Z0-9])$/;
        const entrada = liga_pass.value;

        let msg = "";
        liga_pass.nextElementSibling.textContent = "";

        if ( !entrada )
            msg = "Campo obrigatório, não deve estar vazio!" ;
        else if ( !(entrada.length >= 5 && entrada.length <= 32) )
            msg = "Palavra-passe deve ter entre 5 e 32 caracteres!" ;
        else if ( regex.test(entrada) )
            msg = "Palavra-passe não pode conter somente caracteres especiais!" ;
        else return true;

        liga_pass.nextElementSibling.textContent = msg;
        return false;
    }

    liga_nome.addEventListener("input", (e) => {
        const regex = /^[\wÀ-ÿ´`^~¨ !@#$%&?.-]*$/g;
        const entrada = e.target.value;
        if ( !regex.test(entrada) || entrada.length > 32 ) {
            e.target.value = entrada.slice(0, entrada.length - 1);
        }
    });
    liga_pass.addEventListener("input", (e) => {
        const regex = /^[\w !@#$%&?*.-]*$/g;
        const entrada = e.target.value;
        if ( !regex.test(entrada) || entrada.length > 32 ) {
            e.target.value = entrada.slice(0, entrada.length - 1);
        }
    });

    btn.addEventListener("click", (e) => {
        let correto = validarNomeLiga();
        correto = validarPasseLiga() && correto;

        if ( !correto ) e.preventDefault();
    });
}
else if ( !(arrSearches.step == "detalhes") ) {
    /* == página step padrão: "minhas-ligas" == */
}
else {
    /* == página step "detalhes" == */
}