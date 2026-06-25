const dicionario = [
    'caderno', 'patinete', 'computador', 'garrafa', 'cadeira', 'lâmpada', 'armário', 'óculos', 'talher', 'janela',
    'aprender', 'correr', 'descobrir', 'acordar', 'pintar', 'caminhar', 'desligar', 'chutar', 'escrever', 'cantar',
    'receita', 'crescimento', 'fornecedor', 'audiência', 'fechamento', 'atendimento', 'desenvolvimento', 'estratégia', 'corporação', 'investidor',
    'resort', 'aeroporto', 'floresta', 'museu', 'hotel', 'explorar', 'ônibus', 'aventura', 'exótico', 'relaxamento',
    'malware', 'cache', 'sistema', 'interface', 'mobile', 'digital', 'browser', 'domínio', 'compilador', 'internet'
];

export function selecionaPalavras(qtd) {
    let selecionados = new Array(qtd);
    let index = 0;
    for(let i=0; i<qtd; i++){
        index = Math.floor( Math.random() * dicionario.length );
        if( selecionados.includes( dicionario[index] ) ) {
            i--; continue;
        }
        selecionados[i] = dicionario[index];
    }
    return selecionados;
}