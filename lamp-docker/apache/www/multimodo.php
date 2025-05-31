<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion = new mysqli("mysql", "kali", "kali", "Inventario");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT * FROM MULTIMODO");
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MULTIMODO Inventario</title>
    <style>
        .container { margin-top: 20px; }
        .cabecera { background-color: beige; }
        .cabecera thead { font-size: 30px; word-spacing: 25px; }
        .cabecera tr { font-size: 15px; }
        .cabecera td { padding: 10px; text-align: center; }
        .cabecera thead td { color: black; }
    </style>
</head>
<body>
    <div class="container">
        <div class="cabecera">
            <table cellspacing="0" border="1" width="100%">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>CABLEADO</td>
                        <td>CANTIDAD</td>
                        <td>TAMAÑO</td>
                        <td>COLOR</td>
                        <td>CONECTOR</td>
                        <td>Cambiar información</td>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $fila['ID'] ?></td>
                        <td><?= $fila['CABLEADO'] ?></td>
                        <td><?= $fila['CANTIDAD'] ?></td>
                        <td><?= $fila['TAMANO'] ?></td>
                        <td><?= $fila['COLOR'] ?></td>
                        <td><?= $fila['CONECTOR'] ?></td>
                        <td>
                            <form action="modificar.php" method="post">
                                <input type="hidden" name="ID" value="<?= $fila['ID'] ?>">
                                <input type="submit" value="Modificar">
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="7">
                            <form action="añadir.php" method="post">
                                <input type="submit" value="Añadir" style="width: 30%; height: 50px;">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
</body>
</html>

<?php $conexion->close(); ?>
