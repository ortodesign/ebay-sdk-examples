# 	SELECT ebay_products.*, Product.*, `category`.name, `ebay`.datetimeleft
# 	FROM `ebay_products`, `Product`, `category`, `ebay`
# # 	WHERE (Product.categoryID = category.citi_category_id) AND
# #         (ebay_id = ebay.id) AND (product_id = Product.id)
# 	WHERE  (ebay_id = ebay.id) AND (product_id = Product.id)
# #     	  (Product.id = ebay.pid)
#     ORDER BY product_id, ebay.datetimeleft DESC
# 	LIMIT 10
#

#
# (SELECT ebay_products.*, Product.*, `category`.name, `ebay`.datetimeleft
#  FROM `ebay_products`, `Product`, `category`, `ebay`
#  WHERE (ebay_id = ebay.id) AND (product_id = Product.id))
# UNION
# (SELECT ebay_products.*, Product.*, `category`.name, `ebay`.datetimeleft
#  FROM `ebay_products`, `Product`, `category`, `ebay`
#  WHERE (ebay_id = ebay.id) AND (product_id = Product.id)) ;


# Select * from Product,ebay_products
# WHERE  (Product.id = ebay_products.product_id)


# SELECT pe.id,title,datetimeleft
# FROM ebay,Product as pe
# # WHERE Product.id IN
# 	INNER JOIN
# 			(
# 	SELECT pid
# 	FROM ebay
# # 	WHERE pid = Product.id
# 	ORDER BY ebay.datetimeleft ASC
# 	LIMIT 1
# ) AS eb
# on pe.id= eb.pid


# SELECT
#   Product.id,
#   Product.title,
#   datetimeleft
#   pid
# FROM Product,ebay
# WHERE Product.id IN (SELECT pid
#                      FROM (
#                             SELECT *
#                             FROM ebay
#                              GROUP BY pid
# #                             WHERE pid = Product.id
# #                             HAVING pid = Product.id
# #                             HAVING count(*)>1
#                             ORDER BY datetimeleft DESC
# #                         LIMIT 4
# #                             LIMIT count(Product)
#                           ) temp_tab
# )
# # HAVING count(*)
# # GROUP BY Product.id
# ORDER BY datetimeleft
# ORDER BY Product.id
# ORDER BY RAND()
# LIMIT 1


# select * FROM
# (select * FROM
# (select Product.*, ebay.datetimeleft, product_id as ppid, ebay_id as eeid  from ebay_products
# inner join Product ON product_id = Product.id
# inner join ebay ON  ebay.id = ebay_id
# ) tt2
# ORDER BY datetimeleft) tt3
# GROUP BY ppid

# SELECT * from
# (select Product.*, ebay.datetimeleft, ebay_products.product_id as ppid, ebay_products.ebay_id as eeid  from Product
# inner join ebay_products ON product_id = Product.id
#   inner join ebay ON  ebay.id = ebay_id
#   GROUP BY product_id
# #  ORDER BY datetimeleft,pid
# ) as t1
# GROUP BY ppid

# SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

# select * from
#   (
#     SELECT *
#     FROM ebay_products,Product,ebay
#     WHERE (ebay_products.product_id = Product.id) and (ebay_products.ebay_id = ebay.id)
#   GROUP BY product_id
#     ORDER BY ebay_products.datetimeleft

#   ) as t1


# ORDER BY `ebay`.datetimeleft LIMIT 1

# 			 (SELECT `ebay`.datetimeleft FROM ebay WHERE(ebay_id = ebay.id) AND (product_id = Product.id) ORDER BY `ebay`.datetimeleft LIMIT 1) as 'first')
# 			 (SELECT column FROM table WHERE [condition] ORDER BY column DESC LIMIT 1) as 'last'
#  FROM `ebay_products`, `Product`, `ebay`
#  WHERE (ebay_id = ebay.id) AND (product_id = Product.id)


SELECT `COLUMN_NAME`
FROM `INFORMATION_SCHEMA`.`COLUMNS`
WHERE `TABLE_SCHEMA` = 'tst01'
      AND `TABLE_NAME` = 'parsed_citi';


DELETE t1 FROM ebay_products t1
  INNER JOIN
  ebay_products t2
