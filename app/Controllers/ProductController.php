<?php


require_once './app/Models/Product.php';
require_once './config/Utils.php';

class   ProductController
{
    private $productModel;
    private $utils;
    public function __construct($db)
    {
        $this->productModel = new  Product($db);
        $this->utils = new Utils();
    }
    public function mostrarProductos()
    {
        try {
            $productos = $this->productModel->mostrarProductos();

            // Aquí se envía un array con un key "data" que contiene los productos
            $this->utils->jsonResponse(200, ['message' => $productos, 'success'=>true]);
        } catch (\Throwable $th) {
            $this->utils->jsonResponse(200, ['message' => $th->getMessage(), 'success'=>false]);
        }
    }
    public  function mostrarSubcategorias()
    {
        try {
            $subcategoria = $this->productModel->mostrarSubCategoria();

            // Aquí se envía un array con un key "data" que contiene los productos
            $this->utils->jsonResponse(200, ['data' => $subcategoria]);
        } catch (\Throwable $th) {
            $this->utils->jsonResponse(500, ['error' => $th->getMessage()]);
        }
    }
    public function crearProducto($request)
    {
        $data = $request['body'];
        $iduser = $data['id_usuario'];
        $id_usuario = $data['id_usuario'];
        $clasificacion = intval($data['clasificacion']);
        $precios = $data['precios'];



        // Verifica si la subcategoría existe
        $subcategoria = $this->productModel->obetenerSubcategoria($clasificacion);
        if (!$subcategoria) {
            return $this->utils->jsonResponse(200, ['message' => 'Subcategoría no encontrada']);
        }

        // Verifica si el usuario existe
        $finduser = $this->productModel->findUserById($iduser);
        if (!$finduser) {
            return $this->utils->jsonResponse(200, ['message' => 'Usuario no encontrado', 'success' => false] );
        }

        // Inserta el producto
        $insertdata = $this->productModel->inserProduct($data);
        if (!$insertdata) {
            return $this->utils->jsonResponse(200, ['message' => 'Error al crear producto' ]);
        }

        // Inserta los precios
        $insertprecio = $this->productModel->insertPrecios($precios, $insertdata);
        // if (!$insertprecio) {
        // return $this->utils->jsonResponse(500, ['error' => 'Ocurrió un error al crear el producto']);
        // } else {
        return $this->utils->jsonResponse(200, ['message' => 'Producto creado de manera satisfactoria' , 'success' => true]);
        // }
    }
    public function creatCategoria($request)
    {
        $data = $request['body'];


        // Inserta el producto
        $insertdata = $this->productModel->insertCategoria($data);
        if (!$insertdata) {
            return $this->utils->jsonResponse(200, ['message' => 'Error al crear categoría', 'success' => false]);
        }

        // if (!$insertprecio) {
        // return $this->utils->jsonResponse(500, ['error' => 'Ocurrió un error al crear el producto']);
        // } else {
        return $this->utils->jsonResponse(200, ['message' => 'Categoria se a creado', 'success'=>true]);
        // }
    }    
    
