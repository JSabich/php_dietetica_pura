<?php
require_once 'conexion.php';
require_once 'productos.class.php';

// Evitar error de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *"); //GET, POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type"); //json

// Función para responder con JSON y código de estado HTTP
function respuestaJson($statusCode, $response)
{
    http_response_code($statusCode);
    echo json_encode($response);
    exit();
}

/// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $productos = new Productos(); // productos hereda de conexion, entonces automaticamente creo una conexion a la base
    $datos = $productos->listarProductosSinPaginar(); // Listar todos los productos json
    // Retornar los datos
    respuestaJson(200, $datos);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Insertar un nuevo producto
    $productos = new Productos(); // conectate a la base automaticamente
    $postBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->insertarProducto($postBody);
    // Retornar la respuesta
    respuestaJson(201, 'Producto insertado correctamente');
}

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    // Actualizar un producto existente
    $productos = new Productos(); // conectate a la base automaticamente
    $putBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->actualizarProducto($putBody);
    // Retornar la respuesta
    respuestaJson(200, $datosArray);
}

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    // Eliminar un producto existente
    $productos = new Productos(); // conectate a la base automaticamente
    $deleteBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->eliminarProducto($deleteBody);
    // Retornar la respuesta
    respuestaJson(200, $datosArray);
}
