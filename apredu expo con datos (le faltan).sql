-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2023 a las 02:39:40
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
(66, 'Examen Fisica', 100, 'Examen de educacion fisica', '2023-01-05', 67, 3, 1);

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
(1, '2023');

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
  `create_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
  `update_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
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

INSERT INTO `cargos_empleados` (`id_cargo`, `cargo`, `edit_permisos`, `create_usuarios`, `edit_admin`, `edit_perfil`, `create_estudiantes`, `update_estudiantes`, `update_notas`, `view_notas`, `edit_actividades`, `edit_tipo_actividades`, `view_actividades`, `view_all_actividades`, `view_empleados`, `edit_empleados`, `view_estudiantes`, `edit_estudiantes`, `view_responsables`, `edit_responsables`, `view_fichas`, `delete_fichas`, `edit_fichas`, `view_grados`, `edit_grados`, `view_asignaturas`, `edit_asignaturas`, `view_trimestres`, `edit_trimestres`, `edit_detalles_docentes`) VALUES
(1, 'admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'profesor', 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0);

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
(67, NULL, 4, 2);

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
(35, 'Gabriel', 'Aparicio', '43412312-2', 'ggagte@gmail.com', '7545-6545', 'sdasdasd', '2004-10-27', 1, 'GabrielAp', '$2y$10$6fWf9xRHOLsJ8RqrvB1FKO1clWE4v4Bq/ra.NSpgcxwQvRkQElpz6', 0, 1, '2023-10-10 11:29:36', NULL),
(36, 'Margarita', 'Del Valle', '45475898-9', '201940211@ricaldone.edu.sv', '7458-4564', 'asdasdasd', '2003-07-09', 2, 'Margaret', '$2y$10$5Vcop56Cw5.abys6SmmOrekjNaKNYphYewvIeKdd9Mn.VTHGLtNgm', 0, 1, '2023-10-10 12:06:39', NULL),
(38, 'Rodrigo', 'Valles', '54559898-9', '20190211@ricaldone.edu.sv', '7422-5666', 'asdasds', '1997-06-10', 2, 'Rodri', '$2y$10$T9cY4oZ7JqVhesY.5nZ8RugvaNZ8ZejbEvqTILMdqHFf262KxcL.K', 0, 1, '2023-10-10 17:59:21', NULL);

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
(24, 'Nelson', 'Alonso', '2019-10-08', 'asdasd', '5545454', 2, 13, 'padre', 1),
(25, 'Juan', 'Pablo', '2019-01-01', 'sadasdsad', '4545555', 2, 11, 'padre', 1),
(26, 'Alejandra', 'Carrasco', '2019-09-17', 'sadasd', '7777779', 2, 10, 'tío/a', 1),
(27, 'Tatiana', 'Arevalo', '2019-09-11', 'Las terrazas', '7854555', 2, 15, 'madre', 1),
(28, 'Daniel', 'Alonso', '2019-09-30', 'adsadsadsdfdsgf', '5454545', 2, 13, 'madre', 1),
(29, 'Sebastian', 'Cristiano', '2018-12-28', 'fdgdfgdfgfdgfdg', '7856656', 2, 16, 'padre', 1);

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
(9, 24, 'asdasdasd', '2023-10-10', 36);

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
(759, 25, 61, NULL),
(762, 24, 61, NULL),
(763, 26, 61, NULL),
(764, 27, 61, NULL),
(765, 28, 61, NULL),
(766, 29, 61, NULL),
(767, 24, 62, NULL),
(768, 25, 62, NULL),
(769, 26, 62, NULL),
(770, 27, 62, NULL),
(771, 28, 62, NULL),
(772, 29, 62, NULL),
(774, 24, 63, NULL),
(775, 25, 63, NULL),
(776, 26, 63, NULL),
(777, 27, 63, NULL),
(778, 28, 63, NULL),
(779, 29, 63, NULL),
(781, 24, 64, NULL),
(782, 25, 64, NULL),
(783, 26, 64, NULL),
(784, 27, 64, NULL),
(785, 28, 64, NULL),
(786, 29, 64, NULL),
(788, 24, 65, NULL),
(789, 25, 65, NULL),
(790, 26, 65, NULL),
(791, 27, 65, NULL),
(792, 28, 65, NULL),
(793, 29, 65, NULL),
(795, 24, 66, NULL),
(796, 25, 66, NULL),
(797, 26, 66, NULL),
(798, 27, 66, NULL),
(799, 28, 66, NULL),
(800, 29, 66, NULL);

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
  `lugar_de_trabajo` varchar(150) NOT NULL COMMENT 'Dirección del lugar de trabajo',
  `telefono` varchar(9) NOT NULL COMMENT 'Teléfono del lugar de trabajo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `responsables`
--

INSERT INTO `responsables` (`id_responsable`, `nombre_responsable`, `apellido_responsable`, `dui`, `correo_responsable`, `lugar_de_trabajo`, `telefono`) VALUES
(10, 'Juan', 'bermudez', '23434232-3', 'adas@gmail.com', 'aedasdasd', '2323-5653'),
(11, 'Javier', 'Enrique', '36543345-7', 'elpapu@gmail.com', 'el pepe', '7454-3434'),
(12, 'Sandra', 'Cruz', '54785459-9', 'sandraCruz@gmail.com', 'M. de hacienda', '7545-5422'),
(13, 'Xavi', 'Alonso', '78784555-9', 'Xaviaaaa@gmail.com', 'por alla', '2654-6255'),
(14, 'Juan', 'Lopez', '98121115-3', 'JuanPe@gmail.com', 'Por aqui', '7526-6144'),
(15, 'Marisol', 'Arevalo', '78456325-9', 'MariaaaaSol@gmail.com', 'Corte Suprema', '7854-6333'),
(16, 'Sebastian', 'Cristiano', '85452232-6', 'Sebaaaa@gmail.com', 'por aca', '7545-6655');

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
(21, 'Tercer trimestre', 7, 0);

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
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la actividad', AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `anios`
--
ALTER TABLE `anios`
  MODIFY `id_anio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del año', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id_asignatura` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la asignatura', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cargos_empleados`
--
ALTER TABLE `cargos_empleados`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del cargo', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_asignaturas_empleados`
--
ALTER TABLE `detalle_asignaturas_empleados`
  MODIFY `id_detalle_asignatura_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del detalle de la asignatura y empleado', AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del empleado', AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del estudiante', AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la ficha', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del grado', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la nota', AUTO_INCREMENT=802;

--
-- AUTO_INCREMENT de la tabla `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del responsable', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tipo_actividades`
--
ALTER TABLE `tipo_actividades`
  MODIFY `id_tipo_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del tipo de actividad', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `trimestres`
--
ALTER TABLE `trimestres`
  MODIFY `id_trimestre` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del trimestre', AUTO_INCREMENT=22;

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
