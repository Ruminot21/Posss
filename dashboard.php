<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel POS</title>
<link rel="stylesheet" href="styles.css">
<script src="app.js" defer></script>
</head>
<body>
    <header>
        <h1>💳 Sistema Punto de Venta</h1>
        <p>Usuario: <?= $user['username'] ?> | Rol: <?= $user['role'] ?></p>
        <a href="logout.php">Cerrar sesión</a>
    </header>
    <nav>
        <button onclick="mostrarSeccion('ventas')">Ventas</button>
        <?php if($user['role'] == 'admin'): ?>
        <button onclick="mostrarSeccion('inventario')">Inventario</button>
        <?php endif; ?>
        <button onclick="mostrarSeccion('reportes')">Reportes</button>
    </nav>
    <main>
        <!-- Sección Ventas -->
        <section id="ventas" class="seccion">
            <h2>Registrar Venta</h2>
            <form id="formVenta" method="POST" action="registrar_venta.php">
                <label>Producto:</label>
                <select name="product_id" required>
                    <?php
                    $res = $conn->query("SELECT * FROM products");
                    while($prod = $res->fetch_assoc()) {
                        echo "<option value='{$prod['id']}'>{$prod['name']} - Stock: {$prod['stock']}</option>";
                    }
                    ?>
                </select>
                <label>Cantidad:</label>
                <input type="number" name="quantity" min="1" required>
                <button type="submit">Vender</button>
            </form>
        </section>
        
        <!-- Sección Inventario -->
        <section id="inventario" style="display:none;">
            <h2>Inventario</h2>
            <table>
                <tr><th>Producto</th><th>Precio</th><th>Stock</th></tr>
                <?php
                $res = $conn->query("SELECT * FROM products");
                while($prod = $res->fetch_assoc()) {
                    echo "<tr><td>{$prod['name']}</td><td>\${$prod['price']}</td><td>{$prod['stock']}</td></tr>";
                }
                ?>
            </table>
        </section>
        
        <!-- Sección Reportes -->
        <section id="reportes" style="display:none;">
            <h2>Reportes</h2>
            <?php
            $res = $conn->query("SELECT SUM(total) as total_dia FROM sales WHERE DATE(date) = CURDATE()");
            $row = $res->fetch_assoc();
            echo "<p>Total ventas hoy: \$" . number_format($row['total_dia'] ?? 0, 2) . "</p>";
            ?>
        </section>
    </main>
</body>
</html>
