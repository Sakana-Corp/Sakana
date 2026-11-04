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

Se você já tinha um banco criado antes desta versão, faça backup e execute
[`docs/sql/migracaoIsolamentoUsuarios.sql`](docs/sql/migracaoIsolamentoUsuarios.sql)
no banco selecionado antes de abrir o módulo de mesas ou pedidos. Em uma
instalação nova, basta importar `bdSakana.sql`; não execute a migração de
isolamento depois dele.

Se o banco foi criado antes do registro de aceite da política, execute também
[`docs/sql/migracaoPoliticaUsuario.sql`](docs/sql/migracaoPoliticaUsuario.sql).

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
