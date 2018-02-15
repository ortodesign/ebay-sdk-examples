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
    SELECT *
    FROM ebay_products,Product,ebay
    WHERE (ebay_products.product_id = Product.id) and (ebay_products.ebay_id = ebay.id)
  GROUP BY product_id
    ORDER BY ebay_products.datetimeleft

#   ) as t1


# ORDER BY `ebay`.datetimeleft LIMIT 1

# 			 (SELECT `ebay`.datetimeleft FROM ebay WHERE(ebay_id = ebay.id) AND (product_id = Product.id) ORDER BY `ebay`.datetimeleft LIMIT 1) as 'first')
# 			 (SELECT column FROM table WHERE [condition] ORDER BY column DESC LIMIT 1) as 'last'
#  FROM `ebay_products`, `Product`, `ebay`
#  WHERE (ebay_id = ebay.id) AND (product_id = Product.id)
