<?php
require_once 'conexion.php';
require_once 'productos.class.php';

// Evitar error de CORS
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *"); //GET, POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Expose-Headers: *");
header("Access-Control-Allow-Private-Network: true");

// Función para responder con JSON y código de estado HTTP
function respuestaJson($statusCode, $response)
{
    http_response_code($statusCode);
    echo json_encode($response);
    exit();
}

/// Verificar el método de la solicitud
/*if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $productos = new productos(); // productos hereda de conexion, entonces automaticamente creo una conexion a la base
    $datos = $productos->listarProductosSinPaginar(); // Listar todos los productos json
    // Retornar los datos
    respuestaJson(200, $datos);
}*/


if ($_SERVER['REQUEST_METHOD'] === "GET"){
        #producto por id
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $productos = new productos();
            $datos = $productos->obtenerProducto($id);
            // Retornar los datos
            respuestaJson(200, $datos);
            
        #producto por una parte del nombre 
        }else if (isset($_GET['buscar'])){
            $nombre = $_GET['buscar'];
            $productos = new productos();
            $datos = $productos->buscarProducto($nombre);
          // Retornar los datos
           respuestaJson(200, $datos);
           
       
        }else{
            //quiere todas los productos sin paginar
            $productos = new productos(); // productos hereda de conexion
            $datos = $productos->listarProductosSinPaginar(); // Listar todos los productos json
    // Retornar los datos
           respuestaJson(200, $datos);
    
        }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Insertar un nuevo producto
    $productos = new productos(); // conectate a la base automaticamente
    $postBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->insertarProducto($postBody);
    // Retornar la respuesta
    respuestaJson(201, 'Producto insertado correctamente');
}

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    // Actualizar un producto existente
    $productos = new productos(); // conectate a la base automaticamente
    $putBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->actualizarProducto($putBody);
    // Retornar la respuesta
    respuestaJson(200, $datosArray);
}

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    // Eliminar un producto existente
    $productos = new productos(); // conectate a la base automaticamente
    $deleteBody = file_get_contents("php://input"); // levanta el json del body
    $datosArray = $productos->eliminarProducto($deleteBody);
    // Retornar la respuesta
    respuestaJson(200, $datosArray);
}
