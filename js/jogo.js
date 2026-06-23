import { selecionaPalavras } from "./dicionario.js";

const screen_mem = document.querySelector(".memorize__field");
const screen_time = document.querySelector(".screen__time span");
const screen_input = document.querySelector(".screen__input");
const screen_errors = document.querySelector(".screen__errors span");
const screen_pts = document.querySelector(".screen__pts span");

// para manter controle das estatisticas das tentativas/jogo 
// sem utilizar variáveis globais
function Estatistica() {
    this.resp = "";
    this.multiplicador = 1;
    this.tempo_resp = 0;
    this.acerto = 0;
    this.pts_total = 0;
    this.mudarResp =  (novaResp) => { return this.resp = novaResp; }
    this.incrementarTempR =     () => { return this.tempo_resp++; }
    this.resetarTempR =           () => { return this.tempo_resp = 0; }
    this.incrementarMult =      () => { return this.multiplicador++; }
    this.resetarMultiplicador = () => { return this.multiplicador = 1; }
    this.mudarAcerto = (percent) => { return this.acerto = percent; }
    this.calcularTotalPts = () => {
        console.log(this);
        return this.pts_total += Math.floor(
            10 * this.acerto * (1 + 0.8 ** this.tempo_resp) * this.multiplicador
        );
    }
}

const estatisticas = new Estatistica();

/* -- comportamento do campo das palavras -- */
screen_mem.addEventListener("copy", (e) => { e.preventDefault() });

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
        reiniciarLoop();
    }
}

let erros = 0;
const max_erros = 3;
let memoriza = true;
let temporizador;

const iniciarTemporizador = (tempo_limite) => {
    clearInterval(temporizador);
    const tempo_max = new Date().getTime() + (1000 * tempo_limite);

    function atualizarTempo() {
        const agora = new Date().getTime();
        const distancia = tempo_max - agora;

        if (distancia <= 0) { reiniciarLoop(); return; }

        estatisticas.incrementarTempR();
        const segundos = Math.floor((distancia % (1000 * 60)) / 1000);
        screen_time.textContent = segundos;
    }

    atualizarTempo();
    temporizador = setInterval(atualizarTempo, 1000);
}

const verificarResposta = (input) => {
    const entr = input.value.split(" ");
    const resp = estatisticas.resp.split(" ");

    const compararVet = (vet1, vet2) => {
        let i = 0;
        vet1.forEach( (elemento) => {
            if (vet2.includes( elemento )) i++;
        });
        return i;
    }

    estatisticas.mudarAcerto( compararVet(entr, resp) / resp.length );

    if ( estatisticas.acerto !== 1 ) {
        estatisticas.resetarMultiplicador();
        erros++;
    }

    estatisticas.calcularTotalPts();
    if ( estatisticas.multiplicador < 5 )
        estatisticas.incrementarMult();
}

function reiniciarLoop() {
    if (!memoriza) {
        memoriza = true;
        verificarResposta(screen_input);
    }
    else {
        memoriza = false;
    }
    estatisticas.resetarTempR();
    controlarJogo();
}

const controlarJogo = () => {
    screen_errors.textContent = `${erros}/${max_erros}`;
    screen_pts.textContent = `${estatisticas.pts_total}`;
    if (erros >= max_erros) {
        clearInterval(temporizador);
        screen_input.removeEventListener("keydown", handler);
        screen_input.value = "";
        window.location.replace(`jogo.php?step=pontuacao&pontos=${estatisticas.pts_total}`);
    }

    if (memoriza) {
        estatisticas.mudarResp( String(selecionaPalavras(3)).replaceAll(',', ' ') );
        screen_mem.textContent = estatisticas.resp;
        screen_input.value = "";
        screen_input.disabled = true;
        iniciarTemporizador(5);
    }
    else {
        screen_mem.textContent = "";
        screen_input.disabled = false;
        screen_input.focus();
        iniciarTemporizador(15);
    }
}

controlarJogo();