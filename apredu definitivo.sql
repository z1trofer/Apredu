-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-10-2023 a las 02:27:10
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
(21, 'Actividad ordinaria Parte I', 15, 'Primera entrega de actividad individual del primer trimestre', '2023-01-31', 22, 1, 1),
(22, 'Actividad ordinaria Parte II', 30, ' Segunda entrega de actividad individual del primer trimestre', '2023-02-27', 22, 1, 1),
(23, 'Examen Individual', 55, 'Examen que realiza cada estudiante para evaluar conocimientos adquiridos', '2023-04-21', 22, 2, 1),
(24, 'Actividad Desarrolladora Grupal', 35, 'Todos los estudiantes realizan de manera grupal un trabajo acerca de la naturaleza', '2023-03-23', 24, 3, 1),
(25, 'Actividad Exploratoria Grupal', 15, 'Se realiza una investigación acerca de temas de interés común', '2023-02-26', 25, 1, 1),
(26, 'Examen oral', 10, 'Cada estudiante prepara una exposicion con material de apoyo', '2023-04-13', 26, 3, 1),
(27, 'Actividad complementaria', 15, 'El estudiante realiza unos ejercicios asignados por el docente', '2023-03-28', 27, 3, 1),
(28, 'Aprendamos a conocernos', 5, 'Actividad que consiste en contestar un cuestinario de 20 preguntas acerca de los que nos gusta', '2023-04-11', 28, 3, 1),
(29, 'Actividad ordinaria I', 15, 'Entrega de avance de investigacion', '2023-05-04', 29, 1, 2),
(30, 'Investigacion creativa', 20, 'Entrega de la primera parte de la investigacion del tema asignado', '2023-05-12', 30, 1, 2),
(31, 'Actividad ordinaria Parte III', 20, 'Tercera entrega de actividad individual del primer trimestre', '2023-03-31', 31, 1, 1),
(32, 'Examen de temas puntuales', 10, 'Examen sobre temas puntuales vistos en el primer trimestre', '2023-04-28', 32, 2, 1),
(33, 'Actividad de debate', 30, 'Se realiza un debate en grupo acerca de un tema específico', '2023-04-10', 33, 3, 1),
(34, 'Actividad de investigación', 20, 'Cada estudiante realiza una investigación acerca de un tema específico', '2023-04-14', 34, 1, 1),
(35, 'Examen de comprensión lectora', 10, 'Examen de comprensión lectora basado en textos dados por el docente', '2023-05-05', 35, 2, 1),
(36, 'Actividad grupal de escritura', 30, 'Se realiza un escrito grupal acerca de un tema específico', '2023-04-20', 36, 3, 1),
(38, 'Exposición de trabajos prácticos', 20, 'Cada estudiante realiza un trabajo práctico y lo expone en grupo', '2023-04-25', 38, 3, 1),
(39, 'Actividad ordinaria II', 25, 'Entrega de avance de investigación', '2023-06-01', 39, 1, 2),
(40, 'Trabajo final', 50, 'Cada estudiante realiza un trabajo final sobre un tema específico', '2023-06-29', 40, 3, 2),
(43, 'examens', 50, 'examen de mate', '2023-06-21', 51, 3, 1),
(44, 'Avtividad 1 artt', 25, 'asdsadasd', '2023-03-14', 59, 1, 1),
(45, 'Artistica planas', 25, 'asdasdasdsad', '2023-04-06', 59, 1, 1),
(46, 'examen artistica', 50, 'examen', '2023-04-21', 59, 3, 1),
(47, 'Mate actividad', 35, 'ghghghghgh', '2023-04-05', 55, 2, 1),
(48, 'Mate aaaa', 25, 'zzzzz', '2023-04-06', 55, 1, 1),
(50, 'Examen', 50, 'examen', '2023-08-30', 53, 3, 1),
(51, 'expo', 25, 'efwdfwefwef', '2023-08-18', 53, 2, 1),
(52, 'Examen trimestre 2', 50, 'hnhujjujujuju', '2023-05-03', 53, 3, 2),
(53, 'tareas ordinarias', 20, 'tareas', '2023-07-12', 53, 1, 2),
(54, 'exporaro', 25, 'iyuiyuiui', '2023-08-24', 53, 2, 2),
(55, 'Examn final', 50, 'fianl examen', '2023-10-10', 53, 3, 3),
(56, 'tareas 3 periodo', 25, 'fefrefre', '2023-10-18', 53, 1, 3),
(57, 'trabajo activo', 25, 'rtfgfgfgfgfg', '2023-10-12', 53, 2, 3),
(58, 'sadasd', 15, 'fgfdg', '2023-10-18', 53, 1, 1),
(59, 'sdasd', 10, 'asdasd', '2023-10-24', 53, 3, 1),
(60, 'Examen de mate basica', 50, 'Examen de parvularia', '2023-01-31', 63, 3, 1);

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
  `update_usuarios` tinyint(1) NOT NULL DEFAULT 0,
  `edit_perfil` tinyint(1) NOT NULL DEFAULT 0,
  `create_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
  `update_estudiantes` tinyint(1) NOT NULL DEFAULT 0,
  `update_notas` tinyint(1) NOT NULL DEFAULT 0,
  `view_notas` tinyint(1) NOT NULL DEFAULT 0,
  `edit_actividades` tinyint(1) NOT NULL DEFAULT 0,
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

INSERT INTO `cargos_empleados` (`id_cargo`, `cargo`, `edit_permisos`, `create_usuarios`, `update_usuarios`, `edit_perfil`, `create_estudiantes`, `update_estudiantes`, `update_notas`, `view_notas`, `edit_actividades`, `view_actividades`, `view_all_actividades`, `view_empleados`, `edit_empleados`, `view_estudiantes`, `edit_estudiantes`, `view_responsables`, `edit_responsables`, `view_fichas`, `delete_fichas`, `edit_fichas`, `view_grados`, `edit_grados`, `view_asignaturas`, `edit_asignaturas`, `view_trimestres`, `edit_trimestres`, `edit_detalles_docentes`) VALUES
(1, 'admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'profesor', 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0);

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
(22, 12, 2, 3),
(24, 13, 5, 6),
(25, 13, 5, 7),
(26, 13, 5, 8),
(27, 13, 5, 9),
(28, 19, 3, 6),
(29, 20, 6, 7),
(30, 21, 2, 7),
(31, 22, 3, 7),
(32, 23, 4, 7),
(33, 24, 2, 8),
(34, 25, 1, 8),
(35, 26, 3, 8),
(36, 27, 4, 8),
(38, 29, 6, 9),
(39, 30, 6, 10),
(40, 12, 6, 3),
(48, NULL, 4, 2),
(49, NULL, 3, 2),
(51, 12, 1, 3),
(53, 13, 5, 3),
(55, 14, 1, 5),
(56, 14, 2, 5),
(57, 14, 3, 5),
(59, 14, 8, 5),
(60, NULL, 5, 2),
(62, 18, 4, 4),
(63, 16, 1, 4);

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
(2, 'Gabriel Guillermo', 'Aparicio García', '23234534-4', 'ggagte@gmail.com', '6645-4545', 'elepepepepepepepepepepepepepepepepep', '2013-10-27', 1, 'admin', '$2y$10$MrqSClHaRjDYU3Wbco2jGeOeMTjq/xwTK5QnQPE5vxi4eNZcq1aBe', 0, 1, '2023-09-12 07:28:16', NULL),
(12, 'Sara Marlene', 'Gúzman Torres', '00817345-1', 'sara_guzman@gmail.com', '2323-2323', 'San Cristobal, Calle Al Matazano, San Salvador', '2001-02-01', 2, 'saritas', '$2y$10$fDO44Ha0P.6mT5WD1q0ecufFa692vihSGj2fCOuDJj9aSxMuAHZYW', 0, 1, '2023-10-01 21:36:11', NULL),
(13, 'Joel Mauricio', 'Ruano Pérez', '01347890-5', '20190211@ricaldone.edu.sv', NULL, 'Av. Los Girasoles, San Salvador', '1980-02-10', 2, 'inglis', '$2y$10$/kqwbAibDi4jeG74w.VcCuszsQ3m8irru8.Zi1VZM/lFHj.PvmCPa', 0, 1, '2023-09-18 10:48:37', NULL),
(14, 'Susana Nohemi', 'Gómez Morán', '00126734-3', 'susana_gomez67@gmail.com', NULL, 'Colonia Santa Clara, San Salvador', '1993-09-12', 2, 'susana', '$2y$10$QXOu/plrONzD/cZo1VeMbeozQzPUeLOoUCzTstXgoD77fUw4BtYbC', 0, 1, '2023-09-12 07:28:16', NULL),
(15, 'Laura Maribel', 'Posada Fuentes', '00246798-0', 'laura_posada24@gmail.com', NULL, 'Residencial Versalles, San Salvador', '1999-04-01', 2, '123dfvfdg', '$2y$10$S/Ww7g0IIYSyvYYUS1jvfeVyYzy/a3JWYFx8pWUfgtOE9jotHN4je', 0, 1, '2023-09-12 07:28:16', NULL),
(16, 'Kenia Alejandra', 'Mujica Melgar', '00561278-0', 'kenia_mujica56@gmail.com', NULL, 'Col. El tránsito 3, Santo Tómas, San Salvador', '2000-03-03', 2, 'ghgh', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(17, 'Maribel Lucía', 'Guardia López', '00348901-3', 'maribel_guardia34@gmail.com', NULL, ' Col. Santa Mónica, Mejicanos, San Salvador', '1976-02-27', 2, 'xzcxzcxz', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(18, 'Juan Lauro', 'Portillo Monterosa', '00125690-1', 'juan_portillo12@gmail.com', NULL, 'Col. Santo Domingo, Merliot, San Salvador', '1983-11-02', 2, 'gerrwer', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(19, 'William Humberto', 'Romero Serrano', '00234512-0', 'william_romero23@gmail.com', NULL, 'Calle 25 av. Norte, San Salvador', '2000-09-12', 2, 'sasasd', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(20, 'Lisbeth Guadalupe', 'Mendez Monge', '00124590-4', 'lisbeth_mendez12@gmail.com', NULL, 'San Antonio Abad, San Salvador', '1990-01-31', 2, 'rf453d', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(21, 'María Laura', 'González Iraheta', '00646569-1', 'maria_gon12@gmail.com', NULL, 'Av. Aguilares, San Salvador', '1991-12-12', 2, 'webos', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(22, 'Mauricio Salomón', 'Mendoza Escobar', '0091256-2', 'mauricio_mendoza91@gmail.com', NULL, 'Av. 25 Norte, colonia El Tránsito 3, San Salvador', '1998-02-01', 2, 'dfsfdsd', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(23, 'Juan Carlos', 'Lima Zaragoza', '00125367-8', 'juan_lima12@gmail.com', NULL, 'Col. La Rábida, Col. Urb. su casa #123', '1997-05-31', 2, 'asdsadq', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(24, 'Wilver Geovanni', 'Yanes López', '0631870-5', 'wilver_yanes01@gmail.com', NULL, 'San Salvador', '1976-11-09', 1, 'rthrthtr', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(25, 'Laura Margarita', 'Hernández Carrillo', '0951424-2', 'laura_hernandez09@gmail.com', NULL, 'La Troncal, San Salvador', '1997-05-31', 2, 'dfgfg', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(26, 'Ana Ivette', 'Ticas Pérez', '0345681-2', 'ana_ticas03@gmail.com', NULL, 'Mejicanos, San Salvador', '1976-06-12', 2, '454554wsw', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(27, 'María Luisa', 'Serrano Juárez', '0089642-8', 'maria_serrano08@gmail.com', NULL, 'Ciudad Delgado, San Salvador', '1984-07-30', 2, 'maruia', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(28, 'Joaquín Vladimir', 'Figueroa Torres', '0091844-2', 'joaquin_figueroa09@gmail.com', NULL, 'Col. Altos del cerro, Soyapango, San Salvador', '1987-10-31', 2, 'bhghghgh', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(29, 'Keila Rosario', 'Melgar Mejía', '0067893-1', 'keila_melgar67@gmail.com', NULL, 'Col. 25 de Abril, San Salvador', '1993-08-14', 2, 'sdfsdfsdxcz', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(30, 'Fátima Esmeralda', 'Peralta Méndez', '0652569-8', 'fatima_peralta65@gmail.com', NULL, 'San Salvador', '2000-09-04', 2, 'zzzzzxxxx', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(31, 'Andrea Beatriz', 'Fernández Olivo', '0912035-7', 'andrea_fernandez91@gmail.com', NULL, 'Nejapa, San Salvador', '2001-02-09', 2, 'xzccx', '1234', 0, 0, '2023-09-12 07:28:16', NULL),
(32, 'Alberto', 'Masferrer', '23234553-1', 'albetrt@gmail.com', NULL, 'alla', '1992-01-15', 2, 'Albert', '$2y$10$6RiwbGVqfVYfMzz0Dmpdq.dZEyOid8oIYngYgJgyHbPgURnNCYaOu', 0, 1, '2023-09-12 07:28:16', NULL);

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
  `usuario_estudiante` varchar(50) NOT NULL COMMENT 'Usuario del estudiante',
  `clave` varchar(100) NOT NULL COMMENT 'Clave de acceso del usuario',
  `estado` tinyint(1) NOT NULL COMMENT 'Estado de actividad del usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre_estudiante`, `apellido_estudiante`, `fecha_nacimiento`, `direccion`, `nie`, `id_grado`, `usuario_estudiante`, `clave`, `estado`) VALUES
