
--
-- Estructura de tabla para la tabla `Assistants`
--

CREATE TABLE IF NOT EXISTS `Assistants` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `cellPhone` varchar(15) NOT NULL,
  `position` varchar(100) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `codePostal` varchar(6) NOT NULL,
  `uuid` varchar(500) NOT NULL,
  `code` varchar(5) NOT NULL,
  `secretHash` varchar(50) NOT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Expositor`
--

CREATE TABLE IF NOT EXISTS `Expositor` (
`id` mediumint(8) unsigned NOT NULL,
  `logo` varchar(255) NOT NULL COMMENT '[file]',
  `companyName` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL COMMENT '[ml]',
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Meeting`
--

CREATE TABLE IF NOT EXISTS `Meeting` (
`id` mediumint(8) unsigned NOT NULL,
  `sender` mediumint(8) unsigned DEFAULT NULL,
  `receiver` mediumint(8) unsigned DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT '[enum:pending|canceled|accepted]',
  `emailShare` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[enum:0|1]',
  `cellphoneShare` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[enum:0|1]',
  `atRightNow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[enum:0|1]',
  `atRightNowWhen` datetime DEFAULT NULL,
  `atHalfHour` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[enum:0|1]',
  `atHalfHourWhen` datetime DEFAULT NULL,
  `atOneHour` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[enum:0|1]',
  `atOneHourWhen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RelTagScheduele`
--

CREATE TABLE IF NOT EXISTS `RelTagScheduele` (
`id` mediumint(8) unsigned NOT NULL,
  `idTag` mediumint(8) unsigned DEFAULT NULL,
  `idScheduele` mediumint(8) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RelTagSpeaker`
--

CREATE TABLE IF NOT EXISTS `RelTagSpeaker` (
`id` mediumint(8) unsigned NOT NULL,
  `idTag` mediumint(8) unsigned DEFAULT NULL,
  `idSpeaker` mediumint(8) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Schedule`
--

CREATE TABLE IF NOT EXISTS `Schedule` (
`id` mediumint(8) unsigned NOT NULL,
  `targetDate` tinyint(4) NOT NULL COMMENT '[enum:1|2]',
  `name` varchar(255) NOT NULL COMMENT '[ml]',
  `description` mediumtext COMMENT '[ml][html]',
  `picture` varchar(255) DEFAULT NULL COMMENT '[file]',
  `startDateTime` datetime NOT NULL,
  `finishDateTime` datetime NOT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Speaker`
--

CREATE TABLE IF NOT EXISTS `Speaker` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL COMMENT '[file]',
  `description` mediumtext COMMENT '[ml]',
  `company` varchar(255) DEFAULT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sponsors`
--

CREATE TABLE IF NOT EXISTS `Sponsors` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '[ml]',
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tags`
--

CREATE TABLE IF NOT EXISTS `Tags` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '[ml]',
  `color` varchar(100) DEFAULT NULL,
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Txoko`
--

CREATE TABLE IF NOT EXISTS `Txoko` (
`id` mediumint(8) unsigned NOT NULL,
  `picture` varchar(255) DEFAULT NULL COMMENT '[file]',
  `title` varchar(255) NOT NULL COMMENT '[ml]',
  `text` mediumtext COMMENT '[ml]',
  `lastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Assistants`
--
ALTER TABLE `Assistants`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Expositor`
--
ALTER TABLE `Expositor`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Meeting`
--
ALTER TABLE `Meeting`
 ADD PRIMARY KEY (`id`), ADD KEY `sender` (`sender`), ADD KEY `receiver` (`receiver`);

--
-- Indices de la tabla `RelTagScheduele`
--
ALTER TABLE `RelTagScheduele`
 ADD PRIMARY KEY (`id`), ADD KEY `idTag` (`idTag`), ADD KEY `idScheduele` (`idScheduele`);

--
-- Indices de la tabla `RelTagSpeaker`
--
ALTER TABLE `RelTagSpeaker`
 ADD PRIMARY KEY (`id`), ADD KEY `idTag` (`idTag`), ADD KEY `idSpeaker` (`idSpeaker`);

--
-- Indices de la tabla `Schedule`
--
ALTER TABLE `Schedule`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Speaker`
--
ALTER TABLE `Speaker`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Sponsors`
--
ALTER TABLE `Sponsors`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Tags`
--
ALTER TABLE `Tags`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Txoko`
--
ALTER TABLE `Txoko`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Assistants`
--
ALTER TABLE `Assistants`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Expositor`
--
ALTER TABLE `Expositor`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Meeting`
--
ALTER TABLE `Meeting`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `RelTagScheduele`
--
ALTER TABLE `RelTagScheduele`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `RelTagSpeaker`
--
ALTER TABLE `RelTagSpeaker`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Schedule`
--
ALTER TABLE `Schedule`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Speaker`
--
ALTER TABLE `Speaker`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Sponsors`
--
ALTER TABLE `Sponsors`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Tags`
--
ALTER TABLE `Tags`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Txoko`
--
ALTER TABLE `Txoko`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Meeting`
--
ALTER TABLE `Meeting`
ADD CONSTRAINT `Meeting_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `Assistants` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
ADD CONSTRAINT `Meeting_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `Assistants` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `RelTagScheduele`
--
ALTER TABLE `RelTagScheduele`
ADD CONSTRAINT `RelTagScheduele_ibfk_2` FOREIGN KEY (`idScheduele`) REFERENCES `Schedule` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
ADD CONSTRAINT `RelTagScheduele_ibfk_1` FOREIGN KEY (`idTag`) REFERENCES `Tags` (`Id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `RelTagSpeaker`
--
ALTER TABLE `RelTagSpeaker`
ADD CONSTRAINT `RelTagSpeaker_ibfk_2` FOREIGN KEY (`idSpeaker`) REFERENCES `Speaker` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
ADD CONSTRAINT `RelTagSpeaker_ibfk_1` FOREIGN KEY (`idTag`) REFERENCES `Tags` (`Id`) ON DELETE SET NULL ON UPDATE SET NULL;
