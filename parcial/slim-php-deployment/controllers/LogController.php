<?php
    require_once("./Models/Log.php");

    class LogController extends Log{

        public function cargarUno($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            
            $id_usuario = $parametros['id_usuario'];
            $id_arma = $parametros['id_arma'];
            $accion = $parametros['accion'];
            $fecha_accion = $parametros['fecha_accion'];

    
            $usuario = new Log();
            $usuario->id_usuario = $id_usuario;
            $usuario->id_arma = $id_arma;
            $usuario->accion = $accion;
            $usuario->fecha_accion = $fecha_accion;

            $usuario->guardarLog();

            $payload = json_encode(array("mensaje"=>"Log creado con exito"));

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Usuario::obtenerTodos();
            $payload = json_encode(array("listaMesa"=> $lista));

            $response->getBody()->write($payload);
            
            return $response->withHeader('Content-Type', 'application/json');

        }



    }


?>