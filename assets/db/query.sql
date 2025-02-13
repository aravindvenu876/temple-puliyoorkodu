UPDATE `temple-puliyoorkodu`.`system_main_menu` SET `status`=1 WHERE  `id`=10;

SELECT `tbl1`.`id` AS `id`,`tbl1`.`status` AS `status`,`tbl1`.`rate_type` AS `rate_type`,`tbl1`.`item` AS `item`,`tbl1`.`book_type` AS `book_type`,`tbl1`.`temple_id` AS `temple_id`,`tbl1`.`page` AS `page`,`tbl1`.`rate` AS `rate`,`tbl2`.`book` AS `book_eng`,`tbl3`.`book` AS `book_alt`,`tbl4`.`temple` AS `temple_eng`,`tbl5`.`temple` AS `temple_alt`
FROM ((((`pos_receipt_book` `tbl1`
JOIN `pos_receipt_book_lang` `tbl2` ON(`tbl2`.`book_id` = `tbl1`.`id`))
JOIN `pos_receipt_book_lang` `tbl3` ON(`tbl3`.`book_id` = `tbl1`.`id`))
JOIN `temple_master_lang` `tbl4` ON(`tbl4`.`temple_id` = `tbl1`.`temple_id`))
JOIN `temple_master_lang` `tbl5` ON(`tbl5`.`temple_id` = `tbl1`.`temple_id`))
WHERE `tbl2`.`lang_id` = 1 AND `tbl3`.`lang_id` = 2 AND `tbl4`.`lang_id` = 1 AND `tbl5`.`lang_id` = 2 AND `tbl1`.`status` <> 2