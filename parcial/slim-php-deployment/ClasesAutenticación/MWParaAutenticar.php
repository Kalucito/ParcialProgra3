<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

include_once("./AutentificadorJWT.php");

class MWParaAutenticar
{


  public function VerificarUsuario($request, $handler)
  {
    $objDelaRespuesta = new stdclass();
    $objDelaRespuesta->respuesta = "";

    $method = $request->getMethod();


    if ($method === 'GET') 
    {


    } 
    else if ($method === 'PUT') 
    {

    } 
    else
    {

      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);



      try 
      {

        AutentificadorJWT::verificarToken($token);
        $objDelaRespuesta->esValido = true;

        echo "entree";

      }catch (Exception $e) 
      {
        $objDelaRespuesta->excepcion = $e->getMessage();
        $objDelaRespuesta->esValido = false;
      }


      if ($objDelaRespuesta->esValido) 
      {
        $payload = AutentificadorJWT::ObtenerData($token);
        var_dump($payload);

        if ($payload->mail == "admin@admin.com") 
        {
          $response = $handler->handle($request);
        } 
        else 
        {
          // $objDelaRespuesta->respuesta = "ERROR. Solo socios puede alterar la base de datos de productos.";
          throw new Exception('Usuario no autenticado');
        }
      } 
      else 
      {
        throw new Exception('Usuario no autenticado');
        $objDelaRespuesta->respuesta = "Usuario no registrado";
      }

      if ($objDelaRespuesta->respuesta != "") 
      {
        $objDelaRespuestaJson = json_encode($objDelaRespuesta);

        $response->getBody()->write($objDelaRespuestaJson);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(401);

      }
    }


    return $response;
  }

  public function EsAdmin($request, $response, $next)
  {
    $objDelaRespuesta = new stdclass();
    $objDelaRespuesta->respuesta = "";

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);


    try {
      AutentificadorJWT::verificarToken($token);
      $objDelaRespuesta->esValido = true;
    } catch (Exception $e) {
      //guardar en un log
      $objDelaRespuesta->excepcion = $e->getMessage();
      $objDelaRespuesta->esValido = false;
    }


    if ($objDelaRespuesta->esValido) {
      $payload = AutentificadorJWT::ObtenerData($token);

      if ($payload->empleo == "Socio") {
        $response = $next($request, $response);
      } else {
        $objDelaRespuesta->respuesta = "ERROR. Solo socios puede alterar la base de datos de productos.";
      }
    } else {
      $objDelaRespuesta->respuesta = "Usuario no registrado";
    }

    if ($objDelaRespuesta->respuesta != "") 
    {
      echo "entreeeee";
      $nueva = $response->withJson($objDelaRespuesta, 401);
      return $nueva;
    }



    return $response;
  }

  public function VerificarEmpleoParaPendientes($request, $response, $next)
  {


    if ($request->isGet()) {
      $response->getBody()->write('<p>Verifico credenciales</p>');
      $mail = "d";



      if ($mail == "x") {
        $response->getBody()->write("<h3>Bienvenido $mail </h3>");
        $response = $next($request, $response);
      } else {
        $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
      }

      $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
    }
    return $response;
  }
}