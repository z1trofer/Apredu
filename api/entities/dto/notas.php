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
    public $id_grado = null;
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

    public function setId_grado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado;
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

    public function getId_grado()
    {
        return $this->id_grado;
    }
}
