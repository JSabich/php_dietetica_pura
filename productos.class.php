
<?php
//tengo que agregar el campo 5 que se llama descripción y el campo 7 id_categoria
require_once 'conexion.php';
// hereda de conexion entonces puede usar los methods dentro de la clase padre
class productos extends conexion
{

    // atributo para devolver una respuesta al front
    public $response = ['status' => "ok", "result" => array()];

    # inserta una pelicula en la base de datos
    public function insertarProducto($json)
    {

        #convertimos el json a un array asociativo
        $datos = json_decode($json, true);
        #si no estan los datos requeridos
        if (!isset($datos['nombre']) ||  !isset($datos['en_stock']) || !isset($datos['precio']) || !isset($datos['descripcion']) || !isset($datos['imagen']) || !isset($datos['id_categoria'])) {
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "400",
                "error_msg" => "Datos enviados incompletos o con formato incorrecto"
            );
            #devolvemos un bad request
            return $this->response;
        } else {
            $nombre = $datos['nombre'];
            $en_stock = $datos['en_stock'];
            $precio = $datos['precio'];
            $descripcion = $datos['descripcion'];
            $imagen = $datos['imagen'];
            $id_categoria = $datos['id_categoria'];
            // codigo para levantar la imagen guardarla en el server y guardar la ruta en la base de datos

            // id_producto autonumerico
            $query = "INSERT INTO productos (id_producto, nombre, en_stock, precio, descripcion, imagen, id_categoria) VALUES (NULL, '$nombre','$en_stock', '$precio','$descripcion', '$imagen', '$id_categoria')";
            // este method es de la clase conexion, la clase producto hereda de conexion, asi que puede usar los methods y atributos protected o public         
            $datos = $this->nonQueryId($query); // retorna el id del registro insertado
            if ($datos) { // si tiene un id
                // le devuelve al front un id
                $respuesta = $this->response;
                $respuesta["result"] = array(
                    "id" => $datos
                );
                return $respuesta;
            } else {
                // si no tiene un id
                $respuesta = $this->response;
                $respuesta['status'] = "error";
                $respuesta['result'] = array(
                    "error_id" => "500",
                    "error_msg" => "Error interno del servidor"
                );
                #devolvemos un 500
                return $respuesta;
            }
        }
    }

    public function listarProductosSinPaginar()
    {
        $query = "SELECT * FROM productos";
        $datos = $this->obtenerDatos($query); // de la clase conexion
        return $datos;
    }

    // para listar todas las productos y en cada pagina vendran 16 productos
    public function listarProductos($pagina)
    {
        /*$cantidad es el número de registros que deseas mostrar por página (16 en este caso).
              $inicio es el desplazamiento, que se calcula como (pagina - 1) * cantidad. 
              Esto asegura que cuando estés en la página 1, el offset será 0, 
              para la página 2 el offset será 16, y así sucesivamente.*/

        $inicio = 0;
        $cantidad = 16;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT * FROM productos LIMIT $inicio, $cantidad";
        $datos = $this->obtenerDatos($query);
        return $datos;
    }



    // para buscar una pelicula por su id
    public function obtenerProducto($id)
    {
        $query = "SELECT * FROM productos WHERE id_producto = '$id'";
        $datos = $this->obtenerDatos($query);
        if ($datos) {
            return $datos;
        } else {
            return 0;
        }
    }
    // para buscar una pelicula por una parte de su nombre
    public function buscarProducto($nombre)
    {
        // pasar a minuscula el nombre y el campo de la base tambien
        $nombre = strtolower($nombre);
        $query = "SELECT * FROM productos WHERE LOWER(nombre) LIKE '%$nombre%'";
        $datos = $this->obtenerDatos($query);
        if ($datos) {
            return $datos;
        } else {
            return 0;
        }
    }

    #actualiza una pelicula en la base de datos
    public function actualizarProducto($json)
    {

        #convertimos el json a un array asociativo
        $datos = json_decode($json, true);


        #si no estan los datos requeridos
        if (!isset($datos['id_producto']) || !isset($datos['nombre']) || !isset($datos['en_stock']) || !isset($datos['precio']) || !isset($datos['descripcion']) || !isset($datos['imagen']) || !isset($datos['id_categoria'])) {
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "400",
                "error_msg" => "Datos enviados incompletos o con formato incorrecto"
            );
            #devolvemos un bad request
            return $this->response;
        } else {
            $id = $datos['id_producto'];
            $nombre = $datos['nombre'];
            $en_stock = $datos['en_stock'];
            $precio = $datos['precio'];
            $descripcion = $datos['descripcion'];
            $imagen = $datos['imagen'];
            $id_categoria = $datos['id_categoria'];
            // codigo para levantar la imagen guardarla en el server y guardar la ruta en la base de datos
            // faltan validaciones para la fecha de lanzamiento 
            $query = "UPDATE productos SET nombre = '$nombre', en_stock = '$en_stock', precio = '$precio', descripcion = '$descripcion', imagen = '$imagen', id_categoria = '$id_categoria', WHERE id_producto = '$id'";
            $datos = $this->nonQuery($query);
            if ($datos >= 1) {
                $respuesta = $this->response;
                $respuesta["result"] = array(
                    "mensaje" => "Registro actualizado correctamente"
                );
                return $respuesta;
            } else {
                $this->response['status'] = "error";
                $this->response['result'] = array(
                    "error_id" => "500",
                    "error_msg" => "Error interno del servidor"
                );
                #devolvemos un 500
                return $this->response;
            }
        }
    }
    #eliminar una pelicula por su id de la base de datos
    public function eliminarProducto($json)
    {

        #convertimos el json a un array asociativo
        $datos = json_decode($json, true);
        if (!isset($datos['id_producto'])) {
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "400",
                "error_msg" => "Datos enviados incompletos o con formato incorrecto"
            );
            #devolvemos un bad request
            return $this->response;
        } else {
            $id_producto = $datos['id_producto'];
            $query = "DELETE FROM productos WHERE id_producto = '$id_producto'";
            $datos = $this->nonQuery($query);
            if ($datos >= 1) {
                $respuesta = $this->response;
                $respuesta["result"] = array(
                    "mensaje" => "Registro eliminado correctamente"
                );
                return $respuesta;
            } else {
                $this->response['status'] = "error";
                $this->response['result'] = array(
                    "error_id" => "500",
                    "error_msg" => "Error interno del servidor"
                );
                #devolvemos un 500
                return $this->response;
            }
        }
    }
}

?>