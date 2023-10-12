<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/actividades_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad ACTIVIDADES.
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
    protected $tipo_actividad = null;
    protected $id_trimestre = null;
    protected $id_detalle_asignatura_empleado = null;
    protected $id_asignatura = null;
    protected $id_grado = null;


    /*
    *   Métodos para validar y asignar valores de los atributos. 13 campos
    */
    public function setIdActividad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdGrado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_grado = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setNombreActividad($value)
    {
        if (Validator::validateString($value, 1, 50)) {
            $this->nombre_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTipoActividad($value)
    {
        if (Validator::validateString($value, 1, 40)) {
            $this->tipo_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPonderacion($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->ponderacion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFechaEntrega($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_entrega = $value;
            return true;
        } else {
            return false;
        }
    }
    

    public function setIdTipoActividad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_tipo_actividad = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdTrimestre($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_trimestre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdDetalleAsignaturaEmpleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_detalle_asignatura_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdAsignatura($value)
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