(2, 'Merlinda Saraí', 'SantaMaría Pérez', '2016-03-02', 'Col. Los Girasoles, Santa Domingo, San Salvador', '200232', 3, 'awebo', '', 1),
(3, 'William Alexander', 'Acosta Mendoza', '2018-04-19', 'Col. Los Laureles, San Salvador', '12345-6', 3, 'asdasdas', '124', 0),
(4, 'Lorena Patricia', 'Santos Gutiérrez', '2015-12-12', 'Av. Las Palmeras, San Salvador', '203452-1', 3, 'zzszsdsd', '124', 0),
(5, 'Katherine Yolanda', 'López Barahona', '2014-02-12', 'Av. Texacuangos, Nejapa, San Salvador', '12342-1', 3, 'kiiikiki', '124', 0),
(6, 'Salvador Josué', 'Serrano Monge', '2014-07-23', 'Av. Los Naranjos, San Salvador, El Salvador', '234531-2', 3, '3443drrtddfgd', '124', 0),
(7, 'Mason Leví', 'Serrano Ascencio', '2012-08-11', 'Av. Villa de San Antonio, San Salvador', '12345-5', 3, '454gsdfdfsd', '124', 0),
(8, 'Walter Antonio', 'Menjivar Escalante', '2012-09-12', 'Valle de San Marcos, San Salvador', '222321-2', 9, 'zzzzxcxccxz', '124', 0),
(9, 'Lucas Samuel', 'Medrano Santos', '2016-12-23', 'Col. Santa Marta, San Salvador', '145673-3', 3, '3333gggt', '124', 0),
(10, 'Susana Nohemi', 'Barahona Escobar', '2017-10-09', 'Col. Escalón, 75 av. Norte, San Salvador', '65312-3', 3, 'oloñoñoñ', '124', 0),
(11, 'Marcela Josefina', 'Posada Fuentes', '2018-08-19', 'Av. Aguilares, Calle San Cristóbal, S.S', '345712-3', 6, 'eerwewrwerewr', '124', 0),
(12, 'Carlos Humberto', 'Rivera Durán', '2014-09-08', 'San Salvador', '120945-6', 7, '343434sfdsd', '124', 0),
(13, 'Walter Armando', 'Santos Nuñez', '2015-03-02', 'San Salvador', '231256-3', 6, 'sdsdfsdfs', '124', 0),
(14, 'Astrid Saraí', 'Parada Jimenez', '2016-07-15', 'San Salvador', '134205-4', 4, '345345dfgdfg', '124', 0),
(15, 'Jimena Elizabeth', 'Guardado Fernández', '2016-05-15', 'Col. Escalón, San Salvador', '152305-2', 4, 'zzzzss', '124', 0),
(16, 'Brenda Celene', 'Hernández Delgado', '2015-12-03', 'San Marcos, San Salvador', '142309-5', 5, '1123123', '124', 0),
(17, 'Edwin Alexander', 'Rivera Guardado', '2015-11-19', 'San Salvador', '125409-2', 5, 'dfdssdfsd', '124', 0),
(18, 'Dora Alicia', 'Monge Rivera', '2014-09-21', 'Nejapa, San Salvador', '165323-6', 7, 'sdsdfdsf', '124', 0),
(19, 'Wendy Liseth', 'Carrillo Torres', '2014-06-03', 'San Salvador', '132423-0', 7, '112321232', '124', 0),
(20, 'Isabella Abigail', 'Rodriguez Mujica', '2015-02-01', 'San Salvador', '124309-6', 6, 'awaawwawa', '124', 0),
(21, 'David Francisco', 'Rivera Guardado', '2016-12-23', 'San Salvador', '128675-6', 4, 'ghgfhfghgf', '124', 0),
(22, 'Alejandro', 'Velgara', '2018-06-05', 'wadwaewaewe', '232323', 3, 'AlejandroVelgara', '', 1);

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
    WHERE detalle_asignaturas_empleados.id_grado = NEW.id_grado and anios.anio = YEAR(CURRENT_DATE());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `notas_update_estudiantes` AFTER UPDATE ON `estudiantes` FOR EACH ROW BEGIN
    INSERT INTO notas (id_estudiante, id_actividad, nota)
    SELECT NEW.id_estudiante, actividades.id_actividad, null
    FROM actividades
    INNER JOIN detalle_asignaturas_empleados USING (id_detalle_asignatura_empleado)
	INNER JOIN trimestres USING (id_trimestre)
	INNER JOIN anios USING (id_anio)
    WHERE detalle_asignaturas_empleados.id_grado = NEW.id_grado and anios.anio = YEAR(CURRENT_DATE());
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
(1, 3, 'sadasdsad', '0000-00-00', 2),
(2, 9, 'sadsad', '0000-00-00', 2),
(3, 3, 'sadsad', '0000-00-00', 2),
(4, 2, 'edd', '0000-00-00', 2),
(5, 2, 'dfgfdgfdgdfg', '0000-00-00', 13),
(6, 2, 'cfvcvb sdfs d', '2023-09-18', 13),
(7, 2, 'cfvcvb sdfs dDFG .', '2023-09-18', 13),
(8, 2, 'cfvcvb sdfs dDFG .,', '2023-09-18', 13);

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
(12, 'maternidad'),
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
(1, 2, 21, 5.60),
(2, 2, 22, 8.00),
(3, 2, 23, 9.00),
(4, 3, 21, 5.00),
(5, 3, 22, 8.00),
(6, 3, 23, 4.00),
(8, 4, 21, NULL),
(23, 22, 21, NULL),
(24, 22, 22, NULL),
(25, 22, 23, NULL),
(26, 22, 40, NULL),
(90, 2, 43, 10.00),
(91, 3, 43, 2.00),
(92, 4, 43, 4.00),
(93, 5, 43, 1.00),
(94, 6, 43, NULL),
(95, 7, 43, 8.00),
(96, 9, 43, 6.00),
(97, 10, 43, 3.00),
(98, 22, 43, NULL),
(99, 2, 21, NULL),
(100, 2, 22, NULL),
(101, 2, 23, NULL),
(102, 2, 40, NULL),
(103, 2, 43, NULL),
(106, 16, 44, NULL),
(107, 17, 44, NULL),
(109, 16, 45, NULL),
(110, 17, 45, NULL),
(112, 16, 46, NULL),
(113, 17, 46, NULL),
(115, 16, 47, NULL),
(116, 17, 47, NULL),
(118, 16, 48, 9.00),
(119, 17, 48, 4.00),
(136, 2, 50, 9.00),
(137, 3, 50, 1.00),
(138, 4, 50, 10.00),
(139, 5, 50, 8.00),
(140, 6, 50, 7.50),
(141, 7, 50, 7.00),
(142, 9, 50, 8.00),
(143, 10, 50, 6.00),
(144, 22, 50, 8.30),
(151, 2, 51, 10.00),
(152, 3, 51, 8.00),
(153, 4, 51, 9.00),
(154, 5, 51, 8.00),
(155, 6, 51, 5.00),
(156, 7, 51, 6.00),
(157, 9, 51, 8.00),
(158, 10, 51, 8.00),
(159, 22, 51, 7.00),
(166, 2, 52, 2.00),
(167, 3, 52, 8.00),
(168, 4, 52, 3.00),
(169, 5, 52, 9.00),
(170, 6, 52, 8.00),
(171, 7, 52, 4.00),
(172, 9, 52, 10.00),
(173, 10, 52, 8.00),
(174, 22, 52, 9.00),
(181, 2, 53, 9.00),
(182, 3, 53, 5.50),
(183, 4, 53, 5.00),
(184, 5, 53, 7.00),
(185, 6, 53, 2.00),
(186, 7, 53, 3.00),
(187, 9, 53, 8.00),
(188, 10, 53, 6.70),
(189, 22, 53, 9.90),
(196, 2, 54, 9.00),
(197, 3, 54, 6.00),
(198, 4, 54, 6.00),
(199, 5, 54, 5.00),
(200, 6, 54, 3.00),
(201, 7, 54, 10.00),
(202, 9, 54, 8.00),
(203, 10, 54, 6.00),
(204, 22, 54, 4.00),
(211, 2, 55, 9.00),
(212, 3, 55, 10.00),
(213, 4, 55, 8.00),
(214, 5, 55, 7.00),
(215, 6, 55, 8.80),
(216, 7, 55, 9.00),
(217, 9, 55, 6.00),
(218, 10, 55, 8.00),
(219, 22, 55, 7.00),
(226, 2, 56, 8.00),
(227, 3, 56, 10.00),
(228, 4, 56, 7.00),
(229, 5, 56, 10.00),
(230, 6, 56, 8.00),
(231, 7, 56, 5.00),
(232, 9, 56, 8.00),
(233, 10, 56, 10.00),
(234, 22, 56, 6.00),
(241, 2, 57, 7.00),
(242, 3, 57, 2.00),
(243, 4, 57, 9.00),
(244, 5, 57, 8.00),
(245, 6, 57, 9.00),
(246, 7, 57, 8.00),
(247, 9, 57, 9.00),
(248, 10, 57, 3.00),
(249, 22, 57, 10.00),
(250, 2, 58, NULL),
(251, 3, 58, NULL),
(252, 4, 58, NULL),
(253, 5, 58, NULL),
(254, 6, 58, NULL),
(255, 7, 58, NULL),
(256, 9, 58, NULL),
(257, 10, 58, NULL),
(258, 22, 58, NULL),
(265, 2, 59, NULL),
(266, 3, 59, NULL),
(267, 4, 59, NULL),
(268, 5, 59, NULL),
(269, 6, 59, NULL),
(270, 7, 59, NULL),
(271, 9, 59, NULL),
(272, 10, 59, NULL),
(273, 22, 59, NULL),
(274, 14, 60, NULL),
(275, 15, 60, NULL),
(276, 21, 60, NULL);

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
  `telefono_trabajo` varchar(9) NOT NULL COMMENT 'Teléfono del lugar de trabajo',
  `parentesco` varchar(30) NOT NULL COMMENT 'parentesco del familiar',
  `id_estudiante` int(11) NOT NULL COMMENT 'Campo id del estudiante correspondiente al responsable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `responsables`
