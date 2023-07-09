<?php
    require_once("./Models/Usuario.php");
    require_once("./Models/Log.php");
    require_once("./ClasesAutenticación/AutentificadorJWT.php");

    class UsuarioController extends Usuario{

        public function cargarUno($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            
            $clave = $parametros['clave'];
            $mail = $parametros['mail'];
            $tipo = $parametros['tipo'];

            //Creo el user
            
            $usuario = new Usuario();
            $usuario->clave = $clave;
            $usuario->mail = $mail;
            $usuario->tipo = $tipo;

            $usuario->guardarUsuario();

            $payload = json_encode(array("mensaje"=>"Usuario creado con exito"));

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Usuario::obtenerTodos();
            $payload = json_encode(array("listaUsuario"=> $lista));

            $response->getBody()->write($payload);
            
            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerUsuariosEspecificos($request, $response, $args)
        {
            $lista = Usuario::ObtenerUsuariosEspecificos($args['nombreArma']);

            $payload = json_encode(array("listaUsuarios"=> $lista));

            $response->getBody()->write($payload);
            
            return $response->withHeader('Content-Type', 'application/json');

        }

        public function LoginUsuario($request, $response, $args)
        {
          $ArrayDeParametros = $request->getParsedBody();
      
          $mail = $ArrayDeParametros['mail'];
          $clave = $ArrayDeParametros['clave'];
      
          $user = new Usuario();
          $user->mail = $mail;
          $user->clave = $clave;
      
          $respuesta = Usuario::VerificarUsuarioDB($user);
      
          switch ($respuesta) 
          {
            case -1:
            $payload = json_encode(array("mensaje"=>"No existe el usuario"));

              break;
            case 0:
            $payload = json_encode(array("mensaje"=>"Mail correcto pero clave incorrecta"));

              break;
            case 1:
              $datos = ["tipo" => $user->tipo, "mail" => $user->mail];
            $payload = json_encode(array("mensaje"=>AutentificadorJWT::CrearToken($datos)));
      
            //   Log::LogUsuario($user->mail, "Login");

              break;

          };

        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');





    }
}


?>