CREATE TABLE IF NOT EXISTS `RelScheduleSpeaker` (
`id` mediumint(8) unsigned NOT NULL,
  `idScheduele` mediumint(8) unsigned DEFAULT NULL,
  `idSpeaker` mediumint(8) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `RelScheduleSpeaker`
--
ALTER TABLE `RelScheduleSpeaker`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `RelScheduleSpeaker`
--
ALTER TABLE `RelScheduleSpeaker`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `RelScheduleSpeaker` ADD INDEX(`idScheduele`);
ALTER TABLE `RelScheduleSpeaker` ADD INDEX(`idSpeaker`);

ALTER TABLE `RelScheduleSpeaker` ADD FOREIGN KEY (`idScheduele`) REFERENCES `Schedule`(`id`) ON DELETE SET NULL ON UPDATE SET NULL; 
ALTER TABLE `RelScheduleSpeaker` ADD FOREIGN KEY (`idSpeaker`) REFERENCES `Speaker`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;