-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2015 at 06:24 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `post-hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `postID` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(75) NOT NULL DEFAULT 'thread',
  `tag` varchar(50) NOT NULL DEFAULT 'random',
  `title` varchar(150) DEFAULT NULL,
  `content` text,
  `url` varchar(250) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`postID`),
  KEY `page` (`page`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postID`, `page`, `tag`, `title`, `content`, `url`, `created`) VALUES
(1, 'thread', 'random', 'First Post', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'first-post', '2015-09-26 21:10:34'),
(3, 'thread', 'random', 'Second Post', 'this better work', 'second-post', '2015-09-26 21:13:57'),
(4, 'thread', 'random', 'Third post', 'can i cms or what? lol', 'third-post', '2015-09-27 07:06:48'),
(7, 'about', 'random', 'About the Author', 'I totally didn''t stay up till 4:30 to get this working hahahaha?!?!?!? right? right!!!', 'about-the-author', '2015-09-27 18:59:17'),
(8, 'thread', 'random', 'edit 1', 'boii this is that edit \r\n\r\n\r\nEDIT: i edited a edit woot woot emgivom \r\neee <a href="http://www.facebook.com" blank="_blank">aca</a>\r\n\r\nejncw\r\n<h1>dbvyb</h1>', 'edit-1', '2015-09-27 23:28:53'),
(9, 'thread', 'random', 'edit 2', 'is this editing right?', 'edit-2', '2015-09-27 23:29:14'),
(10, 'thread', 'random', 'fourth post', 'why?', 'fourth-post', '2015-09-27 23:29:45');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
