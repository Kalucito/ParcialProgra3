<?php


    class Arma{
        
        public $id;
        public $foto;
        public $nombre;
        public $precio;
        public $nacionalidad;

        public function __construct() {}

        public function guardarArma()
        {
            $accesoDb = AccesoDB::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("INSERT INTO armas (precio, nombre, foto, nacionalidad) VALUES (:precio, :nombre, :foto, :nacionalidad)");
            
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        
            $consulta->execute();
         
        }

        public static function obtenerTodos()
        {
            $accesoDb = AccesoDb::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("SELECT * FROM armas");
            $consulta->execute();
            
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Arma');
            
        }

        
        public static function BuscarArmaPorNombre($nombre)
        {

            $accesoDb = AccesoDb::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("SELECT * FROM armas WHERE LOWER(nombre) = LOWER(:nombre)");

            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);

            $consulta->execute();
            
            return $consulta->fetchObject('Arma');

        }

        public static function ObtenerPorNacionalidad($nacionalidad)
        {

            $accesoDb = AccesoDb::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("SELECT * FROM armas WHERE LOWER(nacionalidad) = LOWER(:nacionalidad)");

            $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);

            $consulta->execute();
            
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Arma');


        }

        public static function ObtenerPorId ($id)
        {
            $accesoDb = AccesoDB::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("SELECT id, precio, nombre, foto, nacionalidad FROM armas WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        
            $consulta->execute();

            return $consulta->fetchObject('Arma');
        }

        public function GuardarImagen($ruta, $imagen)
        {
            $destino = $ruta . $this->nombre . ".jpg";

            $this->foto = $destino;
            
            if(!move_uploaded_file($imagen["tmp_name"], $destino))
            {
                $this->foto = null;
            }

        }

        public static function EliminarPorId ($id)
        {

            $accesoDb = AccesoDB::obtenerInstancia();
            $consulta = $accesoDb->prepararConsulta("DELETE FROM armas WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        
            $consulta->execute();

            return $consulta->rowCount();

        }

        public function ModificarArma()
        {
            $accesoDb = AccesoDB::obtenerInstancia();

            $consulta = $accesoDb->prepararConsulta("UPDATE armas SET precio = :precio, nombre = :nombre, foto = :foto ,nacionalidad = :nacionalidad WHERE id = :id");

            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

            $consulta->execute();

            return $consulta->rowCount();


        }


        
    }



?>