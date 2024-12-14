<?php
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
            background-color: #f4f4f4; 
            color: #333; 
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        button {
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF; 
            color: white; 
            transition: background-color 0.3s; 
        }
        button:hover {
            background-color: #0056b3; 
        }
        img {
            margin-top: 30px;
            width: 300px; 
            height: auto; 
        }
    </style>
</head>
<body>

    <h1>Communication with E-commerce platforms through API's</h1>
    <br>
    <button onclick="window.location.href='Prueva/clientes.php'">Prueba Customer</button>
    <button onclick="window.location.href='PruevaP/productos.php'"> Prueba Product </button>
    <button onclick="window.location.href='carriers/carriers.php'">Prueba Endpoint restringido (carriers)</button>
<br>
    <img src="logo.png" alt="Descripción de la imagen"> 

</body>
</html>
