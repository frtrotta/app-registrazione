USE `aisla_triathlon`;

ALTER TABLE `adesione_personale`
	CHANGE `indirizzoStato` `indirizzoPaese` varchar(50) NOT NULL;
	
ALTER TABLE `adesione_personale`	
	DROP indirizzoLinea1,
	DROP indirizzoLinea2,
	DROP indirizzoProvincia;

ALTER TABLE `ordine`
	CHANGE `clienteIndirizzoStato` `clienteIndirizzoPaese` varchar(50) NOT NULL;
	
ALTER TABLE `ordine`	
	DROP clienteIndirizzoLinea1,
	DROP clienteIndirizzoLinea2,
	DROP clienteIndirizzoProvincia;
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.15');