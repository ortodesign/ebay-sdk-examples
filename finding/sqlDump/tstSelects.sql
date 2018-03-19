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
WHERE `TABLE_SCHEMA`='tst01'
      AND `TABLE_NAME`='parsed_citi';


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
FROM ebay_products,parsed_citi,ebay
WHERE (ebay_products.product_id = parsed_citi.id) AND (ebay_products.ebay_id = ebay.id)
      AND (ebay_products.datetimeleft > "2018-03-15T11:00:08+0000") AND (ebay_products.datetimeleft < "2018-03-15T20:44:26+0000")
# GROUP BY ebay_id
ORDER BY ebay_products.datetimeleft ASC;


DELETE FROM parsed_citi
WHERE id > 0

SELECT * from synon t1
  INNER JOIN
  synon t2
WHERE
  (t1.pid = t2.pid) AND (t1.id < t2.id)
GROUP BY t2.syn;
# AND (t1.syn = t2.syn)
# AND (t1.id < t2.id);

SELECT * from
(SELECT * from synon t1
 ORDER BY pid) t2
# GROUP BY syn) t2
# GROUP BY pid
# ORDER BY pid

  SELECT t2.pid,COUNT(*) count from
# (SELECT * from
 (SELECT * from synon t1
GROUP BY syn) t2
# GROUP BY pid
# ORDER BY t2.pid) t3
  Having
    COUNT(*) > 1

SELECT *
FROM synon
GROUP BY pid
HAVING COUNT(pid) > 2
ORDER BY pid

## !!!!
select * from synon
where pid in (
select pid from synon group by pid having count(*) > 1);

SELECT
  synon.pid AS spid,
  parsed_citi.id     AS psid,
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
  pid ,
#   syn AS sy,
  COUNT(*) count
FROM
  synon
GROUP BY
#   id,
  pid
#   sy
Having
  COUNT(*) > 2
ORDER BY pid