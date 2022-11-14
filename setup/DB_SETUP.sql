-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema prophecy
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema prophecy
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `prophecy` DEFAULT CHARACTER SET utf8 ;
USE `prophecy` ;

-- -----------------------------------------------------
-- Table `prophecy`.`User_Details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prophecy`.`User_Details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- Create the user "prophecy_agent" with the password ""
CREATE USER prophecy_agent IDENTIFIED BY '';

-- Grant the user "prophecy_agent" all privileges on the database "prophecy"
GRANT ALL PRIVILEGES ON prophecy.* TO prophecy_agent;

-- Make a new entry in user_details
INSERT INTO prophecy.User_Details (username, password, first_name, last_name, email) VALUES ("", "", "", "", ");