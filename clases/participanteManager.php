<?php
require_once('clases' . DIRECTORY_SEPARATOR . 'participante.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'arrayIdManager.php');
require_once('lib' . DIRECTORY_SEPARATOR . 'ABMinterface.php');

class ParticipanteManager extends ArrayIdManager implements ABMinterface{

    private $idCarrera;
    private $clasificacion; //Arreglo  de participantes clasificados por posición en la carrera

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
        Menu::subtitulo("Participantes inscriptos en la carrera");
        foreach ($participantes as $participante) {
            $participante->mostrarCombinado($atletas);
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
            $categoria = Menu::readln("Ingrese en qué categoria desearía inscibirse: ");
            $participante = new Participante($this->idCarrera, $idAtleta, 0, 0,0,$categoria,false);
            $participante->save();
            $this->agregar($participante);
        } else{
            Menu::writeln("No existe el atleta, darlo de alta en el sistema. ");
        }
    }
   

    //Dar de baja un participante de una carrera, se pide el id del participante a eliminar(el número en esa carrera) . Se elimina de la base de datos y del arreglo
    public function baja(){
        $id = Menu::readln("Ingrese número del participante (dorsal) a eliminar: ");
        if ($this->existeId($id)){
            $atleta = $this->getPorId($id);
            $atleta->delete();
            $this->eliminarPorId($id);
        } else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }
    
    //Retorna  una lista con los nombres y dorsales
    public function getClasificacion(){
        $this->clasificacion = [];
        $tamanio = $this->tamanio();
        $participantes = $this->getArreglo();
        foreach ($participantes as $participante){
            if ($participante->getFinalizo()){
                $pos = $participante->getPosGeneral();
                $this->clasificacion[$pos] = $participante->getId();
            }
        }
        return $this->clasificacion;
    }

    // Actualizar los datos de un participante por su ID
    public function modificacion() {
	    $idAtleta = Menu::readln("Ingrese Id del atleta a modificar: ");
        //Verifico que existe, ya está inscripto
        $participantes = $this->getArreglo();
        foreach ($participantes as $participante){
            if ($participante->getIdAtleta()==$idAtleta){
                $idParticipante = $participante->getId();
                if ($this->existeId($idParticipante)){
                    Menu::writeln("A continuación ingrese los nuevos datos, enter para dejarlos sin modificar");
                    $participante = $this->getPorId($idParticipante);
                    $categoria = Menu::readln("Ingrese nueva categoria: ");
                    if ($categoria != ""){
                        $participante->setCategoria($categoria);
                    }
                    $pago = Menu::readln("Ingrese monto pagado: ");
                    if ($pago != ""){
                        $participante->setPago($pago);
                    }
                    $finalizo = Menu::readln("¿Finalizó la carrera? Y/N: ");
                    if ($finalizo  == "Y" || $finalizo=="y"){
                        $participante->setFinalizo(true);
                        $posGeneral = Menu::readln("Ingrese posición general: ");
                        if ($posGeneral != ""){
                            $participante->setPosGeneral($posGeneral);
                        }
                        $posCategoria = Menu::readln("Ingrese posición en la categoría: ");
                        if ($posGeneral != ""){
                            $participante->setPosCategoria($posCategoria);
                        }
                    } else {
                        $participante->setFinalizo(false);
                        $participante->setPosGeneral(0);
                        $participante->setPosCategoria(0); 
                    }
                }
                $participante->update();
                $this->agregar($participante);
                return;
            }
        }    
        Menu::writeln("El id ingresado no se encuentra inscripto");
    }
        
    // Cargar datos resultados carrera general por posición
    public function ingresarResultadosCarrera() {
	    //$idAtleta = Menu::readln("Ingrese Id de atleta a modificar: ");
        //Cargo todos los participantes de la carrera
        $tamanio = $this->tamanio();
        $clasificacion = $this->getClasificacion();
        //Para contabilizar las categorias
        $M = 0;
        $F = 0;
        $participantes = $this->getArreglo();
        for ($pos = 1; $pos <= $tamanio ; $pos++) {
            if (!isset($participantes[$pos])){ 

            $idParticipante = Menu::readln("Ingrese id del participante (dorsal) que llegó en posición: " . $pos . " "); 
            
            if ($this->existeId($idParticipante)){
                $participante = $this->getPorId($idParticipante);
                if (!$participante->getFinalizo()){
                    $participante->setFinalizo(true);
                    $participante->setPosGeneral($pos);
                    if ($participante->getCategoria() == "F"){
                        $F++;
                        $participante->setPosCategoria($F);
                    } else {
                        $M++;
                        $participante->setPosCategoria($M);
                    }
                    $participante->update();
               }else {
                 Menu::writeln("Atención, el participante: " . $participante->getId() . " ya registra la posición: " . $participante->getPosGeneral() . ", si desea cambiarla utilice modificar participación");        
                }
            } else {
                Menu::writeln("El id ingresado no se encuentra inscripto");
            
            }
        }
        }    
       
    }


}

