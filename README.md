# Sakana

Simulador de um sistema de gerenciamento de restaurante, com fluxos de gerente,
garcom e cozinha.

## Requisitos

- PHP 8.0 ou superior, com PDO MySQL habilitado;
- MySQL 8.0 ou MariaDB compatível;
- Apache com `mod_rewrite` e `AllowOverride All` habilitados.

## Instalação local (XAMPP)

1. Inicie Apache e MySQL no XAMPP.
2. Crie o banco `bdSakana` no phpMyAdmin, selecione-o na coluna esquerda e importe [`docs/sql/bdSakana.sql`](docs/sql/bdSakana.sql). O SQL não contém `CREATE DATABASE` nem `USE`, porque o banco deve ser selecionado antes da importação.
3. Coloque esta pasta em `C:\xampp\htdocs\Sakana`.
4. Acesse `http://localhost/Sakana/`. O `.htaccess` precisa estar habilitado no Apache para que o bloqueio das pastas internas e a página 404 funcionem.
5. Se o MySQL local usar outra porta, usuário ou senha, defina `SAKANA_DB_HOST`, `SAKANA_DB_PORT`, `SAKANA_DB_NAME`, `SAKANA_DB_USER` e `SAKANA_DB_PASSWORD` no ambiente do PHP. Sem essas variáveis, o fallback é `localhost:3306`, banco `bdSakana`, usuário `root` sem senha.

Para uma verificação rápida sem Apache, também é possível executar `php -S 127.0.0.1:8080 -t .` dentro da pasta do projeto e abrir `http://127.0.0.1:8080/`. Nesse modo, as regras do `.htaccess` não são processadas.

## Publicação na Hostinger

1. No hPanel, abra **Bancos de dados MySQL** e crie o banco, o usuário e a senha. A Hostinger acrescentará um prefixo aos nomes, por exemplo `u550451621_bdsakana`; use exatamente o nome exibido no painel.
2. Abra o phpMyAdmin pelo hPanel e selecione o banco criado na coluna esquerda. Não tente importar o arquivo pela tela inicial do phpMyAdmin.
3. Na aba **Importar**, selecione [`docs/sql/bdSakana.sql`](docs/sql/bdSakana.sql) e execute. Esse arquivo foi preparado para ser importado dentro do banco selecionado e não tenta executar `CREATE DATABASE bdSakana`.
4. Envie o conteúdo do projeto para `public_html`, incluindo o `.htaccess`, sem criar uma pasta intermediária.
5. Configure em [`config/conexao.php`](config/conexao.php) o host, o nome completo do banco com prefixo, o usuário completo, a senha e a porta `3306`. Se a hospedagem fornecer variáveis de ambiente PHP, prefira usá-las e não grave credenciais no Git.
6. Acesse `https://sakana.tech/` e teste login, cadastro, cardápio, mesas e pedidos.

Se o phpMyAdmin ainda informar erro de permissão, o banco não foi selecionado antes da importação ou o nome usado não é o nome completo criado no hPanel. Não altere o SQL para `CREATE DATABASE`; selecione o banco correto e importe novamente.

## Segurança e acesso

- Senhas são armazenadas com `password_hash`;
- formulários POST usam token CSRF;
- após cinco tentativas inválidas, o login fica bloqueado por 15 minutos;
- sessões autenticadas expiram após 15 minutos sem atividade;
- o acesso às áreas é validado no servidor, inclusive para URLs digitadas
  diretamente;
- o gerente pode alterar o nível de acesso de funcionários na consulta de
  funcionários.
- a aplicação publica também os Termos de Uso no menu institucional.

## Autores

Petrick Preite, Sojiro Takemura, Maria Bononi, Giovanna Ferreira e Matheus
Marangoni.
