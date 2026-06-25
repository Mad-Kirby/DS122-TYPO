# Relatório de Uso de Inteligência Artificial Generativa

Este documento registra todas as interações significativas com ferramentas de IA generativa (como Gemini, ChatGPT, Copilot, etc.) durante o desenvolvimento deste projeto. O objetivo é promover o uso ético e transparente da IA como ferramenta de apoio, e não como substituta para a compreensão dos conceitos fundamentais.

## Política de Uso
O uso de IA foi permitido para as seguintes finalidades:
- Geração de ideias e brainstorming de algoritmos.
- Explicação de conceitos complexos.
- Geração de código boilerplate (ex: estrutura de classes, leitura de arquivos).
- Sugestões de refatoração e otimização de código.
- Debugging e identificação de causas de erros.
- Geração de casos de teste.

É proibido submeter código gerado por IA sem compreendê-lo completamente e sem adaptá-lo ao projeto. Todo trecho de código influenciado pela IA deve ser referenciado neste log.

---

## Registro de Interações


### Interação 1

* **Data:** 19/06/2026
* **Etapa do Projeto:** Modelagem e criação do banco de dados
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Receber apoio para planejar a estrutura do banco de dados MySQL do projeto, considerando usuários, partidas, pontuações e ligas.
* **Prompt(s) Utilizado(s):**

  1. "Como posso organizar as tabelas para salvar usuários, pontuações e ligas?"
* **Resumo da Resposta da IA:** A IA sugeriu uma estrutura com tabelas para usuários, partidas, ligas e associação entre usuários e ligas, explicando a função de cada relacionamento.
* **Análise e Aplicação:** A sugestão foi analisada e adaptada para o projeto. A estrutura foi utilizada para permitir cadastro de usuários, salvamento de partidas, criação de ligas e rankings.
* **Referência no Código:** Arquivo `schema.sql`.

---

### Interação 2

* **Data:** 19/06/2026
* **Etapa do Projeto:** Conexão PHP com MySQL
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Implementar a conexão entre o PHP e o banco de dados utilizando PDO e variáveis de ambiente.
* **Prompt(s) Utilizado(s):**

  1. "Como faço a conexão do PHP com o MySQL usando PDO?"
  2. "Quero usar um arquivo .env para guardar os dados de acesso ao banco."
* **Resumo da Resposta da IA:** A IA explicou como criar um arquivo de conexão com PDO, carregar as informações do `.env` e evitar deixar credenciais diretamente no có digo.
* **Análise e Aplicação:** A lógica foi adaptada ao projeto sem uso de frameworks back-end. Também foi criado um `.gitignore` para impedir o envio do arquivo `.env` ao repositório.
* **Referência no Código:** Arquivos `includes/conexao.php`, `.env` e `.gitignore`.

---

### Interação 3

* **Data:** 25/06/2026
* **Etapa do Projeto:** Cadastro e autenticação de usuários
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Implementar o cadastro, login e controle de sessão dos usuários.
* **Prompt(s) Utilizado(s):**

  1. "Como faço o cadastro salvar usuário no banco com senha segura?"
  2. "Como proteger páginas para apenas usuários logados acessarem?"
* **Resumo da Resposta da IA:** A IA explicou o uso de `password_hash()` para salvar senhas, `password_verify()` para validar login.
* **Análise e Aplicação:**  As páginas principais passaram a exigir autenticação, e o logout foi implementado limpando a sessão.
* **Referência no Código:** Arquivos `cadastro.php`, `login.php`, `includes/auth.php` e `logout.php`.

---

### Interação 4

* **Data:** 25/06/2026
* **Etapa do Projeto:** Salvamento de pontuação e placar geral
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Fazer o jogo em JavaScript enviar a pontuação final para o PHP e salvar a partida no banco de dados.
* **Prompt(s) Utilizado(s):**

  1. "Como faço para o JavaScript enviar a pontuação para o PHP?"
* **Resumo da Resposta da IA:** A IA sugeriu o envio da pontuação por formulário `POST`, com validação no PHP antes de salvar no banco de dados.
* **Análise e Aplicação:** A solução foi adaptada ao jogo existente. O JavaScript passou a enviar a pontuação para uma etapa específica do `jogo.php`, e o PHP passou a validar a pontuação e registrar a partida na tabela `partidas`.
* **Referência no Código:** Arquivos `js/jogo.js` e `jogo.php`.

