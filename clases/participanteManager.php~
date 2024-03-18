<?php
require_once('clases' . DIRECTORY_SEPARATOR . 'participante.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'arrayIdManager.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'ABMinterface.php');

class ParticipanteManager extends ArrayIdManager implements ABMinterface{

    private $idCarrera;


    //De la base de datos levanta todos los participantes inscriptos en una carrera y los agrega al arreglo para manipularlos
    public function levantar(){
        $idCarrera = $this->idCarrera;
        $sql = "select * from participantes
                where id_carrera = ".$idCarrera;
        $participantes = Conexion::query($sql);
        
        foreach ($participantes as $participante){
            $nuevoParticipante = new Participante($participante->id_carrera, $participante->id_atleta, $participante->pago, $participante->pos_general, $participante->pos_categoria, $participante->categoria, $participante->finalizo);
            $nuevoParticipante->setId($participante->id);
            $this->agregar($nuevoParticipante);
        }

    }
    
    //Crea el arreglo de Participantes a partir de los datos de la base de datos
    public function __construct($idCarrera)
    {
       $this->arreglo = [];
       $this->idCarrera = $idCarrera;
		 $this->clasificacion = [];	       
       $this->levantar();
    }
            
    //Muestra los participantes  de una carrera en particular
    public function mostrar(){
        $participantes = $this->getArreglo();
        Menu::subtitulo("Participantes inscriptos en la carrera");
        foreach ($participantes as $participante) {
            $participante->mostrar();
        }
    }
    
    //Muestra los participantes y resultados de una carrera en particular, combinando la información con los arreglos
    public function mostrarCombinado($atletas){
        $participantes = $this->getArreglo();
        $clasificacion = $this->getClasificacion();
        Menu::subtitulo("Participantes clasificados");
		  //Cantidad de inscriptos en la carrera
        $tamanio = $this->tamanio();		  
		  for ($pos = 1; $pos <= $tamanio; $pos++){
				if (isset($clasificacion[$pos])){       		
        			$participantes[$clasificacion[$pos]]->mostrarCombinado($atletas);
        		} 
		  }        
		  Menu::subtitulo("Participantes que aún no terminaron la carrera");        
        foreach ($participantes as $participante) {
				if (!$participante->getFinalizo()){            
            	$participante->mostrarCombinado($atletas);
            }	
        }
    }    
    
    //Inscribe un participante en la carrera, creándolo e ingresándolo en DB
    public function alta(){
        $idAtleta = Menu::readln("Ingrese el número de atleta a incribir: ");
        //Verifico que existe el atleta a inscribir
        $sql = "select *
                from atletas
                where id = ". $idAtleta;
        $atleta = Conexion::query($sql);
         
        if ($atleta != null){
				if($this->atletaEstaInscripto($idAtleta)){
					Menu::writeln('El atleta ya se encuentra inscripto en esta carrera'. PHP_EOL);
          	}else {
          	   //crea el objeto atleta
         		$atletaObjeto = new Atleta($atleta[0]->nombre, $atleta[0]->email, $atleta[0]->fechadenacimiento);
         		//Al objeto atleta le asigna el id de la base de datos
         		$atletaObjeto->setId($idAtleta);
            	Menu::writeln('Está por inscribir al siguiente atleta en la carrera: '. PHP_EOL);
            	$atletaObjeto->mostrar();
            	$rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
					if($rta == 'S' or $rta == 's') {            
            		$categoria = Menu::readln("Ingrese en qué categoria desearía inscibirse(F/M): ");
						if($categoria == "F" or $categoria == "f") {
							$categoria = "F";
						}else {
							$categoria = "M"; //Valor por defecto
						}		          		
          		$participante = new Participante($this->idCarrera, $idAtleta, 0, 0,0,$categoria,false);
            	$participante->save();
            	$this->agregar($participante);
            	Menu::writeln("El atleta: ". $idAtleta . " ha sido inscripto con éxito en la carrera: ". $this->idCarrera . " con el número(dorsal): " . $participante->getId());
          	
            }
        } } else{
            Menu::writeln("No existe el atleta, darlo de alta en el sistema. ");
        }
    }
   

    //Dar de baja un participante de una carrera, se pide el id del participante a eliminar(el número en esa carrera) . Se elimina de la base de datos y del arreglo
    public function baja(){
			$id = Menu::readln("Ingrese número del participante (dorsal) a eliminar: ");
        	if ($this->existeId($id)){
            $participante = $this->getPorId($id);
				Menu::writeln('Está por eliminar al siguiente participante de la carrera: '. PHP_EOL);
            $participante->mostrar();
            $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
				if($rta == 'S' or $rta == 's') {            
            	$participante->delete();
            	$this->eliminarPorId($id);
            	Menu::writeln("Participante eliminado con éxito");
       		}
       	} else{
       		//Utiliza readln para quedarse en pantalla la interacción del USR
				$id = Menu::readln("No existe el id a eliminar.");
            }
    }
 
