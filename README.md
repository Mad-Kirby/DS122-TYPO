# DS122-TYPO

Projeto de desenvolvimento Web em PHP e MySQL. Por Ana Turko, Carlos Schatte, Gabriel Jaremczyk e Henrique Santini

## O que é

DS122-TYPO é um sistema web que oferece cadastro e login de usuários, um jogo de memorização de palavras e digitação com pontuação e um sistema de ligas.

## O que faz

- Permite cadastro de usuários com nome, e-mail e senha segura.
- Oferece login e autenticação para acessar páginas protegidas.
- Inclui uma tela de recuperação de senha (interface de exemplo).
- Contém um jogo que registra pontuações e mostra resultados.
- O jogo consiste de três palavras que aparecem na tela e ficam alguns segundos na tela e logo desaparecem, o jogador precisa digitá-las corretamente para maximizar os pontos, podendo cometer erros somente 3 vezes 
- Oferece um placar geral e controle de ligas de usuários.

## Como rodar

O projeto usa PHP e MySQL. É preciso criar o banco de dados e configurar as credenciais em um arquivo `.env` na raiz do projeto.

> O arquivo `.env` está listado no `.gitignore` para que não seja enviado ao repositório.

### Passos comuns

1. Abra o terminal na raiz do projeto.
2. Instale e habilite a extensão `php-mysql` para que o PHP possa se conectar ao MySQL via PDO.
3. Inicie o serviço MySQL.
4. Crie o banco de dados com o script `schema.sql`.
5. Crie o arquivo `.env` com as variáveis de conexão.
6. Inicie o servidor PHP embutido.
7. Acesse `login.php` no navegador.

### Ubuntu / WSL

No Ubuntu ou WSL, execute:

```bash
sudo service mysql start
sudo mysql < schema.sql
```

Depois, entre no MySQL e execute:

```sql
CREATE USER IF NOT EXISTS 'typo_user'@'localhost' IDENTIFIED BY 'typo123';
GRANT ALL PRIVILEGES ON ds122_typo.* TO 'typo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Crie o arquivo `.env` na raiz do projeto com o conteúdo:

```text
DB_HOST=localhost
DB_NAME=ds122_typo
DB_USER=typo_user
DB_PASSWORD=typo123
```

Execute o servidor PHP na pasta do projeto:

```bash
php -S localhost:8000
```

Abra no navegador:

```text
http://127.0.0.1:8000/login.php
```

### Windows
Se o MySQL estiver instalado via XAMPP, WAMP ou outra distribuição, use o mesmo banco de dados e credenciais no `.env`.

No Windows, abra o terminal do projeto e execute o mesmo servidor embutido do PHP:

1. Abra o PowerShell na pasta do projeto:
```powershell
cd C:\xampp\htdocs\DS122-TYPO-main
```
2. Crie o banco de dados utilizando o **schema.sql** :
```powershell
php -S localhost:8000
```
3. Abra o MySQL:
```powershell
mysql -u root -p
```
e rode:
```sql
CREATE USER IF NOT EXISTS 'typo_user'@'localhost' IDENTIFIED BY 'typo123';
GRANT ALL PRIVILEGES ON ds122_typo.* TO 'typo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
4.crie o arquivo **.env** na raiz do projeto com o conteúdo:
```sql
DB_HOST=localhost
DB_NAME=ds122_typo
DB_USER=typo_user
DB_PASSWORD=typo123
```
5. Execute o servidor PHP:
```powershell
php -S localhost:8000
```
Acesse:

```text
http://127.0.0.1:8000/login.php
```

### Observação

O endereço `http://127.0.0.1:8000` pode apresentar erro **Not Found** porque não existe uma página `index.php`.

Para iniciar o projeto, acesse diretamente:

```text
http://127.0.0.1:8000/login.php
```
ou 
```text
http://localhost:8000/login.php
```
