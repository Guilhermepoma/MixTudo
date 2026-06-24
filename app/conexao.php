<?php
$conn = mysqli_connect('localhost', 'root', '', 'projetoloja');
if (mysqli_connect_errno()) { echo 'Deu ruim na conexao: ' . mysqli_connect_error(); exit; }

mysqli_set_charset($conn, 'utf8');