---

### Interação 5

* **Data:** 25/06/2026
* **Etapa do Projeto:** Sistema de ligas e rankings
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Implementar criação de ligas, entrada em ligas com palavra-chave e exibição de rankings geral e semanal.
* **Prompt(s) Utilizado(s):**

  1. "Como calcular ranking geral e semanal da liga?"
* **Resumo da Resposta da IA:** A IA orientou a criação de consultas SQL para criar ligas, cadastrar usuários em ligas e calcular a soma de pontos dos membros no ranking geral e semanal.
* **Análise e Aplicação:** A implementação foi feita usando PHP e MySQL, com validação dos campos recebidos e verificação da palavra-chave da liga.
* **Referência no Código:** Arquivo `ligas.php`.

---

### Interação 6

* **Data:** 25/06/2026
* **Etapa do Projeto:** Histórico de partidas
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Transformar a página de histórico, que estava estática, em uma página dinâmica com as partidas do usuário logado.
* **Prompt(s) Utilizado(s):**

  1. "Como buscar no banco as partidas do usuário logado?"
* **Resumo da Resposta da IA:** A IA sugeriu uma consulta SQL na tabela de partidas filtrando pelo `id_usuario` da sessão, exibindo a data e a pontuação de cada partida.
* **Análise e Aplicação:** A página foi adaptada para buscar as partidas reais do banco, protegendo o acesso com autenticação e exibindo uma mensagem quando não há partidas.
* **Referência no Código:** Arquivo `historico.php`.

---

### Interação 7

* **Data:** 25/06/2026
* **Etapa do Projeto:** Debugging e configuração do ambiente
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Resolver erros de conexão com banco de dados e orientar a configuração local do projeto em diferentes ambientes.
* **Prompt(s) Utilizado(s):**

  1. "Está aparecendo erro ao conectar com o banco de dados."
  2. "Como configurar o projeto no Windows/XAMPP?"
* **Resumo da Resposta da IA:** A IA explicou possíveis causas do erro, como ausência do banco, usuário sem permissão, `.env` incorreto ou extensão `php-mysql` ausente.
* **Análise e Aplicação:** As orientações foram usadas para testar o banco no phpMyAdmin, verificar tabelas, criar usuário do banco e ajustar permissões.
* **Referência no Código:** Arquivos `schema.sql`, `includes/conexao.php`, `.env` e `README.md`.

---

### Interação 8

* **Data:** 25/06/2026
* **Etapa do Projeto:** Revisão final e ajustes de validação
* **Ferramenta de IA Utilizada:** ChatGPT
* **Objetivo da Consulta:** Revisar se o projeto atendia aos critérios da especificação e corrigir pequenos problemas antes da entrega.
* **Prompt(s) Utilizado(s):**

  1. "Tem algo que falta eu fazer que ainda não atendeu aos critérios?"
* **Resumo da Resposta da IA:** A IA revisou os requisitos do trabalho e identificou ajustes, como permitir login por e-mail no JavaScript e garantir que a pontuação fosse enviada corretamente ao PHP.
* **Análise e Aplicação:** Os ajustes foram aplicados para melhorar a consistência entre front-end e back-end, especialmente na validação do campo de login.
* **Referência no Código:** Arquivos `js/login.js`, `login.php`, `js/jogo.js` e `jogo.php`.

### Interação 9

* **Data:** 16/06/26 - 23/06/12
* **Etapa do projeto:** Formatação CSS
* **Ferramenta IA Utilizada:** Perplexity

* **Objetivo:** Auxiliar na organização, padronização e melhoria visual das páginas do projeto, garantindo consistência entre layout, cores, tipografia, botões, cards e responsividade.

* **Aplicação no Projeto:** A IA foi usada para apoiar a criação e o ajuste dos estilos das telas de autenticação, instruções do jogo, área principal do jogo e ligas, além de orientar melhorias de espaçamento, tamanhos mínimos, responsividade e comportamento dos componentes na interface.

* **Referência no Código:** Arquivos 'autenticar.css', 'como-jogar.css', 'jogo.css', 'ligas.css'.
---
