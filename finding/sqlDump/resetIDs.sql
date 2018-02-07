SET @count = 0;
UPDATE `Product` SET `Product`.`id` = @count:= @count + 1;
ALTER TABLE `Product` AUTO_INCREMENT = 1;