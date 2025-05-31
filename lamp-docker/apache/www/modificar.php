<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar inventario</title>
</head>
<body>
    <h2>Modificar un registro del inventario</h2>
    <form action="modificar.php" method="POST">
        <label for="tabla">Selecciona la tabla:</label>
        <select name="tabla" required>
            <option value="RJ45">RJ45</option>
            <option value="MONOMODO">Monomodo</option>
            <option value="MULTIMODO">Multimodo</option>
            <option value="SCHUKO">Schuko</option>
        </select><br><br>

        <label for="id">ID del cable a modificar:</label>
        <input type="number" name="id" required><br><br>

        <label for="columna">Columna a modificar:</label>
        <input type="text" name="columna" placeholder="Ej: CANTIDAD" required><br><br>

        <label for="valor">Nuevo valor:</label>
        <input type="text" name="valor" required><br><br>

        <button type="submit">Modificar</button>
    </form>
    <div>
        <br>
        <a>
            <button onclick="history.back()">Volver</button>
        </a>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "mysql";
    $username = "kali";
    $password = "kali";
    $dbname = "Inventario";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $tabla   = strtoupper($_POST['tabla']);
    $columna = strtoupper(trim($_POST['columna']));
    $valor   = $_POST['valor'];
    $id      = intval($_POST['id']);

    $tablas = [
        "RJ45"      => ["ID", "CANTIDAD", "TAMANO", "COLOR"],
        "MONOMODO"  => ["ID", "CABLEADO", "CANTIDAD", "TAMANO", "COLOR", "CONECTOR"],
        "MULTIMODO" => ["ID", "CABLEADO", "CANTIDAD", "TAMANO", "COLOR", "CONECTOR"],
        "SCHUKO"    => ["ID", "TIPO", "CANTIDAD"]
    ];

    if (!array_key_exists($tabla, $tablas)) {
        die("Tabla no permitida.");
    }

    if (!in_array($columna, $tablas[$tabla])) {
        die("Columna no válida para la tabla seleccionada.");
    }

    $sql = "UPDATE $tabla SET $columna = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $valor, $id);
    if ($stmt->execute()) {
        echo "Registro actualizado correctamente en la tabla $tabla.<br>";
    } else {
        echo "Error al actualizar la tabla: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

