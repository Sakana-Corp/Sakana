
<?php
require_once __DIR__ . '/../config/conexao.php';

class Cliente {
    public function inserir($nome, $cpf, $telefone, $email, $senha, $codEndereco, $numero, $complemento) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "insert into cliente (nome, cpf, telefone, email, senha, idEndereco, numero, complemento)
                values (:nome, :cpf, :telefone, :email, :senha, :idEndereco, :numero, :complemento)";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':idEndereco', $idEndereco, PDO::PARAM_INT);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        return $stmt->execute();
    }

    public function listarTodos() {
        $sql = "select c.*, e.cep, e.logradouro, e.bairro, e.cidade, e.uf
                from cliente c
                inner join endereco e on c.idEndereco = e.idEndereco
                order by c.nome";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($idCliente) {
        $sql = "select c.*, e.cep, e.logradouro, e.bairro, e.cidade, e.uf
                from cliente c
                inner join endereco e on c.idEndereco = e.idndereco
                where c.codCliente = :codCliente";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email) {
        $sql = "select * from cliente where email = :email";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Se a senha vier vazia, mantém a senha atual
    public function atualizar($codCliente, $nome, $cpf, $telefone, 
    $email, $senha, $codEndereco, $numero, $complemento) {
        $campoSenha = ($senha != "") ? ", senha = :senha" : "";
        $sql = "update cliente
                set nome = :nome, cpf = :cpf, telefone = :telefone, email = :email,
                    codEndereco = :codEndereco, numero = :numero, complemento = :complemento $campoSenha
                where codCliente = :codCliente";
        $stmt = Conexao::getConn()->prepare($sql);
        if ($senha != "") {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->bindParam(':senha', $senhaHash);
        }
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codEndereco', $codEndereco, PDO::PARAM_INT);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function contarPedidos($codCliente) {
        $sql = "select count(*) as total from pedido where codCliente = :codCliente";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function excluir($codCliente) {
        $sql = "delete from cliente where codCliente = :codCliente";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->bindParam(':codCliente', $codCliente, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>