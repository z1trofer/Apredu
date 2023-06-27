<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/notas_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad USUARIO.
*/
class Notas extends NotasQueries
{
    // Declaración de atributos, notas.
    public $id_empleado = null;
    public $id_asignatura = null;
    public $id_trimestre = null;
    public $id_grado = null;
    public $id_actividad = null;
    public $id_nota = null;
    public $nota = null;
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_empleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setId_asignatura($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_asignatura = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_trimestre($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_trimestre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_grado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_actividad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_nota($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_nota = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setnota($value)
    {
        if (Validator::validateMoney($value) && $value > 0 && $value < 10.01) {
            $this->nota = $value;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_empleado()
    {
        return $this->id_empleado;
    }

    public function getId_asignatura()
    {
        return $this->id_asignatura;
    }

    public function getId_trimestre()
    {
        return $this->id_trimestre;
    }

    public function getId_grado()
    {
        return $this->id_grado;
    }

    public function getId_actividad()
    {
        return $this->id_actividad;
    }

    public function getId_nota()
    {
        return $this->id_nota;
    }

    public function getnota()
    {
        return $this->nota;
    }
}
?>