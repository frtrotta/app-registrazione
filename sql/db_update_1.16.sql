USE `aisla_triathlon`;

ALTER TABLE `gara`
	add `iscrizioneModificabileFinoAl` DATETIME NULL;
	
UPDATE `gara` SET
	`iscrizioneModificabileFinoAl` = '2014-06-29 00:00:00'
	WHERE 1;

ALTER TABLE `gara`
	CHANGE `iscrizioneModificabileFinoAl` `iscrizioneModificabileFinoAl` DATETIME NOT NULL;
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.16');