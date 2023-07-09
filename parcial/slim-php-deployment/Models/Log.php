<?php

    class Log{

        public $id;
        public $id_usuario;
        public $id_arma;
        public $accion;
        public $fecha_accion;

        public function __construct() {}

        public function guardarLog()
        {
            $accesoDb = AccesoDB::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("INSERT INTO logs (id_usuario, id_arma, accion, fecha_accion) VALUES (:id_usuario, :id_arma, :accion, :fecha_accion)");
            $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $consulta->bindValue(':id_arma', $this->id_arma, PDO::PARAM_INT);
            $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
            $consulta->bindValue(':fecha_accion', $this->fecha_accion, PDO::PARAM_STR);
        
            $consulta->execute();
         
        }
    
        public static function obtenerTodos()
        {
            $accesoDb = AccesoDb::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("SELECT * FROM logs");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    
        }
        
        public static function LogUsuario($mail,$accion)
        {
    
            $responsable = Usuario::BuscarUsuarioPorMail($mail);

            if ($responsable != false) 
            {
    
                $logUsuario = new Log();
    
                $logUsuario->id_usuario = $responsable->id;
                $logUsuario->accion = $accion;
                $logUsuario->fecha_accion = date("Y-m-d");
    
                $logUsuario->guardarLog();
            }
        }

    }

?>