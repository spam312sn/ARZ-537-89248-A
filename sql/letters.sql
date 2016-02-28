DROP TABLE IF EXISTS `letters`;
CREATE TABLE IF NOT EXISTS `letters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` int(11) NOT NULL,
  `letter_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `to_address` varchar(65) COLLATE utf8_bin NOT NULL,
  `to` varchar(65) COLLATE utf8_bin NOT NULL,
  `from_address` varchar(65) COLLATE utf8_bin NOT NULL,
  `from` varchar(65) COLLATE utf8_bin NOT NULL,
  `subject` text COLLATE utf8_bin,
  `body` text COLLATE utf8_bin,
  `seen` tinyint(1) NOT NULL,
  `dmarc` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `letter_id` (`letter_id`),
  KEY `folder` (`folder`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;