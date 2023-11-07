<?php


class Kits {

    private $id;
    private $chip = FALSE;
    private $numero = FALSE;
    private $remera= FALSE;
    private $medalla = FALSE;

    public function __construct($parametros)
    {
        if (isset($parametros['id'])) {
            $this->id = $parametros['id'];
        }

        if (isset($parametros['chip'])) {
            $this->chip = $parametros['chip'];
        }
        if (isset($parametros['numero'])){
            $this->numero = $parametros['numero'];
        }
        if (isset($parametros['remera'])){
            $this->remera = $parametros['remera'];
        }
        if (isset($parametros['medalla'])){
            $this->medalla = $parametros['medalla'];
        }
    }

    public function mostrar(){
        echo("El kit incluye: ");
        if ($this->chip) {echo ("chip ");}
        if ($this->numero) {echo ("número ");}
        if ($this->remera) {echo ("remera ");}
        if ($this->medalla) {echo ("medalla.");}
    }

    public function toArray(){
        $arreglo = array( 
            "chip" => $this->chip,
            "numero" => $this->numero,
            "remera" => $this->remera,
            "medalla" => $this->medalla);
        return $arreglo;
       }

    /**
     * Get the value of chip
     */ 
    public function getChip()
    {
        return $this->chip;
    }

    

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Get the value of remera
     */ 
    public function getRemera()
    {
        return $this->remera;
    }

    /**
     * Get the value of medalla
     */ 
    public function getMedalla()
    {
        return $this->medalla;
    }

     /**
     * Set the value of chip
     *
     * @return  self
     */ 
    public function setChip($chip)
    {
        $this->chip = $chip;

        return $this;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Set the value of remera
     *
     * @return  self
     */ 
    public function setRemera($remera)
    {
        $this->remera = $remera;

        return $this;
    }

    /**
     * Set the value of medalla
     *
     * @return  self
     */ 
    public function setMedalla($medalla)
    {
        $this->medalla = $medalla;

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
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function save(){
        $chip = $this->getChip();
        $numero = $this->getNumero();
        $remera= $this->getRemera();
        $medalla = $this -> getMedalla();

        //Inserta el kit en la base de datos
        $sql = "INSERT INTO kits (remera, numero, medalla, chip)
                VALUES (:remera, :numero, :medalla, :chip)";
        $stmt = Conexion::prepare($sql);
        // Vincular los valores a los marcadores de posición
        $stmt->bindParam(':remera', $remera, PDO::PARAM_BOOL);
        $stmt->bindParam(':numero', $numero, PDO::PARAM_BOOL);
        $stmt->bindParam(':medalla', $medalla, PDO::PARAM_BOOL);
        $stmt->bindParam(':chip', $chip, PDO::PARAM_BOOL);
        $stmt->execute();
              
        $this->setId(Conexion::getLastId()); 
       
    }

    public function update(){
        $id = $this->getId();
        $chip = $this->getChip();
        $numero = $this->getNumero();
        $remera= $this->getRemera();
        $medalla = $this -> getMedalla();

        //Modifica el kit en la base de datos
        $sql = "UPDATE kits
                SET remera = :remera,
                    numero  = :numero,
                    medalla = :medalla,
                    chip = :chip
                WHERE id = ".$id;
        $stmt = Conexion::prepare($sql);
        // Vincular los valores a los marcadores de posición
        $stmt->bindParam(':remera', $remera, PDO::PARAM_BOOL);
        $stmt->bindParam(':numero', $numero, PDO::PARAM_BOOL);
        $stmt->bindParam(':medalla', $medalla, PDO::PARAM_BOOL);
        $stmt->bindParam(':chip', $chip, PDO::PARAM_BOOL);
        $stmt->execute();
      
    }
 

}

/*
//Main para probar
$k1 = new Kits(null);
$k1->mostrar();
$k2 = new Kits(["medalla"=>true,"remera"=>true]);
$k2->mostrar();
*/