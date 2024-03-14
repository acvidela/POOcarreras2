<?php
require_once 'datos' . DIRECTORY_SEPARATOR . 'conexion.php';

class Atleta {
    private $id;
    private $nombre;
    private $email;
    private $fechaNacimiento; //dd/mm/aaaa

    public function __construct($nombre, $email, $fechaNacimiento) {
        $this->nombre = $nombre;
        $this->email = $email;
        if (is_string($fechaNacimiento)){
            $this->fechaNacimiento = date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $fechaNacimiento)));
            //$this->fechaNacimiento = date("Y-m-d H:i:s", strtotime($fechaNacimiento));
        } else{
            $this->fechaNacimiento = $fechaNacimiento;       
        } 
   }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    protected function getFechaNacimiento(){
        return $this->fechaNacimiento;
    }
    //Calcula la edad del atleta a partir de la fecha de nacimiento
    public function getEdad(){
        $fechaNacimiento = new DateTime($this->getFechaNacimiento());    // Obtener la fecha de nacimiento y convertirla a un objeto DateTime
        $ahora = new DateTime('now'); //Fecha de hoy
        $diferencia = $ahora->diff($fechaNacimiento);  //Calcular la diferencia de hoy con la fecha de nacimiento
        return $diferencia->format("%y"); //Expresar la diferencia en años, para dar la edad
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setFechaNacimiento($fechaNacimiento){
        if (is_string($fechaNacimiento)){
            $this->fechaNacimiento = date("Y-m-d H:i:s", strtotime($fechaNacimiento));
        } else{
            $this->fechaNacimiento = $fechaNacimiento;       
        }       
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

    //Muestra por pantalla un atleta
    public function mostrar(){
        echo "ID: " . $this->getId() 
            . ", Nombre: " . $this->getNombre() 
            . ", Email: " . $this->getEmail() 
            . ", Edad: " . $this->getEdad()
            . PHP_EOL;
    }

    //Guarda en la base de datos
    public function save(){
        $nombre = $this->getNombre();
        $email = $this->getEmail();
        $fechaNacimiento = $this->getFechaNacimiento();
    
        $sql = "INSERT INTO atletas (nombre, email, fechadenacimiento)
                VALUES ('$nombre', '$email', '$fechaNacimiento')";

        Conexion::ejecutar($sql);

        $this->setId(Conexion::getLastId());

    }

        //Borra el atleta de la base de datos
        public function delete(){
            $sql = "DELETE FROM atletas
                    WHERE id = ".$this->id;
            Conexion::ejecutar($sql);
        }

    /*
    /   Modifica al atleta en la base de datos
    */
    public function update() {
        
        //Obtiene los datos del atleta para modificar
        $id = $this->getId();
        $nombre = $this->getNombre();
        $email = $this->getEmail();
        $fecha = $this->getFechaNacimiento();
              
        $sql = "UPDATE atletas
        SET nombre = :nombre,
            email = :email,
            fechadenacimiento = :fecha
        WHERE id = $id";

        $stmt = Conexion::prepare($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR); 
        
        $stmt->execute();
    }

}