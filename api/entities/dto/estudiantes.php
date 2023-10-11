<?php
require_once('../helpers/validator.php');
require_once('../entities/dao/estudiantes_queries.php');

class Estudiantes extends EstudiantesQueries{

    //Atributos del estudiante
    public $id_estudiante = null;
    public $nombre_estudiante = null;
    public $apellido_estudiante = null;
    public $nacimiento = null;
    public $direccion_estudiante = null;
    public $nie = null;
    public $id_grado = null;
    public $usuario_estudiante = null;
    public $clave = null;
    public $estado = null; //por defecto null en la base
    public $id_nota = null;
    public $descripcion_ficha = null;
    public $id_empleado = null;

    public $id_responsable = null;

    public $parentesco = null;
    
    //Setter del estudiante
    public function setIdEstudiante($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdResponsable($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_responsable = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setParentesco($value)
    {
        if ($value == "madre" ||$value == "padre" ||$value == "tío/a" ||$value == "abuelo/a" ||$value == "tutor legal") {
            $this->parentesco = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombresEstudiante($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 60)) {
            $this->nombre_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellidosEstudiante($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 60)) {
            $this->apellido_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNacimiento($value)
    {
        if (Validator::validateDate4($value)) {
            $this->nacimiento = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDireccionEstudiante($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->direccion_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNie($value)
    {
        if (Validator::validateAlphanumeric($value, 7, 7)) {
            $this->nie = $value;
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

    public function setUsuarioEstudiante($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->usuario_estudiante = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if (Validator::validateString($value, 1, 60)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if($value == "on"){
            $value = 1;
        }elseif($value == "off"){
            $value = 0;
        }else{
            return false;
        }
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcionFicha($value)
    {
        if (Validator::validateString($value, 1, 150)) {
            $this->descripcion_ficha = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEmpleado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos del estudiante.
    */
    public function getIdEstudiante()
    {
        return $this->id_estudiante;
    }

    public function getNombreEstudiante()
    {
        return $this->nombre_estudiante;
    }

    public function getApellidoEstudiante()
    {
        return $this->apellido_estudiante;
    }

    public function getNacimiento()
    {
        return $this->nacimiento;
    }

    public function getDireccion()
    {
        return $this->direccion_estudiante;
    }

    public function getNie()
    {
        return $this->nie;
    }

    public function getGrado()
    {
        return $this->id_grado;
    }

    public function getUsuario()
    {
        return $this->usuario_estudiante;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getdescripcion_ficha()
    {
        return $this->descripcion_ficha;
    }

    public function getEmpleado()
    {
        return $this->id_empleado;
    }
}
?>