<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir al inventario</title>
    <style>
        .hidden { display: none; }
    </style>
    <script>
        function mostrarCampos() {
            const seleccion = document.getElementById("tabla").value;
            const campos = document.querySelectorAll(".campo");
            campos.forEach(campo => {
                campo.style.display = campo.dataset.tablas.includes(seleccion) ? "block" : "none";
            });
        }
        window.onload = mostrarCampos;
    </script>
</head>
<body>

<h1>Insertar nuevos datos de cable</h1>

<form method="POST">
    <label for="tabla">Selecciona la tabla:</label>
    <select name="tabla" id="tabla" onchange="mostrarCampos()">
        <option value="RJ45">RJ-45</option>
        <option value="MONOMODO">Monomodo</option>
        <option value="MULTIMODO">Multimodo</option>
        <option value="SCHUKO">SCHUKO</option>
    </select>

    <div id="campos">
        <!-- Comunes -->
        <div class="campo" data-tablas="RJ45 monomodo multimodo SCHUKO">
            <label for="id">ID:</label>
            <input type="text" name="id">
        </div>

        <div class="campo" data-tablas="RJ45 monomodo multimodo SCHUKO">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad">
        </div>

        <div class="campo" data-tablas="RJ45 monomodo multimodo">
            <label for="tamano">Tamaño:</label>
            <input type="text" name="tamano">
        </div>

        <div class="campo" data-tablas="RJ45 monomodo multimodo">
            <label for="color">Color:</label>
            <input type="text" name="color">
        </div>

        <div class="campo" data-tablas="monomodo multimodo">
            <label for="cableado">Cableado:</label>
            <input type="text" name="cableado">
        </div>

        <div class="campo" data-tablas="monomodo multimodo">
            <label for="conector">Conector:</label>
            <input type="text" name="conector">
        </div>

        <div class="campo" data-tablas="SCHUKO">
            <label for="tipo">Tipo:</label>
            <input type="text" name="tipo">
        </div>
    </div>

    <br>
    <button type="submit">Insertar</button>
    <br>
        <div>
        <br>
        <a>
            <button onclick="history.back()">Volver</button>
        </a>
    </div>
</form>

<?php
// Conexión a la base de datos
$servername = "mysql";
$username = "kali";
$password = "kali";
$dbname = "Inventario";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Columnas permitidas por tabla
$tablas = [
    "RJ45" => ["id", "cantidad", "tamano", "color"],
    "MONOMODO" => ["id", "cableado", "cantidad", "tamano", "color", "conector"],
    "MULTIMODO" => ["id", "cableado", "cantidad", "tamano", "color", "conector"],
    "SCHUKO" => ["id", "tipo", "cantidad"]
];

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla = $_POST['tabla'];
    if (!array_key_exists($tabla, $tablas)) {
        die("Error: tabla no válida.");
    }

    $datos = [];
    foreach ($tablas[$tabla] as $columna) {
        $datos[$columna] = $_POST[$columna] ?? null;
    }

    $columnas = implode(", ", array_keys($datos));
    $placeholders = implode(", ", array_fill(0, count($datos), "?"));
    $tipos = str_repeat("s", count($datos));

    $stmt = $conn->prepare("INSERT INTO $tabla ($columnas) VALUES ($placeholders)");
    if ($stmt === false) {
        die("Error al preparar la sentencia: " . $conn->error);
    }

    $stmt->bind_param($tipos, ...array_values($datos));
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Datos insertados correctamente en $tabla.</p>";
    } else {
        echo "<p style='color:red;'>Error al insertar: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
$conn->close();
?>

</body>
</html>