WHERE
  (t1.ebay_id = t2.ebay_id) AND (t1.id < t2.id);


DELETE t1 FROM parsed_citi t1
  INNER JOIN
  parsed_citi t2
WHERE
  (t1.pre_syn = t2.pre_syn) AND (t1.id < t2.id);


DELETE t1 FROM ebay_products t1
  INNER JOIN
  ebay_products t2
WHERE
  (t1.product_id = t2.product_id) AND (t1.ebay_id = t2.ebay_id) AND (t1.id < t2.id);


SELECT *
FROM ebay_products, parsed_citi, ebay
WHERE (ebay_products.product_id = parsed_citi.id) AND (ebay_products.ebay_id = ebay.id)
      AND (ebay_products.datetimeleft > "2018-03-15T11:00:08+0000") AND
      (ebay_products.datetimeleft < "2018-03-15T20:44:26+0000")
# GROUP BY ebay_id
ORDER BY ebay_products.datetimeleft ASC;


DELETE FROM parsed_citi
WHERE id > 0

SELECT *
FROM synon t1
  INNER JOIN
  synon t2
WHERE
  (t1.pid = t2.pid) AND (t1.id < t2.id)
GROUP BY t2.syn;
# AND (t1.syn = t2.syn)
# AND (t1.id < t2.id);

SELECT *
FROM
  (SELECT *
   FROM synon t1
   ORDER BY pid) t2
# GROUP BY syn) t2
# GROUP BY pid
# ORDER BY pid

SELECT
  t2.pid,
  COUNT(*) count
FROM
  # (SELECT * from
  (SELECT *
   FROM synon t1
   GROUP BY syn) t2
# GROUP BY pid
# ORDER BY t2.pid) t3
HAVING
  COUNT(*) > 1

SELECT *
FROM synon
GROUP BY pid
HAVING COUNT(pid) > 2
ORDER BY pid

## !!!!
SELECT *
FROM synon
WHERE pid IN (
  SELECT pid
  FROM synon
  GROUP BY pid
  HAVING count(*) > 1);

SELECT
  synon.pid      AS spid,
  parsed_citi.id AS psid,
  syn,
  shortName
FROM synon, parsed_citi
WHERE (pid IN (
  SELECT pid
  FROM synon
  GROUP BY pid
  HAVING count(pid) = 1)
      ) AND (parsed_citi.id = synon.pid);


SELECT
  syn,
  pid,
  #   syn AS sy,
  COUNT(*) count
FROM
  synon
GROUP BY
  #   id,
  pid
#   sy
HAVING
  COUNT(*) > 2
ORDER BY pid;

DELETE t1 FROM ebay_products t1
  INNER JOIN
  ebay_products t2
WHERE
  ((t1.product_id = t2.product_id) OR (t1.ebay_id = t2.ebay_id)) AND (t1.id < t2.id);

CREATE TABLE citi_cats AS
  SELECT
    categoryName,
    categoryId
  FROM parsed_citi
  GROUP BY categoryId;

SELECT *
FROM (SELECT categoryName AS cn,)


SELECT *
FROM ebay_products, parsed_citi, ebay
WHERE (ebay_products.product_id = parsed_citi.id) AND (ebay_products.ebay_id = ebay.id)

#узнать кол-во дублей
SELECT
  product_id,
  ebay_id,
  #   syn AS sy,
  COUNT(*) count
FROM
  ebay_products
GROUP BY
  #   id,
  product_id
#   sy
HAVING
  COUNT(*) > 0
ORDER BY product_id;

#выбрать дубли по id parsed_citi (дубляжи по синонимам)
SELECT *
FROM oops
WHERE id IN (SELECT product_id
             FROM (SELECT
                     product_id,
                     ebay_id
                   #   syn AS sy,
                   #                COUNT(*) count
                   FROM
                     ebay_products
                   GROUP BY
                     #   id,
                     product_id
                   #   sy
                   HAVING
                     COUNT(*) > 1
                   ORDER BY product_id) t1);

