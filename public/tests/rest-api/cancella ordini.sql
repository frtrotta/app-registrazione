create temporary table richieste_tesseramento_da_cancellare (
	id bigint(20) not null
);
create temporary table tesseramenti_da_cancellare (
	id bigint(20) not null
);
create temporary table iscrizioni_da_cancellare (
	id bigint(20) not null
);
create temporary table adesioni_personali_da_cancellare (
	id bigint(20) not null
);
create temporary table inviti_da_cancellare (
	id bigint(20) not null
);
create temporary table squadre_da_cancellare (
	id bigint(20) not null
);
-- ----------------------------------------------------------------------------------------------------

insert into richieste_tesseramento_da_cancellare select id from richiesta_tesseramento where eseguitaIl > '2015/09/17';
insert into iscrizioni_da_cancellare select id from iscrizione where eseguitaIl > '2015/09/17'; 

insert into tesseramenti_da_cancellare select id from tesseramento where idRichiestaTesseramento in (select id from richieste_tesseramento_da_cancellare);
insert into inviti_da_cancellare select id from invito where idIscrizione in (select id from iscrizioni_da_cancellare);

insert into adesioni_personali_da_cancellare select idAdesionePersonale from iscrizione__adesione_personale where idIscrizione in (select id from iscrizioni_da_cancellare);
insert into squadre_da_cancellare select idSquadra from iscrizione__squadra where idIscrizione in (select id from iscrizioni_da_cancellare);

-- -----------------------------------------------------------------------------------------------------

delete from tesseramento where id in (select id from tesseramenti_da_cancellare);
delete from richiesta_tesseramento where id in (select id from richieste_tesseramento_da_cancellare);
delete from iscrizione__adesione_personale where idIscrizione in (select id from iscrizioni_da_cancellare);
delete from iscrizione__squadra where idIscrizione in (select id from iscrizioni_da_cancellare);
delete from adesione_personale__squadra where idAdesionePersonale in (select id from adesioni_personali_da_cancellare);
delete from adesione_personale__invito where idInvito in (select id from inviti_da_cancellare);
delete from invito where id in (select id from inviti_da_cancellare);
delete from adesione_personale where id in (select id from adesioni_personali_da_cancellare);
delete from squadra where id in (select id from squadre_da_cancellare);
delete from iscrizione where id in (select id from iscrizioni_da_cancellare);

delete from ordine where ricevutoIl > '2015/09/17';