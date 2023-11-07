<?php
require_once('menu.php');
require_once('clases\carreraManager.php');
require_once('clases\atletaManager.php');
  
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

                self::menu($titulo,$opciones);

        }        
        

        //Un administrador va a operar con atletas
        protected function ABMatletas(){
                $titulo = "Menu ABM atletas";

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
               
                self::menu($titulo,$opciones);

        }
       
        //Un administrador va a operar en una carrera  y sus participantes
        protected function ABMparticipantes(){
                $titulo = "Menu ABM Participantes";
        
                $opciones = [];
        
                //0 volver, 1 mostrar, inscribir, 
        
                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase
        
                $opciones[1][0] = 1;
                $opciones[1][1] = "Mostrar resultados carrera";
                $opciones[1][2] = array($this->carreraManager,"mostrarResultadoCarrera");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Inscribir participante";
                $opciones[2][2] = array($this->carreraManager,"inscribirParticipante");
        
                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar participación";
                $opciones[3][2] = array($this->carreraManager,"modificarParticipante");
        
                /*
                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar atleta";
                $opciones[3][2] = array($this->atletaManager,"modificaAtleta");
        
                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar atletas";
                $opciones[4][2] = array($this->atletaManager,"mostrarAtletas");
        
                $opciones[5][0] = 5;
                $opciones[5][1] = "Inscribir participante";
                $opciones[5][2] = 
        
        */
                self::menu($titulo,$opciones);
        
        }
        
        //Se le presentan todas las opciones para operar a un Administrador
        public function operacionesAdmin(){
                $titulo = "Operación a realizar: ";

                $opciones = [];

                //0 volver, 1 carreras, 2 atletas, 3 participantes

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Administrar carreras";
                $opciones[1][2] = array($this,"ABMcarreras");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Administrar atletas";
                $opciones[2][2] = array($this,"ABMatletas");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Administrar participantes en una carrera";
                $opciones[3][2] = array($this,"ABMParticipantes");

                self::menu($titulo,$opciones);
        }
}
