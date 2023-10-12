-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-10-2023 a las 04:42:48
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `apredu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL COMMENT 'Id de la actividad',
  `nombre_actividad` varchar(40) NOT NULL COMMENT 'Nombre de la actividad',
  `ponderacion` int(11) NOT NULL COMMENT 'Ponderación de nota de la actividad',
  `descripcion` varchar(150) NOT NULL COMMENT 'Descripción de la actividad',
  `fecha_entrega` date NOT NULL COMMENT 'Fecha de entrega para la actividad',
  `id_detalle_asignatura_empleado` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla detalle_asignatura_empleados',
  `id_tipo_actividad` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla tipo_actividades',
  `id_trimestre` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla trimestres'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id_actividad`, `nombre_actividad`, `ponderacion`, `descripcion`, `fecha_entrega`, `id_detalle_asignatura_empleado`, `id_tipo_actividad`, `id_trimestre`) VALUES
(61, 'Examen Caligrafia', 50, 'examen', '2023-03-14', 65, 3, 1),
(62, 'planas', 50, 'Planas semanales', '2023-02-10', 65, 1, 1),
(63, 'Planas', 50, 'Planas artistica', '2023-02-02', 64, 1, 1),
(64, 'Examen', 50, 'Examen artistica', '2023-02-02', 64, 3, 1),
(65, 'Libro Ortografia', 100, 'Llenado de libro de texto', '2023-01-05', 66, 2, 1),
(66, 'Examen Fisica', 100, 'Examen de educacion fisica', '2023-01-05', 67, 3, 1),
(67, 'Libro de Ortografia 2 trimestre', 100, 'El Libro del segundo trimestre', '2023-06-06', 66, 2, 2),
(68, 'Libro de Ortografia 3 trimestre', 100, 'Libro del tercer trimestre', '2023-10-16', 66, 2, 3),
(69, 'Exame Fisica 2', 100, 'Examen del segundo trimestre', '2023-06-20', 67, 3, 2),
(70, 'Examen fisica 3', 100, 'Examen del tercer trimestre', '2023-11-02', 67, 3, 3),
(71, 'Examen primer triestre', 100, 'Examen del primer trimestre', '2023-02-21', 68, 3, 1),
(72, 'Artisitcas Planas', 50, 'Planas', '2023-06-14', 64, 1, 2),
(73, 'Examen Artistica', 50, 'Examen T Trimestre', '2023-07-14', 64, 3, 2),
(74, 'Artistica Actividad', 25, 'Actividad Manualidad que lo niños haran', '2023-09-07', 64, 2, 3),
(75, 'Planas', 25, 'Planas', '2023-10-13', 64, 1, 3),
(76, 'Examen', 50, 'Examen', '2023-10-19', 64, 3, 3),
(77, 'Planas Caligrafia', 50, 'Planas', '2023-06-07', 65, 1, 2),
(78, 'Examen', 50, 'Caligrafia Examen', '2023-07-14', 65, 3, 2),
(79, 'Planas Caligrafia', 100, 'Planas', '2023-11-02', 65, 1, 3),
(80, 'Cuaderno Ortografia', 50, 'Cuaderno Ortografia primer trimestre', '2023-01-05', 71, 1, 1),
(81, 'Examen Ortografia', 50, 'Examen de Ortografia', '2023-02-02', 71, 3, 1),
(82, 'Cuaderno Ortografia', 50, 'Cuaderno del segundo trimestre', '2023-07-04', 71, 1, 2),
(83, 'Examen Ortografia', 50, 'Exmane segundo triemstre', '2023-07-06', 71, 3, 2),
(84, 'Cuaderno Ortografia', 50, 'Cuaderno del tercer trimestre', '2023-10-05', 71, 1, 3),
(85, 'Examen Final', 50, 'Examen del tercer trimestre', '2023-10-20', 71, 3, 3),
(86, 'Examen Segundo Trimestre', 100, 'Examen del segundo trimestre', '2023-08-08', 68, 3, 2),
(87, 'Examen fisica 3', 100, 'Exa', '2023-10-13', 68, 3, 3),
(88, 'Activdad Artistica', 35, 'Actividad', '2023-01-13', 69, 2, 1),
(89, 'Examen Artistica', 50, 'Examen de asd', '2023-02-25', 69, 3, 1),
(90, 'Planas Artistica', 15, 'Planas', '2023-03-01', 69, 1, 1),
(91, 'Actividad De Manualidad', 35, 'Actividad Creativa', '2023-03-24', 69, 2, 2),
(92, 'Examen', 50, 'Examen', '2023-05-10', 69, 3, 2),
(93, 'Planas', 15, 'las planas', '2023-08-16', 69, 1, 2),
(94, 'Planas', 15, 'LAs plasnas', '2023-10-10', 69, 1, 3),
(95, 'Actividad Final Integradora', 35, 'Actividad Final', '2023-09-05', 69, 2, 3),
(96, 'Examen Final Artisitca', 50, 'El Examen Final', '2023-11-02', 69, 3, 3),
(97, 'Planas', 100, 'Planas de numeros', '2023-03-30', 70, 1, 1),
(98, 'Planas', 100, 'Planas de Molde', '2023-07-29', 70, 1, 2),
(99, 'Planas', 100, 'Planas En Carta', '2023-11-03', 70, 1, 3);

