
<?php
require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $preco = $_POST["preco"] ?? "";
    $quantidade = $_POST["quantidadeEstoque"] ?? "";
    $categoria = trim($_POST["categoria"] ?? "");
    $limite = $_POST["limiteMinimo"] ?? "";

    if (
        $nome === "" ||
        $categoria === "" ||
        $preco === "" ||
        $quantidade === "" ||
        !is_numeric($preco) ||
        $preco < 0 ||
        filter_var($quantidade, FILTER_VALIDATE_INT) === false ||
        $quantidade < 0 ||
        ($limite !== "" &&
            (filter_var($limite, FILTER_VALIDATE_INT) === false || $limite < 0))
    ) {
        $mensagem = "Preencha os campos corretamente.";
    } else {
        $preco = (float) $preco;
        $quantidade = (int) $quantidade;
        $limite = ($limite === "") ? 5 : (int) $limite;

        $sql = "INSERT INTO Produto
                (nome, preco, quantidadeEstoque, categoria, limiteMinimo)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "sdisi",
            $nome,
            $preco,
            $quantidade,
            $categoria,
            $limite
        );

        if ($stmt->execute()) {
            $mensagem = "Produto cadastrado com sucesso!";
        } else {
            $mensagem = "Não foi possível cadastrar o produto.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #245b3f;
        }

        label {
            display: block;
            margin-top: 12px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            background: #245b3f;
            color: white;
            border: none;
            cursor: pointer;
        }

        .mensagem {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Produtos</h1>

        <form method="POST">
            <label for="nome">Nome da bebida:</label>
            <input type="text" id="nome" name="nome"
                   maxlength="100" required>

            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco"
                   min="0" step="0.01" required>

            <label for="quantidadeEstoque">Quantidade inicial:</label>
            <input type="number" id="quantidadeEstoque"
                   name="quantidadeEstoque" min="0" step="1" required>

            <label for="categoria">Categoria:</label>
            <input type="text" id="categoria" name="categoria"
                   maxlength="50" required>

            <label for="limiteMinimo">Limite mínimo de estoque:</label>
            <input type="number" id="limiteMinimo" name="limiteMinimo"
                   min="0" step="1"
                   placeholder="Opcional — padrão: 5 unidades">

            <button type="submit">Cadastrar Produto</button>
        </form>

        <?php if ($mensagem !== ""): ?>
            <p class="mensagem">
                <?= htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
