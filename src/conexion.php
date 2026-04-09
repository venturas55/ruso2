<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    return true;
}

loadEnv(__DIR__ . '/../.env');

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