SELECT *
FROM ebay_products, parsed_citi, ebay, Product
  INNER JOIN
  (SELECT *
   FROM ebay_products, parsed_citi, ebay
   WHERE (ebay_products.product_id = parsed_citi.id) AND (ebay_products.ebay_id = ebay.id)
   ORDER BY ebay_products.datetimeleft ASC) t1
  JOIN
  (SELECT *
   FROM ebay_products, Product, ebay
   WHERE (ebay_products.product_id = Product.id) AND (ebay_products.ebay_id = ebay.id)
   ORDER BY Product.id ASC) t2


SELECT *
FROM ebay_products, parsed_citi, ebay, Product
GROUP BY product_id
HAVING count(product_id) > 1

SELECT
  tst01.parsed_citi.id        psid,
  Product.id                  ppid,
  tst01.parsed_citi.shortName sn,
  tst01.Product.our_name      oon,
  count(*)                    count
FROM parsed_citi, Product
WHERE tst01.parsed_citi.id != tst01.Product.id
GROUP BY ppid
HAVING COUNT(*) > 1


CREATE TABLE IF NOT EXISTS doubles AS
  SELECT *
  FROM (SELECT
          product_id,
          ebay_id,
          #   syn AS sy,
          COUNT(*) count
        FROM
          ebay_products
        GROUP BY
          #   id,
          product_id
        #   sy
        HAVING
          COUNT(*) > 0
        ORDER BY product_id) t1;

SELECT
  t1.id,
  t1.categoryName,
  t1.url,
  t1.shortName sn,
  t2.our_name  sn
FROM parsed_citi AS t1
  JOIN Product AS t2
GROUP BY t1.id


