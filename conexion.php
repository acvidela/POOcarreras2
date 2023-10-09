<?php
    class Conexion{
        private static $db = null;
        // Información de conexión.
        /* private $host;
        private $port;
        private $database;
        private $user;
        private $password; */
    
        private static function getDatosDb(){
            $nombreArchivo = "datos\base.json";
            if (is_readable($nombreArchivo)){
                $datos = file_get_contents($nombreArchivo);
                $datos = json_decode($datos);
               return $datos;
            }
            return null;
        }
        
        private function __construct(){
        try {
            // Cadena de conexión
            $datosDb = self::getDatosDb();
            $dsn = "pgsql:host=$datosDb->host;port=$datosDb->port;dbname=$datosDb->database;user=$datosDb->user;password=$datosDb->password";
        
            // Crear una instancia de PDO
            self::$db = new PDO($dsn);
        
            // Configurar el modo de error de PDO para manejar excepciones
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            // Puedes usar esta conexión para realizar consultas
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }
        
    public static function getInstance(){
        if (isset (self::$db))
            return self::$db;
        else
            return new self();
    }
    
}