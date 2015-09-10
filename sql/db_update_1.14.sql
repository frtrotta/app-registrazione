USE `aisla_triathlon`;

ALTER TABLE `utente`
	DROP username;
	
UPDATE `utente`
	SET email = '[da richiedere]'
	WHERE nome = '' AND cognome = '';
	
UPDATE `utente`
	SET email = '[da richiedere 1]'
	WHERE nome = 'Franco' AND cognome = 'Rastelli';

ALTER TABLE `utente`
  ADD UNIQUE KEY `email` (`email`);
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.14');