CREATE TABLE IF NOT EXISTS
  `oops` (
  `id`         INT(11)         NOT NULL AUTO_INCREMENT,
  category_pid INT             NULL,
  older_pid    INT             NULL,
  url          VARCHAR(512)    NULL,
  price        INT             NULL,
  brand        VARCHAR(20)     NULL,
  shortname    VARCHAR(1024)   NULL,
  syn          VARCHAR(1024)   NULL,
  price_min    INT             NULL,
  price_max    INT             NULL,
  pic          VARCHAR(512)    NULL,
  descr        TEXT            NULL,
  out_apr      INT DEFAULT '0' NULL,
  out_tot      INT DEFAULT '0' NULL,
  out_arc      INT DEFAULT '0' NULL,
  source       INT             NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE oops
    (SELECT
       older_pid               AS `id`,
       category_pid,
       older_pid               AS older_pid,
       url,
       price,
       brand                   AS brand,
       shortname,
       syn,
       NULL                    AS price_min,
       NULL                    AS price_max,
       pic,
       NULL                    AS descr,
       NULL                    AS out_apr,
       NULL                    AS out_tot,
       NULL                    AS out_arc,
       IFNULL(min_procent, 50) AS min_procent,
       IFNULL(max_procent, 80) AS max_procent,
       src                     AS src,
       (SELECT COUNT(*) count
        FROM ebay_products
        WHERE ebay_products.product_id = older_pid
        GROUP BY older_pid
        HAVING COUNT(*) > 0
       )                       AS finded
     FROM
       (SELECT
          Product.id    AS older_pid,
          our_name      AS shortname,
          citilinkURL   AS url,
          categoryID    AS category_pid,
          citilinkPrice AS price,
          synonyms      AS syn,
          picture_url   AS pic,
          min_procent   AS min_procent,
          max_procent   AS max_procent,
          NULL          AS brand,
          'Product'     AS src
        FROM Product
        UNION
        SELECT
          parsed_citi.id AS older_pid,
          shortName      AS shortname,
          url            AS url,
          categoryId     AS category_pid,
          price          AS price,
          pre_syn        AS syn,
          pic            AS pic,
          NULL           AS min_procent,
          NULL           AS max_procent,
          brandName      AS brand,
          'parsed_citi'  AS src
        FROM parsed_citi) t1)
     ORDER BY finded;

CREATE TABLE oops
(
  id           INT DEFAULT '0'         NOT NULL PRIMARY KEY,
  category_pid INT                     NULL,
  older_pid    INT DEFAULT '0'         NOT NULL,
  url          VARCHAR(512)            NULL,
  price        INT                     NULL,
  brand        VARCHAR(15)             NULL,
  shortname    VARCHAR(512)            NULL,
  syn          VARCHAR(512)            NULL,
  price_min    BINARY(0)               NULL,
  price_max    BINARY(0)               NULL,
  pic          VARCHAR(512)            NULL,
  descr        TEXT                    NULL,
  out_apr      BINARY(0)               NULL,
  out_tot      BINARY(0)               NULL,
  out_arc      BINARY(0)               NULL,
  min_procent  INT(11) DEFAULT '0'     NOT NULL,
  max_procent  INT(11) DEFAULT '0'     NOT NULL,
  src          VARCHAR(36) DEFAULT ''  NOT NULL,
  finded       INT(21) DEFAULT '0'     NULL
);

INSERT INTO oops SELECT
                   older_pid               AS `id`,
                   category_pid,
                   older_pid               AS older_pid,
                   url,
                   price,
                   brand                   AS brand,
                   shortname,
                   syn,
                   NULL                    AS price_min,
                   NULL                    AS price_max,
                   pic,
                   NULL                    AS descr,
                   NULL                    AS out_apr,
                   NULL                    AS out_tot,
                   NULL                    AS out_arc,
                   IFNULL(min_procent, 50) AS min_procent,
                   IFNULL(max_procent, 80) AS max_procent,
                   src                     AS src,
                   (SELECT COUNT(*) count
                    FROM ebay_products
                    WHERE ebay_products.product_id = older_pid
                    GROUP BY older_pid
                    HAVING COUNT(*) > 0
                   )                       AS finded
                 FROM
                   (SELECT
                      Product.id    AS older_pid,
                      our_name      AS shortname,
                      citilinkURL   AS url,
                      categoryID    AS category_pid,
                      citilinkPrice AS price,
                      synonyms      AS syn,
                      picture_url   AS pic,
                      min_procent   AS min_procent,
                      max_procent   AS max_procent,
                      NULL          AS brand,
                      'Product'     AS src
                    FROM Product
                    UNION
                    SELECT
                      parsed_citi.id AS older_pid,
                      shortName      AS shortname,
                      url            AS url,
                      categoryId     AS category_pid,
                      price          AS price,
                      pre_syn        AS syn,
                      pic            AS pic,
                      NULL           AS min_procent,
                      NULL           AS max_procent,
                      brandName      AS brand,
                      'parsed_citi'  AS src
                    FROM parsed_citi) t1;


ALTER TABLE oops
  ALTER min_procent SET DEFAULT 50,
  ALTER max_procent SET DEFAULT 80;


SELECT
  tst01.oops.shortname,
  (SELECT categoryName
   FROM citi_cats
   WHERE category_pid = citi_cats.categoryId) AS categoryName,
  #   oops.*,
  count(*)                                    AS count1
FROM oops, ebay_products
WHERE oops.older_pid = ebay_products.product_id
GROUP BY older_pid
HAVING count(*) > 0

SELECT
  tst01.ebay_products.appr,
  tst01.oops.shortname,
  tst01.oops.syn,
  (SELECT categoryName
   FROM citi_cats
   WHERE category_pid = citi_cats.categoryId) AS categoryName,
  ebay_products.ebay_id,
  ebay_products.product_id,
  count(*)                                    AS finded
FROM oops
  LEFT JOIN ebay_products ON (oops.older_pid = ebay_products.product_id)
WHERE (ebay_products.product_id IS NOT NULL) #выбираем которые не нашлись (IS NOT NULL - наоборот которые нашлись)
  AND (ebay_products.datetimeleft > "2018-04-03T03:42:24+0000")
  AND (ebay_products.appr = TRUE )
GROUP BY ebay_products.ebay_id
# GROUP BY ebay_products.product_id
ORDER BY ebay_id DESC;





UPDATE Product
SET citilinkPrice = citilinkPrice * 57.5598

SELECT
  a.name,
  IFNULL(s.total, 0)
FROM article a
  LEFT JOIN (
              SELECT
                idArticle,
                COUNT(*) AS total
              FROM sale
              WHERE date = "2011-01-01"
              GROUP BY idArticle
            ) AS s ON a.id = s.idArticle


