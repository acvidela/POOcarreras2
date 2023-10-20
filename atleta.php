<?php
require_once 'conexion.php';

class Atleta {
    private $id;
    private $nombre;
    private $email;
    private $fechaNacimiento; //dd/mm/aaaa

    public function __construct($nombre, $email, $fechaNacimiento) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->fechaNacimiento = $fechaNacimiento;
        //new DateTime(date("d-m-Y"));
        //$this->fechaNacimiento = DateTime::createFromFormat('d/m/Y',$fechaNacimiento);
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

    public function getEdad(){
       // Obtener la fecha de nacimiento y convertirla a un objeto DateTime
        $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $this->getFechaNacimiento());
        $ahora = new DateTime(date("d-m-Y"));
        $diferencia = $ahora->diff($fechaNacimiento);
        return $diferencia->format("%y");

    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setFechaNacimiento($fechaNacimiento){
        $this->fechaNacimiento = $fechaNacimiento;      
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
        echo "ID: " . $this->getId() . ", Nombre: " . $this->getNombre() . ", Email: " . $this->getEmail() . ", Edad: " . $this->getEdad();
    }

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