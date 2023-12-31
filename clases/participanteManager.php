<?php
require_once('clases\participante.php');
require_once('lib\arrayIdManager.php');
require_once('lib\ABMinterface.php');

class ParticipanteManager extends ArrayIdManager implements ABMinterface{

    private $idCarrera;

    //De la base de datos levanta todos los participantes inscriptos en una carrera y los agrega al arreglo para manipularlos
    public function levantar(){
        $idCarrera = $this->idCarrera;
        $sql = "select * from participantes
                where id_carrera = ".$idCarrera;
        $participantes = Conexion::query($sql);
        
        foreach ($participantes as $participante){
            $nuevoParticipante = new Participante($participante->id_carrera, $participante->id_atleta, $participante->pago, $participante->pos_general, $participante->pos_categoria, $participante->categoria);
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
            
    
    //Muestra los participantes y resultados de una carrera en particular
    public function mostrar(){
        $participantes = $this->getArreglo();
        Menu::subtitulo("Participantes inscriptos en la carrera");
        foreach ($participantes as $participante) {
            $participante->mostrar();
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
            $participante = new Participante($this->idCarrera, $idAtleta, 0, 0,0,$categoria);
            $participante->save();
            $this->agregar($participante);
        } else{
            Menu::writeln("No existe el atleta, darlo de alta en el sistema. ");
        }
    }
   

    //Dar de baja un participante de una carrera, se pide el id del participante a eliminar. Se elimina de la base de datos y del arreglo
    public function baja(){
        $id = Menu::readln("Ingrese número del atleta a eliminar: ");
        if ($this->existeId($id)){
            $atleta = $this->getPorId($id);
            $atleta->delete();
            $this->eliminarPorId($id);
        } else{
            $id = Menu::readln("No existe el id a eliminar.");
        }
    }
    
    // Actualizar los datos de un participante por su ID
    public function modificacion() {
	    $idAtleta = Menu::readln("Ingrese Id de atleta a modificar: ");
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
                    $posGeneral = Menu::readln("Ingrese posición general: ");
                    if ($posGeneral != ""){
                        $participante->setPosGeneral($posGeneral);
                    }
                    $posCategoria = Menu::readln("Ingrese posición en la categoría: ");
                    if ($posGeneral != ""){
                        $participante->setPosCategoria($posCategoria);
                    }

                    $participante->update();
                    $this->agregar($participante);
                    return;
                }else {
                    Menu::writeln("El id ingresado no se encuentra inscripto");
                }
            }    
        }
        Menu::writeln("El id ingresado no se encuentra inscripto");
    }
        


}

