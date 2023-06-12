<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/responsables_queries.php');

class Resposnables extends ResponsablesQueries{

        //Atributos del responsable
        public $id_responsable = null;
        public $nombre_responsable = null;
        public $apellido_responsable = null;
        public $dui = null;
        public $correo = null;
        public $lugar_trabajo = null;
        public $telefono_trabajo = null;
        public $parentesco = null;
        
        //Atributos telefono-responsable
        public $telefono = null;

            //Setter del responsable
    public function setIdResponsable($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_responsable = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombresResponsable($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 60)) {
            $this->nombre_responsable = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellidosResponsable($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 60)) {
            $this->apellido_responsable = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDui($value)
    {
        if (Validator::validateDUI($value)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if (Validator::validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setLugarTrabajo($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->lugar_trabajo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefonoTrabajo($value)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono_trabajo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setParentesco($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->parentesco = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos del responsable.
    */

    public function getIdResponsable()
    {
        return $this->id_responsable;
    }

    public function getNombreResponsable()
    {
        return $this->nombre_responsable;
    }

    public function getDui()
    {
        return $this->dui;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getNombreResponsable()
    {
        return $this->nombre_responsable;
    }

    public function getLugarTrabajo()
    {
        return $this->lugar_trabajo;
    }

    public function getTelefonoTrabajo()
    {
        return $this->telefono_trabajo;
    }

    public function getParentesco()
    {
        return $this->parentesco;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }
}