--
-- Disparadores `actividades`
--
DELIMITER $$
CREATE TRIGGER `notas_insert_actividades` AFTER INSERT ON `actividades` FOR EACH ROW BEGIN
    INSERT INTO notas (id_estudiante, id_actividad, nota)
	select estudiantes.id_estudiante, NEW.id_actividad, null from estudiantes 
    INNER JOIN detalle_asignaturas_empleados USING (id_grado)
	INNER JOIN actividades USING (id_detalle_asignatura_empleado)
where estudiantes.id_grado = (select detalle_asignaturas_empleados.id_grado from actividades 
INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
where id_actividad = NEW.id_actividad) GROUP BY id_estudiante;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anios`
--

CREATE TABLE `anios` (
  `id_anio` int(11) NOT NULL COMMENT 'Id del año',
  `anio` varchar(4) NOT NULL COMMENT 'Año lectivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anios`
--

INSERT INTO `anios` (`id_anio`, `anio`) VALUES
(21, '2003'),
(20, '2004'),
(19, '2005'),
(18, '2006'),
(17, '2007'),
(16, '2008'),
(15, '2009'),
(14, '2010'),
(13, '2011'),
(12, '2012'),
(11, '2013'),
(10, '2014'),
(9, '2015'),
(8, '2016'),
(7, '2017'),
(6, '2018'),
(5, '2019'),
(4, '2020'),
(3, '2021'),
(2, '2022'),
(1, '2023'),
(22, '2024');

--
-- Disparadores `anios`
--
DELIMITER $$
CREATE TRIGGER `anios_after_crear` AFTER INSERT ON `anios` FOR EACH ROW BEGIN
    INSERT INTO trimestres (trimestre, id_anio, estado)
    VALUES ('Primer trimestre', NEW.id_anio, 0),
        ('Segundo trimestre', NEW.id_anio, 0),
        ('Tercer trimestre', NEW.id_anio, 0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id_asignatura` int(11) NOT NULL COMMENT 'Id de la asignatura',
  `asignatura` varchar(20) NOT NULL COMMENT 'asignaturas de aprendizaje'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id_asignatura`, `asignatura`) VALUES
(8, 'Artística'),
(9, 'Caligrafía'),
(2, 'Ciencias Ambientales'),
(4, 'Educación Física'),
(3, 'Estudios Sociales'),
(7, 'Informática'),
(5, 'Inglés'),
(6, 'Lenguaje'),
(1, 'Matemáticas'),
(10, 'Ortografía');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos_empleados`
--

CREATE TABLE `cargos_empleados` (
  `id_cargo` int(11) NOT NULL COMMENT 'Id del cargo',
  `cargo` varchar(25) NOT NULL COMMENT 'Cargo del empleado',
  `edit_permisos` tinyint(1) NOT NULL DEFAULT 0,
  `create_usuarios` tinyint(1) NOT NULL DEFAULT 0,
  `edit_admin` tinyint(1) NOT NULL DEFAULT 0,
  `edit_perfil` tinyint(1) NOT NULL DEFAULT 0,
  `update_notas` tinyint(1) NOT NULL DEFAULT 0,
  `view_notas` tinyint(1) NOT NULL DEFAULT 0,
  `edit_actividades` tinyint(1) NOT NULL DEFAULT 0,
  `edit_tipo_actividades` tinyint(1) NOT NULL DEFAULT 0,
  `view_actividades` tinyint(1) NOT NULL DEFAULT 0,
  `view_all_actividades` tinyint(1) NOT NULL DEFAULT 0,
  `view_empleados` tinyint(1) NOT NULL DEFAULT 0,
  `edit_empleados` tinyint(1) NOT NULL DEFAULT 0,
  `view_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
  `edit_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
  `view_responsables` tinyint(1) NOT NULL DEFAULT 0,
  `edit_responsables` tinyint(1) NOT NULL DEFAULT 0,
  `view_fichas` tinyint(1) NOT NULL DEFAULT 0,
  `delete_fichas` tinyint(1) NOT NULL DEFAULT 0,
  `edit_fichas` tinyint(1) NOT NULL DEFAULT 0,
  `view_grados` tinyint(1) NOT NULL DEFAULT 0,
  `edit_grados` tinyint(1) NOT NULL DEFAULT 0,
  `view_asignaturas` tinyint(1) NOT NULL DEFAULT 0,
  `edit_asignaturas` tinyint(1) NOT NULL DEFAULT 0,
  `view_trimestres` tinyint(1) NOT NULL DEFAULT 0,
  `edit_trimestres` tinyint(1) NOT NULL DEFAULT 0,
  `edit_detalles_docentes` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos_empleados`
--

