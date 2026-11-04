-- Selecione o banco correto no phpMyAdmin antes de executar esta migração.
-- Use esta migração somente em bancos criados antes da versão que passou a
-- registrar a aceitação da Política de Privacidade.

ALTER TABLE LoginUser
    ADD COLUMN politicaAceita TINYINT(1) NOT NULL DEFAULT 0 AFTER nivelAcesso,
    ADD COLUMN politicaAceitaEm DATETIME NULL AFTER politicaAceita;
