CREATE TABLE tst01.ebay
(
    id int(11) PRIMARY KEY NOT NULL,
    datetimeleft datetime,
    url varchar(1024),
    pid int(11),
    timestamp timestamp
);
CREATE UNIQUE INDEX ebay_id_uindex ON tst01.ebay (id);