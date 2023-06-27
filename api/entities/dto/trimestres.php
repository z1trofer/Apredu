<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/trimestres_queries.php');

class Trimestres extends TrimestresQueries{

    //Atributos del año
    public $id_anio = null;
    public $anio = null;

     //Atributos del trimestre
    public $id_trimestre = null;
    public $trimestre = null;
    public $estado = null;

     //Método Setter del año
    public function setIdAnio($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_anio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAnio($value)
    {
        if (Validator::validateAnio($value)) {
            $this->anio = $value;
            return true;
        } else {
            return false;
        }
    }

     //Método Setter del trimestre

    public function setIdTrimestre($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_trimestre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTrimestre($value)
    {
        if (Validator::validateString($value, 1, 30)) {
            $this->trimestre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }


    public function getIdAnio()
    {
        return $this->id_anio;
    }

    public function getAnio()
    {
        return $this->anio;
    }

    public function getIdTrimestre()
    {
        return $this->id_trimestre;
    }

    public function getTrimestre()
    {
        return $this->trimestre;
    }

    public function getEstado()
    {
        return $this->estado;
    }
}
?>
