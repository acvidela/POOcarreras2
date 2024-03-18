<?php
class Participante {
    private $id;  //Este número será el dorsal del atleta, el número que llevará en esa carrera. (Un atleta puede tener varios)
    private $idCarrera;
    private $idAtleta;  //Este identificador es de un atleta, la persona, para todas las carreras en las que participa
    private $pago = 0.0;
    private $posGeneral = 0;
    private $posCategoria = 0;
    private $categoria;
    private $finalizo =  false;

    public function __construct($idCarrera, $idAtleta, $pago, $posGeneral, $posCategoria, $categoria, $finalizo) {
        $this->idCarrera = $idCarrera;
        $this->idAtleta = $idAtleta;
        $this->pago = $pago;
        $this->posGeneral = $posGeneral;
        $this->posCategoria = $posCategoria;
        $this->categoria = $categoria;
        $this->finalizo = $finalizo;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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

    /**
     * Get the value of id_carrera
     */ 
    public function getIdCarrera()
    {
        return $this->idCarrera;
    }

    /**
     * Set the value of id_carrera
     *
     * @return  self
     */ 
    public function setIdCarrera($idCarrera)
    {
        $this->idCarrera = $idCarrera;

        return $this;
    }

    /**
     * Get the value of idAtleta
     */ 
    public function getIdAtleta()
    {
        return $this->idAtleta;
    }

    /**
     * Set the value of idAtleta
     *
     * @return  self
     */ 
    public function setIdAtleta($idAtleta)
    {
        $this->idAtleta = $idAtleta;

        return $this;
    }

    /**
     * Get the value of pago
     */ 
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * Set the value of pago
     *
     * @return  self
     */ 
    public function setPago($pago)
    {
        $this->pago = $pago;

        return $this;
    }

    /**
     * Get the value of posGeneral
     */ 
    public function getPosGeneral()
    {
        return $this->posGeneral;
    }

    /**
     * Set the value of posGeneral
     *
     * @return  self
     */ 
    public function setPosGeneral($posGeneral)
    {
        $this->posGeneral = $posGeneral;

        return $this;
    }

    /**
     * Get the value of posCategoria
     */ 
    public function getPosCategoria()
    {
        return $this->posCategoria;
    }

    /**
     * Set the value of posCategoria
     *
     * @return  self
     */ 
    public function setPosCategoria($posCategoria)
    {
        $this->posCategoria = $posCategoria;

        return $this;
    }

    /**
     * Get the value of categoria
     */ 
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set the value of categoria
     *
     * @return  self
     */ 
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

   //Imprime por pantalla un participante inscripto en una carrera, ampliando sus datos personales con la clase Atleta  
    public function mostrarCombinado($atletas){
        if ($this->getFinalizo()){ 
            $atleta = $atletas->getPorId($this->getIdAtleta());
            echo "Posición general: " . $this->getPosGeneral() . ", dorsal del atleta: " . $this->getId() . ", corresponde al atleta: " .  $atleta->getNombre().  ". Categoría: " . $this->getCategoria() . ", posición en la categoría: " . $this->getPosCategoria();
        } else{
            echo "Dorsal del atleta: " . $this->getId() . ", corresponde al atleta: " .  $this->getIdAtleta(). ", no finalizó la carrera";
        }
        echo(PHP_EOL);
    }
    
    //Imprime por pantalla un participante inscripto en una carrera,  
    public function mostrar(){
        if ($this->getFinalizo()){ 
            echo "Dorsal del atleta: " . $this->getId() . ", corresponde al atleta: " .  $this->getIdAtleta().  ", Posición general: " . $this->getPosGeneral() .", Categoría: " . $this->getCategoria() . ", Posición en la categoría: " .$this->getPosCategoria();
        } else{
            echo "Dorsal del atleta: " . $this->getId() . ", corresponde al atleta: " .  $this->getIdAtleta(). ", No finalizó la carrera";
        }
        echo(PHP_EOL);
    }

    //Operaciones en la Base de Datos

      
    /*
    /   Guarda al participante en la base de datos y le setea el id generado por la base de datos al insertarlo
    */
    public function save() {
        //Obtiene los datos del participante para guardar
        $idCarrera = $this->getIdCarrera();
        $idAtleta = $this->getIdAtleta();
        $pago = $this->getPago();
        $posGeneral = $this->getPosGeneral();
        $posCategoria = $this->getPosCategoria();
        $categoria = $this->getCategoria();
        $finalizo = $this->getFinalizo();
        $sql = "INSERT INTO participantes (id_carrera, id_atleta, pago, pos_general, pos_categoria, categoria, finalizo)
                VALUES ($idCarrera, $idAtleta, $pago, $posGeneral, $posCategoria, '$categoria', false)";

        Conexion::ejecutar($sql);

        $idParticipante = Conexion::getLastId();
        $this->setId($idParticipante);
        
    }
    
    //Borra al participante de la carrera de la base de datos
    public function delete(){
        $sql = "DELETE FROM participantes
                WHERE id = ".$this->id;
        Conexion::ejecutar($sql);
    }

     /*
    /   Modifica un participante en la base de datos
    */
    public function update() {

        //Obtiene los datos del participante para actualizar
   
        $id = $this->getId();
        //$idCarrera = $this->getId_carrera();  //No permite actualizar
        //$id_atleta = $this->getId_atleta();       //No permite actualizar
        $pago = $this->getPago();
        $posGeneral = $this->getPosGeneral();
        $posCategoria = $this->getPosCategoria();
        $categoria = $this->getCategoria();
        $finalizo = $this->getFinalizo();
                
        $sql = "UPDATE participantes
        SET pago = :pago,
            pos_general = :posGeneral,
            pos_categoria = :posCategoria,
            categoria = :categoria,
            finalizo = :finalizo
        WHERE id = $id";
         
        $stmt = Conexion::prepare($sql);
        $stmt->bindParam(':pago', $pago, PDO::PARAM_INT);
        $stmt->bindParam(':posGeneral', $posGeneral, PDO::PARAM_INT);
        $stmt->bindParam(':posCategoria', $posCategoria, PDO::PARAM_INT); 
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $stmt->bindParam(':finalizo', $finalizo, PDO::PARAM_BOOL);

        $stmt->execute();
    }

        /**
         * Get the value of finalizo
         */ 
        public function getFinalizo()
        {
                return $this->finalizo;
        }

        /**
         * Set the value of finalizo
         *
         * @return  self
         */ 
        public function setFinalizo($finalizo)
        {
                $this->finalizo = $finalizo;

                return $this;
        }
}
