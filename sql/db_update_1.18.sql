USE `aisla_triathlon`;

ALTER TABLE `adesione_personale__invito`
  DROP FOREIGN KEY `adesione_personale__invito_ibfk_2`;

ALTER TABLE `adesione_personale__invito`
	DROP PRIMARY KEY,
	DROP INDEX `codice_invito`;
	
ALTER TABLE `invito`
	DROP PRIMARY KEY;
	
ALTER TABLE `invito`
	ADD `id` bigint(20) unsigned NOT NULL;

ALTER TABLE `invito`
	ADD PRIMARY KEY (`id`),
	ADD UNIQUE KEY `codice` (`codice`);
	
ALTER TABLE `invito`
	CHANGE `id` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
	
ALTER TABLE `adesione_personale__invito`
	DROP `codiceInvito`,
	ADD `idInvito` bigint(20) unsigned NOT NULL;
	
ALTER TABLE `adesione_personale__invito`
	ADD PRIMARY KEY (`idAdesionePersonale`,`idInvito`),
	ADD UNIQUE KEY `id_invito` (`idInvito`);

ALTER TABLE `adesione_personale__invito`
	ADD CONSTRAINT `adesione_personale__invito_ibfk_2` FOREIGN KEY (`idInvito`) REFERENCES `invito` (`id`) ON UPDATE CASCADE;
  
DELETE FROM `versione_database`;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.18');