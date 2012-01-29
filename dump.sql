-- phpMyAdmin SQL Dump
-- version 3.2.2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 29 gen, 2012 at 03:25 PM
-- Versione MySQL: 5.5.19
-- Versione PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `zwe-framework`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `IDBlog` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDParent` varchar(20) NOT NULL COMMENT 'The page in which the news is written (it is a varchar because the page can not be in the db, but only a controller)',
  `IDUser` bigint(20) unsigned NOT NULL COMMENT 'The author of the news',
  `Title` varchar(50) NOT NULL,
  `Text` text NOT NULL,
  `CreationDate` datetime NOT NULL,
  PRIMARY KEY (`IDBlog`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`IDBlog`, `IDParent`, `IDUser`, `Title`, `Text`, `CreationDate`) VALUES
(1, 'blog', 1, 'News di prova', 'Ciao come va??\r\n\r\nIo tutto benissimo, tu?', '2011-12-22 10:06:48'),
(2, 'blog', 1, 'Seconda news di prova', 'Tutto benone pure io, grazie mille!', '2011-12-22 10:06:48');

-- --------------------------------------------------------

--
-- Struttura della tabella `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `IDPage` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDParent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `IDPageType` bigint(20) unsigned NOT NULL,
  `Url` varchar(50) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Position` bigint(20) NOT NULL,
  PRIMARY KEY (`IDPage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `page`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `page_type`
--

DROP TABLE IF EXISTS `page_type`;
CREATE TABLE IF NOT EXISTS `page_type` (
  `IDPageType` bigint(20) unsigned NOT NULL,
  `Type` varchar(50) NOT NULL,
  PRIMARY KEY (`IDPageType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `page_type`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `IDUser` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` char(40) CHARACTER SET utf8 NOT NULL,
  `Salt` char(40) NOT NULL,
  `CreationDate` datetime NOT NULL,
  `Active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Allowed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`IDUser`, `Email`, `Username`, `Password`, `Salt`, `CreationDate`, `Active`, `Allowed`) VALUES
(9, 'flicofloc@gmail.com', 'zweer', '3685f18be7def1d643636c52047447680846f81f', 'b106da8be98b056183ee29bceb6dda234ee0efad', '2011-12-31 14:44:55', 1, 0);
