<?php
    require_once("./Models/Arma.php");

    class ArmaController extends Arma{

        public function cargarUno($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            
            $precio = $parametros['precio'];
            $nombre = $parametros['nombre'];
            $nacionalidad = $parametros['nacionalidad'];

            
            $arma = new Arma();
            $arma->precio = $precio;
            $arma->nombre = $nombre;
            $arma->nacionalidad = $nacionalidad;

            $arma->GuardarImagen( "Fotos/Armas/", $_FILES['foto']);

            $arma->guardarArma();

            $payload = json_encode(array("mensaje"=>"Arma creada con exito"));

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Arma::obtenerTodos();
            $payload = json_encode(array("listaArmas"=> $lista));

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerPorNacionalidad($request, $response, $args) 
        {

            $lista = Arma::ObtenerPorNacionalidad($args['nacionalidad']);

            $payload = json_encode(array("listaArmas de " . $args['nacionalidad'] => $lista));

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function TraerPorId($request, $response, $args)
        {

            $arma = Arma::ObtenerPorId($_GET['id']);

            if($arma != false)
            $payload = json_encode($arma);
            else
            $payload = json_encode(array("mensaje"=>"No existe un arma con ese ID"));


            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        public function BorrarUno($request, $response, $args)
        {
            $armaId = $args['armaId'];

            if(Arma::EliminarPorId($armaId) != 0)
            {
                $payload = json_encode(array("mensaje" => "Arma borrada con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "No se pudo borrar el arma"));
            }

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

            $parametros = $request->getParsedBody();


            $id = $parametros['id'];
            $precio = $parametros['precio'];
            $nombre = $parametros['nombre'];
            $nacionalidad = $parametros['nacionalidad'];
            $foto = $parametros['foto'];

            $armaModificada = new Arma();

            $armaModificada->id = $id;
            $armaModificada->precio = $precio;
            $armaModificada->nombre = $nombre;
            $armaModificada->nacionalidad = $nacionalidad;
            $armaModificada->foto = $foto;

            if($armaModificada->ModificarArma() > 0)
            {
                $payload = json_encode(array("mensaje" => "Arma modificada."));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "No se pudo modificar el arma."));
            }

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        

        }



    }


?>