<?php
require_once('../helpers/database.php');
/*
 *	Clase para manejar el acceso a datos de la entidad Grado.
 */
class GradosQueries
{
    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
     */


    //Método par insertar datos a la tabla de grados
    public function createRow()
    {
        $sql = 'INSERT INTO grados(grado)
                VALUES(?)';
        $params = array($this->grado);
        return Database::executeRow($sql, $params);
    }

    //Método para leer todos los registros de la tabla, ordenados por el nombre del grado
    public function readAll()
    {
        $sql = 'SELECT id_grado, grado 
                FROM grados
                ORDER BY id_grado ASC';
        return Database::getRows($sql);
    }

    //Método para consultar datos de una columna específica por medio de un parametro del id
    public function readOne()
    {
        $sql = 'SELECT id_grado, grado
                FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Método para realizar la actualización de la tabla por medio de una query parametrizada
    public function updateRow()
    {
        $sql = 'UPDATE grados
                SET grado = ?
                WHERE id_grado = ?';
        $params = array($this->grado, $this->id);
        return Database::executeRow($sql, $params);
    }

    //Metodo para eliminar un dato de la tabla por medio del id
    public function deleteRow()
    {
        $sql = 'DELETE FROM grados
                WHERE id_grado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Obtener asignaturas de de un grado
    public function readDetalle()
    {
        $sql = 'SELECT id_detalle_asignatura_empleado, asignaturas.asignatura from detalle_asignaturas_empleados 
        INNER JOIN asignaturas USING (id_asignatura)
        where id_grado = ? ORDER BY asignatura ASC';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // funcion para insertar el detalle
    public function InsertarDetalle($asignatura)
    {
        $sql = 'INSERT INTO detalle_asignaturas_empleados (id_asignatura, id_grado)
                VALUES(?, ?)';
        $params = array($asignatura, $this->id);
        return Database::executeRow($sql, $params);
    }

    // funcion para eliminar el detalle
    public function deleteDetalle()
    {
        $sql = 'DELETE FROM detalle_asignaturas_empleados
                WHERE id_detalle_asignatura_empleado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // funcion para leer todas las asignaturas
    public function readAsignaturas()
    {
        $sql = "SELECT id_asignatura, asignatura
            FROM asignaturas";
        return Database::getRows($sql);
    }

    //Generar el grafico de grados con mas estudiantes
    public function cantidadEstudiantesXgrado()
    {
        $sql = 'SELECT grado, COUNT(*) AS cantidad_estudiantes
                FROM estudiantes INNER JOIN grados USING(id_grado)
                GROUP BY grado
                ORDER BY cantidad_estudiantes DESC
                LIMIT 3;
                ';
        return Database::getRows($sql);
    }

    //consulta para el reporte de actividades por grado
    public function gradoActividades()
    {
        $sql = 'SELECT a.id_trimestre, t.trimestre, a.nombre_actividad, a.ponderacion, a.descripcion, a.fecha_entrega, ta.tipo_actividad,  asi.asignatura
        FROM actividades a
        JOIN detalle_asignaturas_empleados dae USING(id_detalle_asignatura_empleado)
        JOIN asignaturas asi USING(id_asignatura)
        JOIN trimestres t USING(id_trimestre)
        JOIN tipo_actividades ta USING(id_tipo_actividad)
        JOIN grados g USING(id_grado)
        JOIN anios an USING(id_anio)
        WHERE g.id_grado = ?
        AND an.anio = (SELECT MAX(anio) FROM anios)
        ORDER BY t.id_trimestre, a.fecha_entrega, asi.asignatura';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    public function graficoPromedio()
    {
        $sql = 'SELECT a.asignatura, ROUND(AVG(n.nota),2) AS promedio
        FROM notas n
        JOIN actividades act USING(id_actividad)
        JOIN detalle_asignaturas_empleados dae USING(id_detalle_asignatura_empleado)
        JOIN asignaturas a USING(id_asignatura)
        JOIN grados g USING(id_grado)
        WHERE g.id_grado = ?
        GROUP BY a.id_asignatura
        ORDER BY promedio DESC
        LIMIT 3';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

}