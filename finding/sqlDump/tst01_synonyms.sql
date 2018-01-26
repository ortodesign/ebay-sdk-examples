CREATE TABLE tst01.synonyms
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    synonym varchar(20000),
    ProductID int(11)
);
INSERT INTO tst01.synonyms (id, synonym, ProductID) VALUES (1, 'SM-G935FD', 1);
INSERT INTO tst01.synonyms (id, synonym, ProductID) VALUES (2, 'SM-G935FD', 2);