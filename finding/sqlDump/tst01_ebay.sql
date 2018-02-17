CREATE TABLE ebay
(
  id           BIGINT UNSIGNED NOT NULL
    PRIMARY KEY,
  datetimeleft VARCHAR(24)     NULL,
  url          VARCHAR(1024)   NULL,
  pid          INT             NULL,
  timestamp    TIMESTAMP       NULL,
  ebaydata     LONGTEXT        NULL,
  CONSTRAINT ebay_id_uindex
  UNIQUE (id)
)
  ENGINE = InnoDB;


