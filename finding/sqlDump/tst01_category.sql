CREATE TABLE tst01.category
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pid int(11),
    name varchar(512),
    citi_category_id int(11)
);
INSERT INTO tst01.category (id, pid, name, citi_category_id) VALUES (1, null, 'Мобильные телефоны', 214);