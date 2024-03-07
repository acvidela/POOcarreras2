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

    public static function subtitulo($subtitulo){
        echo PHP_EOL;
        self::writeln($subtitulo);
        self::writeln(str_repeat('-', mb_strlen($subtitulo)));
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
    
            $opcion = readline('Elija una opción: ');
        
            if (isset($opciones[$opcion])) {
                $funcion = $opciones[$opcion][2];
					//La función tiene argumentos                
                if (isset($opciones[$opcion][3])){
                	call_user_func($funcion,$opciones[$opcion][3]);                
                } else {
                	call_user_func($funcion);
                } 
				 }	             
             else {
            	self::writeln("Opción inválida");
            }
            }
    }
    

    public function operacionesAdmin(){
        $menuAdmin = new MenuAdmin();
        $menuAdmin->operacionesAdmin();
    }

}


