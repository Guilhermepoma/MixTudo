<?php
// Conexao com o banco de dados
// SE FOR TESTAR LOCAL, TALVEZ PRECISE MUDAR A SENHA

$host = 'localhost';
$user = 'root';
$pass = '';   // no easyphp normalmente é vazio
$db   = 'projetoloja';

// Tentei com PDO mas nao funcionou, ai fiz com mysqli msm
$conn = mysqli_connect($host, $user, $pass, $db);

if (mysqli_connect_errno()) {
    //die('Erro na conexao: ' . mysqli_connect_error());
    echo "Deu ruim na conexao: " . mysqli_connect_error();
    exit;
}

// pra nao ter problema com acentuacao
mysqli_set_charset($conn, 'utf8');

// $conn ta pronta pra usar nas outras paginas
