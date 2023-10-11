<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/asignaturas_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad ASIGNATURA.
*/
class Asignaturas extends AsignaturasQueries
{
    // Declaración de atributos (propiedades).
    protected $id_asignatura = null;
    protected $asignatura = null;


    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_asignatura = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAsignatura($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->asignatura = $value;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id_asignatura; 
    }

    public function getAsignaturas()
    {
        return $this->asignatura; 
    }

}
?>