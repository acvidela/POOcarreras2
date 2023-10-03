<?php
    class Conexion{
        static $db = null;
    
    private function __construct(){
    try {
        $url = "postgres://htslmwoa:ZRmocbFztc6uzg9rVaWzB11E-jgPEFT6@mahmud.db.elephantsql.com/htslmwoa";
    
        // Crear una instancia de PDO con la URL de conexión
        self::$db = new PDO($url);
    
        // Configurar el modo de error de PDO para manejar excepciones
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Puedes usar esta conexión para realizar consultas
        return self::$db;
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