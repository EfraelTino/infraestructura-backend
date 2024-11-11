<?php

class Product
{

    private $db;


    public function __construct($db)
    {
        $this->db = $db;
    }

    // Obtener todos los productos
    public function mostrarProductos()
    {
        $stmt = $this->db->prepare("SELECT * FROM productos P JOIN sub_categoria S ON P.id_subcategoria = S.id JOIN categoria C ON S.id_categoria = C.id WHERE activo = 1 ORDER BY is_premium DESC, RAND()");
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productos;
    }

    public function mostrarCategoria()
    {
        $stmt = $this->db->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productos;
    }
    public function mostrarSubCategoria()
    {
        $stmt = $this->db->prepare("SELECT * FROM sub_categoria");
        $stmt->execute();
        $subcategoria = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $subcategoria;
    }

    // filtro de productos
    public function getProductos($filters = [])
    {
        $query = "SELECT DISTINCT p.*, c.nombre_cat, sc.nombre_subcat 
        FROM productos p
        JOIN sub_categoria sc ON p.id_subcategoria = sc.id
        JOIN categoria c ON sc.id_categoria = c.id
        WHERE 1=1 AND p.activo = 1";
        $params = [];
        $categorias = $filters['categorias'];
        $subcategorias = $filters['subcategorias'];
        // if (!empty($filters['categorias'])) {
        if (!empty($categorias[0])) {

            $placeholders = implode(',', array_fill(0, count($filters['categorias']), '?'));
            $query .= " AND c.id IN ($placeholders)";
            $params = array_merge($params, $filters['categorias']);
        }

        if (!empty($subcategorias[0])) {
            $placeholders = implode(',', array_fill(0, count($filters['subcategorias']), '?'));
            $query .= " AND sc.id IN ($placeholders)";
            $params = array_merge($params, $filters['subcategorias']);
        }




        // Ejecutar la consulta y devolver los resultados
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
        // // Imprime los resultados obtenidos de la base de datos
        // var_dump($resultado);

    }

