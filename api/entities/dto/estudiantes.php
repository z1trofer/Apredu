<?php
require_once('../../helpers/validator.php');
require_once('../../entities/dao/estudiantes_queries.php');

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
        if (Validator::validateString($value, 1, 200)) {
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
        if (Validator::validateAlphanumeric($value, 1, 9)) {
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
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
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

}