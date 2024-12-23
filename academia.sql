-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 23-12-2024 a las 05:49:46
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `academia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_estudiantes`
--

CREATE TABLE IF NOT EXISTS `asistencia_estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante_grupos` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Volcado de datos para la tabla `asistencia_estudiantes`
--

INSERT INTO `asistencia_estudiantes` (`id`, `id_estudiante_grupos`, `fecha`) VALUES
(54, 18, '2023-08-03'),
(55, 18, '2023-08-05'),
(56, 18, '2023-08-07'),
(57, 18, '2023-08-09'),
(58, 10, '2024-11-03'),
(59, 10, '2024-11-05'),
(60, 10, '2024-11-07'),
(61, 6, '2024-11-04'),
(62, 6, '2024-11-06'),
(63, 16, '2024-11-03'),
(64, 16, '2024-11-05'),
(65, 14, '2024-11-04'),
(66, 7, '2024-11-03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE IF NOT EXISTS `clases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `num_estudiantes` int(11) NOT NULL,
  `costo` double NOT NULL,
  `observaciones` varchar(100) NOT NULL,
  `pago` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `fecha`, `id_profesor`, `id_curso`, `hora_inicio`, `hora_fin`, `id_estudiante`, `num_estudiantes`, `costo`, `observaciones`, `pago`) VALUES
