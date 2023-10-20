<?php
require_once('atleta.php');
require_once('arrayIdManager.php');

class AtletaManager extends ArrayIdManager{

    //De la base de datos levanta los atletas y los agrega al arreglo para manipularlos
    protected function levantarAtletas(){
        $sql = "select * from atletas";
        $atletas = Conexion::query($sql);
        
        foreach ($atletas as $atleta){
            $nuevoAtleta = new Atleta($atleta->nombre, $atleta->email, $atleta->fechadenacimiento);
            $nuevoAtleta->setId($atleta->id);
            
            $this->agregar($nuevoAtleta);
        }

    }
    
    //Crea el arreglo de Carreras a partir de los datos de la base de datos
    public function __construct()
    {
       $this->arreglo = [];
       $this->levantarAtletas();
    }
            
    //Dar de baja un atleta, se pide el id del atleta a eliminar. Se elimina de la base de datos y del arreglo
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

