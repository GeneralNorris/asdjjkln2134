<?php 
/* 
 
 
ALTER TABLE `artikel` ALTER `preis` DROP DEFAULT;
ALTER TABLE `artikel` CHANGE COLUMN `preis` `preis` DECIMAL(10,2) NOT NULL AFTER `id`;
 
 
 
 
 
 */


?>