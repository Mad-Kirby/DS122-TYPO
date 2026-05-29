# DS122-TYPO
Trabalho de desenvolvimento WEB
Não sei oque não sei oque lá
# Grande

## Como clonar o repositório no Ubuntu/WSL

abram o terminal do Ubuntu e escolha a pasta que quer salvar o projeto:

```bash
cd ~
mkdir projeto-web1
cd projeto-web1
```

Depois eh só clonar o repositório:

```bash
git clone https://github.com/Mad-Kirby/DS122-TYPO.git
```

Entra na pasta do projeto:

```bash
cd DS122-TYPO
```

e FINALMENTE abre no VS Code:

```bash
code .
```

## Como rodar o projeto no navegador

abre o terminal do vscode:

```bash
php -S localhost:8000
```

Depois abre no navegador:

```text
http://127.0.0.1:8000/login.php
```

PRESTA ATENÇÃO:

```text
http://127.0.0.1:8000
```

pode mostrar erro **Not Found**, porque ainda não existe uma página inicial `index.php`.

Por enquanto, para iniciar o projeto, acessem diretamente:

```text
http://127.0.0.1:8000/login.php
```

Outras páginas disponíveis:

```text
http://127.0.0.1:8000/cadastro.php
http://127.0.0.1:8000/recuperar-senha.php
http://127.0.0.1:8000/jogo.php
```
