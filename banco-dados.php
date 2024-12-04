<?php
// Definir as configurações de conexão com o banco de dados
$host = 'localhost';  // Alterar para o seu host
$user = 'root';       // Alterar para o seu usuário de banco de dados
$password = '';       // Alterar para a sua senha de banco de dados
$dbname = 'meubanco'; // Alterar para o nome do seu banco de dados

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão com o banco de dados: " . $conn->connect_error]));
}

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados JSON da requisição
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nome']) && isset($data['email']) && isset($data['site'])) {
        $nome = $data['nome'];
        $email = $data['email'];
        $site = $data['site'];

        // Sanitizar os dados (para evitar injeção de SQL)
        $nome = $conn->real_escape_string($nome);
        $email = $conn->real_escape_string($email);
        $site = $conn->real_escape_string($site);

        // Inserir os dados no banco de dados
        $query = "INSERT INTO produtos (nome, email, site) VALUES ('$nome', '$email', '$site')";
        
        if ($conn->query($query)) {
            echo json_encode(["status" => "success", "message" => "Produto adicionado com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao adicionar produto!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dados incompletos!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método não permitido"]);
}

$conn->close();
?>
