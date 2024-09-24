DROP TABLE `localidades`
CREATE TABLE IF NOT EXISTS `localidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `municipio_id` int(11) NOT NULL,
  `rota` VARCHAR(255),
  `anciaes` CHAR(2) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cultos`;
CREATE TABLE IF NOT EXISTS `cultos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `dia` varchar(40) NOT NULL,
  `hora` varchar(5) NOT NULL,
  `tipo` char(1) NOT NULL,
  `rota` VARCHAR(255),
  `anciaes` CHAR(2) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

---

DROP TABLE `ensaio`
CREATE TABLE IF NOT EXISTS `ensaio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `horario_id` varchar(5) NOT NULL,
  `dia_semana` CHAR(1) NOT NULL DEFAULT '1',
  `semana` char(1) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE `regional`
CREATE TABLE IF NOT EXISTS `regional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `data` date NOT NULL,
  `horario_id` INT NOT NULL,
  `dia_semana` CHAR(1) NOT NULL DEFAULT '1',
  `regionais` VARCHAR(255),
  `avaliacao` CHAR(1),
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE `batismo`
CREATE TABLE IF NOT EXISTS `batismo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `data` date NOT NULL,
  `horario_id` INT NOT NULL,
  `dia_semana` CHAR(1) NOT NULL DEFAULT '1',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


DROP TABLE `horarios`
CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hora` varchar(5) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

INSERT INTO `horarios` (`id`,`hora`,`created`,`modified`) VALUES (1,'10:00',null,null);

DROP TABLE `municipios`
CREATE TABLE IF NOT EXISTS `municipios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `favorito` char(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE `reuniao`
CREATE TABLE IF NOT EXISTS `reuniao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `data` date NOT NULL,
  `dia_semana` CHAR(1) NOT NULL DEFAULT '1',
  `horario_id` INT NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

DROP TABLE `ordenacao`
CREATE TABLE IF NOT EXISTS `ordenacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localidade_id` INT NOT NULL,
  `dia_semana` CHAR(1) NOT NULL DEFAULT '1',
  `data` date NOT NULL,
  `horario_id` INT NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `nome`, `username`, `email`, `password`, `status`, `created`, `modified`) VALUES (1, 'sidnei', 'sidnei', 'sialkas@gmail.com', '$2y$10$OlQdL/TfLoCAZGqV9hI0Geu3/MfaDmhTnl13VqqFRfv9biSNgdN86', 1, NULL, NULL);

-- --------------------------------------------------------
--
-- Estrutura da tabela `municipio`
--

DROP TABLE IF EXISTS `municipios`;
CREATE TABLE IF NOT EXISTS `municipios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `favorito` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

INSERT INTO `municipios` (`id`, `nome`, `favorito`, `created`, `modified`) VALUES (1, 'guarulhos', '1', NULL, NULL);
INSERT INTO `municipios` (`id`, `nome`, `favorito`, `created`, `modified`) VALUES (2, 'itaquaquecetuba', '0', NULL, NULL);

