DROP TABLE IF EXISTS `folders`;

CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(30) NOT NULL,
  `label` varchar(65) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`folder_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=UTF8 AUTO_INCREMENT=1 ;

INSERT INTO `folders` (`id`, `folder_name`, `label`) VALUES
(1, 'inbox', 'INBOX'),
(2, 'spam', '[Gmail]/Spam'),
(3, 'trash', '[Gmail]/Trash');