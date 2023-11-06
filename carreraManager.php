<?php
require_once('menu.php');
require_once('carrera.php');
require_once('arrayIdManager.php');

class CarreraManager extends ArrayIdManager{
    
	//De la base de datos levanta las carreras y los kits de cada una y los agrega al arreglo para manipularlos
    protected function levantarCarreras(){
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
       $this->levantarCarreras();
    }


    // Actualizar los datos de un carrera por su ID
    public function actualizarCarrera($id, $nombre, $circuito,$fecha,$precio,$kits) {
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
    public function mostrarCarreras(){
	    $carreras = self::getArreglo();
        foreach ($carreras as $carrera) {
            $carrera->mostrar();
        }    
    }

    //Muestra por pantalla los resultados de una carrera, se solicita el id de la carrera
    public function mostrarResultadoCarrera(){
        $id = Menu::readln("Ingrese número de la carrera para mostrar resultados:");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            //Muestra los datos de la carrera
            $carrera->mostrar();
            //Muestra los datos de cada participante
            $carrera->mostrarResultado();
        } else{
            $id = Menu::readln("No existe el id de carrera para mostrar.");
        }

    }
 
    //Dar de baja una carrera, se pide el id de la carrera a eliminar. Se elimina de la base de datos y del arreglo
    public function bajaCarrera(){
        $id = Menu::readln("Ingrese número de la carrera a eliminar:");
        if ($this->existeId($id)){
            $carrera = $this->getPorId($id);
            $carrera->delete();
            $this->eliminarPorId($id);
        } else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }

    //Dar de alta una carrera  $id,$nombre,$circuito,$fecha,$precio,$kits
    public function altaCarrera(){
        $nombre = Menu::readln("Ingrese nombre carrera: ");
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
    public function modificaCarrera(){
        $id = Menu::readln("Ingrese Id de carrera a modificar: ");
        if($this->existeId($id)){
            $carreraModificado = $this->getPorId($id);         	   
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
            
            $this->modificar($carreraModificado);
    }else {
        Menu::writeln("El id ingresado no se encuentra entre nuestras carreras");
    }
}
}

