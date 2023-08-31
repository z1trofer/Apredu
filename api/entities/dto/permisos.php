<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/permisos_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad USUARIO.
*/
class Permisos extends PermisosQueries
{
    // Declaración de atributos (propiedades).
    public $id = null;
    
  
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }
}
?>