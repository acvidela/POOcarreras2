<?php
require_once('menues' . DIRECTORY_SEPARATOR . 'menu.php');
require_once('clases' . DIRECTORY_SEPARATOR . 'carrera.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'arrayIdManager.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'ABMinterface.php');

class CarreraManager extends ArrayIdManager implements ABMinterface{
    
	//De la base de datos levanta las carreras y los kits de cada una y los agrega al arreglo para manipularlos
    public function levantar(){
        $sql = "select * from carreras";
        $carreras = Conexion::query($sql);
        
        foreach ($carreras as $carrera){
            $idKits = $carrera->id_kits;
            $sql = "select * from kits
                    where id = $idKits";
            $kits = Conexion::query($sql);
            $nuevoKit = new Kits(['chip'=>$kits[0]->chip,'numero' => $kits[0]->numero,'remera'=>$kits[0]->remera,'medalla'=>$kits[0]->medalla]);
            $nuevoKit->setId($idKits);
            
            $nuevaCarrera = new Carrera($carrera->nombre, $carrera->circuito, $carrera->fecha,$carrera->precio,$nuevoKit);
            $nuevaCarrera->setId($carrera->id);
            
            $this->agregar($nuevaCarrera);
        }

    }
    
    //Crea el arreglo de Carreras a partir de los datos de la base de datos
    public function __construct()
    {
       $this->arreglo = [];
       $this->levantar();
    }


    // Actualizar los datos de un carrera por su ID
    public function actualizar($id, $nombre, $circuito,$fecha,$precio,$kits) {
	 	$carreras = $this->getArreglo();        
      	foreach ($carreras as $carrera) {
      	   if ($carrera->getId() === $id) {
                $carrera->setNombre($nombre);
                $carrera->setCircuito($circuito);
                $carrera->setFecha($fecha);
                $carrera->setPrecio($precio);
                $carrera->setKits($kits);
                break;
            }
        }
    }
    
    //Muestra por pantalla todas las carreras
    public function mostrar(){
	    $carreras = self::getArreglo();
	    Menu::cls();        
        Menu::subtitulo('Lista de carreras en nuestro sistema');
			$lineas = 0;
			foreach ($carreras as $carrera) {
         	$carrera->mostrar();
            $lineas+=2;
            if ((($lineas) % (Menu::lineasPorPagina())) === 0) {
		        Menu::waitForEnter();
      		  Menu::cls(); // Limpiar la pantalla antes de imprimir las siguientes líneas
    			}
        } 
        Menu::waitForEnter();   
    }

	//Muestra por pantalla las carreras próximas a realizarse
    public function mostrarProximas(){
	    $carreras = self::getArreglo();
        Menu::subtitulo('Lista de carreras próximas a realizarse');
			$lineas = 0;
			Menu::cls();        
			foreach ($carreras as $carrera) {
				$fechaCarrera = new DateTime($carrera->getFecha());
				$hoy = new DateTime();
				if ($fechaCarrera > $hoy){        	
         		$carrera->mostrar();
            	$lineas+=2;
            	if ((($lineas + 1) % (Menu::lineasPorPagina())) === 0) {
		        		Menu::waitForEnter();
      		  		Menu::cls(); // Limpiar la pantalla antes de imprimir las siguientes líneas
    				}
    			}	
        } 
        Menu::waitForEnter();   
    }
    
