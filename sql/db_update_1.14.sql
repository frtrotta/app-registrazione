USE `aisla_triathlon`;

ALTER TABLE `utente`
	DROP username;

ALTER TABLE `utente`
  ADD UNIQUE KEY `email` (`email`);
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.14');