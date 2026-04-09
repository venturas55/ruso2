<?php

class ApptivaDB
{
    private $host;
    private $usuario;
    private $clave;
    private $dbname; 

    public $conexion;
    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->usuario = $_ENV['DB_USER'] ?? 'root';
        $this->clave = $_ENV['DB_PASS'] ?? '';
        $this->dbname = $_ENV['DB_NAME'] ?? '';
        
        $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->dbname)
            or die(mysql_error());
        $this->conexion->set_charset("utf8");
    }

    public function cerrar()
    {
        $this->conexion->close();
    }


    //BUSCAR
    public function buscar($tabla, $condicion)
    {
        $consulta = $this->conexion->query("select * from $tabla where $condicion") or die($this->conexion->error);
        if ($consulta)
            return $consulta->fetch_all(MYSQLI_ASSOC);
        return false;
    }

    //INSERTAR
    public function insertar($tabla, $datos)
    {
        $consulta = $this->conexion->query("insert into $tabla VALUES $datos") or die($this->conexion->error);
        if ($consulta)
            return true;
        return false;
    }

    //BORRAR
    public function borrar($tabla, $condicion)
    {
        $consulta = $this->conexion->query("delete from $tabla where $condicion")  or die($this->conexion->error);
        if ($consulta)
            return true;
        return false;
    }

    //ACTUALIZAR
    public function actualizar($tabla, $campos, $condicion)
    {
        $consulta = $this->conexion->query("update $tabla set $campos where $condicion") or die($this->conexion->error);
        if ($consulta)
            return true;
        return false;
    }
}
