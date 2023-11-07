<?php
abstract class ArrayIdManager {
    protected $arreglo = [];
    

    protected function getArreglo(){
        return ($this->arreglo);
    }

    // Agregar un objeto nuevo en la posición id del elemento
    public function agregar($elemento) {
        $id =$elemento->getId();
        $this->arreglo[$id] = $elemento;
    }
    
	//Busca si existe un id dentro de los elementos del arreglo	
	public function existeId($id){
		  foreach ($this->arreglo as $elemento) {
            if ($elemento->getId() == $id) {
                return TRUE;
            }
        }	
        return FALSE;	
	}

    // Eliminar un elemento por su ID
    public function eliminarPorId($id) {
        foreach ($this->arreglo as $key => $elemento) {
            if ($elemento->getId() == $id) {
                unset($this->arreglo[$key]);
                break;
            }
        }
    }

	// Retorna por id el elemento, retorna NULL si no está
    public function getPorId($id) {
        foreach ($this->arreglo as $elemento) {
            if ($elemento->getId() == $id) {
                return $elemento;                
            }
        }
        return NULL;
    }
    
    //Va a modificar recibiendo un objeto, el id permanece
    public function modificar($elementoModificado) {
      $id = $elementoModificado->getId();
      if (self::existeId($id)){
      	foreach ($this->arreglo as $key => $elemento) {
            if ($elemento->getId() == $id) {
                $this->arreglo[$key] = $elementoModificado;
                break;
            }
         }
      }
    } 
        
    /**
     * Set the value of arreglo
     *
     * @return  self
     */ 
    public function setArreglo($arreglo)
    {
        $this->arreglo = $arreglo;

        return $this;
    }
}
