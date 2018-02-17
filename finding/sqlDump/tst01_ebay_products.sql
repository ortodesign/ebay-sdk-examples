CREATE TABLE ebay_products
(
  id           INT AUTO_INCREMENT
    PRIMARY KEY,
  ebay_id      BIGINT UNSIGNED NULL,
  product_id   INT             NULL,
  datetimeleft VARCHAR(24)     NULL,
  CONSTRAINT ebay_products_id_uindex
  UNIQUE (id)
)
  ENGINE = InnoDB;

CREATE INDEX ebay_id
  ON ebay_products (ebay_id);

CREATE INDEX product_id
  ON ebay_products (product_id);


