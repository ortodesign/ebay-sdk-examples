CREATE TABLE tst01.ebay_products
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ebay_id int(11),
    product_id int(11)
);
CREATE UNIQUE INDEX ebay_products_id_uindex ON tst01.ebay_products (id);