    public function crearSubcategoria($request)
    {
        $data = $request['body'];


        // Inserta el producto
        $insertdata = $this->productModel->insertSubCategoria($data);
        if (!$insertdata) {
            return $this->utils->jsonResponse(200, ['message' => 'Error al crear categoría', 'success' => false]);
        }

        // if (!$insertprecio) {
        // return $this->utils->jsonResponse(500, ['error' => 'Ocurrió un error al crear el producto']);
        // } else {
        return $this->utils->jsonResponse(200, ['message' => 'Subcategoria se ha creado', 'success'=>true]);
    }
    public function productoPorCategoria($request){
        $data = $request['body'];
        try {
            $productos = $this->productModel->obtenerProductoPorCategoria($data['categoria']);
            if (empty($productos)) {
                return $this->utils->jsonResponse(['status' => 200], 
                ['message' => 'No se encontraron productos de esta categoría', 'success' => false]);
            }
            return $this->utils->jsonResponse(['status' => 200], ['message' => $productos , 'success' => true]);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(['status' => 500], ['message' => $th->getMessage(), 'success' => false]);
        }
    
    }
    public function actualizarProducto($request)
    {
        $data = $request['body'];
        $producto = $this->productModel->buscarProductoPorId($data['idProducto']);

        if (!$producto) {
            return $this->utils->jsonResponse(404, ['message' => 'Producto no encontrado']);
        }

        // Compara el estado actual con el nuevo estado
        if ($producto['activo'] == $data['estado']) {
            return $this->utils->jsonResponse(200, ['message' => 'El estado del producto ya está actualizado', 'estado' => $producto['activo']]);
        }

        $id = $data['idProducto'];
        $estado = $data['estado'];
        $actualizar = $this->productModel->actualizarEstadoProducto($id, $estado);

        if ($actualizar === false) {
            return $this->utils->jsonResponse(500, ['message' => 'No se pudo actualizar el estado del producto']);
        }

        return $this->utils->jsonResponse(200, ['message' => "Producto actualizado correctamente"]);
    }
    public function subirPremiumProducto($request)
    {
        $data = $request['body'];

        // Buscar usuario por ID
        $buscarUser = $this->productModel->findUserById(intval($data['idusuario']));

        if (!$buscarUser) {
            return $this->utils->jsonResponse(404, ['error' => 'Usuario no encontrado']);
        }

        if ($data['is_premium'] == 0) {
            // Si el producto ya es premium, lo actualizamos a no premium (is_premium = 0)
            $actualizar = $this->productModel->productoPremium($data['idProducto'], 0);
            if ($actualizar === false) {
                return $this->utils->jsonResponse(500, ['error' => 'No se pudo actualizar el estado del producto']);
            }
            return $this->utils->jsonResponse(200, ['message' => 'El producto ha sido actualizado a no premium']);
        } else {
            // Si el usuario no es premium, limitar a 3 productos premium
            if ($buscarUser['is_premium'] == 0) {
                $cantidad = 0;
                $buscarProductos = $this->productModel->productoDeUsuario($data['idusuario']);

                // Asegurarse de que $buscarProductos es un array
                if (!is_array($buscarProductos)) {
                    return $this->utils->jsonResponse(500, ['error' => 'Error al obtener productos del usuario']);
                }

                // Contar productos premium
                foreach ($buscarProductos as $producto) {
                    if ($producto['is_premium'] == 1) {
                        $cantidad++;
                    }
                }

                // Verificar si excede el límite de 3 productos premium
                if ($cantidad >= 3) { // Cambiado a >= para manejar correctamente el límite
                    return $this->utils->jsonResponse(500, ['error' => 'Tienes una cuenta free, solo puedes tener 3 productos premium']);
                }

                // Actualizar el producto a premium
                $actualizar = $this->productModel->productoPremium($data['idProducto'], 1);
                if ($actualizar === false) {
                    return $this->utils->jsonResponse(500, ['error' => 'No se pudo marcar el producto como premium']);
                }

                return $this->utils->jsonResponse(200, ['message' => 'El producto se mostrará como premium']);
            } else {
                // Lógica para usuarios premium (sin limitación)
                $actualizar = $this->productModel->productoPremium($data['idProducto'], 1);
                if ($actualizar === false) {
                    return $this->utils->jsonResponse(500, ['error' => 'No se pudo marcar el producto como premium']);
                }

                return $this->utils->jsonResponse(200, ['message' => 'Usuario premium, producto añadido sin restricciones']);
            }
        }
    }

    public function editProduct($request)
    {
        $data = $request['body'];
        $productousuario = $this->productModel->detalleProductoUsuario($data['idproduct'], $data['usuario']);
        if ($productousuario === false) {
            return $this->utils->jsonResponse(400, ['error' => 'Producto no coincide con el del usuario']);
        }

        return $this->utils->jsonResponse(200, ['message' => $productousuario]);
    }
    public function actualizarItem($request)
    {
        $data = $request['body'];
        $precios = $data['precios'];
        $subcategoria = $this->productModel->obetenerSubcategoria($data['clasificacion']);
        if (!$subcategoria) {
            return $this->utils->jsonResponse(404, ['error' => 'Subcategoría no encontrada']);
        }
        $updateData = $this->productModel->updateProduct($data, $data['id_producto']);
        if (!$updateData) {
            return $this->utils->jsonResponse(400, ['error' => 'Error al actualizar producto']);
        }
        $updatePrecio = $this->productModel->updatePrecios($precios, $updateData);
        if (!$updatePrecio) {
            return $this->utils->jsonResponse(400, ['error' => 'Error al actualizar precios']);
        }

        return $this->utils->jsonResponse(200, ['message' => $data]);
    }
    public function updatefoto($request)
    {
        $data = $request['body'];
        $updateData = $this->productModel->updateFoto($data, $data['id_producto']);
        if (!$updateData) {
            return $this->utils->jsonResponse(400, ['error' => 'Error al actualizar producto']);
        }

        return $this->utils->jsonResponse(200, ['message' => $updateData]);
    }

