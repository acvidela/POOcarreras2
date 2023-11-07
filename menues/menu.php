<?php
require_once('menuAdmin.php');

class Menu{
    
    public function __construct(){
    }

    //Función que muestra una línea en pantalla con el salto de línea
    public static function writeln($texto) {
        echo ($texto);
        echo(PHP_EOL);
    }

    //Función que muestra una línea en pantalla con el salto de línea
    public static function readln($texto) {
        echo ($texto);
        $rta = readline();
        echo(PHP_EOL);
        return $rta;
   }   
   
   //Limpia la pantalla dependiendo del sistema operativo que estemos usando 
   public function cls(){
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Estás en Windows
            popen('cls', 'w');//system("cls");
		} else {
    		system("clear");
      }
   }

	public function pantallaBienvenida($nombreSistema){
        self::writeln("**************************************");
        self::writeln("**                                 **");     
        self::writeln("**   Bienvenidos a ".$nombreSistema."      **");
        self::writeln("**                                 **");     
        self::writeln("**************************************"); 
        self::writeln("");                            
    }

    public function pantallaDespedida(){
        self::writeln("Gracias por utilizar nuestro sistema");
        self::writeln("");
    }

    protected function exit(){
        return 1;   
    }

    //Opciones es una matriz, en cada fila el array opción tiene el número de la opción, nombre de la opción y la función
    protected function menu($titulo, $opciones) {
        $opcion = 1;

        while($opcion != 0){
            echo (PHP_EOL);
            echo ('---------------------------'.PHP_EOL);
            echo ($titulo.PHP_EOL);
            echo ('---------------------------'.PHP_EOL);
    
            foreach ($opciones as $opcion) {
                echo ($opcion[0] .' - '. $opcion[1]. PHP_EOL );
            } 
    
            $opcion = readline('Elija una opcion: ');
        
            if (isset($opciones[$opcion])) {
                $funcion = $opciones[$opcion][2];
                call_user_func($funcion);
            } else {
            self::writeln("Opción inválida");
            }
        }
    }

    //Primera elección del programa, para decidir quién opera con el sistema
    public function elegirUsuario(){
		self::cls();

        $titulo = "Elige un tipo de usuario:";
        
        $opciones = [];

        $opciones[0][0]= 0;
        $opciones[0][1] = "Salir";
        $opciones[0][2] = array($this, "exit");  //Llamar a la función exit de esta clase
        
        $opciones[1][0] = 1;
        $opciones[1][1] = "Participante";
        $opciones[1][2] = array($this,"operacionesParticipante");

        $opciones[2][0] = 2;
        $opciones[2][1] = "Administrador";
        $opciones[2][2] = array($this,"menuAdmin");

        self::menu($titulo,$opciones);
    }

    private function operacionesParticipante(){
        self::writeln("Operaciones del participante");
    }
    
    private function menuAdmin(){
        $menuAdmin = new MenuAdmin();
        $menuAdmin->operacionesAdmin();
    }

}


