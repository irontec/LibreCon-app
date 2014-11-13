--
-- Estructura de tabla para la tabla `KlearRoles`
--

CREATE TABLE IF NOT EXISTS `KlearRoles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='[entity]';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KlearRolesSections`
--

CREATE TABLE IF NOT EXISTS `KlearRolesSections` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `klearRoleId` mediumint(8) unsigned NOT NULL,
  `klearSectionId` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `klearRoleId` (`klearRoleId`),
  KEY `klearSectionId` (`klearSectionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='[entity]';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KlearSections`
--

CREATE TABLE IF NOT EXISTS `KlearSections` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='[entity]';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `KlearUsersRoles`
--

CREATE TABLE IF NOT EXISTS `KlearUsersRoles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `klearUserId` mediumint(8) unsigned NOT NULL,
  `klearRoleId` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `klearUserId` (`klearUserId`),
  KEY `klearRoleId` (`klearRoleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='[entity]';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `KlearRolesSections`
--
ALTER TABLE `KlearRolesSections`
  ADD CONSTRAINT `KlearRolesSections_ibfk_1` FOREIGN KEY (`klearRoleId`) REFERENCES `KlearRoles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `KlearRolesSections_ibfk_2` FOREIGN KEY (`klearSectionId`) REFERENCES `KlearSections` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `KlearUsersRoles`
--
ALTER TABLE `KlearUsersRoles`
  ADD CONSTRAINT `KlearUsersRoles_ibfk_1` FOREIGN KEY (`klearUserId`) REFERENCES `KlearUsers` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `KlearUsersRoles_ibfk_2` FOREIGN KEY (`klearRoleId`) REFERENCES `KlearRoles` (`id`) ON DELETE CASCADE;
  
 
  
  
  
  
INSERT INTO `KlearRoles` (`id`, `name`, `description`, `identifier`) VALUES
(1, 'Administrador', 'Usuario con permisos de administrador', 'admin'),
(2, 'Usuario', 'Con roles para un usuario normal con restricciones.', 'user');


INSERT INTO `KlearUsersRoles` (`id`, `klearUserId`, `klearRoleId`) VALUES
(1, 1, 1);