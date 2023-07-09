    <?php
    // Error Handling
    error_reporting(-1);
    ini_set('display_errors', 1);

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;
    use Slim\Routing\RouteCollectorProxy;

    require __DIR__ . '/vendor/autoload.php';
    require_once("./controllers/ArmaController.php");
    require_once("./controllers/VentaController.php");
    require_once("./controllers/UsuarioController.php");
    require_once("./ClasesAutenticación/MWParaAutenticar.php");
    require_once("./db/AccesoDb.php");


    // Instantiate App
    $app = AppFactory::create();


    // Add error middleware
    $app->addErrorMiddleware(true, true, true);

    // Add parse body
    $app->addBodyParsingMiddleware();

    // Routes
    // $app->get('[/]', function (Request $request, Response $response) {    
    //     $payload = json_encode(array('method' => 'GET', 'msg' => "Bienvenido a SlimFramework 2023"));
    //     $response->getBody()->write($payload);
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    // $app->post('[/]', function (Request $request, Response $response) {    
    //     $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    //     $response->getBody()->write($payload);
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    // $app->post('/test', function (Request $request, Response $response) {    
    //     $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    //     $response->getBody()->write($payload);
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    $app->group('/usuarios', function (RouteCollectorProxy $group) {
        $group->get('/{nombreArma}', \UsuarioController::class . ':TraerUsuariosEspecificos');
        $group->post('[/]', \UsuarioController::class . ':cargarUno');
        $group->post('/login', \UsuarioController::class . ':LoginUsuario');
    });

    // $app->group('/pedidos', function (RouteCollectorProxy $group) {
    //     $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    //     $group->post('[/]', \UsuarioController::class . ':cargarUno');
    // });

    $app->group('/ventas', function (RouteCollectorProxy $group) {
        $group->get('[/]', \VentaController::class . ':TraerVentasFecha');
        $group->post('[/]', \VentaController::class . ':cargarUno');

    })->add(MWParaAutenticar::class . ':VerificarUsuario');

    $app->group('/armas', function (RouteCollectorProxy $group) {
        if(isset($_GET['id']))
        {
            $group->get('[/]', \ArmaController::class . ':TraerPorId');
        }
        else
        {
            $group->get('[/]', \ArmaController::class . ':TraerTodos');
        }
        $group->get('/{nacionalidad}', \ArmaController::class . ':TraerPorNacionalidad');
        $group->post('[/]', \ArmaController::class . ':cargarUno');
        $group->delete('/{armaId}', \ArmaController::class . ':BorrarUno');
        $group->put('[/]', \ArmaController::class . ':ModificarUno');
    });

    $app->run();