(1, '2024-12-10', 1, 1, '08:00:00', '10:00:00', 25, 1, 15, '', 'SI'),
(2, '2024-12-12', 1, 1, '08:00:00', '10:00:00', 3, 1, 30, '', 'SI'),
(3, '2024-12-13', 1, 1, '11:00:00', '12:00:00', 4, 0, 15, '', 'SI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases_grupo`
--

CREATE TABLE IF NOT EXISTS `clases_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `estado` int(11) NOT NULL,
  `id_clase_semanal_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Volcado de datos para la tabla `clases_grupo`
--

INSERT INTO `clases_grupo` (`id`, `fecha`, `estado`, `id_clase_semanal_grupo`) VALUES
(40, '2023-09-18', 0, 0),
(41, '2023-09-25', 0, 0),
(42, '2024-11-04', 0, 1),
(43, '2024-11-11', 0, 1),
(44, '2024-11-18', 0, 1),
(45, '2024-11-25', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases_paquete`
--

CREATE TABLE IF NOT EXISTS `clases_paquete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_paquete` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `observaciones` varchar(50) NOT NULL,
  `num_clase` varchar(6) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `clases_paquete`
--

INSERT INTO `clases_paquete` (`id`, `id_paquete`, `id_curso`, `id_profesor`, `hora_inicio`, `hora_fin`, `observaciones`, `num_clase`, `fecha`) VALUES
(7, 1, 1, 1, '08:00:00', '10:00:00', '', '2 / 12', '2024-12-13'),
(8, 1, 1, 1, '10:00:00', '11:00:00', '', '3 / 12', '2024-12-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase_semanal_grupo`
--

CREATE TABLE IF NOT EXISTS `clase_semanal_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grupo` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `dia` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `clase_semanal_grupo`
--

INSERT INTO `clase_semanal_grupo` (`id`, `id_grupo`, `id_profesor`, `id_curso`, `hora_inicio`, `hora_fin`, `dia`) VALUES
(1, 1, 1, 1, '08:00:00', '10:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `idnivel` int(11) NOT NULL,
  `pago1p` decimal(10,0) NOT NULL,
  `pago2p` decimal(10,0) NOT NULL,
  `pago3p` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `idnivel`, `pago1p`, `pago2p`, `pago3p`) VALUES
(1, 'Comunicación y Lenguaje', 3, '15', '15', '18'),
(6, 'Calculo', 4, '15', '18', '20'),
(30, 'CTA', 0, '12', '15', '18'),
(37, 'Razonamiento Matemático', 3, '15', '18', '20'),
(38, 'Física', 4, '15', '18', '20'),
(39, 'Fisica', 3, '15', '17', '20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `DNI_estudiante` varchar(15) NOT NULL,
  `nombres_estudiante` varchar(20) NOT NULL,
  `apellidos_estudiante` varchar(20) NOT NULL,
  `telefono_estudiante` varchar(15) NOT NULL,
  `correo_estudiante` varchar(20) NOT NULL,
  `fecha_inicio_estudiante` date NOT NULL,
  PRIMARY KEY (`id_estudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `DNI_estudiante`, `nombres_estudiante`, `apellidos_estudiante`, `telefono_estudiante`, `correo_estudiante`, `fecha_inicio_estudiante`) VALUES
(1, '40602315', 'Matias', 'Ramirez Collado', '955149263', '', '2023-09-01'),
(3, '49873405', 'Mateo Alberto', 'Ramirez Benavides', '980689580', '', '2023-10-02'),
(4, '41980348', 'Ricardo', 'López', '', '', '2023-10-02'),
(5, '48936892', 'Luisa', 'Rondón', '955149263', 'correo', '2023-10-04'),
(6, '49085034', 'Frank', 'Caccya Suarez', '955149263', '', '2023-10-04'),
(7, '67847382', 'Luisa', 'Ramirez Flores', '', '', '0000-00-00'),
(8, '48920381', 'Mateo', 'Puma', '980689580', 'correo', '2023-10-04'),
(9, '49028495', 'Hector', 'Valer', '', '', '0000-00-00'),
(10, '409121857', 'Mateo', 'Caccya', '', '', '2023-10-04'),
(11, '67865467', 'Luisa', 'Rios', '', '', '0000-00-00'),
(12, '76546879', 'Luisa', 'Rios', '', '', '0000-00-00'),
(13, '45678523', 'Juan', 'Sanchez', '234564', 'correo@gmailcom', '2024-08-21'),
(18, '20304050', 'Luis', 'Quispe', '987654321', '', '0000-00-00'),
(19, '21314151', 'Fernando', 'Guevara', '90785674', '', '0000-00-00'),
(20, '40506070', 'Milu', 'Gutierrez', '213242', '', '0000-00-00'),
(21, '22324252', 'Martha', 'Olayunca', '435261', '', '0000-00-00'),
(22, '57890730', 'Albita', 'Cumpa', '98368201', '', '2024-11-04'),
(23, '49094909', 'Aleth Guadalupe', 'Cori Cumpa', '908908908', '', '2024-11-04'),
(24, '67766776', 'Norma Irma', 'Yarrow', '987836765', '', '2024-11-06'),
(25, '40203548', 'Alba Guadalupe', 'Cumpa', '980689580', '', '0000-00-00'),
(26, '67382992', 'Luis', 'Ramirez', '356772', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_grupos`
--

CREATE TABLE IF NOT EXISTS `estudiantes_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `estudiantes_grupos`
--

INSERT INTO `estudiantes_grupos` (`id`, `id_estudiante`, `id_grupo`) VALUES
(1, 1, 1),
(6, 6, 1),
(7, 7, 1),
(9, 9, 1),
(10, 10, 1),
(13, 13, 14),
(14, 22, 1),
(16, 23, 1),
(17, 24, 1),
(18, 1, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_pagos`
--

CREATE TABLE IF NOT EXISTS `fecha_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grupo` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=200 ;

--
-- Volcado de datos para la tabla `fecha_pagos`
--

INSERT INTO `fecha_pagos` (`id`, `id_grupo`, `fecha_pago`) VALUES
(174, 14, '2024-08-23'),
(181, 1, '2023-10-01'),
(182, 1, '2023-10-15'),
(183, 1, '2023-10-25'),
(196, 10, '2024-11-12'),
(197, 10, '2024-11-16'),
(198, 16, '2024-11-14'),
(199, 16, '2024-11-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_grupo` varchar(60) NOT NULL,
  `inicio_grupo` date NOT NULL,
  `fin_grupo` date NOT NULL,
  `numero_pagos` int(11) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `inicio_grupo`, `fin_grupo`, `numero_pagos`) VALUES
(1, 'Grupo Cepreunsa 1 - 2023', '2024-11-03', '2024-11-30', 3),
(9, 'Grupo Cepreunsa 2023', '2023-08-03', '2023-09-10', 0),
(10, 'Grupo Cepreunsa Verano', '2023-09-13', '2023-11-30', 2),
(11, 'grupo prueba', '2023-08-27', '2023-09-03', 0),
(14, 'Verano 2024', '2024-08-21', '2024-09-21', 1),
(15, 'Grupo Si', '2024-11-07', '2024-11-09', 0),
(16, 'Grupo Cho', '2024-11-14', '2024-11-16', 2),
(18, 'Grupo Cepreunsa Invierno', '2024-11-01', '2024-11-30', 0),
(19, 'Grupo 10', '2024-11-01', '2024-11-30', 0),
(20, 'Grupo 9', '2024-11-01', '2024-11-30', 0),
(21, 'Grupo 11', '2024-11-01', '2024-11-30', 0),
(22, 'Grupo 12', '2024-10-27', '2024-11-03', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE IF NOT EXISTS `horario` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(5) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=614 ;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`cod`, `id`, `hora_inicio`, `hora_fin`, `idProfesor`, `dia`) VALUES
(106, '6_2', '08:00:00', '08:30:00', 37, 6),
(107, '6_8', '11:00:00', '11:30:00', 37, 6),
(108, '1_10', '12:00:00', '12:30:00', 37, 1),
(109, '1_11', '12:30:00', '01:00:00', 37, 1),
(110, '1_12', '01:00:00', '01:30:00', 37, 1),
(111, '1_13', '01:30:00', '02:00:00', 37, 1),
(112, '1_14', '02:00:00', '02:30:00', 37, 1),
(113, '1_15', '02:30:00', '03:00:00', 37, 1),
(114, '1_16', '03:00:00', '03:30:00', 37, 1),
(210, '1_3', '08:30:00', '09:00:00', 36, 1),
(211, '6_3', '08:30:00', '09:00:00', 36, 6),
(212, '1_4', '09:00:00', '09:30:00', 36, 1),
(213, '6_4', '09:00:00', '09:30:00', 36, 6),
(214, '1_5', '09:30:00', '10:00:00', 36, 1),
(215, '6_5', '09:30:00', '10:00:00', 36, 6),
(216, '1_6', '10:00:00', '10:30:00', 36, 1),
(217, '3_6', '10:00:00', '10:30:00', 36, 3),
(218, '6_6', '10:00:00', '10:30:00', 36, 6),
(219, '3_7', '10:30:00', '11:00:00', 36, 3),
(220, '6_7', '10:30:00', '11:00:00', 36, 6),
(221, '3_8', '11:00:00', '11:30:00', 36, 3),
(222, '6_8', '11:00:00', '11:30:00', 36, 6),
(223, '3_9', '11:30:00', '12:00:00', 36, 3),
(224, '5_9', '11:30:00', '12:00:00', 36, 5),
(225, '3_10', '12:00:00', '12:30:00', 36, 3),
(226, '5_10', '12:00:00', '12:30:00', 36, 5),
(227, '3_11', '12:30:00', '01:00:00', 36, 3),
(228, '5_11', '12:30:00', '01:00:00', 36, 5),
(229, '3_12', '01:00:00', '01:30:00', 36, 3),
(230, '5_12', '01:00:00', '01:30:00', 36, 5),
(231, '3_13', '01:30:00', '02:00:00', 36, 3),
(232, '5_13', '01:30:00', '02:00:00', 36, 5),
(233, '6_13', '01:30:00', '02:00:00', 36, 6),
(234, '3_14', '02:00:00', '02:30:00', 36, 3),
(235, '5_14', '02:00:00', '02:30:00', 36, 5),
(236, '6_14', '02:00:00', '02:30:00', 36, 6),
(237, '3_15', '02:30:00', '03:00:00', 36, 3),
(238, '4_15', '02:30:00', '03:00:00', 36, 4),
(239, '6_15', '02:30:00', '03:00:00', 36, 6),
(240, '3_16', '03:00:00', '03:30:00', 36, 3),
(241, '4_16', '03:00:00', '03:30:00', 36, 4),
(242, '6_16', '03:00:00', '03:30:00', 36, 6),
(243, '3_17', '03:30:00', '04:00:00', 36, 3),
(244, '4_17', '03:30:00', '04:00:00', 36, 4),
(245, '6_17', '03:30:00', '04:00:00', 36, 6),
(246, '4_18', '04:00:00', '04:30:00', 36, 4),
(247, '6_18', '04:00:00', '04:30:00', 36, 6),
(248, '4_19', '04:30:00', '05:00:00', 36, 4),
(249, '6_19', '04:30:00', '05:00:00', 36, 6),
(250, '1_20', '05:00:00', '05:30:00', 36, 1),
(251, '4_20', '05:00:00', '05:30:00', 36, 4),
(252, '5_20', '05:00:00', '05:30:00', 36, 5),
(253, '6_20', '05:00:00', '05:30:00', 36, 6),
(254, '1_21', '05:30:00', '06:00:00', 36, 1),
(255, '4_21', '05:30:00', '06:00:00', 36, 4),
(256, '5_21', '05:30:00', '06:00:00', 36, 5),
(257, '1_22', '06:00:00', '06:30:00', 36, 1),
(258, '5_22', '06:00:00', '06:30:00', 36, 5),
(259, '1_23', '06:30:00', '07:00:00', 36, 1),
(260, '3_23', '06:30:00', '07:00:00', 36, 3),
(261, '5_23', '06:30:00', '07:00:00', 36, 5),
(262, '3_24', '07:00:00', '07:30:00', 36, 3),
(263, '5_24', '07:00:00', '07:30:00', 36, 5),
(264, '3_25', '07:30:00', '08:00:00', 36, 3),
(265, '3_26', '08:00:00', '08:30:00', 36, 3),
(266, '6_27', '08:30:00', '09:00:00', 36, 6),
(382, '5_2', '08:00:00', '08:30:00', 67, 5),
(383, '6_2', '08:00:00', '08:30:00', 67, 6),
(384, '5_3', '08:30:00', '09:00:00', 67, 5),
(385, '6_3', '08:30:00', '09:00:00', 67, 6),
(386, '6_4', '09:00:00', '09:30:00', 67, 6),
(387, '6_5', '09:30:00', '10:00:00', 67, 6),
(388, '5_6', '10:00:00', '10:30:00', 67, 5),
(389, '6_6', '10:00:00', '10:30:00', 67, 6),
(390, '6_7', '10:30:00', '11:00:00', 67, 6),
(391, '6_8', '11:00:00', '11:30:00', 67, 6),
(392, '6_9', '11:30:00', '12:00:00', 67, 6),
(449, '1_2', '08:00:00', '08:30:00', 1, 1),
(450, '2_2', '08:00:00', '08:30:00', 1, 2),
(451, '3_2', '08:00:00', '08:30:00', 1, 3),
(452, '4_2', '08:00:00', '08:30:00', 1, 4),
(453, '5_2', '08:00:00', '08:30:00', 1, 5),
(454, '6_2', '08:00:00', '08:30:00', 1, 6),
(455, '1_3', '08:30:00', '09:00:00', 1, 1),
(456, '2_3', '08:30:00', '09:00:00', 1, 2),
(457, '3_3', '08:30:00', '09:00:00', 1, 3),
(458, '4_3', '08:30:00', '09:00:00', 1, 4),
(459, '5_3', '08:30:00', '09:00:00', 1, 5),
(460, '6_3', '08:30:00', '09:00:00', 1, 6),
(461, '1_4', '09:00:00', '09:30:00', 1, 1),
(462, '2_4', '09:00:00', '09:30:00', 1, 2),
(463, '3_4', '09:00:00', '09:30:00', 1, 3),
(464, '4_4', '09:00:00', '09:30:00', 1, 4),
(465, '5_4', '09:00:00', '09:30:00', 1, 5),
(466, '6_4', '09:00:00', '09:30:00', 1, 6),
(467, '1_5', '09:30:00', '10:00:00', 1, 1),
(468, '2_5', '09:30:00', '10:00:00', 1, 2),
(469, '3_5', '09:30:00', '10:00:00', 1, 3),
(470, '4_5', '09:30:00', '10:00:00', 1, 4),
(471, '5_5', '09:30:00', '10:00:00', 1, 5),
(472, '6_5', '09:30:00', '10:00:00', 1, 6),
(473, '1_6', '10:00:00', '10:30:00', 1, 1),
(474, '2_6', '10:00:00', '10:30:00', 1, 2),
(475, '3_6', '10:00:00', '10:30:00', 1, 3),
(476, '4_6', '10:00:00', '10:30:00', 1, 4),
(477, '5_6', '10:00:00', '10:30:00', 1, 5),
(478, '6_6', '10:00:00', '10:30:00', 1, 6),
(479, '3_7', '10:30:00', '11:00:00', 1, 3),
(480, '5_7', '10:30:00', '11:00:00', 1, 5),
(481, '6_7', '10:30:00', '11:00:00', 1, 6),
(482, '3_8', '11:00:00', '11:30:00', 1, 3),
(483, '5_8', '11:00:00', '11:30:00', 1, 5),
(484, '6_8', '11:00:00', '11:30:00', 1, 6),
(485, '3_9', '11:30:00', '12:00:00', 1, 3),
(486, '5_9', '11:30:00', '12:00:00', 1, 5),
(487, '1_10', '12:00:00', '12:30:00', 1, 1),
(488, '1_11', '12:30:00', '01:00:00', 1, 1),
(489, '1_12', '01:00:00', '01:30:00', 1, 1),
(490, '1_13', '01:30:00', '02:00:00', 1, 1),
(491, '6_13', '01:30:00', '02:00:00', 1, 6),
(492, '1_14', '02:00:00', '02:30:00', 1, 1),
(493, '6_14', '02:00:00', '02:30:00', 1, 6),
(494, '5_15', '02:30:00', '03:00:00', 1, 5),
(495, '6_15', '02:30:00', '03:00:00', 1, 6),
(496, '5_16', '03:00:00', '03:30:00', 1, 5),
(497, '6_16', '03:00:00', '03:30:00', 1, 6),
(498, '5_17', '03:30:00', '04:00:00', 1, 5),
(499, '6_17', '03:30:00', '04:00:00', 1, 6),
(500, '5_18', '04:00:00', '04:30:00', 1, 5),
(501, '6_18', '04:00:00', '04:30:00', 1, 6),
(502, '5_19', '04:30:00', '05:00:00', 1, 5),
(503, '6_19', '04:30:00', '05:00:00', 1, 6),
(504, '5_20', '05:00:00', '05:30:00', 1, 5),
(505, '6_20', '05:00:00', '05:30:00', 1, 6),
(506, '5_21', '05:30:00', '06:00:00', 1, 5),
(507, '6_21', '05:30:00', '06:00:00', 1, 6),
(508, '5_22', '06:00:00', '06:30:00', 1, 5),
(509, '6_22', '06:00:00', '06:30:00', 1, 6),
(510, '1_2', '08:00:00', '08:30:00', 70, 1),
(511, '2_2', '08:00:00', '08:30:00', 70, 2),
(512, '5_2', '08:00:00', '08:30:00', 70, 5),
(513, '1_3', '08:30:00', '09:00:00', 70, 1),
(514, '2_3', '08:30:00', '09:00:00', 70, 2),
(515, '5_3', '08:30:00', '09:00:00', 70, 5),
(516, '1_4', '09:00:00', '09:30:00', 70, 1),
(517, '3_4', '09:00:00', '09:30:00', 70, 3),
(518, '5_4', '09:00:00', '09:30:00', 70, 5),
(519, '1_5', '09:30:00', '10:00:00', 70, 1),
(520, '3_5', '09:30:00', '10:00:00', 70, 3),
(521, '4_5', '09:30:00', '10:00:00', 70, 4),
(522, '5_5', '09:30:00', '10:00:00', 70, 5),
(523, '6_5', '09:30:00', '10:00:00', 70, 6),
(524, '1_6', '10:00:00', '10:30:00', 70, 1),
(525, '3_6', '10:00:00', '10:30:00', 70, 3),
(526, '4_6', '10:00:00', '10:30:00', 70, 4),
(527, '6_6', '10:00:00', '10:30:00', 70, 6),
(528, '1_7', '10:30:00', '11:00:00', 70, 1),
(529, '3_7', '10:30:00', '11:00:00', 70, 3),
(530, '6_7', '10:30:00', '11:00:00', 70, 6),
(531, '1_12', '01:00:00', '01:30:00', 70, 1),
(532, '1_13', '01:30:00', '02:00:00', 70, 1),
(533, '1_14', '02:00:00', '02:30:00', 70, 1),
(534, '4_14', '02:00:00', '02:30:00', 70, 4),
(535, '1_15', '02:30:00', '03:00:00', 70, 1),
(536, '4_15', '02:30:00', '03:00:00', 70, 4),
(537, '4_16', '03:00:00', '03:30:00', 70, 4),
(548, '1_3', '08:30:00', '09:00:00', 68, 1),
(549, '2_3', '08:30:00', '09:00:00', 68, 2),
(550, '1_4', '09:00:00', '09:30:00', 68, 1),
(551, '2_4', '09:00:00', '09:30:00', 68, 2),
(552, '5_4', '09:00:00', '09:30:00', 68, 5),
(553, '1_5', '09:30:00', '10:00:00', 68, 1),
(554, '2_5', '09:30:00', '10:00:00', 68, 2),
(555, '5_5', '09:30:00', '10:00:00', 68, 5),
(556, '1_6', '10:00:00', '10:30:00', 68, 1),
(557, '5_6', '10:00:00', '10:30:00', 68, 5),
(558, '6_6', '10:00:00', '10:30:00', 68, 6),
(559, '1_7', '10:30:00', '11:00:00', 68, 1),
(560, '5_7', '10:30:00', '11:00:00', 68, 5),
(561, '6_7', '10:30:00', '11:00:00', 68, 6),
(562, '1_8', '11:00:00', '11:30:00', 68, 1),
(563, '3_8', '11:00:00', '11:30:00', 68, 3),
(564, '5_8', '11:00:00', '11:30:00', 68, 5),
(565, '6_8', '11:00:00', '11:30:00', 68, 6),
(566, '1_9', '11:30:00', '12:00:00', 68, 1),
(567, '3_9', '11:30:00', '12:00:00', 68, 3),
(568, '5_9', '11:30:00', '12:00:00', 68, 5),
(569, '6_9', '11:30:00', '12:00:00', 68, 6),
(570, '1_10', '12:00:00', '12:30:00', 68, 1),
(571, '3_10', '12:00:00', '12:30:00', 68, 3),
(572, '5_10', '12:00:00', '12:30:00', 68, 5),
(573, '6_10', '12:00:00', '12:30:00', 68, 6),
(574, '2_11', '12:30:00', '01:00:00', 68, 2),
(575, '3_11', '12:30:00', '01:00:00', 68, 3),
(576, '6_11', '12:30:00', '01:00:00', 68, 6),
(577, '2_12', '01:00:00', '01:30:00', 68, 2),
(578, '3_12', '01:00:00', '01:30:00', 68, 3),
(579, '3_13', '01:30:00', '02:00:00', 68, 3),
(580, '3_14', '02:00:00', '02:30:00', 68, 3),
(581, '5_14', '02:00:00', '02:30:00', 68, 5),
(582, '3_15', '02:30:00', '03:00:00', 68, 3),
(583, '5_15', '02:30:00', '03:00:00', 68, 5),
(584, '3_16', '03:00:00', '03:30:00', 68, 3),
(585, '5_16', '03:00:00', '03:30:00', 68, 5),
(586, '3_17', '03:30:00', '04:00:00', 68, 3),
(587, '5_17', '03:30:00', '04:00:00', 68, 5),
(588, '1_18', '04:00:00', '04:30:00', 68, 1),
(589, '5_18', '04:00:00', '04:30:00', 68, 5),
(590, '1_19', '04:30:00', '05:00:00', 68, 1),
(591, '4_19', '04:30:00', '05:00:00', 68, 4),
(592, '1_20', '05:00:00', '05:30:00', 68, 1),
(593, '4_20', '05:00:00', '05:30:00', 68, 4),
(594, '1_21', '05:30:00', '06:00:00', 68, 1),
(595, '4_21', '05:30:00', '06:00:00', 68, 4),
(596, '1_22', '06:00:00', '06:30:00', 68, 1),
(597, '4_22', '06:00:00', '06:30:00', 68, 4),
(598, '1_23', '06:30:00', '07:00:00', 68, 1),
(599, '4_23', '06:30:00', '07:00:00', 68, 4),
(600, '4_24', '07:00:00', '07:30:00', 68, 4),
(601, '6_0', '07:00:00', '07:30:00', 69, 6),
(602, '6_1', '07:30:00', '08:00:00', 69, 6),
(603, '1_2', '08:00:00', '08:30:00', 69, 1),
(604, '1_3', '08:30:00', '09:00:00', 69, 1),
(605, '1_4', '09:00:00', '09:30:00', 69, 1),
(606, '1_5', '09:30:00', '10:00:00', 69, 1),
(607, '6_5', '09:30:00', '10:00:00', 69, 6),
(608, '6_6', '10:00:00', '10:30:00', 69, 6),
(609, '2_9', '11:30:00', '12:00:00', 69, 2),
(610, '2_10', '12:00:00', '12:30:00', 69, 2),
(611, '6_10', '12:00:00', '12:30:00', 69, 6),
(612, '2_11', '12:30:00', '01:00:00', 69, 2),
(613, '6_11', '12:30:00', '01:00:00', 69, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel`
--

CREATE TABLE IF NOT EXISTS `nivel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `nivel`
--

INSERT INTO `nivel` (`id`, `nombre`) VALUES
(1, 'Primaria'),
(2, 'Secundaria'),
(3, 'Preuniversitario'),
(4, 'Universidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_estudiantes`
--

CREATE TABLE IF NOT EXISTS `pagos_estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante_grupo` int(11) NOT NULL,
  `numero_pago` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `monto` int(11) NOT NULL,
  `estado` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Volcado de datos para la tabla `pagos_estudiantes`
--

INSERT INTO `pagos_estudiantes` (`id`, `id_estudiante_grupo`, `numero_pago`, `fecha`, `monto`, `estado`) VALUES
(49, 6, 1, '0000-00-00', 50, 'C'),
(50, 6, 2, '0000-00-00', 50, 'C'),
(51, 6, 3, '0000-00-00', 50, 'C'),
(52, 7, 1, '0000-00-00', 0, 'A'),
(53, 7, 2, '0000-00-00', 0, 'A'),
(54, 7, 3, '0000-00-00', 200, 'B'),
(55, 1, 1, '2024-11-05', 200, 'B'),
(56, 1, 2, '2024-11-05', 200, 'B'),
(57, 1, 3, '2024-11-05', 200, 'B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE IF NOT EXISTS `paquetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_estudiante` int(11) NOT NULL,
  `num_clases` int(11) NOT NULL,
  `num_clases_reg` double NOT NULL,
  `costo` int(11) NOT NULL,
  `observaciones` varchar(50) NOT NULL,
  `estado_pago` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id`, `id_estudiante`, `num_clases`, `num_clases_reg`, `costo`, `observaciones`, `estado_pago`) VALUES
(1, 1, 12, 3, 120, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE IF NOT EXISTS `profesores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` int(10) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(40) NOT NULL,
  `fNacimiento` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `DNI`, `nombres`, `apellidos`, `telefono`, `correo`, `fNacimiento`) VALUES
(1, 45678907, 'Luis Carlos', 'Ramos Gutierrez', '987654782', 'correo@gmail.com', '2023-04-30'),
(68, 87654321, 'Nicolas', 'Gutierrrez Cusi', '9543665432', 'correo@gmail.com', '2023-05-29'),
(69, 76897656, 'paul raul', 'rivas', '9543665432', 'correo@gmail.com', '2023-05-29'),
(71, 47567893, 'Fernando', 'Vivas Aurestegui', '94703426', 'fernandovivas@gmail.com', '1998-12-24'),
(72, 678987653, 'Fernando', 'Mercado Diaz', '94703426', 'correo@gmail.com', '2024-12-19'),
(75, 45654567, 'Baltazar', 'Alvarado Quispe', '94703426', 'correo@gmail.com', '2024-12-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores_cursos`
--

CREATE TABLE IF NOT EXISTS `profesores_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProfesor` int(11) NOT NULL,
  `idCurso` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Volcado de datos para la tabla `profesores_cursos`
--

INSERT INTO `profesores_cursos` (`id`, `idProfesor`, `idCurso`) VALUES
(16, 1, 29),
(17, 36, 29),
(18, 37, 29),
(19, 67, 29),
(20, 70, 29),
(21, 70, 6),
(24, 1, 1),
(25, 70, 1),
(26, 1, 6),
(33, 1, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
