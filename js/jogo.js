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
    this.dificuldade = {
        tmp_chute: 15,
        tmp_memoria: 3,
        qtd_palav: 1
    }
    this.incrementarTempR =     () => { this.tempo_resp++; }
    this.resetarTempR =         () => { this.tempo_resp = 0; }
    this.resetarMultiplicador = () => { this.multiplicador = 1; }
    this.mudarAcerto =   (percent) => { this.acerto = percent; }
    this.getTmpChute =          () => { return this.dificuldade.tmp_chute; }
    this.getTmpMemoria =        () => { return this.dificuldade.tmp_memoria; }
    this.incrementarMult =      () => {
        if (this.multiplicador >= 5) { return; }
        this.multiplicador++;
    }
    this.mudarResp =  () => { 
        const arr_palavras = selecionaPalavras(this.dificuldade.qtd_palav);
        const nova_resp = String(arr_palavras).replaceAll(',', ' ');
        this.resp = nova_resp; 
    }
    this.calcularTotalPts = () => {
        this.pts_total += Math.floor(
            10 * this.acerto * (1 + 0.8 ** this.tempo_resp) * this.multiplicador
        );
    }
    this.mudarDificuldade = () => {
        const flags = [100, 200, 400, 600, 800];
        const pts = this.pts_total;
        if (this.pts_total <= flags[0] || this.pts_total > flags[4]) return;

        if (pts > flags[0] && pts <= flags[1]) {
            this.dificuldade.qtd_palav = 2;
        } else if (pts > flags[1] && pts <= flags[2]) {
            this.dificuldade.qtd_palav = 3;
            this.dificuldade.tmp_memoria = 5;
        } else if (pts > flags[2] && pts <= flags[3]) {
            this.dificuldade.tmp_memoria = 3;
            this.dificuldade.tmp_chute = 10;
        } else {
            this.dificuldade.tmp_memoria = 1;
        }
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
    const entr = input.value.trim().split(" ");
    const resp = estatisticas.resp.split(" ");

    const compararVet = (vet_e, vet_r) => {
        let i = 0;
        vet_r.forEach( (elemento) => {
            if (vet_e.includes( elemento )) i++;
        });
        return i;
    }

    estatisticas.mudarAcerto( compararVet(entr, resp) / resp.length );

    if ( estatisticas.acerto !== 1 ) {
        estatisticas.resetarMultiplicador();
        erros++;
    }

    estatisticas.calcularTotalPts();
    if ( estatisticas.acerto == 1 )
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
        
        redirecionarTela();
    }
    estatisticas.mudarDificuldade();

    if (memoriza) {
        estatisticas.mudarResp();
        screen_mem.textContent = estatisticas.resp;
        screen_input.value = "";
        screen_input.disabled = true;
        iniciarTemporizador( estatisticas.getTmpMemoria() );
    }
    else {
        screen_mem.textContent = "";
        screen_input.disabled = false;
        screen_input.focus();
        iniciarTemporizador( estatisticas.getTmpChute() );
    }
}

controlarJogo();

function redirecionarTela() {
    const form_post = document.createElement("form");
    form_post.setAttribute('action', "jogo.php?step=salvar-pontuacao");
    form_post.setAttribute('method', "POST");

    const input_pts = document.createElement("input");
    input_pts.setAttribute('type', "hidden");
    input_pts.setAttribute('name', "pontos");
    input_pts.value = estatisticas.pts_total;

    document.body.append(form_post);
    form_post.append(input_pts);

    form_post.submit(); 
}