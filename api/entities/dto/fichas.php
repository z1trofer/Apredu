<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/fichas_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad PRODUCTO.
*/
class Fichas extends FichasQueries
{
    // Declaración de atributos (propiedades).
    protected $id_ficha = null;
    protected $id_estudiante = null;
    protected $descripcion_ficha = null;
    protected $fecha_ficha = null;
    protected $id_empleado = null;

    /*
    *   Métodos para validar y asignar valores de los atributos. 13 campos
    */
    public function setid_ficha($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_ficha = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_estudiante($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->id_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setdescripcion_ficha($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->descripcion_ficha = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setfecha_ficha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_ficha = $value;
            return true;
        } else {
            return false;
        }
    }
    

    public function setid_empleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }    
   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getid_ficha()
    {
        return $this->id_ficha;
    }

    public function getid_estudiante()
    {
        return $this->id_estudiante;
    }

    public function getdescripcion_ficha()
    {
        return $this->descripcion_ficha;
    }

    public function getfecha_ficha()
    {
        return $this->fecha_ficha;
    }

    public function getid_empleado()
    {
        return $this->id_empleado;
    }
    
}
