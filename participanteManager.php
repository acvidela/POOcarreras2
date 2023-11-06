<?php
require_once('participante.php');
require_once('arrayIdManager.php');

class ParticipanteManager extends ArrayIdManager{

    private $idCarrera;

    //De la base de datos levanta todos los participantes inscriptos en una carrera y los agrega al arreglo para manipularlos
    protected function levantarParticipantes(){
        $idCarrera = $this->idCarrera;
        $sql = "select * from participantes
                where id_carrera = ".$idCarrera;
        $participantes = Conexion::query($sql);
        
        foreach ($participantes as $participante){
            $nuevoParticipante = new Participante($participante->id_carrera, $participante->id_atleta, $participante->pago, $participante->posGeneral, $participante->pos_categoria, $participante->categoria);
            $nuevoParticipante->setId($participante->id);
            
            $this->agregar($nuevoParticipante);
        }

    }
    
    //Crea el arreglo de Participantes a partir de los datos de la base de datos
    public function __construct($idCarrera)
    {
       $this->arreglo = [];
       $this->idCarrera = $idCarrera;
       $this->levantarParticipantes();
    }
            
    
    //Muestra los participantes y resultados de una carrera en particular
    public function mostrar(){
        $participantes = $this->getArreglo();
        foreach ($participantes as $participante) {
            $participante->mostrar();
        }
    }    
    
    //Inscribe un participante en la carrera, creándolo e ingresándolo en DB
    public function altaParticipante(){
        $idAtleta = Menu::readln("Ingrese el número de atleta a incribir: ");
        $categoria = Menu::readln("Ingrese en qué categoria desearía inscibirse: ");
        
        $participante = new Participante($this->idCarrera, $idAtleta, 0, 0,0,$categoria);
        $participante->save();

        $this->agregar($participante);
    }
   

    //Dar de baja un participante de una carrera, se pide el id del participante a eliminar. Se elimina de la base de datos y del arreglo
    public function bajaAtleta(){
        $id = Menu::readln("Ingrese número del atleta a eliminar:");
        if ($this->existeId($id)){
            $atleta = $this->getPorId($id);
            $atleta->delete();
            $this->eliminarPorId($id);
        } else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }
    
    // Actualizar los datos de un atleta por su ID
    public function modificaAtleta() {
	    $id = Menu::readln("Ingrese Id de atleta a modificar: ");
        if($this->existeId($id)){
            $atletaModificado = $this->getPorId($id);         	   
            Menu::writeln("A continuación ingrese los nuevos datos, enter para dejarlos sin modificar");
            $nombre = Menu::readln("Ingrese nombre y apellido: ");
            if ($nombre != ""){
                $atletaModificado->setNombre($nombre);
            }
            $email = Menu::readln("Ingrese email: ");
            if ($email != ""){
                $atletaModificado->setEmail($email);
            }
            $fechaNacimiento =  Menu::readln("Ingrese fecha de nacimiento, con el formato dd/mm/yyyy: ");
            if ($fechaNacimiento != ""){
                $atletaModificado->setFechaNacimiento($fechaNacimiento);
            }
            //Lo modifica en la Base de Datos
            $atletaModificado->update();
            //Lo modifica en el arreglo  
            $this->modificar($atletaModificado);
                }else {
                    Menu::writeln("El id ingresado no se encuentra entre nuestros atletas");
            }
    }
       
    // Mostrar por pantalla todos los atletas
	public function mostrarAtletas(){
		$atletas = $this->getArreglo();
		foreach ($atletas as $atleta) {
	    	$atleta->mostrar();
   	 	echo(PHP_EOL);
      }
      echo(PHP_EOL);
   }

    /*
    /   Guarda el atleta en la base de datos y le setea el id generado por la base de datos al insertarlo
    */
    public function altaAtleta() {
        $nombre = Menu::readln("Ingrese nombre y apellido: ");
        $email = Menu::readln("Ingrese email: ");
        $fechaNacimiento =  Menu::readln("Ingrese fecha de nacimiento, con el formato dd/mm/yyyy: ");
    
        $atleta = new Atleta($nombre,$email,$fechaNacimiento);
        $atleta->save();

        $this->agregar($atleta);
   
    }

}

