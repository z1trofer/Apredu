<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/actividades_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad PRODUCTO.
*/
class Actividades extends ActividadesQueries
{
    // Declaración de atributos (propiedades).
    protected $id_actividad = null;
    protected $nombre_actividad = null;
    protected $ponderacion = null;
    protected $descripcion = null;
    protected $fecha_entrega = null;
    protected $id_tipo_actividad = null;
    protected $id_trimestre = null;
    protected $id_detalle_asignatura_empleado = null;
    protected $id_asignatura = null;
    protected $id_grado = null;


    /*
    *   Métodos para validar y asignar valores de los atributos. 13 campos
    */
    public function setid_actividad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_grado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setnombre_actividad($value)
    {
        if (Validator::validateString($value, 1, 50)) {
            $this->nombre_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setdescripcion($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setponderacion($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->ponderacion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setfecha_entrega($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_entrega = $value;
            return true;
        } else {
            return false;
        }
    }
    

    public function setid_tipo_actividad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_tipo_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_trimestre($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_trimestre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_detalle_asignatura_empleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_detalle_asignatura_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setid_asignatura($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_asignatura = $value;
            return true;
        } else {
            return false;
        }
    }
    
   
    
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getid_actividad()
    {
        return $this->id_actividad;
    }

    public function getnombre_actividad()
    {
        return $this->nombre_actividad;
    }

    public function getponderacion()
    {
        return $this->ponderacion;
    }

    public function getdescripcion()
    {
        return $this->descripcion;
    }

    public function getfecha_entrega()
    {
        return $this->fecha_entrega;
    }

    public function getid_tipo_actividad()
    {
        return $this->id_tipo_actividad;
    }
    public function getid_trimestre()
    {
        return $this->id_trimestre;
    }
    public function getid_detalle_asignatura_empleado()
    {
        return $this->id_detalle_asignatura_empleado;
    }
    
    
}
?>