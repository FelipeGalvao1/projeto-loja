<?php

/* 
    Vamos criar um Header, ou seja, um cabeçalho.
    Esse cabeçalho permite o acesso a listagem de usuarios com diversas origens
    Por isso estamos usando o *(asterisco) para essa permissão que será para 
     - http
     - https
     - file
     - ftp
*/

header("Access-Control-Allow-Origin:*");

// Vamos definir qual será o formato de dados que o cliente irá enviar e receber
// em relação a api. Este formato será do tipo JSON (JavaScript Object Notation)

header("Content-Type: application/json;charset=utf-8");

// Abaixo estamos incluindo o arquivo database.php que possui a 
// classe Database com a conexão com o banco de dados

include_once "../../config/database.php";

// O arquivo usuario.php será incluido para que a classe Usuario 
// seja usada. Vale lembrar que esta classe possui o CRUD

include_once "../../domain/usuario.php";

// Criamos um objeto chamado $database. É uma instância da classe Database
// que está na pasta config e isso nos dará acesso a todo o seu conteúdo público

$database = new Database();

// Executar a função que está dentro do database chamada getConnection, oius
// esta função realiza a conexao com o banco de dados 

$db = $database->getConnection();
 
// Vamos fazer uma instância da classe usuário para ter acesso a todo 
// o seu conteúdo.

$usuario = new Usuario($db);

// rs = resultado

$rs = $usuario->listar();

/* 
    Vamos construir uma estrutura exebir os dados do banco no formato de 
    json.
    Como esses dados estão dispostos em linhas e colunas, nós precisaremos
    criar um array para exibir todos os dados corretamente
*/

if($rs->rowCount()>0){
    $usuario_arry["saida"] = array();
    
/*
    A estrutura while (enquanto) realiza a busca de todos os usuários 
    cadastrados até o chegar ao final da tabela e traz os dados para 
    fetch array organizar em formato de array.
    Assim será mais fácil de adicionar no array de usuários para apresentar 
    ao final
*/

    while($linha = $rs->fetch(PDO::FETCH_ASSOC)){

    // O comando extract é capaz de separar de forma mais simples
    // os campos da tabela tbusuarios     
    extract($linha);
    $array_item = array(
        "idusuario"=>$idusuario,
        "nomeusuario"=>$nomeusuario,
        "senha"=>$senha,
        "foto"=>$foto
    );

    array_push($usuario_arry["saida"],$array_item);
    }

    header("HTTP/1.0 200");
    echo json_encode($usuario_arry);
}else{
    header("HTTP/1.0 400");
    echo json_encode(array("mensagem"=>"Não há usuários cadastrados"));
}

?>