INSERT INTO `cargos_empleados` (`id_cargo`, `cargo`, `edit_permisos`, `create_usuarios`, `edit_admin`, `edit_perfil`, `update_notas`, `view_notas`, `edit_actividades`, `edit_tipo_actividades`, `view_actividades`, `view_all_actividades`, `view_empleados`, `edit_empleados`, `view_estudiantes`, `edit_estudiantes`, `view_responsables`, `edit_responsables`, `view_fichas`, `delete_fichas`, `edit_fichas`, `view_grados`, `edit_grados`, `view_asignaturas`, `edit_asignaturas`, `view_trimestres`, `edit_trimestres`, `edit_detalles_docentes`) VALUES
(1, 'admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'profesor', 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(5, 'registro academico', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_asignaturas_empleados`
--

CREATE TABLE `detalle_asignaturas_empleados` (
  `id_detalle_asignatura_empleado` int(11) NOT NULL COMMENT 'id del detalle de la asignatura y empleado',
  `id_empleado` int(11) DEFAULT NULL COMMENT 'Campo foráneo conectado a la tabla empleados',
  `id_asignatura` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla asignaturas',
  `id_grado` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla grados'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_asignaturas_empleados`
--

INSERT INTO `detalle_asignaturas_empleados` (`id_detalle_asignatura_empleado`, `id_empleado`, `id_asignatura`, `id_grado`) VALUES
(64, 36, 8, 2),
(65, 36, 9, 2),
(66, 36, 10, 2),
(67, 38, 4, 2),
(68, 38, 4, 3),
(69, 39, 8, 3),
(70, 39, 9, 3),
(71, 39, 10, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL COMMENT 'Id del empleado',
  `nombre_empleado` varchar(30) NOT NULL COMMENT 'nombre del empleado',
  `apellido_empleado` varchar(30) NOT NULL COMMENT 'apellido del empleado',
  `dui` varchar(10) NOT NULL COMMENT 'Documento de identidad del empleado',
  `correo_empleado` varchar(100) NOT NULL COMMENT 'Correo electrónico del empleado',
  `telefono` varchar(9) DEFAULT NULL,
  `direccion` varchar(150) NOT NULL COMMENT 'Direccion del empleado',
  `fecha_nacimiento` date NOT NULL COMMENT 'Fecha de nacimiento del empleado',
  `id_cargo` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  cargos',
  `usuario_empleado` varchar(50) NOT NULL COMMENT 'Nombre del usuario del empleado',
  `clave` varchar(100) NOT NULL COMMENT 'Clave de acceso del empleado',
  `intentos` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_clave` datetime NOT NULL DEFAULT current_timestamp(),
  `timer_intento` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `apellido_empleado`, `dui`, `correo_empleado`, `telefono`, `direccion`, `fecha_nacimiento`, `id_cargo`, `usuario_empleado`, `clave`, `intentos`, `estado`, `fecha_clave`, `timer_intento`) VALUES
(35, 'Admin Jesus', 'Cristo', '43412312-2', 'asdasd@gmail.com', '7545-6545', 'sdasdasd', '2004-10-27', 1, 'admin', '$2y$10$Hb5ntlviJZCjgKw0bVarJerLJa0tZrh.a8gy1bQQ1DdQ37Va4CAcu', 0, 1, '2023-10-11 19:28:32', NULL),
(36, 'Margarita', 'Del Valle', '45475898-9', '201940211@ricaldone.edu.sv', '7458-4564', 'asdasdasd', '2003-07-09', 2, 'Margaret', '$2y$10$bq4xyWwtgz1lv9kaBBhjgOGALPjmV5Ziv7rKaxjHop2Cv0i2awvYC', 2, 1, '2023-10-11 19:27:33', NULL),
(38, 'Rodrigo', 'Valles', '54559898-9', '201930211@ricaldone.edu.sv', '7422-5666', 'asdasds', '1997-06-10', 2, 'Rodri', '$2y$10$ryl0PLxR6Wpz.i68a8DgsOnY1xyhGxdr0jiZEi.DWrJ67tL9NOqM2', 0, 1, '2023-10-11 19:27:57', NULL),
(39, 'Claudia Carmen', 'Posada Fuentes', '42424532-5', 'Claui@gmail.com', '6886-6555', 'En la savana', '2000-06-11', 2, 'Claudi', '$2y$10$xejmBoygs.HhtQuHbURWuOB2HPtj5GeQDODS/sP0LJkE97sbPrChe', 0, 1, '2023-10-11 19:59:19', NULL),
(40, 'Rebeca', 'Registro', '45456546-1', '20190211@ricaldone.edu.sv', '7477-7777', 'Proasd', '2005-09-28', 5, 'ReBe', '$2y$10$eZwAv8Hdaw7EaTaFFcmDeeswWxf0ivv0t14P5Jf/QB7s8yf74K08i', 0, 1, '2023-10-11 20:40:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL COMMENT 'Id del estudiante',
  `nombre_estudiante` varchar(30) NOT NULL COMMENT 'Nombre del estudiante',
  `apellido_estudiante` varchar(30) NOT NULL COMMENT 'Apellido del estudiante',
  `fecha_nacimiento` date NOT NULL COMMENT 'Fecha de nacimiento del estudiante',
  `direccion` varchar(150) NOT NULL COMMENT 'Direccion del estudiante',
  `nie` varchar(8) NOT NULL COMMENT 'Número de identificación del estudiante',
  `id_grado` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  grado',
  `id_responsable` int(11) DEFAULT NULL COMMENT 'id del responsable a cargo del estudiante',
  `parentesco_responsable` enum('padre','madre','tío/a','abuelo/a','tutor legal') DEFAULT NULL COMMENT 'parentesco del responsable hacia el estudiante',
  `estado` tinyint(1) NOT NULL COMMENT 'Estado de actividad del usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre_estudiante`, `apellido_estudiante`, `fecha_nacimiento`, `direccion`, `nie`, `id_grado`, `id_responsable`, `parentesco_responsable`, `estado`) VALUES
(24, 'Nelson Salvador', 'Alonso Contreras', '2019-10-08', 'asdasd', '5545454', 2, 13, 'padre', 1),
(25, 'Juan Pablo', 'Enrique Mendez', '2019-01-01', 'sadasdsad', '4545555', 2, 11, 'padre', 1),
(26, 'Alejandra Maria', 'Carrasco García', '2019-09-17', 'sadasd', '7777779', 2, 10, 'tío/a', 1),
(27, 'Tatiana Estefany', 'Arevalo Gutierrez', '2019-09-11', 'Las terrazas', '7854555', 2, 15, 'madre', 1),
(28, 'Daniel Alejandro', 'Alonso Murcia', '2019-09-30', 'adsadsadsdfdsgf', '5454545', 2, 13, 'madre', 1),
(29, 'Sebastian Alfonso', 'Cristiano Argueta', '2018-12-28', 'fdgdfgdfgfdgfdg', '7856656', 2, 16, 'padre', 1),
(30, 'Rodrigo Alejandro', 'Quintanilla Mendez', '2019-08-06', 'Calle los Robles', '4785555', 2, 20, 'padre', 1),
(31, 'Diego Alejandro', 'Matamoros Martinez', '2019-08-21', 'Col la Gloria', '9862656', 3, 19, 'madre', 1),
(32, 'Javier Marcelo', 'Chiquito García', '2018-02-12', 'San jacinto', '4453545', 3, 10, 'madre', 1),
(33, 'Miguel Angel', 'Castillo Cercios', '2018-12-20', 'Jan Miguel', '1211345', 3, 18, 'padre', 1),
(34, 'Mauricio Alex', 'Izquierdo Arevalo', '2019-01-11', 'Ayutuxtepeque', '2432423', 3, 15, 'madre', 1),
(35, 'Diego Carlos', 'Maldonado Ascencio', '2017-01-30', 'Mejicanos', '4456545', 5, 16, 'padre', 1),
(36, 'Gabriel Alejandro', 'Paredes Castro', '2017-12-11', 'Por Las cascadas', '5445645', 5, 25, 'tío/a', 1),
(37, 'Victor Zavaleta', 'Martinez Rivas', '2017-06-05', 'En la casa de ahi', '1536456', 5, 24, 'padre', 1),
(38, 'Rodrigo', 'Del Valle Clara', '2017-05-09', 'En el Valle', '1231231', 5, 23, 'padre', 1),
(39, 'Gilberto Ricardo', 'Ramirez Lazo', '2017-01-11', 'En soya', '1234534', 5, 21, 'madre', 1),
(40, 'Rebeca Alejandra', 'Rosales Castrol', '2017-02-07', 'En el monte', '2423143', 5, 22, 'tío/a', 1);

--
-- Disparadores `estudiantes`
--
DELIMITER $$
CREATE TRIGGER `notas_after_estudiantes` AFTER INSERT ON `estudiantes` FOR EACH ROW BEGIN
    INSERT INTO notas (id_estudiante, id_actividad, nota)
    SELECT NEW.id_estudiante, actividades.id_actividad, null
    FROM actividades
    INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
	INNER JOIN trimestres USING (id_trimestre)
	INNER JOIN anios USING (id_anio)
    WHERE detalle_asignaturas_empleados.id_grado = NEW.id_grado and anios.anio = (SELECT DISTINCT anios.anio from trimestres 
        INNER JOIN anios USING (id_anio)
        where id_anio = (select id_anio from trimestres where estado = true));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `notas_update_estudiantes` AFTER UPDATE ON `estudiantes` FOR EACH ROW BEGIN
IF NEW.id_grado != OLD.id_grado THEN
    INSERT INTO notas (id_estudiante, id_actividad, nota)
    SELECT NEW.id_estudiante, actividades.id_actividad, null
    FROM actividades
    INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
	INNER JOIN trimestres USING (id_trimestre)
	INNER JOIN anios USING (id_anio)
    WHERE detalle_asignaturas_empleados.id_grado = NEW.id_grado and anios.anio = (SELECT DISTINCT anios.anio from trimestres 
        INNER JOIN anios USING (id_anio)
        where id_anio = (select id_anio from trimestres where estado = true)); END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `id_ficha` int(11) NOT NULL COMMENT 'Id de la ficha',
  `id_estudiante` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  estudiante',
  `descripcion_ficha` varchar(200) NOT NULL COMMENT 'Descripción de la ficha reporte',
  `fecha_ficha` date NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación de la ficha',
  `id_empleado` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  empleados'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`id_ficha`, `id_estudiante`, `descripcion_ficha`, `fecha_ficha`, `id_empleado`) VALUES
(9, 24, 'asdasdasd', '2023-10-10', 36),
(10, 25, 'Se porto mal', '2023-10-11', 35),
(11, 32, 'Pego un chicle en el pupitre', '2023-10-11', 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int(11) NOT NULL COMMENT 'Id del grado',
  `grado` varchar(20) NOT NULL COMMENT 'Grado académico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `grado`) VALUES
(8, 'Cuarto grado'),
(2, 'Kinder 4'),
(3, 'Kinder 5'),
(4, 'Parvularia'),
(5, 'Primer grado'),
(9, 'Quinto grado'),
(6, 'Segundo grado'),
(10, 'Sexto grado'),
(7, 'Tercer grado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL COMMENT 'Id de la nota',
  `id_estudiante` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  estudiante',
  `id_actividad` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  actividad',
  `nota` decimal(9,2) DEFAULT NULL COMMENT 'Nota obtenida del estudiante de la actividad'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id_nota`, `id_estudiante`, `id_actividad`, `nota`) VALUES
(759, 25, 61, 5.00),
(762, 24, 61, 10.00),
(763, 26, 61, 8.00),
(764, 27, 61, 10.00),
(765, 28, 61, 10.00),
(766, 29, 61, 8.00),
(767, 24, 62, 10.00),
(768, 25, 62, 7.00),
(769, 26, 62, 10.00),
(770, 27, 62, 10.00),
(771, 28, 62, 10.00),
(772, 29, 62, 8.00),
(774, 24, 63, 10.00),
(775, 25, 63, 7.00),
(776, 26, 63, 8.00),
(777, 27, 63, 10.00),
(778, 28, 63, 10.00),
(779, 29, 63, 8.00),
(781, 24, 64, 10.00),
(782, 25, 64, 4.00),
(783, 26, 64, 10.00),
(784, 27, 64, 10.00),
(785, 28, 64, 10.00),
(786, 29, 64, 10.00),
(788, 24, 65, 10.00),
(789, 25, 65, 8.00),
(790, 26, 65, 10.00),
(791, 27, 65, 10.00),
(792, 28, 65, 10.00),
(793, 29, 65, 10.00),
(795, 24, 66, 7.00),
(796, 25, 66, 9.50),
(797, 26, 66, 7.50),
(798, 27, 66, 8.00),
(799, 28, 66, 5.00),
(800, 29, 66, 4.20),
(802, 30, 61, 6.00),
(803, 30, 62, 7.00),
(804, 30, 63, 4.00),
(805, 30, 64, 4.00),
(806, 30, 65, 8.00),
(807, 30, 66, 10.00),
(808, 24, 67, 8.00),
(809, 25, 67, 5.00),
(810, 26, 67, 8.00),
(811, 27, 67, 8.00),
(812, 28, 67, 8.00),
(813, 29, 67, 8.00),
(814, 30, 67, 5.00),
(815, 24, 68, 8.00),
(816, 25, 68, 6.00),
(817, 26, 68, 10.00),
(818, 27, 68, 7.00),
(819, 28, 68, 8.00),
(820, 29, 68, 10.00),
(821, 30, 68, 6.00),
(822, 24, 69, 10.00),
(823, 25, 69, 7.00),
(824, 26, 69, 10.00),
(825, 27, 69, 10.00),
(826, 28, 69, 10.00),
(827, 29, 69, 8.00),
(828, 30, 69, 1.00),
(829, 24, 70, 10.00),
(830, 25, 70, 10.00),
(831, 26, 70, 10.00),
(832, 27, 70, 10.00),
(833, 28, 70, 10.00),
(834, 29, 70, 10.00),
(835, 30, 70, 10.00),
(836, 31, 71, 10.00),
(837, 32, 71, 10.00),
(838, 33, 71, 10.00),
(839, 34, 71, 10.00),
(843, 24, 72, 7.00),
(844, 25, 72, 4.00),
(845, 26, 72, 8.00),
(846, 27, 72, 9.00),
(847, 28, 72, 10.00),
(848, 29, 72, 7.00),
(849, 30, 72, 5.00),
(850, 24, 73, 10.00),
(851, 25, 73, 5.00),
(852, 26, 73, 8.00),
(853, 27, 73, 4.00),
(854, 28, 73, 10.00),
(855, 29, 73, 9.00),
(856, 30, 73, 4.00),
(857, 24, 74, 10.00),
(858, 25, 74, 4.00),
(859, 26, 74, 7.00),
(860, 27, 74, 10.00),
(861, 28, 74, 8.00),
(862, 29, 74, 7.00),
(863, 30, 74, 6.00),
(864, 24, 75, 10.00),
(865, 25, 75, 6.00),
(866, 26, 75, 6.00),
(867, 27, 75, 5.00),
(868, 28, 75, 10.00),
(869, 29, 75, 7.00),
(870, 30, 75, 7.00),
(871, 24, 76, 10.00),
(872, 25, 76, 10.00),
(873, 26, 76, 10.00),
(874, 27, 76, 10.00),
(875, 28, 76, 10.00),
(876, 29, 76, 10.00),
(877, 30, 76, 10.00),
(878, 24, 77, 8.00),
(879, 25, 77, 7.00),
(880, 26, 77, 8.00),
(881, 27, 77, 8.00),
(882, 28, 77, 8.00),
(883, 29, 77, 8.00),
(884, 30, 77, 8.00),
(885, 24, 78, NULL),
(886, 25, 78, NULL),
(887, 26, 78, NULL),
(888, 27, 78, NULL),
(889, 28, 78, NULL),
(890, 29, 78, NULL),
(891, 30, 78, NULL),
(892, 24, 79, 7.00),
(893, 25, 79, 2.00),
(894, 26, 79, 8.00),
(895, 27, 79, 7.00),
(896, 28, 79, 6.00),
(897, 29, 79, 8.00),
(898, 30, 79, 1.00),
(899, 31, 80, 4.00),
(900, 32, 80, 8.00),
(901, 33, 80, 7.00),
(902, 34, 80, 9.00),
(906, 31, 81, 7.00),
(907, 32, 81, 8.00),
(908, 33, 81, 7.00),
(909, 34, 81, 8.00),
(913, 31, 82, 8.00),
(914, 32, 82, 1.00),
(915, 33, 82, 4.00),
(916, 34, 82, 5.00),
(920, 31, 83, 10.00),
(921, 32, 83, 10.00),
(922, 33, 83, 10.00),
(923, 34, 83, 10.00),
(927, 31, 84, 5.00),
(928, 32, 84, 9.00),
(929, 33, 84, 8.00),
(930, 34, 84, 7.00),
(934, 31, 85, 3.00),
(935, 32, 85, 4.00),
(936, 33, 85, 10.00),
(937, 34, 85, 5.00),
(941, 31, 86, 4.00),
(942, 32, 86, 8.00),
(943, 33, 86, 7.00),
(944, 34, 86, 7.00),
(948, 31, 87, 10.00),
(949, 32, 87, 10.00),
(950, 33, 87, 10.00),
(951, 34, 87, 10.00),
(955, 31, 88, 5.00),
(956, 32, 88, 7.00),
(957, 33, 88, 8.00),
(958, 34, 88, 1.00),
(962, 31, 89, 9.00),
(963, 32, 89, 10.00),
(964, 33, 89, 10.00),
(965, 34, 89, 10.00),
(969, 31, 90, 6.00),
(970, 32, 90, 8.00),
(971, 33, 90, 7.00),
(972, 34, 90, 7.00),
(976, 31, 91, 1.00),
(977, 32, 91, 1.00),
(978, 33, 91, 10.00),
(979, 34, 91, 6.00),
(983, 31, 92, 7.00),
(984, 32, 92, 8.00),
(985, 33, 92, 8.00),
(986, 34, 92, 8.00),
(990, 31, 93, 5.00),
(991, 32, 93, 7.00),
(992, 33, 93, 8.00),
(993, 34, 93, 4.00),
(997, 31, 94, 6.00),
(998, 32, 94, 10.00),
(999, 33, 94, 5.00),
(1000, 34, 94, 4.00),
(1004, 31, 95, 5.00),
(1005, 32, 95, 8.00),
(1006, 33, 95, 8.00),
(1007, 34, 95, 4.00),
(1011, 31, 96, 6.00),
(1012, 32, 96, 10.00),
(1013, 33, 96, 10.00),
(1014, 34, 96, 9.00),
(1018, 31, 97, 8.00),
(1019, 32, 97, 8.00),
(1020, 33, 97, 8.00),
(1021, 34, 97, 8.00),
(1025, 31, 98, 10.00),
(1026, 32, 98, 10.00),
(1027, 33, 98, 10.00),
(1028, 34, 98, 10.00),
(1032, 31, 99, 8.00),
(1033, 32, 99, 8.00),
(1034, 33, 99, 8.00),
(1035, 34, 99, 8.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsables`
--

CREATE TABLE `responsables` (
  `id_responsable` int(11) NOT NULL COMMENT 'Id del responsable',
  `nombre_responsable` varchar(30) NOT NULL COMMENT 'Nombre del responsable',
  `apellido_responsable` varchar(30) NOT NULL COMMENT 'Apellido del responsable',
  `dui` varchar(10) NOT NULL COMMENT 'Documento de identidad del estudiante',
  `correo_responsable` varchar(100) DEFAULT NULL COMMENT 'Correo electrónico del estudiante',
  `lugar_de_trabajo` varchar(35) NOT NULL COMMENT 'Dirección del lugar de trabajo',
  `telefono` varchar(9) NOT NULL COMMENT 'Teléfono del lugar de trabajo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `responsables`
--

INSERT INTO `responsables` (`id_responsable`, `nombre_responsable`, `apellido_responsable`, `dui`, `correo_responsable`, `lugar_de_trabajo`, `telefono`) VALUES
(10, 'Antonia Soraya', 'García Alvaro', '78842445-5', 'GarciaAas@gmail.com', 'La csa de aya', '7589-3561'),
(11, 'Javier Alberto', 'Enrique', '36543345-7', 'elpapu@gmail.com', 'pepes car wash', '7454-3434'),
(12, 'Sandra Cecillia', 'Cruz', '54785459-9', 'sandraCruz@gmail.com', 'M. de hacienda', '7545-5422'),
(13, 'Xavi Morti', 'Alonso', '78784555-9', 'Xaviaaaa@gmail.com', 'por alla Company', '2654-6255'),
(14, 'Juan Pablo', 'Lopez', '98121115-3', 'JuanPe@gmail.com', 'Fiscalia', '7526-6144'),
(15, 'Marisol Camila', 'Arevalo', '78456325-9', 'MariaaaaSol@gmail.com', 'Corte Suprema', '7854-6333'),
(16, 'Diego Pablo', 'Maldonado Ascencio', '79625625-6', 'Dieguido@gmail.com', 'Estado Cuscatlan', '7982-6666'),
(17, 'Edgar Ricardo', 'Arjona Morales', '45465456-9', 'asdasd@gmail.com', 'srs bar', '7896-6666'),
(18, 'Pedro', 'Pones Castillo', '89541233-3', 'Poner23@gmail.com', 'Carcas SA de SV', '7444-5555'),
(19, 'Socorro Camila', 'Matamoros Valle', '98656565-6', 'gabSoco@gmail.com', 'Policia Nacional Civil', '7555-6442'),
(20, 'Carlos Daniel', 'Quintanilla Avalos', '95556565-9', 'Quintales@gmail.com', 'quitnal el gordo', '7962-6565'),
(21, 'Sonia', 'Carmen Lazo', '87815155-9', 'Carmencita@gmail.com', 'Alcaldia Municipal de Mejicanos', '7564-5666'),
(22, 'Carol Carmen', 'Castrol Gonzales', '75211111-0', '5855Carmenq@gmail.com', 'Hospital Rosales', '7224-1111'),
(23, 'Rodrigo Salvador', 'Campos Del Valle', '56655555-9', 'Campesiono@gmail.com', 'Jugo del Valle', '7254-1111'),
(24, 'René Alejandro', 'Martinez Correas', '85555522-1', 'CorreAs@gmail.com', 'CocaCola Planta Lourdes', '2144-1214'),
(25, 'William Carlos', 'Paredes Collares', '22155555-9', 'Collares@gmail.com', 'CollarAas', '7825-6666');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_actividades`
--

CREATE TABLE `tipo_actividades` (
  `id_tipo_actividad` int(11) NOT NULL COMMENT 'Id del tipo de actividad',
  `tipo_actividad` varchar(40) NOT NULL COMMENT 'Tipo de actividad asignable para el estudiante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_actividades`
--

INSERT INTO `tipo_actividades` (`id_tipo_actividad`, `tipo_actividad`) VALUES
(1, 'Actividad ordinaria'),
(2, 'Actividad integradora'),
(3, 'Examen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trimestres`
--

CREATE TABLE `trimestres` (
  `id_trimestre` int(11) NOT NULL COMMENT 'Id del trimestre',
  `trimestre` varchar(20) NOT NULL COMMENT 'Trimestres del año',
  `id_anio` int(11) NOT NULL COMMENT 'Campo foráneo conectado a la tabla  anio',
  `estado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trimestres`
--

INSERT INTO `trimestres` (`id_trimestre`, `trimestre`, `id_anio`, `estado`) VALUES
(1, 'Primer trimestre', 1, 1),
(2, 'Segundo trimestre', 1, 0),
(3, 'Tercer trimestre', 1, 0),
(4, 'Primer trimestre', 2, 0),
(5, 'Segundo trimestre', 2, 0),
(6, 'Tercer trimestre', 2, 0),
(7, 'Primer trimestre', 3, 0),
(8, 'Segundo trimestre', 3, 0),
(9, 'Tercer trimestre', 3, 0),
(10, 'Primer trimestre', 4, 0),
(11, 'Segundo trimestre', 4, 0),
(12, 'Tercer trimestre', 4, 0),
(13, 'Primer trimestre', 5, 0),
(14, 'Segundo trimestre', 5, 0),
(15, 'Tercer trimestre', 5, 0),
(16, 'Primer trimestre', 6, 0),
(17, 'Segundo trimestre', 6, 0),
(18, 'Tercer trimestre', 6, 0),
(19, 'Primer trimestre', 7, 0),
(20, 'Segundo trimestre', 7, 0),
(21, 'Tercer trimestre', 7, 0),
(22, 'Primer trimestre', 22, 0),
(23, 'Segundo trimestre', 22, 0),
(24, 'Tercer trimestre', 22, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `fk_detalle_asignaturas_empleados` (`id_detalle_asignatura_empleado`),
  ADD KEY `fk_tipo_actividad` (`id_tipo_actividad`),
  ADD KEY `fk_trimestre` (`id_trimestre`);

--
-- Indices de la tabla `anios`
--
ALTER TABLE `anios`
  ADD PRIMARY KEY (`id_anio`),
  ADD UNIQUE KEY `anio` (`anio`);

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id_asignatura`),
  ADD UNIQUE KEY `asignatura` (`asignatura`);

--
-- Indices de la tabla `cargos_empleados`
--
ALTER TABLE `cargos_empleados`
  ADD PRIMARY KEY (`id_cargo`),
  ADD UNIQUE KEY `cargo` (`cargo`),
  ADD UNIQUE KEY `unique_cargo` (`id_cargo`);

--
-- Indices de la tabla `detalle_asignaturas_empleados`
--
ALTER TABLE `detalle_asignaturas_empleados`
  ADD PRIMARY KEY (`id_detalle_asignatura_empleado`),
  ADD UNIQUE KEY `unique_grado_asignatura` (`id_asignatura`,`id_grado`),
  ADD KEY `fk_grado_empleados` (`id_grado`),
  ADD KEY `fk_empleado_asignatura` (`id_empleado`),
  ADD KEY `fk_asignatura_empleado` (`id_asignatura`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `dui` (`dui`),
  ADD UNIQUE KEY `correo_empleado` (`correo_empleado`),
  ADD UNIQUE KEY `usuario_empleado` (`usuario_empleado`),
  ADD KEY `fk_cargo` (`id_cargo`) USING BTREE;

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `nie` (`nie`),
  ADD KEY `fk_grado` (`id_grado`),
  ADD KEY `fk_responsable_estudiante` (`id_responsable`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id_ficha`),
  ADD KEY `fk_ficha_estudiante` (`id_estudiante`),
  ADD KEY `fk_ficha_empleado` (`id_empleado`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`),
  ADD UNIQUE KEY `grado` (`grado`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `fk_nt_estudiantes` (`id_estudiante`),
  ADD KEY `fk_nt_actividad` (`id_actividad`);

--
-- Indices de la tabla `responsables`
--
ALTER TABLE `responsables`
  ADD PRIMARY KEY (`id_responsable`),
  ADD UNIQUE KEY `dui` (`dui`),
  ADD UNIQUE KEY `correo_responsable` (`correo_responsable`);

--
-- Indices de la tabla `tipo_actividades`
--
ALTER TABLE `tipo_actividades`
  ADD PRIMARY KEY (`id_tipo_actividad`);

--
-- Indices de la tabla `trimestres`
--
ALTER TABLE `trimestres`
  ADD PRIMARY KEY (`id_trimestre`),
  ADD KEY `fk_anio` (`id_anio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la actividad', AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `anios`
--
ALTER TABLE `anios`
  MODIFY `id_anio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del año', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id_asignatura` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la asignatura', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cargos_empleados`
--
ALTER TABLE `cargos_empleados`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del cargo', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_asignaturas_empleados`
--
ALTER TABLE `detalle_asignaturas_empleados`
  MODIFY `id_detalle_asignatura_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del detalle de la asignatura y empleado', AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del empleado', AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del estudiante', AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la ficha', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del grado', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la nota', AUTO_INCREMENT=1039;

--
-- AUTO_INCREMENT de la tabla `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del responsable', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tipo_actividades`
--
ALTER TABLE `tipo_actividades`
  MODIFY `id_tipo_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del tipo de actividad', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `trimestres`
--
ALTER TABLE `trimestres`
  MODIFY `id_trimestre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del trimestre', AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_detalle_asignaturas_empleados` FOREIGN KEY (`id_detalle_asignatura_empleado`) REFERENCES `detalle_asignaturas_empleados` (`id_detalle_asignatura_empleado`),
  ADD CONSTRAINT `fk_tipo_actividad` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividades` (`id_tipo_actividad`),
  ADD CONSTRAINT `fk_trimestre` FOREIGN KEY (`id_trimestre`) REFERENCES `trimestres` (`id_trimestre`);

--
-- Filtros para la tabla `detalle_asignaturas_empleados`
--
ALTER TABLE `detalle_asignaturas_empleados`
  ADD CONSTRAINT `fk_asignatura_empleado` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id_asignatura`),
  ADD CONSTRAINT `fk_empleado_asignatura` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `fk_grado_empleados` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`);

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargos_empleados` (`id_cargo`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `fk_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`),
  ADD CONSTRAINT `fk_responsable_estudiante` FOREIGN KEY (`id_responsable`) REFERENCES `responsables` (`id_responsable`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD CONSTRAINT `fk_ficha_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `fk_ficha_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `fk_nt_actividad` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nt_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `trimestres`
--
ALTER TABLE `trimestres`
  ADD CONSTRAINT `fk_anio` FOREIGN KEY (`id_anio`) REFERENCES `anios` (`id_anio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
