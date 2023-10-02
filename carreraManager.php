<?php
require_once('carrera.php');
require_once('arrayIdManager.php');

class CarreraManager extends ArrayIdManager{
    
	// Agregar carreras  ($id,$nombre,$circuito,$fecha,$precio,$kits
	public function cargaInicial(){
    	self::agregar(new Carrera(1,'Desafio del Lago', 'el dique','04/06/2023','$5000', new Kits(["medalla"=>true,"remera"=>true])));
    	self::agregar(new Carrera(2,'Desafio de las Animas', 'las animas','04/07/2023','$7000',new Kits(null)));
    	self::agregar(new Carrera(3,'Pioneros', 'Los pioneros','03/08/2023','$3000',new Kits(["chip"=>true,"remera"=>true])));
    	
   } 	
    
   public function getJSON() {
    $jsonCarrera = [];
    $carrera = $this->getArreglo();

    foreach ($carrera as $carrera) {
        $jsonCarrera[] = $carrera->toArray(); // Asumiendo que tienes un método "toArray()" en la clase Atleta
    }

    $jsonString = json_encode($jsonCarrera, JSON_PRETTY_PRINT);

    //$ids = $this->getIds(); // Supongo que esta función devuelve una cadena JSON válida con los IDs

    $finalJson = [
        "carreras" => $jsonCarrera,
    //    "ids" => json_decode($ids) // Decodificar la cadena JSON de IDs
    ];

    return json_encode($finalJson, JSON_PRETTY_PRINT);
}
    
   
   
    // Actualizar los datos de un carrera por su ID
    public function actualizarCarrera($id, $nombre, $circuito,$fecha,$precio,$kits) {
	 	$carreras = self::getArreglo();        
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
    

   
   //Del archivo de texto crea el arreglo de carreras con carreras ya existentes   
    public function setJSON($datos){
            $carrerasDatos = json_decode($datos,1);
            if (isset($carrerasDatos['carreras'])){
                $carreras = $carrerasDatos['carreras'];     
                foreach ($carreras as $carrera) {
                    $kits = $carrera['kits'];
                    $nuevoKits = new Kits($kits['chip'],$kits['numero'],$kits['remera'],$kits['medalla']);      
                    $nuevaCarrera = new Carrera($carrera['id'],$carrera['nombre'], $carrera['circuito'],$carrera['fecha'],$carrera['precio'],$nuevoKits);
                    $this->agregarJSON($nuevaCarrera);
                }
      //          $this->setIds($carrerasDatos['ids']);
            }
    
    }   
   
   public function mostrarCarreras(){
	  $carreras = self::getArreglo();
      foreach ($carreras as $carrera) {
    		echo "ID: " . $carrera->getId() . ", Nombre: " . $carrera->getNombre() . ", Circuito: " . $carrera->getCircuito() .", Fecha: " . $carrera->getFecha() . ", Precio: " .$carrera->getPrecio();
            echo(PHP_EOL);
            $carrera->getKits()->mostrar();
            echo(PHP_EOL);
      }    
      echo(PHP_EOL);
    }

    
}

/*
//Main para probar
// Crear el objeto del Administrador de carrera

$carreraManager2 = new carreraManager();
$carreraManager2->cargaInicial();

$carreraManager2->mostrarCarreras();

// Actualizar un carrera $id, $nombre, $circuito,$fecha,$precio,$kits
$carreraManager2->actualizarcarrera(1, 'super Desafio', 'la cascada','14/08/2001','$5000',new Kits());

// Eliminar un carrera
$carreraManager2->eliminarPorId(2);

// Mostrar carreras después de la actualización y eliminación
$carreraManager2->mostrarCarreras();
*/