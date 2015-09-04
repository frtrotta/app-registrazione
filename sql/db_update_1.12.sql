USE `aisla_triathlon`;

ALTER TABLE verifica_pagamento
  RENAME TO conferma_pagamento;
  
DELETE FROM versione;
  
INSERT INTO `versione_database` (`versione`) VALUES
('1.12');