--

INSERT INTO `responsables` (`id_responsable`, `nombre_responsable`, `apellido_responsable`, `dui`, `correo_responsable`, `lugar_de_trabajo`, `telefono_trabajo`, `parentesco`, `id_estudiante`) VALUES
(1, 'William', 'Afton', '54545434-2', 'morado@gmail.com', 'freddy fazbears pizzeria', '6343-2343', 'Tutor legal', 14),
(2, 'Cara deloco', 'De posada', '23423563-3', 'ellocod@gmail.com', 'por aqui y alla', '2323-4564', 'Padre', 19),
(3, 'Cara Larga', 'Ludovina', '76453452-2', 'ggagte@gmail.com', 'alla', '2342-2323', 'Madre', 2),
(5, 'el coco', 'ludovina', '76544323-2', 'aaaaaa@gmail.com', 'estudiante', '7655-4444', 'Padre', 2);

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
(3, 'Examen'),
(4, 'Actividad extraordinaria');

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
  ADD UNIQUE KEY `usuario_estudiante` (`usuario_estudiante`),
  ADD KEY `fk_grado` (`id_grado`);

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
  ADD UNIQUE KEY `correo_responsable` (`correo_responsable`),
  ADD KEY `responsables_estudiantes` (`id_estudiante`);

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
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la actividad', AUTO_INCREMENT=61;

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
  MODIFY `id_detalle_asignatura_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del detalle de la asignatura y empleado', AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del empleado', AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del estudiante', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la ficha', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del grado', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la nota', AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT de la tabla `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del responsable', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_actividades`
--
ALTER TABLE `tipo_actividades`
  MODIFY `id_tipo_actividad` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del tipo de actividad', AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `fk_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`);

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
-- Filtros para la tabla `responsables`
--
ALTER TABLE `responsables`
  ADD CONSTRAINT `responsables_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `trimestres`
--
ALTER TABLE `trimestres`
  ADD CONSTRAINT `fk_anio` FOREIGN KEY (`id_anio`) REFERENCES `anios` (`id_anio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
