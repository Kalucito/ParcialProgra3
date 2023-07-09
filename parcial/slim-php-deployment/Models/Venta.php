<?php 

class Venta{

    public $id;
    public $id_arma;
    public $id_comprador;
    public $cantidad;
    public $imagen;
    public $fecha;
    
    public function __construct() {}

    public function guardarVenta()
    {
        $accesoDb = AccesoDB::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("INSERT INTO ventas (id_arma, id_comprador, cantidad, imagen, fecha) VALUES (:id_arma, :id_comprador,:cantidad, :imagen, :fecha)");
        
        $consulta->bindValue(':id_arma', $this->id_arma, PDO::PARAM_INT);
        $consulta->bindValue(':id_comprador', $this->id_comprador, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
    
        $consulta->execute();
     
    }

    public static function obtenerTodos()
    {
        $accesoDb = AccesoDb::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT * FROM ventas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');

    }

    public function GuardarImagen($ruta, $imagen)
    {

        $usuario = Usuario::ObtenerPorId($this->id_comprador);
        
        $nombreUsuario = strtok(($usuario->mail), "@");

        $nombreDeImagen = Arma::ObtenerPorId($this->id_arma)->nombre . $nombreUsuario . $this->fecha .".jpg";
                
        $destino = $ruta . $nombreDeImagen;

        $this->imagen = $nombreDeImagen;
        
        if(!move_uploaded_file($imagen["tmp_name"], $destino))
        {
            $this->imagen = null;
        }

    }

    public function ObtenerVentasFecha()
    {
        $accesoDb = AccesoDb::obtenerInstancia();
        $consulta = $accesoDb->prepararConsulta("SELECT * FROM ventas WHERE fecha >= '2022-11-13' AND fecha <= '2022-11-16'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }


}


?>