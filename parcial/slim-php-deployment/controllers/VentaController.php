<?php
    require_once("./Models/Venta.php");
    require_once("./Models/Usuario.php");
    require_once("./Models/Arma.php");

    class VentaController extends Venta{

        public function cargarUno($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            
            $nombreArma = $parametros['nombreArma'];
            $cantidad = $parametros['cantidad'];
            $fecha = $parametros['fecha'];
            $mailComprador = $parametros['mail'];

            $id_arma = Arma::BuscarArmaPorNombre($nombreArma)->id;

            $id_comprador = Usuario::BuscarUsuarioPorMail($mailComprador)->id;

            if($id_arma != false && $id_comprador != false)
            {
                $venta = new Venta();
                $venta->id_arma = $id_arma;
                $venta->id_comprador = $id_comprador;
                $venta->cantidad = $cantidad;
                $venta->fecha = $fecha;
                
                $venta->GuardarImagen('Fotos/FotosArma2023/' ,$_FILES['imagen']);

                $venta->guardarVenta();
    
                $payload = json_encode(array("mensaje"=>"Venta realizada con exito"));
                
            }
            else
            {
                $payload = json_encode(array("mensaje"=>"No existe un arma con ese nombre"));
            }
            

            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');

        }

        // public function TraerTodos($request, $response, $args)
        // {
        //     $lista = Usuario::obtenerTodos();
        //     $payload = json_encode(array("listaVenta"=> $lista));

        //     $response->getBody()->write($payload);
            
        //     return $response->withHeader('Content-Type', 'application/json');

        // }

        public function TraerVentasFecha($request, $response, $args) 
        {

            $lista = Venta::ObtenerVentasFecha();
            $payload = json_encode(array("listaVenta"=> $lista));

            $response->getBody()->write($payload);
            
            return $response->withHeader('Content-Type', 'application/json');
        }



    }


?>