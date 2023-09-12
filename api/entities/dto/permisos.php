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
    public $permiso = null;
    public $atributo = null;
    public $id_cargo = null;
    
  
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPermiso($value)
    {
        if (Validator::validateBoolean(($value))) {
            $this->permiso = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAtributo($value)
    {
        if ($value != 'id_cargo' && $value != 'cargo') {
            $this->atributo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCargo($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cargo = $value;
            return true;
        } else {
            return false;
        }
    }

    //getters
    public function getId()
    {
        return $this->id;
    }

    public function getPermiso()
    {
        return $this->permiso;
    }

    public function getAtributo()
    {
        return $this->atributo;
    }

    public function getCargo()
    {
        return $this->id_cargo;
    }


}
?>