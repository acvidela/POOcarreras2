<?php
require_once('clases\kits.php');
require_once('clases\participanteManager.php');

class Carrera {

    private $id;
    private $nombre;
    private $circuito;
    private $fecha;
    private $precio;
    private $kits;
    private $participantes = null;

    public function __construct($nombre,$circuito,$fecha,$precio,$kits)
    {
        $this->nombre = $nombre;
        $this->circuito = $circuito;
        $this->fecha = $fecha;
        $this->precio = $precio;
        $this->kits = $kits;
    }

    public function toArray(){
        $arreglo = array( 
            "id" => $this->id,
            "nombre" => $this->nombre,
            "circuito" => $this->circuito,
            "fecha" => $this->fecha,
            "precio" => $this->precio,
            "kits" => $this->kits->toArray());

        return $arreglo;
       }
   

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of kits
     */ 
    public function getKits()
    {
        return $this->kits;
    }

    /**
     * Set the value of kits
     *
     * @return  self
     */ 
    public function setKits($kits)
    {
        $this->kits = $kits;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of circuito
     */ 
    public function getCircuito()
    {
        return $this->circuito;
    }

    /**
     * Set the value of circuito
     *
     * @return  self
     */ 
    public function setCircuito($circuito)
    {
        $this->circuito = $circuito;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    //Operaciones en la Base de Datos

    //Imprime por pantalla una carrera
    public function mostrar(){
        echo "ID: " . $this->getId() . ", Nombre: " . $this->getNombre() . ", Circuito: " . $this->getCircuito() .", Fecha: " . $this->getFecha() . ", Precio: " .$this->getPrecio();
        echo(PHP_EOL);
        $this->getKits()->mostrar();
        echo(PHP_EOL);
    }

      
    /*
    /   Guarda la carrera en la base de datos y le setea el id generado por la base de datos al insertarlo
    */
    public function save() {
        //Obtiene el kit de la carrera a guardar
        $kits = $this->getKits();
        $kits->save();
        $kitId = $kits->getId();

        //Obtiene los datos de la carrera para guardar
        $nombre = $this->getNombre();
        $circuito = $this->getCircuito();
        $fecha = $this->getFecha();
        $precio = $this->getPrecio();
                
        $sql = "INSERT INTO carreras (nombre, circuito, fecha, precio, id_kits)
                VALUES ('$nombre', '$circuito', '$fecha', '$precio', $kitId)";

        Conexion::ejecutar($sql);

        $idCarrera = Conexion::getLastId();
        $this->setId($idCarrera);
        
        //Agrega como clave extranjera en el kits el id de la carrera
        $sql = "UPDATE kits 
                SET id_carrera = $idCarrera
                WHERE id = $kitId";

        Conexion::ejecutar($sql);

    }
    
    //Borra la carrera de la base de datos, el kit lo borra en cascade cuando borro la carrera
    public function delete(){
        $sql = "DELETE FROM carreras
                WHERE id = ".$this->id;
        Conexion::ejecutar($sql);
    }

     /*
    /   Modifica la carrera en la base de datos
    */
    public function update() {
        
        //Obtiene los datos de la carrera para guardar
        $id = $this->getId();
        $nombre = $this->getNombre();
        $circuito = $this->getCircuito();
        $fecha = $this->getFecha();
        $precio = $this->getPrecio();
        
        $sql = "UPDATE carreras
        SET nombre = :nombre,
            circuito = :circuito,
            fecha = :fecha,
            precio = :precio
        WHERE id = $id";

        $stmt = Conexion::prepare($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':circuito', $circuito, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR); 
        $stmt->bindParam(':precio', $precio, PDO::PARAM_INT); 

        $stmt->execute();
    }

 
    /**
     * Get the value of participantes
     * Levanta los participantes de la base de datos para esa carrera
     */ 
    public function getParticipantes()
    {
        if (!isset($this->participantes)){
            $this->participantes = new ParticipanteManager($this->id);
        }
        return $this->participantes;
    }

    public function mostrarResultado(){
        $participantes = $this->getParticipantes();
        $participantes->mostrar(); 
    }

    public function modificarParticipante(){
        $participantes = $this->getParticipantes();
        $participantes->modificacion(); 
    }

}
