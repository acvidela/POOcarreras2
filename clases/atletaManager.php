<?php
require_once('clases' . DIRECTORY_SEPARATOR . 'atleta.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'arrayIdManager.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'ABMinterface.php');

class AtletaManager extends ArrayIdManager implements ABMinterface{

    //De la base de datos levanta los atletas y los agrega al arreglo para manipularlos
    public function levantar(){
        $sql = "select * 
                from atletas";
        $atletas = Conexion::query($sql);
        
        foreach ($atletas as $atleta){
            //crea el objeto atleta
            $nuevoAtleta = new Atleta($atleta->nombre, $atleta->email, $atleta->fechadenacimiento);
            //Al objeto atleta le asigna el id de la base de datos
            $nuevoAtleta->setId($atleta->id);
            //Lo agrega al arreglo
            $this->agregar($nuevoAtleta);
        }

    }
    
    //Crea el arreglo de Carreras a partir de los datos de la base de datos
    public function __construct() {
        $this->levantar();
    }
    
    /*
    /   Guarda el atleta en la base de datos y le setea el id generado por la base de datos al insertarlo
    */
    public function alta() {
        $nombre = Menu::readln("Ingrese nombre y apellido: ");
        $email = Menu::readln("Ingrese email: ");
        $fechaNacimiento =  Menu::readln("Ingrese fecha de nacimiento, con el formato dd/mm/yyyy: ");
        //Crea el nuevo objeto atleta
        $atleta = new Atleta($nombre,$email,$fechaNacimiento);
        //Lo inserta en la base de datos
        $atleta->save();
        //Lo agrega al arreglo
        $this->agregar($atleta);
        $rta = Menu::readln("Atleta creado con éxito");
    }

    //Dar de baja un atleta, se pide el id del atleta a eliminar. Se elimina de la base de datos y del arreglo
    public function baja(){
        $id = Menu::readln("Ingrese número del atleta a eliminar:");
        if ($this->existeId($id)){
            $atleta = $this->getPorId($id);
            Menu::writeln('Está por eliminar al siguiente atleta del sistema: '. PHP_EOL);
            $atleta->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
            if($rta == 'S' or $rta == 's') {  
                //Lo elimina de la base de datos
                $atleta->delete();
                //Lo elimina del arreglo
                $this->eliminarPorId($id);
                $rta = Menu::readln("Atleta eliminado con éxito");
        } }else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }
    
    // Actualizar los datos de un atleta por su ID
    public function modificacion() {
	    $id = Menu::readln("Ingrese Id de atleta a modificar: ");
        if($this->existeId($id)){
            $atletaModificado = $this->getPorId($id);         	   
            Menu::writeln('Está por modificar al siguiente atleta del sistema: '. PHP_EOL);
            $atletaModificado->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
            if($rta == 'S' or $rta == 's') {  
                Menu::writeln("A continuación ingrese los nuevos datos, ENTER para dejarlos sin modificar");
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
            $rta = Menu::readln("Atleta modificado con éxito");
        }}else {
                Menu::writeln("El id ingresado no se encuentra entre nuestros atletas");
        }
    }
       
    // Mostrar por pantalla todos los atletas
	public function mostrar(){
		$atletas = $this->getArreglo();
		Menu::cls();		
		Menu::subtitulo('Lista de atletas en nuestro sistema');
		$lineas = 0;
		  
      foreach ($atletas as $atleta) {
	    	$atleta->mostrar();
   	   $lineas+=1;
         if ((($lineas) % (Menu::lineasPorPagina())) === 0) {
		   	Menu::waitForEnter();
      		Menu::cls(); // Limpiar la pantalla antes de imprimir las siguientes líneas
    		}
        } 
        Menu::waitForEnter();   
    }
}

