<?php

require_once './app/Controllers/UserController.php';
require_once './app/Controllers/ProductController.php';
require_once './config/database.php';

// No crear una nueva instancia de Router aquí
// $router ya está disponible desde index.php

$db = getDBConnection(); // Obtener la conexión a la base de datos
$userController = new UserController($db); // Crear instancia del controlador
$productoController = new ProductController($db);
$router->get('/', function () {
    echo "Welcome to the API!";
});



$router->post('/usuario/crear-user', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método loginUser con los datos de la solicitud
    echo $userController->crearUsuario(['body' => $data]);
});
$router->post('/recovery-password', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método loginUser con los datos de la solicitud
    echo $userController->recoverPassword(['body' => $data]);
});

$router->post('/search_email', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método loginUser con los datos de la solicitud
    echo $userController->searchEmail(['body' => $data]);
});
$router->post('/change-password', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método loginUser con los datos de la solicitud
    echo $userController->changePassword(['body' => $data]);
});
$router->post('/usuario/login', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método loginUser con los datos de la solicitud
    echo $userController->loginUser(['body' => $data]);
});
$router->post('/usuario/activar-cuenta', function () use ($userController) {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Llamar al método activarCuenta con los datos de la solicitud
    echo $userController->activarCuenta(['body' => $data]);
});
$router->post('/userdata', function () use ($userController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userController->searchUserId(['body' => $data]);
});
$router->post('/update-profile', function () use ($userController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userController->updateDatas(['body' => $data]);
});
$router->post('/actualizar-foto-perfil', function () use ($userController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userController->updateFotoPerfil(['body' => $data]);
});
$router->post('/update-pass', function () use ($userController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userController->updatePass(['body' => $data]);
});
$router->post('/update-password', function () use ($userController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userController->updatePassword(['body' => $data]);
});
$router->post('/categorias', [$productoController, 'mostrarCategoria']);
$router->post('/subcategorias', [$productoController, 'mostrarSubCategoria']);

$router->post('/producto-categoria', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->productoPorCategoria(['body' => $data]);
});
// Producto rutas
$router->post('/productos', [$productoController, 'mostrarProductos']);
$router->post('/categorias/subcategoria', [$productoController, 'mostrarSubcategorias']);
$router->post('/crear-producto', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->crearProducto(['body' => $data]);
});
$router->post('/crear-categoria', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->creatCategoria(['body' => $data]);
});
$router->post('/crear-subcategoria', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->crearSubcategoria(['body' => $data]);
});
$router->post('/activar-producto', function()use ($productoController){
    $data= json_decode(file_get_contents('php://input'), true);
    echo $productoController->actualizarProducto(['body'=>$data]);
});
$router->post('/producto-premium', function()use ($productoController){
    $data= json_decode(file_get_contents('php://input'), true);
    echo $productoController->subirPremiumProducto(['body'=>$data]);
});

$router->post('/editar-producto', function()use ($productoController){
    $data= json_decode(file_get_contents('php://input'), true);
    echo $productoController->editProduct(['body'=>$data]);
});
$router->post('/actualizar-fotografia', function()use ($productoController){
    $data= json_decode(file_get_contents('php://input'), true);
    echo $productoController->updatefoto(['body'=>$data]);
});
$router->post('/actualizar-producto', function()use ($productoController){
    $data= json_decode(file_get_contents('php://input'), true);
    echo $productoController->actualizarItem(['body'=>$data]);
});
$router->post('/buscar-producto/{name}', function ($name) use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);

        // La respuesta ya es manejada por buscarProduct
        $productoController->buscarProduct($name);

});

$router->post('/productos/{id}', function ($id) use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->detalleProducto($id);
});
$router->post('/productosusuario/{id}', function ($id) use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->productoUsuario($id);
});
$router->post('/searchproductuser', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->searchProductUser(['body' => $data]);
});
$router->post('/filtrar/filtro', function () use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $productoController->getFilterProducts(['body' => $data]);
});
// $router->get('/filtro-producto/filtrar', [$productoController, 'getFilterProducts']);

$router->get('/producto/{categoria}/{subcategoria}', function ($categoria, $subcategoria) use ($productoController) {
    echo $productoController->productosPorSubcategoria($categoria, $subcategoria);
});

$router->get('/producto/{categoria}', function ($categoria) use ($productoController) {
    echo $productoController->productosPorCateogira($categoria);
});

