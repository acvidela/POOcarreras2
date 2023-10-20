<?php
require_once('menu.php');
require_once('carreraManager.php');
require_once('atletaManager.php');
  
class MenuAdmin extends Menu{
      
        
      //Un administrador va a operar con carreras
        protected function ABMcarreras(){
                $titulo = "Menu ABM Carreras";

                $opciones = [];

                $carreraManager = new CarreraManager();

                //0 volver, 1 alta, 2 baja, 3 modificacion, 4 mostrar

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Alta carrera";
                $opciones[1][2] = array($carreraManager,"altaCarrera");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja carrera";
                $opciones[2][2] = array($carreraManager,"bajaCarrera");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar carrera";
                $opciones[3][2] = array($carreraManager,"modificaCarrera");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar carreras";
                $opciones[4][2] = array($carreraManager,"mostrarCarreras");

                self::menu($titulo,$opciones);

        }        
        

        //Un administrador va a operar con participantes
        protected function ABMparticipantes(){
                $titulo = "Menu ABM Participantes";

                $opciones = [];

                $atletaManager = new AtletaManager();

                //0 volver, 1 alta, 2 baja, 3 modificacion, 4 mostrar

                $opciones[0][0]= 0;
                $opciones[0][1] = "Volver al menu anterior";
                $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase

                $opciones[1][0] = 1;
                $opciones[1][1] = "Alta atleta";
                $opciones[1][2] = array($atletaManager,"altaAtleta");

                $opciones[2][0] = 2;
                $opciones[2][1] = "Baja atleta";
                $opciones[2][2] = array($atletaManager,"bajaAtleta");

                $opciones[3][0] = 3;
                $opciones[3][1] = "Modificar atleta";
                $opciones[3][2] = array($atletaManager,"modificaAtleta");

                $opciones[4][0] = 4;
                $opciones[4][1] = "Mostrar atletas";
                $opciones[4][2] = array($atletaManager,"mostrarAtletas");

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
