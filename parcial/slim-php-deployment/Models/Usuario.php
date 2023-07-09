<?php

require_once("./db/AccesoDb.php");

class Usuario {

    public $id;
    public $clave;
    public $mail;
    public $tipo;

    public function __construct() {}

    public function guardarUsuario()
    {
        $accesoDb = AccesoDB::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("INSERT INTO usuarios (clave, mail, tipo) VALUES (:clave, :mail, :tipo)");
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    
        $consulta->execute();
     
    }

    public static function obtenerTodos()
    {
        $accesoDb = AccesoDb::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');

    }

    public static function BuscarUsuarioPorMail($mail)
    {

        $accesoDb = AccesoDb::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT * FROM usuarios WHERE LOWER(mail) = LOWER(:mail)");

        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);

        $consulta->execute();
        
        return $consulta->fetchObject('Usuario');

    }

    public static function ObtenerPorId ($id)
    {
        $accesoDb = AccesoDB::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
        
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }
        
    
    public static function ObtenerUsuariosEspecificos($nombre)
    {
        $accesoDb = AccesoDb::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT DISTINCT usuarios.id, usuarios.clave, usuarios.mail, usuarios.tipo FROM usuarios INNER JOIN ventas ON usuarios.id = ventas.id_comprador INNER JOIN armas ON armas.id = ventas.id_arma WHERE armas.nombre = :nombre");

        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');

    }

    public static function VerificarUsuarioDB($user)
    {

        $arrayUsuarios = array();
        $arrayUsuarios = Usuario::obtenerTodos();

        $verificado = -1;

        foreach ($arrayUsuarios as $usuario) {
            if ($usuario->mail == $user->mail) 
            {
                if ($usuario->clave == $user->clave) 
                {
                    $verificado = 1;
                    $user->tipo = $usuario->tipo;
                } 
                else 
                {
                    $verificado = 0;
                }
            }
        }
        return $verificado;
    }


      
    
    

}


?>