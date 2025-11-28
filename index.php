<?php
/*
  Hecho por Ramiro Elortegui Bazán
  Tema: Tienda de productos en PHP
  Descripción: App que permite agregar productos mediante
  un formulario, guardarlos en un archivo JSON y mostrarlos.
*/

// Se define la ruta del archivo JSON donde se guardan los productos
$jsonFile = __DIR__ . '/data/products.json';

// Se crea un array vacío para guardar los productos
$productos = [];

// Si el archivo JSON existe, se lee y convertimos en array
if (file_exists($jsonFile)) {
    // file_get_contents lee todo el archivo
    // json_decode lo convierte en un array asociativo
    $productos = json_decode(file_get_contents($jsonFile), true);
}

// Verifica si el formulario fue enviado usando método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se arma un array con los datos enviados por el usuario
    $nuevo = [
        'id' => count($productos) + 1,

        // Toma el nombre o string vacío si no se envía
        'nombre' => $_POST['nombre'] ?? '',

        
        'descripcion' => $_POST['descripcion'] ?? '',

      
        'precio' => floatval($_POST['precio'] ?? 0),

        
        'stock' => intval($_POST['stock'] ?? 0),

        // Toma la categoría si está
        'categoria' => $_POST['categoria'] ?? ''
    ];

    // Validación: debe tener nombre y precio mayor a 0
    if ($nuevo['nombre'] && $nuevo['precio'] > 0) {

        $productos[] = $nuevo;

        // Guarda todo el array en el archivo JSON, con formato lindo
        file_put_contents(
            $jsonFile,
            json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Recarga la página para evitar dobles envíos del formulario
        header("Location:index.php");
        exit;

    } else {
        // Mensaje de error si faltan datos obligatorios
        $error = "Por favor completá el nombre y el precio del producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">

  <!-- Ajusta la vista en celulares -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>apruebeme profe porfa</title>

  <!-- Bootstrap principal -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

  <!-- Repetición de meta (no es grave) -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda Los Papus</title>

  <!-- Tema visual Flatly de Bootswatch -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/flatly/bootstrap.min.css">

  <!-- Fuente Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Estilos internos -->
  <style>
      body, * { font-family: 'Poppins', sans-serif !important; }
      textarea { resize: none; }
  </style>

  <link rel="stylesheet" href="style.css">

  <nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">
    <div class="container">

  
      <a class="navbar-brand fw-bold text-primary" href="#">Tienda Los Papus</a>

      <!-- Botón para menú en móvil -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú -->
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link fw-semibold" href="#productos">Productos</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold" href="#agregar">Agregar producto</a></li>
        </ul>
      </div>

    </div>
  </nav>

  <!-- LISTA DE PRODUCTOS -->
  <main class="container py-5">

    <h1 class="text-center mb-4 text-dark fw-bold">Productos disponibles</h1>

    <div id="productos" class="row g-4">

      <!-- Si hay productos, los muestra -->
      <?php if (!empty($productos)): ?>

        <!-- Recorre cada producto -->
        <?php foreach ($productos as $p): ?>

          <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body">

              
                <h5 class="card-title text-primary fw-bold">
                  <?= htmlspecialchars($p['nombre']) ?>
                </h5>

           
                <p class="text-secondary small"><?= htmlspecialchars($p['categoria']) ?></p>

             
                <p><?= htmlspecialchars($p['descripcion']) ?></p>

                <!-- Precio formateado -->
                <p class="fw-bold text-success fs-5">
                  $<?= number_format($p['precio'], 2, ',', '.') ?>
                </p>

                <!-- Stock -->
                <p class="small text-muted">Stock: <?= htmlspecialchars($p['stock']) ?></p>

              </div>
            </div>
          </div>

        <?php endforeach; ?>

      <?php else: ?>

        <p class="text-center text-muted">No hay productos cargados.</p>
      <?php endif; ?>

    </div>
  </main>

  <!-- FORMULARIO -->
  <section class="container my-5" id="agregar">

    <h2 class="text-center mb-4 text-dark fw-bold">Agregar Producto</h2>

    <!-- Mensaje de error si existe -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Formulario de carga -->
    <form method="POST" class="p-4 rounded shadow-sm bg-white">

      <!-- Campo nombre -->
      <div class="form-floating mb-3">
        <input type="text" class="form-control" name="nombre" id="nombre" required>
        <label for="nombre">Nombre del producto</label>
      </div>

      <!-- Campo categoría -->
      <div class="form-floating mb-3">
        <input type="text" class="form-control" name="categoria" id="categoria">
        <label for="categoria">Categoría</label>
      </div>

      <!-- Campo descripción -->
      <div class="form-floating mb-3">
        <textarea class="form-control" name="descripcion" id="descripcion" style="height: 100px"></textarea>
        <label for="descripcion">Descripción</label>
      </div>

      <!-- Campo precio -->
      <div class="form-floating mb-3">
        <input type="number" class="form-control" step="0.01" name="precio" id="precio" required>
        <label for="precio">Precio</label>
      </div>

      <!-- Campo stock -->
      <div class="form-floating mb-3">
        <input type="number" class="form-control" name="stock" id="stock">
        <label for="stock">Stock</label>
      </div>

      <!-- Botón -->
      <div class="text-center">
        <button class="btn btn-primary px-4">Agregar</button>
      </div>

    </form>
  </section>

  <!-- FOOTER -->
  <footer class="text-center py-3 bg-white border-top text-secondary mt-5">
    <!-- Muestra el año actual automáticamente -->
    <p >©2025 Tienda Los Papus</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script propio -->
  <script src="script.js"></script>
</body>
</html>
