-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 31 gen, 2012 at 09:58 AM
-- Versione MySQL: 5.5.20
-- Versione PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `zwe-framework`
--

-- --------------------------------------------------------

--
-- Cancella le tabelle non più utili
--

DROP TABLE `page`;
DROP TABLE `page_type`;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `IDBlog` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDPage` varchar(20) NOT NULL COMMENT 'The page in which the news is written (it is a varchar because the page can not be in the db, but only a controller)',
  `IDParent` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'If this is a comment, IDParent > 0 (= IDBlog of the post it wants to comment)',
  `IDUser` bigint(20) unsigned NOT NULL COMMENT 'The author of the news',
  `Title` varchar(50) NOT NULL,
  `Text` text NOT NULL,
  `TextPreview` text COMMENT 'The preview of the text. If missing, the text will be cut automatically',
  `Image` text NOT NULL COMMENT 'Absolute link of the image you want to use in the post top',
  `ImagePreview` text COMMENT 'The coords of the selection to preview',
  `CreationDate` datetime NOT NULL,
  PRIMARY KEY (`IDBlog`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`IDBlog`, `IDPage`, `IDParent`, `IDUser`, `Title`, `Text`, `TextPreview`, `Image`, `ImagePreview`, `CreationDate`) VALUES
(1, 'blog', 0, 9, 'News di prova', 'Ciao come va??\r\n\r\nIo tutto benissimo, tu?', NULL, '', '', '2011-12-22 10:06:48'),
(2, 'blog', 0, 9, 'Seconda news di prova', 'Tutto benone pure io, grazie mille!', NULL, '', '', '2011-12-22 10:06:48');

-- --------------------------------------------------------

--
-- Struttura della tabella `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `IDGroup` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) NOT NULL,
  PRIMARY KEY (`IDGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `group`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `permission`
--

DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `IDPermission` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDParent` bigint(20) unsigned NOT NULL COMMENT 'if = 0: resource; else: permission',
  `Name` varchar(20) NOT NULL,
  PRIMARY KEY (`IDPermission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `permission`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `permission_group`
--

DROP TABLE IF EXISTS `permission_group`;
CREATE TABLE IF NOT EXISTS `permission_group` (
  `IDPermission` bigint(20) unsigned NOT NULL,
  `IDGroup` bigint(20) unsigned NOT NULL,
  `Deny` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDPermission`,`IDGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `permission_group`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
CREATE TABLE IF NOT EXISTS `permission_user` (
  `IDPermission` bigint(20) unsigned NOT NULL,
  `IDUser` bigint(20) unsigned NOT NULL,
  `Deny` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDPermission`,`IDUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `permission_user`
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