    public function getFilterProducts($request)
    {
        $data = $request['body'];

        $filters = [
            'categorias' => isset($data['categorias']) ? explode(',', $data['categorias']) : null,
            'subcategorias' => isset($data['subcategorias']) ? explode(',', $data['subcategorias']) : null,
        ];

        try {

            $products = $this->productModel->getProductos($filters);
            return $this->utils->jsonResponse(200, ['data' => $products]);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(500, ['error' => $data]);
        }
    }

    public function searchProductUser($request)
    {
        $data = $request['body'];


        try {
            $producto =  $this->productModel->searchProductUser($data['userId'], $data['dataSearch']);
            return $this->utils->jsonResponse(200, ['data' => $producto]);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(500, ['error' => $data]);
        }
    }

    public function mostrarCategoria()
    {
        try {
            $productos = $this->productModel->mostrarCategoria();

            // Aquí se envía un array con un key "data" que contiene los productos
            $this->utils->jsonResponse(200, ['message' => $productos]);
        } catch (\Throwable $th) {
            $this->utils->jsonResponse(500, ['error' => $th->getMessage()]);
        }
    }
    public function mostrarSubCategoria()
    {
        try {
            $productos = $this->productModel->mostrarSubCategoria();

            // Aquí se envía un array con un key "data" que contiene los productos
            $this->utils->jsonResponse(200, ['data' => $productos]);
        } catch (\Throwable $th) {
            $this->utils->jsonResponse(500, ['error' => $th->getMessage()]);
        }
    }

    // Buscar producto
    public function buscarProduct($producto)
    {
        try {
            $result = $this->productModel->buscarProducto($producto);
            if ($result) {
                $this->utils->jsonResponse(200, ['data' => $result]);
            } else {
                $this->utils->jsonResponse(404, ['error' => 'Producto no encontrado1']);
            }
        } catch (\Throwable $th) {
            $this->utils->jsonResponse(500, ['error' => $th->getMessage()]);
        }
    }

    //Detalle de producto x id
    public function detalleProducto($id)
    {
        try {
            // Verificación estricta del ID
            if (!is_numeric($id)) {
                return $this->utils->jsonResponse(400, ['error' => 'Producto no encontrado2']);
            }

            $producto = $this->productModel->buscarProductoPorId($id);
            if (!$producto) {
                return $this->utils->jsonResponse(404, ['error' => 'Producto no encontrado3']);
            }

            $detalle = $this->productModel->detalleProducto(['id' => $id]);
            return $this->utils->jsonResponse(200, ['data' => $detalle]);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(500, ['error' => $th->getMessage()]);
        }
    }


    // Detalle de producto de usuario
    public function productoUsuario($id)
    {
        try {
            $stmt = $this->productModel->productoDeUsuario($id);
            if (!$stmt) {
                return $this->utils->jsonResponse(['status' => 400], ['error' => 'No se encontraron productos']);
            }
            return $this->utils->jsonResponse(['status' => 200], ['data' => [$stmt]]);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(['status' => 500], ['error' => $th->getMessage()]);
        }
    }
    public function productosPorCateogira($categoria)
    {
        try {
            $productos = $this->productModel->productoCategoria($categoria);
            if (empty($productos)) {
                return $this->utils->jsonResponse(['status' => 500], ['error' => 'No se encontraron productos de esta categoría ' . $categoria]);
            }
            return $this->utils->jsonResponse(['status' => 200], $productos);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(['status' => 500], ['error' => $th->getMessage()]);
        }
    }

    public function productosPorSubcategoria($categoria, $subcategoria)
    {
        try {
            $productos = $this->productModel->productoSubcategoria($categoria, $subcategoria);
            if (empty($productos)) {
                return $this->utils->jsonResponse(['status' => 400], ['error' => 'No se encontraron productos de esta subcategoría ' . $subcategoria]);
            }
            return $this->utils->jsonResponse(['status' => 200], $productos);
        } catch (\Throwable $th) {
            return $this->utils->jsonResponse(['status' => 500], ['error' => $th->getMessage()]);
        }
    }
}
