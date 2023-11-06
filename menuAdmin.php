<?php
require_once('menu.php');
require_once('carreraManager.php');
require_once('atletaManager.php');
  
class MenuAdmin extends Menu{
      
        private $carreraManager;
        private $atletaManager;

        public function __construct()
        {
                $this->carreraManager = new CarreraManager();
                $this->atletaManager = new AtletaManager();
        }

      //Un administrador va a operar con carreras
        protected function ABMcarreras(){
                $titulo = "Menu ABM Carreras";

                $opciones = [];

                //0 volver, 1 alta, 2 baja, 3 modificacion, 4 mostrar, 5 mostrar resultados

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Alta carrera";
                $opciones[1][2] = array($this->carreraManager,"altaCarrera");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja carrera";
                $opciones[2][2] = array($this->carreraManager,"bajaCarrera");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar carrera";
                $opciones[3][2] = array($this->carreraManager,"modificaCarrera");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar carreras";
                $opciones[4][2] = array($this->carreraManager,"mostrarCarreras");

                $opciones[5][0] = 5;
                $opciones[5][1] = "Mostrar resultados carrera";
                $opciones[5][2] = array($this->carreraManager,"mostrarResultadoCarrera");

                self::menu($titulo,$opciones);

        }        
        

        //Un administrador va a operar con participantes
        protected function ABMparticipantes(){
                $titulo = "Menu ABM Participantes";

                $opciones = [];

                //0 volver, 1 alta, 2 baja, 3 modificacion, 4 mostrar

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Alta atleta";
                $opciones[1][2] = array($this->atletaManager,"altaAtleta");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja atleta";
                $opciones[2][2] = array($this->atletaManager,"bajaAtleta");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar atleta";
                $opciones[3][2] = array($this->atletaManager,"modificaAtleta");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar atletas";
                $opciones[4][2] = array($this->atletaManager,"mostrarAtletas");

                $opciones[5][0] = 5;
                $opciones[5][1] = "Inscribir participante";
                $opciones[5][2] = array($this->carreraManager,"inscribirParticipante");


                self::menu($titulo,$opciones);

        }
       
        //Se le presentan todas las opciones para operar a un Administrador
        public function operacionesAdmin(){
                $titulo = "Operación a realizar: ";

                $opciones = [];

                //0 volver, 1 carreras, 2 participantes, 3 pagos

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Administrar carreras";
                $opciones[1][2] = array($this,"ABMcarreras");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Administrar participantes";
                $opciones[2][2] = array($this,"ABMparticipantes");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Administrar pagos";
                $opciones[3][2] = array($this,"menuPagos");

                self::menu($titulo,$opciones);
        }
}
