CREATE TABLE tst01.ebay
(
    id bigint(20) unsigned PRIMARY KEY NOT NULL,
    datetimeleft datetime,
    url varchar(1024),
    pid int(11),
    timestamp timestamp
);
CREATE UNIQUE INDEX ebay_id_uindex ON tst01.ebay (id);
INSERT INTO tst01.ebay (id, datetimeleft, url, pid, timestamp) VALUES (152892106340, '2018-02-10 14:08:45', 'http://ex.com', 2, '2018-02-09 15:10:15');
INSERT INTO tst01.ebay (id, datetimeleft, url, pid, timestamp) VALUES (292434956876, '2018-02-10 00:51:06', 'http://ex.com/2', 5, null);
INSERT INTO tst01.ebay (id, datetimeleft, url, pid, timestamp) VALUES (332541822869, '2018-02-09 15:08:45', 'http://ex.com', 2, '2018-02-09 15:10:15');