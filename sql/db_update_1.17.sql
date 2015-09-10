USE `aisla_triathlon`;

ALTER TABLE `iscrizione`
	CHANGE `motto` `motto` varchar(50)  NULL;
	
UPDATE `iscrizione`
	SET `motto` = null
	WHERE `motto` = '';
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.17');