	//Ingresa por pantalla los resultados de una carrera, se solicita el id de la carrera
    public function ingresarResultadosCarrera(){
        $id = Menu::readln("Ingrese número de la carrera para ingresar resultados: ");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            Menu::writeln('Está por ingresar los resultados de la carrera: '. PHP_EOL);
         	$carrera->mostrar();
         	$rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
				if($rta == 'S' or $rta == 's') {            
            	$carrera->ingresarResultadosCarrera();
        		}
        }else{
            	$id = Menu::readln("No existe el id de carrera para mostrar.");
        }
    }


     //Muestra por pantalla los resultados de una carrera, se solicita el id de la carrera
    public function mostrarResultadoCarrera($atletas){
        $id = Menu::readln("Ingrese número de la carrera para mostrar resultados: ");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            //Muestra los datos de la carrera
            $carrera->mostrar();
            //Muestra los datos de cada participante
            $carrera->mostrarResultado($atletas);
        } else{
            $id = Menu::readln("No existe el id de carrera para mostrar.");
        }

    }

    //Modifica los datos de un participante(posicion, pago, categoria) en una carrera, se solicita el id de la carrera
    public function modificarParticipante(){
        $id = Menu::readln("Ingrese número de la carrera en la que realizar modificaciones de participantes: ");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            //Muestra los datos de la carrera
            $carrera->modificarParticipante();
        } else{
            $id = Menu::readln("No existe el id de carrera seleccionada.");
        }

    }

    //Dar de baja una carrera, se pide el id de la carrera a eliminar. Se elimina de la base de datos y del arreglo
    public function baja(){
		  Menu::subtitulo('Dar de baja una carrera');        
        $id = Menu::readln("Ingrese número de la carrera a eliminar: ");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            Menu::writeln('Está por eliminar la siguiente carrera del sistema: '. PHP_EOL);
            $carrera->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? Se eliminarán también los participantes inscriptos S/N: ');            
				if($rta == 'S' or $rta == 's') {
					$carrera->delete();
            	$this->eliminarPorId($id);
            	Menu::writeln("Carrera eliminada con éxito");
            }	
        } else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }

    //Dar de alta una carrera  $id,$nombre,$circuito,$fecha,$precio,$kits
    public function alta(){
			Menu::subtitulo('Dar de alta una carrera');        
        $nombre = Menu::readln(PHP_EOL . "Ingrese nombre carrera: ");
        $circuito = Menu::readln("Ingrese circuito: ");
        $fecha = Menu::readln("Ingrese fecha de carrera, con el formato dd/mm/yyyy: ");
        $precio = Menu::readln("Ingrese precio de la carrera: ");
        $kits = new Kits(null);
        $rta = Menu::readln("La carrera entregará chip? S/N: ");
        if ($rta== 'S' || $rta == 's'){
                $kits->setChip(TRUE);
        }
        $rta = Menu::readln("La carrera entregará número? S/N: ");
        if ($rta== 'S' || $rta == 's'){
                $kits->setNumero(TRUE);
        }
        $rta = Menu::readln("La carrera entregará medalla? S/N: ");
        if ($rta== 'S' || $rta == 's'){
                $kits->setMedalla(TRUE);
        }
        $rta = Menu::readln("La carrera entregará remera? S/N: ");
        if ($rta== 'S' || $rta == 's'){
                $kits->setRemera(TRUE);
        }
        $carrera = new Carrera($nombre,$circuito,$fecha, $precio,$kits);
        //Guarda la carrera en la tabla carreras y guarda el kit en la tabla kits, relaciona las claves entre ellos
        $carrera->save();

        $this->agregar($carrera);
        Menu::writeln("Carrera agregada con éxito");

    }


    //Modifica un kits de una  carrera. $chip, $numero, $remera, $medalla
    private function modificaKits($id_carrera){
            $kitsModificado = $this->getPorId($id_carrera)->getKits();
            $rta = Menu::readln("La carrera entregará chip? S/N: ");
            if ($rta== 'S' || $rta == 's'){
                    $kitsModificado->setChip(TRUE);
            } elseif ($rta== 'N' || $rta == 'n'){
                    $kitsModificado->setChip(FALSE);
            }
            $rta = Menu::readln("La carrera entregará número? S/N: ");
            if ($rta== 'S' || $rta == 's'){
                    $kitsModificado->setNumero(TRUE);
            } elseif ($rta== 'N' || $rta == 'n'){
                    $kitsModificado->setNumero(FALSE);
            }
            $rta = Menu::readln("La carrera entregará medalla? S/N: ");
            if ($rta== 'S' || $rta == 's'){
                    $kitsModificado->setMedalla(TRUE);
            } elseif ($rta== 'N' || $rta == 'n'){
                    $kitsModificado->setMedalla(FALSE);
            }
            $rta = Menu::readln("La carrera entregará remera? S/N: ");
            if ($rta== 'S' || $rta == 's'){
                    $kitsModificado->setRemera(TRUE);
            } elseif ($rta== 'N' || $rta == 'n'){
                    $kitsModificado->setRemera(FALSE);
            }
            $kitsModificado->update();
            return $kitsModificado;
    }

    //Modificar una carrera $id,$nombre,$circuito,$fecha,$precio,$kits
    public function modificacion(){
			Menu::subtitulo('Modificar una carrera');   			        
        $id = Menu::readln("Ingrese Id de carrera a modificar: ");
        if($this->existeId($id)){
            $carreraModificado = $this->getPorId($id);         	   
				Menu::writeln('Está por modificar la siguiente carrera del sistema: '. PHP_EOL);
            $carreraModificado->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
				if($rta == 'S' or $rta == 's') {            
            	Menu::writeln("A continuación ingrese los nuevos datos, enter para dejarlos sin modificar");
            	$nombre = Menu::readln("Ingrese nombre: ");
            	if ($nombre != ""){
               	 $carreraModificado->setNombre($nombre);
            	}
            	$circuito = Menu::readln("Ingrese circuito: ");
            	if ($circuito != ""){
               	 $carreraModificado->setCircuito($circuito);
            	}
            	$fecha =  Menu::readln("Ingrese fecha de carrera, con el formato dd/mm/yyyy: ");
            	if ($fecha != ""){
         	   	$carreraModificado->setFecha($fecha);
        			}
            	$precio =  Menu::readln("Ingrese precio de carrera: ");
            	if ($precio != ""){
            	$carreraModificado->setPrecio($precio);
        			}
            	//Pide los datos y lo modifica en la base de datos
            	$kits = $this->modificaKits($id);
	            $carreraModificado->setKits($kits);
   	         $carreraModificado->update();
   	         Menu::writeln("Carrera modificada con éxito");
      		}    
        }else {
   	     Menu::writeln("El id ingresado no se encuentra entre nuestras carreras");
    }
    }

//Solicita el número de carrera y el número de atleta a inscribir en la carrera    
    public function inscribirParticipante(){
        $id = Menu::readln("Ingrese Id de carrera para inscribir: ");
        if($this->existeId($id)){
            $carrera = $this->getPorId($id);
            Menu::writeln('Está por inscribir en la siguiente carrera: '. PHP_EOL);
            $carrera->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
			if($rta == 'S' or $rta == 's') {            
                $participantes = $carrera->getParticipantes();
                //Agrega en el arreglo/tabla de participantes uno nuevo en la carrera deseada
                 $participantes->alta();
             }
        }
        else{
            $id = Menu::readln("No existe el id de carrera ingresado.");
        }
    }
    
   //Solicita en qué carrera, qué participante quiere dar de baja 
	public function borrarParticipante(){
        $id = Menu::readln("Ingrese Id de carrera en la que dar de baja un participante: ");
        if($this->existeId($id)){
            $carrera = $this->getPorId($id);
            Menu::writeln('Está por borrar en la siguiente carrera: '. PHP_EOL);
            $carrera->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
			if($rta == 'S' or $rta == 's') {            
                $participantes = $carrera->getParticipantes();
                //Dará de baja un participante de la carrera elegida
                 $participantes->baja();
             }
        }
        else{
            $id = Menu::readln("No existe el id de carrera ingresado.");
        }
    }

}

