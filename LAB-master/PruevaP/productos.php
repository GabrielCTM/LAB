<?php 
$baseUrl = 'http://localhost:8080/api/products'; 
$wsKey = 'QS9EUMM54DL74P7YIHFIS6V53S297V7I'; 

// Función para hacer la solicitud a la API
function apiRequest($url, $method = 'GET', $data = null) {
    $options = [
        'http' => [
            'header' => "Content-Type: application/xml\r\n",
            'method' => $method,
            'content' => $data,
            'ignore_errors' => true
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "Error en la solicitud a la API: " . print_r($http_response_header, true) . "\n";
    }

    return $response;
}

// Función para obtener detalles de un producto específico
function getProduct($baseUrl, $wsKey, $id) {
    $url = "$baseUrl/$id?ws_key=$wsKey";
    $xmlResponse = apiRequest($url);

    if ($xmlResponse) {
        $response = simplexml_load_string($xmlResponse);
        $product = $response->product;

        if ($product) {
            $id = (string)$product->id;
            $name = (string)$product->name->language[0];
            $price = number_format((float)$product->price, 2, '.', '');
            $quantity = (string)$product->quantity;
            $active = (string)$product->active;
            $description = (string)$product->description->language[0];

            echo "<div class='product-details'>";
            echo "<h3>ID: $id</h3>";
            echo "<p><strong>Nombre:</strong> $name</p>";
            echo "<p><strong>Precio:</strong> $$price</p>";
            echo "<p><strong>Cantidad:</strong> $quantity</p>";
            echo "<p><strong>Activo:</strong> " . ($active == '1' ? 'Sí' : 'No') . "</p>";
            echo "<p><strong>Descripción:</strong><br>$description</p>";
            echo "</div>";
        } else {
            echo "Producto no encontrado.<br>";
        }
    } else {
        echo "Error al obtener detalles del producto.<br>";
    }
}

// Función para listar todos los productos
function listProducts($baseUrl, $wsKey) {
    $url = "$baseUrl?ws_key=$wsKey";
    $xmlResponse = apiRequest($url);

    if ($xmlResponse) {
        $response = simplexml_load_string($xmlResponse);

        if (isset($response->products) && isset($response->products->product)) {
            foreach ($response->products->product as $product) {
                getProduct($baseUrl, $wsKey, (string)$product['id']);
                echo "<br>"; 
            }
        } else {
            echo "No se encontraron productos o la estructura de la respuesta es incorrecta.<br>";
        }
    } else {
        echo "Error al obtener la lista de productos.<br>";
    }
}

// Función para eliminar un producto
function deleteProduct($baseUrl, $wsKey, $id) {
    $url = "$baseUrl/$id?ws_key=$wsKey";
    $response = apiRequest($url, 'DELETE');

    if ($response) {
        echo "Producto con ID $id eliminado exitosamente.<br>";
    } else {
        echo "Producto con ID $id eliminado exitosamente.<br>";
    }
}

// Formulario para listar productos
if (isset($_POST['listar'])) {
    echo "<h2>Lista de Productos</h2>";
    listProducts($baseUrl, $wsKey);
}

// Formulario para buscar producto específico
if (isset($_POST['buscar'])) {
    $productId = $_POST['product_id'];
    echo "<h2>Detalles del Producto ID: $productId</h2>";
    getProduct($baseUrl, $wsKey, $productId);
}

// Formulario para eliminar un producto
if (isset($_POST['eliminar'])) {
    $productId = $_POST['product_id'];
    echo "<h2>Eliminando Producto ID: $productId</h2>";
    deleteProduct($baseUrl, $wsKey, $productId);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #007bff;
        }
        h2 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #ffffff;
        }
        input[type="number"], button {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #007bff;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .product-details {
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 15px;
            background-color: #e9ecef;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Gestión de Productos</h1>

    <form method="post">
        <button type="submit" name="listar">Listar Productos</button>
    </form>

    <form method="post">
        <h2>Buscar Producto</h2>
        <input type="number" name="product_id" placeholder="ID del Producto" required>
        <button type="submit" name="buscar">Buscar</button>
    </form>

    <form method="post">
        <h2>Eliminar Producto</h2>
        <input type="number" name="product_id" placeholder="ID del Producto" required>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>

    <!-- Botón para regresar al index.php -->
    <button onclick="window.location.href='../index.php'">Regresar a la Página Principal</button>
</body>
</html>
