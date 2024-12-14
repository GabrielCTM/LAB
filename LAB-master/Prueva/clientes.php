<?php 
$baseUrl = 'http://localhost:8080/api/customers'; 
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

// Función para obtener detalles de un cliente específico
function getCustomer($baseUrl, $wsKey, $id) {
    $url = "$baseUrl/$id?ws_key=$wsKey";
    $xmlResponse = apiRequest($url);

    if ($xmlResponse) {
        $response = simplexml_load_string($xmlResponse);
        if (isset($response->customer)) {
            $customer = $response->customer;

            $id = (string)$customer->id;
            $firstname = (string)$customer->firstname;
            $lastname = (string)$customer->lastname;
            $email = (string)$customer->email;
            $active = (string)$customer->active;

            echo "( ID: $id / ";
            echo " Nombre: " . trim($firstname . ' ' . $lastname) . " / ";
            echo " Email: $email / ";
            echo " Activo: " . ($active == '1' ? 'Sí' : 'No') . ")<br><br>";
        }
    }
}

// Función para listar todos los clientes
function listCustomers($baseUrl, $wsKey) {
    for ($clienteId = 1; $clienteId <= 100; $clienteId++) {
        getCustomer($baseUrl, $wsKey, $clienteId);
    }
}

// Función para crear un nuevo cliente
function createCustomer($baseUrl, $wsKey, $firstname, $lastname, $email, $passwd) {
    if (empty($passwd)) {
        echo "Error: La contraseña no puede estar vacía.<br>";
        return;
    }

    $passwd = md5($passwd);

    $data = <<<XML
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <customer>
    <firstname><![CDATA[$firstname]]></firstname>
    <lastname><![CDATA[$lastname]]></lastname>
    <email><![CDATA[$email]]></email>
    <passwd><![CDATA[$passwd]]></passwd>
    <id_lang>1</id_lang>
    <id_default_group>3</id_default_group>
    <active>1</active>
  </customer>
</prestashop>
XML;

    $url = "$baseUrl?ws_key=$wsKey";
    $xmlResponse = apiRequest($url, 'POST', $data);

    if ($xmlResponse) {
        echo "Cliente creado exitosamente.<br>";
    } else {
        echo "Error al crear el cliente.<br>";
    }
}

// Función para eliminar un cliente
function deleteCustomer($baseUrl, $wsKey, $id) {
    if (empty($id)) {
        echo "Error: El ID del cliente no puede estar vacío.<br>";
        return;
    }
    
    $url = "$baseUrl/$id?ws_key=$wsKey";
    $xmlResponse = apiRequest($url, 'DELETE');
    if ($xmlResponse) {
        echo "Cliente eliminado exitosamente.<br>";
    } else {
        echo "Cliente eliminado exitosamente.<br>";
    }
}

// Función para cargar todos los IDs de los clientes
function loadAllCustomerIDs($baseUrl, $wsKey) {
    $url = "$baseUrl?ws_key=$wsKey";
    $xmlResponse = apiRequest($url);

    if ($xmlResponse) {
        $response = simplexml_load_string($xmlResponse);
        
        
        if (isset($response->customers) && isset($response->customers->customer)) {
            $customerIDs = [];
            foreach ($response->customers->customer as $customer) {
                $customerIDs[] = (string)$customer['id'];
            }
            return $customerIDs; 
        } else {
            echo "No se encontraron clientes o la estructura de la respuesta es incorrecta.<br>";
            return [];
        }
    } else {
        echo "Error al obtener la lista de clientes.<br>";
        return [];
    }
}

// Función para imprimir todos los clientes 
function printCustomers($baseUrl, $wsKey) {
    $customerIDs = loadAllCustomerIDs($baseUrl, $wsKey); 

    if (count($customerIDs) > 0) {
        foreach ($customerIDs as $id) {
            getCustomer($baseUrl, $wsKey, $id); 
        }
    } else {
        echo "No hay clientes para mostrar.<br>";
    }
}



// Metodos -----------------------------------------------------------------------------------------------------------------------

echo "<br>---------------------------------------------------------------- RESULTADO Y USO DE LOS ENDPOINTS DE LA API ----------------------------------------------------------------<br><br>";

// Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['numero'])) {
        $numero = $_POST['numero'];
        echo "--- Mostrar cliente específico ---<br><br>";
        getCustomer($baseUrl, $wsKey, $numero);
    }

    if (isset($_POST['numero2'])) {
        $numero2 = $_POST['numero2'];
        echo "--- Eliminar cliente específico ---<br><br>";
        deleteCustomer($baseUrl, $wsKey, $numero2);
    }

    if (isset($_POST['create_customer'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        echo "--- Creación de un nuevo cliente ---<br><br>";
        createCustomer($baseUrl, $wsKey, $firstname, $lastname, $email, $passwd);
    }

    if (isset($_POST['list_customers'])) {
        echo "--- Lista de clientes ---<br><br>";
        printCustomers($baseUrl, $wsKey);
    }

    
}

echo "<br>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br><br>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #666;
            margin-top: 30px;
        }
        
        form {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Gestión de Clientes</h1>

    <form method="post">
        <h2>Ver Cliente</h2>
        <input type="text" name="numero" placeholder="ID del cliente" required>
        <button type="submit">Mostrar</button>
    </form>

    <form method="post">
        <h2>Eliminar Cliente</h2>
        <input type="text" name="numero2" placeholder="ID del cliente" required>
        <button type="submit">Eliminar</button>
    </form>

    <form method="post">
        <h2>Crear Cliente</h2>
        <input type="text" name="firstname" placeholder="Nombre" required>
        <input type="text" name="lastname" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="passwd" placeholder="Contraseña" required>
        <button type="submit" name="create_customer">Crear</button>
    </form>

    <form method="post">
        <h2>Listar Clientes</h2>
        <button type="submit" name="list_customers">Listar</button>
    </form>

    


    <!-- Botón para regresar al index.php -->
    <button onclick="window.location.href='../index.php'">Regresar a la Página Principal</button>

</body>
</html>
