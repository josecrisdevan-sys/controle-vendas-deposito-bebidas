
<?php
require_once "conexao.php";

$busca = trim($_GET["busca"] ?? "");

$sql = "SELECT idProduto, nome, preco, quantidadeEstoque, categoria
        FROM Produto
        WHERE nome LIKE ?
        ORDER BY nome";

$stmt = $conexao->prepare($sql);
$termo = "%" . $busca . "%";
$stmt->bind_param("s", $termo);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produtos</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #245b3f;
        }

        input, button {
            padding: 10px;
            margin-top: 10px;
        }

        input {
            width: 65%;
        }

        button {
            background: #245b3f;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #245b3f;
            color: white;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Consulta de Produtos e Estoque</h1>

    <form method="GET">
        <label for="busca">Pesquisar bebida pelo nome:</label><br>
        <input
            type="text"
            id="busca"
            name="busca"
            value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="Digite o nome da bebida"
        >
        <button type="submit">Pesquisar</button>
    </form>

    <table>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Quantidade disponível</th>
            <th>Categoria</th>
        </tr>

        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($produto = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($produto["nome"], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>R$ <?= number_format((float)$produto["preco"], 2, ',', '.') ?></td>
                    <td><?= (int)$produto["quantidadeEstoque"] ?></td>
                    <td><?= htmlspecialchars($produto["categoria"], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Nenhum produto encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>

<?php
$stmt->close();
?>
