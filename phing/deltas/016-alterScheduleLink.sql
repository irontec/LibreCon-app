--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Schedule`
--
-- ALTER TABLE `Schedule`
--  ADD PRIMARY KEY (`id`), ADD KEY `link1Type` (`link1Type`), ADD KEY `link2Type` (`link2Type`), ADD KEY `link3Type` (`link3Type`), ADD KEY `link4Type` (`link4Type`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Schedule`
--
-- ALTER TABLE `Schedule`
-- MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Schedule`
--
-- ALTER TABLE `Schedule`
-- ADD CONSTRAINT `Schedule_ibfk_1` FOREIGN KEY (`link1Type`) REFERENCES `Links` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
-- ADD CONSTRAINT `Schedule_ibfk_2` FOREIGN KEY (`link2Type`) REFERENCES `Links` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
-- ADD CONSTRAINT `Schedule_ibfk_3` FOREIGN KEY (`link3Type`) REFERENCES `Links` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
-- ADD CONSTRAINT `Schedule_ibfk_4` FOREIGN KEY (`link4Type`) REFERENCES `Links` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

-- ALTER TABLE `RelScheduleSpeaker` ADD FOREIGN KEY (`idSchedule`) REFERENCES `Schedule`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;
-- ALTER TABLE `RelTagScheduele` ADD FOREIGN KEY (`idTag`) REFERENCES `Tags`(`id`) ON DELETE SET NULL ON UPDATE SET NULL; 
-- ALTER TABLE `RelTagScheduele` ADD FOREIGN KEY (`idScheduele`) REFERENCES `Schedule`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;

