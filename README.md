# Sakana

Simulador de um sistema de gerenciamento de restaurante, com fluxos de gerente,
garcom e cozinha.

## Requisitos

- PHP 8.0 ou superior, com PDO MySQL habilitado;
- MySQL 8.0 ou MariaDB compatível;
- Apache com `mod_rewrite` e `AllowOverride All` habilitados.

## Instalação local

1. Importe [`docs/sql/bdSakana.sql`](docs/sql/bdSakana.sql) pelo phpMyAdmin; o script cria o schema, tabelas e usuários de teste.
2. Configure `SAKANA_DB_HOST`, `SAKANA_DB_PORT`, `SAKANA_DB_NAME`, `SAKANA_DB_USER` e `SAKANA_DB_PASSWORD` no ambiente da hospedagem. Sem variáveis, o fallback local é `localhost:3306`, banco `bdSakana`, usuário `root` sem senha.
3. Publique somente os arquivos da aplicação na pasta `public_html`.
4. Acesse `https://sakana.tech/`.

### Deploy na Hostinger

No hPanel, crie o banco MySQL e o usuário, anote o host, nome com prefixo e senha e importe o SQL. Configure as mesmas cinco variáveis no ambiente PHP (ou ajuste os valores de fallback em `config/conexao.php` antes do upload, sem versionar credenciais). Mantenha `.htaccess` na raiz e confirme `AllowOverride`/mod_rewrite ativos.

Logins seed: administrador `admin@sakana.tech` / `SakanaAdmin!2026`; usuário comum `usuario@sakana.tech` / `SakanaUser!2026`. Troque as senhas após o primeiro acesso.

O arquivo `.htaccess` desativa a listagem de diretórios, bloqueia pastas
internas e direciona páginas inexistentes para [`404.php`](404.php).

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