   //Retorna un arreglo donde el id es la posición en la carreara y el contenido el id
    public function getClasificacion(){
			$clasificacion = [];
			$participantes = $this->getArreglo();
      	foreach ($participantes as $participante){       
        		if($participante->getFinalizo()) {
        			$clasificacion[$participante->getPosGeneral()] = $participante->getId(); 
        		}
        	}
        	return $clasificacion;
    }
		
	//Retorna si un atleta (dado su id) ya se encuentra inscripto en esta carrera	
	public function atletaEstaInscripto($idAtleta){
		$participantes = $this->getArreglo();
      foreach ($participantes as $participante){
      	if ($participante->getIdAtleta()==$idAtleta){
                return true;
	      }	
	   }
	   return false;
	 }   
	 
	// Ante cambios en la clasificación general reordena la clasificacion por categorias	 
	 public function modificarClasificacionCategorias(){
		$F = $M = 0;
		$participantes = $this->getArreglo();	          
      $clasificacion = $this->getClasificacion();
      for ($pos = 1; $pos <= $this->tamanio(); $pos++){
			if (isset($clasificacion[$pos])){       		
				if($participantes[$clasificacion[$pos]]->getCategoria() == 'F') {
					$F++;
					$participantes[$clasificacion[$pos]]->setPosCategoria($F);        			
				} else{	        			
					$M++;        				
       			$participantes[$clasificacion[$pos]]->setPosCategoria($M);
       	 	} 
       	 	$participantes[$clasificacion[$pos]]->update();
		   }
		}	 
	 }
	 
	 //Modifica la clasificación de un participante
	 public function modificarClasificacion($participante){
		$finalizo = Menu::readln("¿Finalizó la carrera? S/N: ");
		$clasificacion = $this->getClasificacion();      
      if ($finalizo  == "S" || $finalizo=="s" || ($finalizo == "" and $participante->getFinalizo()) ){
      	$posGeneral = Menu::readln("Ingrese posición general: ");
         if ($posGeneral != ""){
				if((!isset($clasificacion[$posGeneral]))and ($posGeneral <= $this->tamanio()))  {         	
         		$participante->setPosGeneral($posGeneral);
					$participante->setFinalizo(true);
	         } else {
	         	Menu::writeln("No pudo asignarse la posición, ya está asignada a otro participante");
	          }
	       }}else {
            	$participante->setFinalizo(false);
               $participante->setPosGeneral(0);
               $participante->setPosCategoria(0);
                        
          }
			$this->modificarClasificacionCategorias();
		}                              
         

    // Actualizar los datos de un participante por su ID
    public function modificacion() {
		$idParticipante = Menu::readln("Ingrese Id del participante (dorsal) a modificar: ");
      //Verifico que existe, ya está inscripto
      $participantes = $this->getArreglo();
      if ($this->existeId($idParticipante)){
			$participante = $this->getPorId($idParticipante);      	
      	Menu::writeln('Está por modificar al siguiente participante de la carrera: '. PHP_EOL);
         $participante->mostrar();
         $rta = Menu::readln(PHP_EOL . '¿Está seguro? S/N: ');            
			if($rta == 'S' or $rta == 's') {            
      		Menu::writeln("A continuación ingrese los nuevos datos, ENTER para dejarlos sin modificar");
         	$categoria = Menu::readln("Ingrese nueva categoria: ");
         	if ($categoria != ""){
					if($categoria == 'F' or $categoria == 'f') {                        
            		$participante->setCategoria("F");
            	}else {
            		$participante->setCategoria("M");
					}	
         	}
         	$pago = Menu::readln("Ingrese monto pagado: ");
         	if ($pago != ""){
	         	$participante->setPago($pago);
         	}
         	//La modificacion de la clasificación se procesa en una nueva función dada su complejidad
         	$this->modificarClasificacion($participante);
				$participante->update();
				      	
      	}
      	return;
       }
        $idParticipante = Menu::readln("El id ingresado no se encuentra inscripto");
    }
        
    // Cargar datos resultados carrera general por posición
    public function ingresarResultadosCarrera() {

	     //Cantidad de inscriptos en la carrera
        $tamanio = $this->tamanio();
		 //Cargo la clasificación ya cargada 	        
        $clasificacion = $this->getClasificacion();
        
        $participantes = $this->getArreglo();
        for ($pos = 1; $pos <= $tamanio ; $pos++) {
            if (!isset($clasificacion[$pos])){ 
					$idParticipante = Menu::readln("Ingrese id del participante (dorsal) que llegó en posición: " . $pos . " "); 
               if ($this->existeId($idParticipante)){
            	    $participante = $this->getPorId($idParticipante);
               	 if (!$participante->getFinalizo()){
                  	  $participante->setFinalizo(true);
                    	  $participante->setPosGeneral($pos);
                       $participante->update();
               }else {
                 Menu::writeln("Atención, el participante: " . $participante->getId() . " ya registra la posición: " . $participante->getPosGeneral() . ", si desea cambiarla utilice modificar participación");        
               }
            }else {
                Menu::writeln("El id ingresado no se encuentra inscripto");
            }
          }
        }
        $this->modificarClasificacionCategorias();    
       
    }


}

