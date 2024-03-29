<?php
require_once('menu.php');
require_once('clases' . DIRECTORY_SEPARATOR . 'carreraManager.php');
require_once('clases' . DIRECTORY_SEPARATOR . 'atletaManager.php');
  
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

                //0 volver, 1 alta, 2 baja, 3 modificacion, 4 mostrar, 5 mostrar próximas

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Alta carrera";
                $opciones[1][2] = array($this->carreraManager,"alta");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja carrera";
                $opciones[2][2] = array($this->carreraManager,"baja");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar carrera";
                $opciones[3][2] = array($this->carreraManager,"modificacion");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar todas las carreras";
                $opciones[4][2] = array($this->carreraManager,"mostrar");

                
					 $opciones[5][0] = 5;
                $opciones[5][1] = "Mostrar próximas carreras";
                $opciones[5][2] = array($this->carreraManager,"mostrarProximas");
                
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
                $opciones[1][2] = array($this->atletaManager,"alta");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja atleta";
                $opciones[2][2] = array($this->atletaManager,"baja");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar atleta";
                $opciones[3][2] = array($this->atletaManager,"modificacion");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar atletas";
                $opciones[4][2] = array($this->atletaManager,"mostrar");
               
                self::menu($titulo,$opciones);

        }
       
        //Un administrador va a operar en una carrera  y sus participantes
        protected function ABMparticipantes(){
                $titulo = "Menu ABM Participantes";
        
                $opciones = [];
        
                //0 volver, 1 inscribir, 2 borrar, 3 modificar, 4 mostrar resultados, 5 cargar resultados
        
                $opciones[0][0] = 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase
        
                $opciones[1][0] = 1;
                $opciones[1][1] = "Inscribir participante";
                $opciones[1][2] = array($this->carreraManager,"inscribirParticipante");
        
					 $opciones[2][0] = 2;
                $opciones[2][1] = "Dar de baja un participante";
                $opciones[2][2] = array($this->carreraManager,"borrarParticipante");
                        
                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar participación";
                $opciones[3][2] = array($this->carreraManager,"modificarParticipante");
        
					 $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar resultados carrera";
                $opciones[4][2] = array($this->carreraManager,"mostrarResultadoCarrera");
                $opciones[4][3] = $this->atletaManager;                
                
                $opciones[5][0] = 5;
                $opciones[5][1] = "Cargar resultados carrera";
                $opciones[5][2] = array($this->carreraManager,"ingresarResultadosCarrera");

					                                 
                self::menu($titulo,$opciones);
        
        }
        
        //Se le presentan todas las opciones para operar a un Administrador
        public function operacionesAdmin(){
                $titulo = "Operación a realizar: ";

                $opciones = [];

                //0 volver, 1 carreras, 2 atletas, 3 participantes

                $opciones[0][0]= 0;
                $opciones[0][1] = "Salir del sistema";
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
