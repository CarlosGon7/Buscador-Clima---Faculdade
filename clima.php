<?php
header('Content-Type: application/json');


// Caminho do arquivo JSON
$file = __DIR__ . '/data/history.json';


// ==============================
// LISTAR HISTÓRICO
// ==============================
if (isset($_GET['listarHistorico'])) {


    if (!file_exists($file)) {
        echo json_encode([]);
        exit;
    }


    $conteudo = file_get_contents($file);
    if (!$conteudo) {
        echo json_encode([]);
        exit;
    }


    echo $conteudo;
    exit;
}


// ==============================
// LIMPAR HISTÓRICO
// ==============================
if (isset($_GET['limparHistorico'])) {


    file_put_contents($file, "[]");


    echo json_encode([
        "status" => "success",
        "message" => "Histórico limpo com sucesso"
    ]);
    exit;
}


// ==============================
// SALVAR HISTÓRICO (POST)
// ==============================


// Recebe dados enviados via POST
$input = file_get_contents('php://input');
if (!$input) {
    echo json_encode(["status" => "error", "message" => "Nenhum dado recebido"]);
    exit;
}


$data = json_decode($input, true);
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
    exit;
}


// Lê histórico existente
if (file_exists($file)) {
    $history = json_decode(file_get_contents($file), true);
    if (!$history) $history = [];
} else {
    $history = [];
}


// Adiciona data/hora
$data['data'] = date("Y-m-d H:i:s");


// Salva no array
$history[] = $data;


// Grava no arquivo
if (file_put_contents($file, json_encode($history, JSON_PRETTY_PRINT))) {
    echo json_encode(["status" => "success", "message" => "Histórico atualizado"]);
} else {
    echo json_encode(["status" => "error", "message" => "Não foi possível salvar"]);
}
?>