    //Obtener producto x id
    public function buscarProductoPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : false;
    }
    public function actualizarEstadoProducto($id, $estado)
    {
        $actualizar = intval($estado);
        $stmt = $this->db->prepare("UPDATE productos SET activo=? WHERE id_producto =?");
        $stmt->execute([$actualizar, $id]);
        return $stmt->rowCount();
    }
    public function productoPremium($id, $premium)
    {
        $actualizar = intval($premium);
        $stmt = $this->db->prepare("UPDATE productos SET is_premium=? WHERE id_producto =?");
        $stmt->execute([$actualizar, $id]);
        return $stmt->rowCount();
    }
    // Buscar un producto cualquiera
    public function buscarProducto($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE nombre LIKE ? AND activo = 1");
        $stmt->execute(["%$name%"]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productos;
    }

    // Obtener detalle de un producto
    public function detalleProducto($data)
    {
        $stmt = $this->db->prepare("SELECT 
        ps.id_producto AS id_producto, 
        ps.imagen, 
        ps.nombre, 
        ps.descripcion, 
        ps.calidad, 
        ps.unidad_medida, 
        ps.id_proveedor, 
        ps.id_subcategoria,
        ps.precio_base, 
        ps.fecha_creacion,
        ps.contacto,
        CONCAT('[', 
            (SELECT 
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'precio', pr.precio, 
                        'contiene', pr.contiene, 
                        'desde_cantidad', pr.desde_cantidad, 
                        'hasta_candidad', pr.hasta_candidad
                    )
                ) 
            FROM precios pr 
            WHERE ps.id_producto = pr.id_producto 
            ORDER BY pr.id
            ), 
            ']'
        ) AS precios
    FROM 
        productos ps
    JOIN 
        sub_categoria sc ON ps.id_subcategoria = sc.id
    JOIN 
        categoria c ON sc.id_categoria = c.id
    WHERE 
         ps.id_producto = ?");
        $stmt->execute([$data['id']]);

        // retorno un resultado
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function detalleProductoUsuario($idProducto, $iduser)
    {
        $stmt = $this->db->prepare("SELECT 
        ps.id_producto AS id_producto, 
        ps.imagen, 
        ps.nombre, 
        ps.descripcion, 
        ps.calidad, 
        ps.unidad_medida, 
        ps.id_proveedor, 
        ps.id_subcategoria,
        ps.precio_base, 
        ps.fecha_creacion,
        ps.contacto,
        CONCAT('[', 
            (SELECT 
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'id_precios', pr.id,
                        'precio', pr.precio, 
                        'contiene', pr.contiene, 
                        'desde_cantidad', pr.desde_cantidad, 
                        'hasta_candidad', pr.hasta_candidad
                    )
                ) 
            FROM precios pr 
            WHERE ps.id_producto = pr.id_producto 
            ORDER BY pr.id
            ), 
            ']'
        ) AS precios
    FROM 
        productos ps
    JOIN 
        sub_categoria sc ON ps.id_subcategoria = sc.id
    JOIN 
        categoria c ON sc.id_categoria = c.id
    WHERE 
         ps.id_producto = ? AND id_proveedor =?");
        $stmt->execute([$idProducto, $iduser]);

        // retorno un resultado
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Mostrar el producto de un usuario
    public function productoDeUsuario($id)
    {
        // $stmt = $this->db->prepare("SELECT * from productos  JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id where id_proveedor = ?");
        $stmt = $this->db->prepare("SELECT * from productos  JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id where id_proveedor = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function productoCategoria($categoria)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos 
            JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id 
            JOIN categoria ON sub_categoria.id_categoria = categoria.id  
            WHERE categoria.nombre_cat = ?");
        $stmt->execute([$categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Solo se llama a fetch una vez
    }
    public function productoSubcategoria($categoria, $subcategoria)
    {
        $stmt = $this->db->prepare("SELECT productos.*, sub_categoria.*, categoria.* FROM productos 
            JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id 
            JOIN categoria ON categoria.id = sub_categoria.id_categoria 
            WHERE categoria.nombre_cat = ? AND sub_categoria.nombre_subcat = ?");
        $stmt->execute([$categoria, $subcategoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // categoria y subcategorias
    public function obetenerSubcategoria($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM sub_categoria WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchProductUser($id, $search)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id WHERE id_proveedor = ? AND nombre LIKE ?");
        $search = "%" . $search . "%";  // Agrega los comodines para la búsqueda parcial
        $stmt->execute([$id, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // insert producto
    public function inserProduct($data)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s', time());
        $datepositivo = 1;
        $stmt = $this->db->prepare(
            "INSERT INTO productos (imagen, nombre, descripcion,  calidad, id_proveedor, id_subcategoria , fecha_creacion, unidad_medida, precio_base, contacto,activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $data['foto'],
            $data['producto'],
            $data['descripcion'],
            $data['calidad'],
            $data['id_usuario'],
            $data['clasificacion'],
            $fechaActual,
            $data['medidas'],
            $data['base'],
            $data['telefono'],
            $datepositivo
        ]);

        // Verifica si la inserción fue exitosa
        if ($success) {
            // Obtén el ID del último registro insertado
            $lastInsertId = $this->db->lastInsertId();
            return $lastInsertId;
        } else {
            return false; // O maneja el error de otra manera
        }
    }
    public function insertCategoria($data)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s', time());
        $datepositivo = 1;
        $stmt = $this->db->prepare(
            "INSERT INTO categoria (nombre_cat, foto_cat, fecha_creacion,  cat_estado) VALUES (?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $data['nombre'],
            $data['foto'],
            $fechaActual,
            $datepositivo
        ]);

        // Verifica si la inserción fue exitosa
        if ($success) {
            // Obtén el ID del último registro insertado
            $lastInsertId = $this->db->lastInsertId();
            return $lastInsertId;
        } else {
            return false; // O maneja el error de otra manera
        }
    }
    public function insertSubCategoria($data)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s', time());
        $datepositivo = 1;
        $stmt = $this->db->prepare(
            "INSERT INTO sub_categoria (nombre_subcat, foto_subcat, fecha_creacion,  id_categoria , estado) VALUES (?, ?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $data['nombre'],
            $data['foto'],
            $fechaActual,
            intval($data['categoria']),
            $datepositivo
        ]);

        // Verifica si la inserción fue exitosa
        if ($success) {
            // Obtén el ID del último registro insertado
            $lastInsertId = $this->db->lastInsertId();
            return $lastInsertId;
        } else {
            return false; // O maneja el error de otra manera
        }
    }
    public function insertPrecios($precios, $productID)
    {
        try {
            // Inicia una transacción
            $this->db->beginTransaction();

            // Prepara la consulta SQL
            $stmt = $this->db->prepare(
                "INSERT INTO precios (precio, contiene, desde_cantidad, hasta_candidad, id_producto) 
                VALUES (?, ?, ?, ?, ?)"
            );

            // Recorre el array de precios y ejecuta la consulta para cada uno
            foreach ($precios as $precio) {
                $stmt->execute([
                    $precio['preciosoles'],
                    $precio['contiene'],
                    $precio['preciodesde'],
                    $precio['preciohasta'],
                    $productID
                ]);
            }

            // Confirma la transacción
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            // Revierte la transacción en caso de error
            $this->db->rollBack();
            return false; // O maneja el error de otra manera
        }
    }
    public function updateProduct($data, $productID)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s', time());

        $stmt = $this->db->prepare(
            "UPDATE productos 
         SET imagen = ?, nombre = ?, descripcion = ?, calidad = ?, 
             id_proveedor = ?, id_subcategoria = ?, fecha_creacion = ?, 
             unidad_medida = ?, precio_base = ?, contacto = ?
         WHERE id_producto = ?"
        );

        $success = $stmt->execute([
            $data['foto'],
            $data['producto'],
            $data['descripcion'],
            $data['calidad'],
            $data['id_usuario'],
            $data['clasificacion'],
            $fechaActual,
            $data['medidas'],
            $data['base'],
            $data['telefono'],
            $productID
        ]);

        return $success ? $productID : false;
    }
    public function updateFoto($data, $productID)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d H:i:s', time());

        $stmt = $this->db->prepare(
            "UPDATE productos 
         SET imagen = ?
         WHERE id_producto = ?"
        );

        $success = $stmt->execute([
            $data['foto'],
            $productID
        ]);

        return $success ? $productID : false;
    }
    public function updatePrecios($precios, $productID)
    {
        $productID = intval($productID);

        try {
            // Inicia una transacción
            $this->db->beginTransaction();

            // Prepara la consulta SQL para actualizar
            $stmt = $this->db->prepare(
                "UPDATE precios 
             SET precio = ?, contiene = ?, desde_cantidad = ?, hasta_candidad = ? 
             WHERE id_producto = ? AND id = ?"
            );

            // Recorre el array de precios y ejecuta la consulta para cada uno
            foreach ($precios as $precio) {
                $stmt->execute([
                    $precio['preciosoles'],
                    $precio['contiene'],
                    $precio['preciodesde'],
                    $precio['preciohasta'],
                    $productID,
                    $precio['id_precios']  // Asegúrate de que `id_precio` esté en `$precio`
                ]);
            }

            // Confirma la transacción
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            // Revierte la transacción en caso de error
            $this->db->rollBack();
            return $e;
        }
    }

    public  function obtenerProductoPorCategoria($data)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos JOIN sub_categoria ON productos.id_subcategoria = sub_categoria.id JOIN categoria ON sub_categoria.id_categoria = categoria.id  WHERE categoria.nombre_cat  = ? AND productos.activo = 1 ORDER BY RAND()");
        $stmt->execute([$data]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
