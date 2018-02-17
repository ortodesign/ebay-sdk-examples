CREATE TABLE Product
(
  id                      INT AUTO_INCREMENT
    PRIMARY KEY,
  our_name                VARCHAR(1024) NULL,
  min_lefttime            VARCHAR(50)   NULL,
  citilinkURL             VARCHAR(500)  NULL,
  synonyms                TEXT          NULL,
  title                   VARCHAR(500)  NULL,
  citilinkPrice           FLOAT         NULL,
  picture_url             VARCHAR(1024) NULL,
  last_all_ebay_count     INT           NULL,
  last_approve_ebay_count INT           NULL,
  min_procent             INT           NULL,
  max_procent             INT           NULL,
  ebay_ids                TEXT          NULL,
  citilinkID              INT           NULL,
  ebay_price              VARCHAR(10)   NULL,
  citilink_data           LONGTEXT      NULL,
  categoryID              INT           NULL
)
  ENGINE = InnoDB;


