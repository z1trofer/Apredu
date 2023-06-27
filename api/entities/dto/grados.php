<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/grados_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad Grado.
*/
class Grados extends GradosQueries
{
    // Declaración de atributos (propiedades).
    protected $id = null;
    protected $grado = null;
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setGrado($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->grado = $value;
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
        return $this->id;
    }

    public function getGrado()
    {
        return $this->grado;
    }
}
?>