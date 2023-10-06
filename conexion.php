<?php
    class Conexion{
        static $db = null;
        private $host = 'mahmud.db.elephantsql.com';
        private $port = '5432';
        private $database = 'htslmwoa';
        private $user = 'htslmwoa';
        private $password = 'ZRmocbFztc6uzg9rVaWzB11E-jgPEFT6';
    
        private function __construct(){
        try {
            // Información de conexión
            // Cadena de conexión
            $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->database;user=$this->user;password=$this->password";
        
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