<?php

// Configuração do banco de dados
$serverName = '10.244.216.227,1433'; // Formato: 'hostname,port'
$databaseName = 'teste';
$username = 'sa';
$password = 'uni226';


    // Criar a conexão com o banco de dados usando PDO_SQLSRV
    $pdo = new PDO("sqlsrv:Server=$serverName;Database=$databaseName", $username, $password);

    // Configurar PDO para lançar exceções em erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL
    $cnp = '02013885610';

    $sql = "
        SELECT
            CASE
                WHEN ContratoFinanceiro.Codigo IS NULL THEN 'NÃO É CONTRATANTE'
                WHEN ContratoFinanceiro.Codigo IS NOT NULL THEN 'CONTRATANTE'
            END AS 'Res tipy String',
            CASE
                WHEN ContratoFinanceiro.Codigo IS NULL THEN 0
                WHEN ContratoFinanceiro.Codigo IS NOT NULL THEN 1
            END AS 'contratante'
        FROM ContratoFinanceiro
        LEFT JOIN Pessoa ON Pessoa.autoid = ContratoFinanceiro.pessoa
        WHERE Pessoa.cnp = :cnp
    ";

    // Preparar a consulta
    $stmt = $pdo->prepare($sql);

    // Vincular o valor do CNP
    $stmt->bindParam(':cnp', $cnp, PDO::PARAM_STR);

    // Executar a consulta
    $stmt->execute();

    // Obter os resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    try{
        dd($results);
    }catch(Exception $e){
        echo $e;
    }
    // Exibir os resultados
    foreach ($results as $row) {
        echo "Res tipy String: " . $row['Res tipy String'] . "<br>";
        echo "contratante: " . $row['contratante'] . "<br>";
    }
