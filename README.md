# Sakana

Simulador de um sistema de gerenciamento de restaurante, com fluxos de gerente,
garcom e cozinha.

## Requisitos

- PHP 8.0 ou superior, com PDO MySQL habilitado;
- MySQL 8.0 ou MariaDB compatível;
- Apache com `mod_rewrite` e `AllowOverride All` habilitados.

## Instalação local

1. Crie o banco `bdSakana` e importe [`docs/sql/bdSakana.sql`](docs/sql/bdSakana.sql).
2. Configure as credenciais em [`config/conexao.php`](config/conexao.php).
3. Publique a pasta no Apache como `/Sakana`.
4. Acesse `http://localhost/Sakana/`.

O arquivo `.htaccess` desativa a listagem de diretórios, bloqueia pastas
internas e direciona páginas inexistentes para a rota inicial.

## Segurança e acesso

- Senhas são armazenadas com `password_hash`;
- formulários POST usam token CSRF;
- após cinco tentativas inválidas, o login fica bloqueado por 15 minutos;
- sessões autenticadas expiram após 15 minutos sem atividade;
- o acesso às áreas é validado no servidor, inclusive para URLs digitadas
  diretamente;
- o gerente pode alterar o nível de acesso de funcionários na consulta de
  funcionários.

## Autores

Petrick Preite, Sojiro Takemura, Maria Bononi, Giovanna Ferreira e Matheus
Marangoni.
