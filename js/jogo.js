import { selecionaPalavras } from "./dicionario.js";

const campoMem = document.querySelector(".memorize__field");
const screen_time = document.querySelector(".screen__time span");
const screen_input = document.querySelector(".screen__input");
const screen_errors = document.querySelector(".screen__errors span");

// para manter controle das estatisticas das tentativas/jogo 
// sem utilizar variáveis globais
function Estatistica() {
    this.resp = "";
    this.multiplicador = 1;
    this.tempResp = 0;
    this.acerto = 0;
    this.ptsTotal = 0;
    this.changeResp =  (novaResp) => { return this.resp = novaResp; }
    this.incrementaTempR =     () => { return this.tempResp++; }
    this.zeraTempR =           () => { return this.tempResp = 0; }
    this.incrementaMult =      () => { return this.multiplicador++; }
    this.resetaMultiplicador = () => { return this.multiplicador = 1; }
    this.changeAcerto = (percent) => { return this.acerto = percent; }
    this.calcTotalPts = () => {
        return this.ptsTotal += Math.floor(
            10 * this.acerto * (1 + 0.95 ** this.tempResp) * this.multiplicador
        );
    }
}

const estatisticas = new Estatistica();

/* -- comportamento do campo das palavras -- */
campoMem.addEventListener("copy", (e) => { e.preventDefault() });

/* -- comportamento do campo de resposta -- */
screen_input.addEventListener("paste", (e) => { e.preventDefault(); });
screen_input.addEventListener("drop",  (e) => { e.preventDefault(); });
screen_input.addEventListener("input", (e) => {
    const regex = /^[a-zA-ZÀ-ÿ´`^~¨ ]*$/g;
    const entrada = e.target.value;
    if ( !regex.test(entrada) || entrada.length > 40 ) {
        e.target.value = entrada.slice(0, entrada.length - 1);
    }
});
screen_input.addEventListener("keydown", handler);
function handler(e) {
    if (e.code === "Enter") {
        setTimerZero();
    }
}

let erros = 0;
const maxErros = 3;
let memorize = true;
let timerInterval;

const startCoundown = (tempoLimite) => {
    clearInterval(timerInterval);
    const targetTime = new Date().getTime() + (1000 * tempoLimite);

    function updateCountdown() {
        const agora = new Date().getTime();
        const distancia = targetTime - agora;

        if (distancia <= 0) { setTimerZero(); return; }

        estatisticas.incrementaTempR();
        const segundos = Math.floor((distancia % (1000 * 60)) / 1000);
        screen_time.textContent = segundos;
    }
    updateCountdown();
    timerInterval = setInterval(updateCountdown, 1000);
}

const verifyAnswer = (input) => {
    const entr = input.value.split(" ");
    const resp = estatisticas.resp.split(" ");

    const comparaVet = (vet1, vet2) => {
        let i = 0;
        vet1.forEach( (elemento, index) => {
            console.log(`${elemento} | ${vet2[index]}`);
            if (elemento === vet2[index]) i++;
        });
        console.log(`${entr}|${resp} \n Corretas: ${i}`);
        return i;
    }

    estatisticas.changeAcerto( comparaVet(entr, resp) / resp.length );
    if ( estatisticas.multiplicador < 5 )
        estatisticas.incrementaMult();


    if ( estatisticas.acerto !== 1 ) {
        estatisticas.resetaMultiplicador();
        erros++;
    }

    estatisticas.calcTotalPts();
}

function setTimerZero() {
    if (!memorize) {
        memorize = true;
        verifyAnswer(screen_input);
    }
    else {
        memorize = false;
    }
    estatisticas.zeraTempR();
    controlGame();
}

const controlGame = () => {
    screen_errors.textContent = `${erros}/${maxErros}`;
    if (erros >= maxErros) {
        clearInterval(timerInterval);
        screen_input.removeEventListener("keydown", handler);
        screen_input.value = "";
        return;
    }

    if (memorize) {
        estatisticas.changeResp( String(selecionaPalavras(3)).replaceAll(',', ' ') );
        campoMem.textContent = estatisticas.resp;
        screen_input.value = "";
        screen_input.disabled = true;
        startCoundown(2);
    }
    else {
        campoMem.textContent = "";
        screen_input.disabled = false;
        screen_input.focus();
        startCoundown(10);
    }
}
controlGame();