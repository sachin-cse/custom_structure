-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2022 at 11:40 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eclick_skeleton_`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `contactId` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `entryDate` varchar(20) NOT NULL DEFAULT 'Y',
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `contactType` varchar(100) NOT NULL DEFAULT 'C',
  `serializedData` longtext NOT NULL,
  `seen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content`
--

CREATE TABLE `tbl_content` (
  `contentID` int(11) NOT NULL,
  `menucategoryId` int(11) NOT NULL DEFAULT 0,
  `seoId` int(11) NOT NULL,
  `contentHeading` varchar(100) NOT NULL DEFAULT '',
  `permalink` varchar(250) DEFAULT NULL,
  `subHeading` varchar(255) NOT NULL,
  `displayHeading` enum('Y','N') NOT NULL DEFAULT 'Y',
  `contentDescription` longtext DEFAULT NULL,
  `contentShortDescription` text NOT NULL,
  `ImageName` varchar(255) NOT NULL,
  `displayOrder` int(11) DEFAULT 0,
  `contentStatus` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_content`
--

INSERT INTO `tbl_content` (`contentID`, `menucategoryId`, `seoId`, `contentHeading`, `permalink`, `subHeading`, `displayHeading`, `contentDescription`, `contentShortDescription`, `ImageName`, `displayOrder`, `contentStatus`) VALUES
(45, 53, 0, 'Contact Us', 'contact-us', '', 'Y', '<ul class=\"ul row\"><li class=\"col-sm-4\">\n<div class=\"sk_comm\">\n<div class=\"siteicon icon_map\">Â </div>\n<div>\n<div class=\"subheading\">Office Address</div>\nGodrej Genesis, 12th Floor, Unit - 1207<br>Plot - XI, Block - EP &amp; GP, Sector V, Salt Lake, <br>Kolkata -700091, <br>West Bengal, India.</div>\n</div>\n</li>\n<li class=\"col-sm-4\">\n<div class=\"sk_comm\">\n<div class=\"siteicon icon_phone\">Â </div>\n<div>\n<div class=\"subheading\">Call Us</div>\n<div>9874563210 / 9632587410</div>\n</div>\n</div>\n</li>\n<li class=\"col-sm-4\">\n<div class=\"sk_comm\">\n<div class=\"siteicon icon_email\">Â </div>\n<div>\n<div class=\"subheading\">Email Us</div>\n<a href=\"mailto:skeleton@eclickapps.com\">skeleton@eclickapps.com</a></div>\n</div>\n</li>\n</ul>', '', '', -1, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_body`
--

CREATE TABLE `tbl_email_body` (
  `id` int(11) NOT NULL,
  `emailSubject` varchar(255) NOT NULL,
  `emailBody` text NOT NULL,
  `smsBody` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_email_body`
--

INSERT INTO `tbl_email_body` (`id`, `emailSubject`, `emailBody`, `smsBody`) VALUES
(1, 'Forgot password', '<tr>\n    <td>\n        <p style=\"margin:0; padding:0 0 15px;\">Hello,</p>\n        <p style=\"margin:0; padding:0; text-align:justify;\">You recently asked us to reset your password. To reset it, please follow this link:</p>\n    </td>\n</tr>\n<tr>\n    <td style=\"padding:30px 0 0; text-align:center;\">\n        <a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Change password</a>\n    </td>\n</tr>', ''),
(2, 'Phone number updated', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have just updated your phone number on <a href=\"{site_path}\" style=\"text-decoration:none; color:#666666;\">eclickprojects.com</a>. If this change was not made by you, please email us at <a href=\"mailto:{site_email_editor}\" style=\"text-decoration:none; color:#666666;\">{site_email_editor}</a>.</p>\n	</td>\n</tr>', ''),
(3, 'Verify your new email address', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You  have  just  updated  your  email  address  on  <a href=\"{site_path}\" style=\"text-decoration:none; color:#666666;\">eclickprojects.com</a>.  To  complete  the  process,  please follow this link:</p>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Verify email address</a>\n	</td>\n</tr>', ''),
(4, 'Change email address', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">You have just updated your email address on eclickprojects.com.</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">We\'re just sending this email to your old address to make sure that you were the one who made the change. If not, please email us at editors@eclickprojects.com.</p>\n	</td>\n</tr>', ''),
(5, 'Password updated', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have just updated your password on <a href=\"{site_path}\" style=\"text-decoration:none; color:#666666;\">embroideryetc.com.au</a>. If this change was not made by you, please email us at <a href=\"mailto:{site_email}\" style=\"text-decoration:none; color:#666666;\">{site_email}</a>.</p>\r\n	</td>\r\n</tr>', ''),
(6, 'Account confirmation', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\r\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">You recently registered on embroideryetc.com.au. Your login credentials are given below:</p>\r\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">\r\n			Username: {username}<br />\r\n			Password: {password}\r\n		</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">To complete your registration, please follow this link:</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0; text-align:center;\">\r\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Confirm account</a>\r\n	</td>\r\n</tr>', ''),
(7, 'Your account has been deactivated', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">This email is to inform you that your account has been deactivated on eclickprojects.com. You will no longer be able to log on to the site using this email address, nor will you be able to receive internal messages.</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">\n			Time registered: {registered_time}<br />\n			Time deactivated: {deactivated_time}\n		</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">You are welcome to register on the site as a member, provided you use a different email address.</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">We wish you well in the future.</p>\n	</td>\n</tr>', ''),
(8, 'We have received your email', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">Thank you for contacting eclickprojects.com.</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">Please note that due to the large number of applications we receive, we will only contact successful candidates. Successful candidates can expect to be notified in the next two weeks. Whatever the outcome, we really appreciate your time and enthusiasm!</p>\n	</td>\n</tr>', ''),
(9, 'Email address changed', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Hello,</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have just updated your email address on <a href=\"{site_path}\" style=\"text-decoration:none; color:#666666;\">eclickprojects.com</a>. If this change was not made by you, please email us at <a href=\"mailto:{site_email_editor}\" style=\"text-decoration:none; color:#666666;\">{site_email_editor}</a>.</p>\n	</td>\n</tr>', ''),
(10, 'Please verify your email address', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">You recently registered for eclickprojects.com, but your email address has not been verified yet. Your login credentials are given below:</p>\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">\n			Username: {username}<br />\n			Password: {password}\n		</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">To complete your registration, please follow this link:</p>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Verify email address</a>\n	</td>\n</tr>', ''),
(11, 'Contact information', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have got a new contact information with the following information:</p>\n		\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested by:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{name}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {email}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {phone}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0;\">{service}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Message:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0;\">{message}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n		</table>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\n	</td>\n</tr>', ''),
(12, 'Association request', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding-bottom:15px;\">Dear Administrator,</p>\r\n		<p style=\"margin:0; padding-bottom:0; text-align:justify;\">You have got a new association request with the following information:</p>\r\n		\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested by:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\r\n								<img src=\"{userpic}\" alt=\"{username}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\r\n							</td>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{username}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {useremail}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {userphone}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {useraddress}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\r\n								<img src=\"{orgpic}\" alt=\"{orgname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\r\n							</td>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{orgname}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n	</tr>\r\n	<tr>\r\n	<td style=\"padding:30px 0 0; text-align:center;\">\r\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\r\n	</td>\r\n	</tr>', ''),
(13, 'Association request', '<tr>\n	<td>\n		<p style=\"margin:0; padding-bottom:15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding-bottom:0; text-align:justify;\">We have received your new association request with the following information:</p>\n		\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\n								<img src=\"{orgpic}\" alt=\"{orgname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\n							</td>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{orgname}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n		</table>\n		<p style=\"margin:0; padding:30px 0 0; text-align:justify;\">Please note that due to the large number of requests we receive, we will only contact successful associates. Members associated with verified organisation can expect to be notified in the next two weeks. Whatever the outcome, we really appreciate your time and enthusiasm!</p>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\n	</td>\n</tr>', ''),
(14, 'Association confirmation', '<tr>\n		<td>\n			<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n			<p style=\"margin:0; padding:0; text-align:justify;\">Congratulations! Your association request has been approved and now you are associated with <strong>{boardname}</strong>.</p>\n		</td>\n	</tr>\n	<tr>\n		<td style=\"padding:30px 0 0; text-align:center;\">\n			<a href=\"{site_path}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Click here to login</a>\n		</td>\n	</tr>', ''),
(15, 'Association request rejected', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">We are sorry to inform you that your association request for <strong>{boardname}</strong> has been rejected.</p>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{site_path}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Click here to login</a>\n	</td>\n</tr>', ''),
(16, 'New organisation request', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have got a new organisation request with the following information:</p>\n		\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested by:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\n								<img src=\"{userpic}\" alt=\"{username}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\n							</td>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{username}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {useremail}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {userphone}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {useraddress}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\n								<img src=\"{orgpic}\" alt=\"{orgname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\n							</td>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{orgname}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n		</table>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\n	</td>\n</tr>', ''),
(17, 'New organisation request', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding-bottom:15px;\">Dear {fullname},</p>\r\n		<p style=\"margin:0; padding-bottom:0; text-align:justify;\">We have received your new organisation listing request with the following information:</p>\r\n		\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\r\n								<img src=\"{orgpic}\" alt=\"{orgname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\r\n							</td>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{orgname}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n		<p style=\"margin:0; padding:30px 0 0; text-align:justify;\">Please note that due to the large number of requests we receive, we will only contact associates of approved organisations. Members associated with verified organisation can expect to be notified in the next two weeks. Whatever the outcome, we really appreciate your time and enthusiasm!</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0; text-align:center;\">\r\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\r\n	</td>\r\n</tr>', ''),
(18, 'Organisation confirmation', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">Congratulations! Your new organisation listing request has been approved and now you are associated with:</p>\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Organisation:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\n								<img src=\"{orgpic}\" alt=\"{boardname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\n							</td>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{boardname}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n		</table>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Click here to login</a>\n	</td>\n</tr>', ''),
(19, 'Organisation request rejected', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {fullname},</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">We are sorry to inform you that your new organisation request for <strong>{boardname}</strong> has been rejected.</p>\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Organisation:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px; width:100px\" valign=\"middle\" align=\"center\">\r\n								<img src=\"{orgpic}\" alt=\"{boardname}\" width=\"100\" height=\"100\" style=\"border-radius:100px;\">\r\n							</td>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{boardname}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {orgemail}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {orgphone}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {orgaddress}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0; text-align:center;\">\r\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Click here to login</a>\r\n	</td>\r\n</tr>', ''),
(20, 'OTP to reset password', '<tr>\r\n    <td>\r\n        <p style=\"margin:0; padding:0 0 15px;\">Hello,</p>\r\n        <p style=\"margin:0; padding:0; text-align:justify;\">You recently asked us to reset your password. To reset it, please enter this OTP:</p>\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td style=\"padding:30px 0 0; text-align:center;\">\r\n        <span style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">{OTP}</span>\r\n    </td>\r\n</tr>\r\n<tr>\r\n    <td style=\"padding:30px 0 0;text-align:left\">\r\n       <i>Please note: This OTP is valid for 10 minutes only. \r\n    </i></td>\r\n</tr>', ''),
(21, 'Subscribtion information', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\r\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">A new subscriber has been added with the following information:</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">\r\n\r\n			Email: {email}\r\n		</p>\r\n	</td>\r\n</tr>', ''),
(22, 'Unsubscribtion request information', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\r\n		<p style=\"margin:0; padding:0 0 15px; text-align:justify;\">A new unsubscribe request has been added with the following information:</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">\r\n\r\n			Email: {email}<br/>\r\nReason: {reason}<br/>\r\n		</p>\r\n	</td>\r\n</tr>', ''),
(23, 'Quote information', '<tr>\n	<td>\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have got a new quote information with the following information:</p>\n		\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested by:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{name}</strong></p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Company</span>: {company}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {email}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {phone}</p>\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {address}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								{request}\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n			<tr>\n				<td style=\"padding:30px 0 0;\">\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Message:</strong></p>\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n						<tr>\n							<td style=\"padding:30px\">\n								<p style=\"margin:0;\">{message}</p>\n							</td>\n						</tr>\n					</table>\n				</td>\n			</tr>\n		</table>\n	</td>\n</tr>\n<tr>\n	<td style=\"padding:30px 0 0; text-align:center;\">\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\n	</td>\n</tr>', ''),
(24, 'Quote information', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {name},</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">We have received your quote information with the following information:</p>\r\n		\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested for:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								{request}\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Message:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0;\">{message}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0;\">\r\n		<p style=\"margin:0;\">We will contact you soon.</p>\r\n	</td>\r\n</tr>', ''),
(25, 'Web design questionnaire information', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear Administrator,</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">You have got a new web design questionnaire with the following information:</p>\r\n		\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Requested by:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{name}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Email</span>: {email}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Phone</span>: {phone}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:60px; display:inline-block;\">Address</span>: {address}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Business Information:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{company}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">Nature of Business</span>: {natureBusiness}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">Targeting Audience</span>: {audience}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">List of Services your company provide</span>: {serviceList}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Website Information:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								{webInfo}\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Message:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0;\">{message}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0; text-align:center;\">\r\n		<a href=\"{link}\" style=\"height:40px; line-height:40px; background:#1976d2; color:#fff; font-size:20px; padding:0 18px 2px; display:inline-block; text-decoration:none; border-radius:4px;\">Check request</a>\r\n	</td>\r\n</tr>', ''),
(26, 'Web design questionnaire information', '<tr>\r\n	<td>\r\n		<p style=\"margin:0; padding:0 0 15px;\">Dear {name},</p>\r\n		<p style=\"margin:0; padding:0; text-align:justify;\">We have received your web design questionnaire with the following information:</p>\r\n		\r\n		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Business Information:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0; padding:0 0 5px; color:#000;\"><strong>{company}</strong></p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">Nature of Business</span>: {natureBusiness}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">Targeting Audience</span>: {audience}</p>\r\n								<p style=\"margin:0;\"><span style=\"width:240px; display:inline-block;\">List of Services your company provide</span>: {serviceList}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Website Information:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								{webInfo}\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:30px 0 0;\">\r\n					<p style=\"margin:0; padding:10px; background:#d1d1d1; color:#000;\"><strong>Message:</strong></p>\r\n					<table style=\"background: #f2f2f2;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n						<tr>\r\n							<td style=\"padding:30px\">\r\n								<p style=\"margin:0;\">{message}</p>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td style=\"padding:30px 0 0;\">\r\n		<p style=\"margin:0;\">We will contact you soon.</p>\r\n	</td>\r\n</tr>', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_category`
--

CREATE TABLE `tbl_menu_category` (
  `categoryId` int(11) NOT NULL,
  `parentId` int(11) NOT NULL DEFAULT 0,
  `moduleId` int(11) NOT NULL DEFAULT 0,
  `seoId` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL DEFAULT '',
  `permalink` varchar(250) DEFAULT NULL,
  `isBanner` enum('1','0') NOT NULL DEFAULT '1',
  `categoryImage` varchar(100) NOT NULL DEFAULT '',
  `isBannerCaption` enum('1','0') NOT NULL DEFAULT '1',
  `bannerCaption` text NOT NULL,
  `categoryUrl` text NOT NULL,
  `categoryUrlTarget` varchar(255) NOT NULL,
  `displayOrder` int(11) NOT NULL DEFAULT 0,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `isTopMenu` enum('Y','N') NOT NULL DEFAULT 'Y',
  `isFooterMenu` enum('Y','N') NOT NULL DEFAULT 'N',
  `hiddenMenu` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_menu_category`
--

INSERT INTO `tbl_menu_category` (`categoryId`, `parentId`, `moduleId`, `seoId`, `categoryName`, `permalink`, `isBanner`, `categoryImage`, `isBannerCaption`, `bannerCaption`, `categoryUrl`, `categoryUrlTarget`, `displayOrder`, `status`, `isTopMenu`, `isFooterMenu`, `hiddenMenu`) VALUES
(49, 0, 0, 28, 'Home', 'home', '1', '', '1', '', '/', '_self', 1, 'Y', 'Y', 'N', 'N'),
(50, 0, 0, 90, 'About Us', 'about-us', '1', '', '1', '', '', 'Default', 2, 'Y', 'Y', 'N', 'N'),
(53, 0, 319, 32, 'Contact Us', 'contact-us', '1', '', '1', '', '', 'Default', 8, 'Y', 'Y', 'N', 'N'),
(57, 0, 0, 36, 'Privacy Policy', 'privacy-policy', '1', '', '1', '', '', 'Default', 12, 'Y', 'Y', 'N', 'N'),
(58, 0, 0, 37, 'Terms &amp; Conditions', 'terms-amp-conditions', '1', '', '1', '', '', 'Default', 13, 'Y', 'Y', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_module`
--

CREATE TABLE `tbl_module` (
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `menu_name` varchar(100) NOT NULL DEFAULT '',
  `menu_description` text NOT NULL,
  `menu_image` varchar(100) NOT NULL DEFAULT '',
  `parent_dir` varchar(100) NOT NULL DEFAULT '',
  `child_dir` varchar(100) NOT NULL DEFAULT '',
  `isDefault` enum('0','1') NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT 'Y',
  `displayOrder` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_module`
--

INSERT INTO `tbl_module` (`menu_id`, `parent_id`, `menu_name`, `menu_description`, `menu_image`, `parent_dir`, `child_dir`, `isDefault`, `status`, `displayOrder`) VALUES
(1, 0, 'CMS', '', 'cms.png', 'content', '', '1', 'Y', 3),
(2, 1, 'Content', '', '1502692909.png', 'content', '', '1', 'Y', 0),
(3, 0, 'SEO', '', 'seo.png', 'seo', '', '1', 'Y', 5),
(4, 3, 'Default & Home', '', '', 'seo', 'defaulthome', '1', 'Y', 1),
(5, 3, 'Title & Meta', '', '', 'seo', 'titlemeta', '1', 'Y', 2),
(6, 3, 'Tag Manager &amp; Others', '', '', 'seo', 'others', '1', 'Y', 3),
(7, 3, 'Social', '', '', 'seo', 'social', '1', 'Y', 4),
(99, 0, 'Site Page', '', 'sitepage.png', 'sitepage', '', '1', 'Y', 1),
(100, 99, 'Add Page', '', 'addpage.png', 'sitepage', '', '1', 'Y', 0),
(101, 99, 'Pages', '', 'pages.png', 'sitepage', 'pages', '1', 'Y', 0),
(319, 0, 'Communication', '', 'communication.png', 'communication', '', '1', 'Y', 4),
(320, 319, 'Content', '', '', 'communication', 'communicationcontent', '0', 'Y', 0),
(321, 319, 'Contact Mail', '', '', 'communication', 'contactmail', '0', 'Y', 1),
(322, 319, 'Settings', '', '', 'communication', 'communicationsettings', '0', 'Y', 2),
(339, 0, 'Theme', '', 'theme.png', 'theme', '', '1', 'Y', 2),
(340, 339, 'Menu', '', '', 'theme', 'menu', '1', 'Y', 0),
(342, 339, 'Slider', '', '', 'theme', 'slider', '1', 'Y', 0),
(343, 339, 'CSS &amp; JS', '', '', 'theme', 'cssampjs', '1', 'Y', 0),
(344, 339, 'Settings', '', '', 'theme', 'themesettings', '1', 'Y', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `name`, `value`) VALUES
(6, 'nav', 'a:1:{i:0;a:4:{s:4:\"name\";s:9:\"Main Menu\";s:9:\"permalink\";s:9:\"main-menu\";s:3:\"css\";s:0:\"\";s:5:\"pages\";s:753:\"a:4:{i:0;O:8:\"stdClass\":8:{s:7:\"item_id\";s:4:\"root\";s:9:\"parent_id\";s:4:\"none\";s:4:\"node\";s:0:\"\";s:6:\"module\";s:1:\"0\";s:3:\"act\";s:3:\"new\";s:5:\"depth\";i:0;s:4:\"left\";s:1:\"1\";s:5:\"right\";i:8;}i:1;O:8:\"stdClass\":8:{s:7:\"item_id\";s:2:\"49\";s:9:\"parent_id\";s:4:\"root\";s:4:\"node\";s:4:\"Home\";s:6:\"module\";s:1:\"0\";s:3:\"act\";s:3:\"new\";s:5:\"depth\";i:1;s:4:\"left\";i:2;s:5:\"right\";i:3;}i:2;O:8:\"stdClass\":8:{s:7:\"item_id\";s:2:\"50\";s:9:\"parent_id\";s:4:\"root\";s:4:\"node\";s:8:\"About Us\";s:6:\"module\";s:1:\"0\";s:3:\"act\";s:3:\"new\";s:5:\"depth\";i:1;s:4:\"left\";i:4;s:5:\"right\";i:5;}i:3;O:8:\"stdClass\":8:{s:7:\"item_id\";s:2:\"53\";s:9:\"parent_id\";s:4:\"root\";s:4:\"node\";s:10:\"Contact Us\";s:6:\"module\";s:3:\"319\";s:3:\"act\";s:3:\"new\";s:5:\"depth\";i:1;s:4:\"left\";i:6;s:5:\"right\";i:7;}}\";}}'),
(7, 'communication', 'a:12:{s:6:\"isForm\";i:1;s:11:\"formHeading\";s:23:\"Feel free to contact us\";s:10:\"successMsg\";s:55:\"Thanks for your interest. We will get back to you soon.\";s:9:\"isCaptcha\";i:1;s:5:\"isMap\";i:1;s:10:\"mapAddress\";s:109:\"Godrej Genesis, 12th Floor, Unit - 1207 Plot - XI, Block - EP &amp; GP, Sector V, Salt Lake,  Kolkata -700091\";s:12:\"emailSubject\";s:19:\"Contact information\";s:9:\"emailBody\";s:323:\"<p style=\"margin:0;padding:0 0 15px;\">Dear Administrator,</p>\n<p style=\"margin:0;padding:0 0 15px;text-align:justify;\">A new contact information has been added with the following information:</p>\n<p style=\"margin:0;padding:0;text-align:justify;\">Name: {name}<br>Email: {email}<br>Phone: {phone}<br>Message: {comments}</p>\";s:7:\"toEmail\";s:27:\"site-control@eclickapps.com\";s:2:\"cc\";s:25:\"krishnendu@eclickapps.com\";s:3:\"bcc\";s:21:\"hasnat@eclickapps.com\";s:7:\"replyTo\";s:20:\"noreply@eclick.co.in\";}'),
(9, 'SEO', 'a:4:{s:7:\"seoData\";s:0:\"\";s:15:\"googleAnalytics\";s:0:\"\";s:10:\"tagManager\";s:0:\"\";s:18:\"tagManagerNoscript\";s:0:\"\";}'),
(11, 'CSSJS', 'a:3:{s:3:\"css\";a:3:{i:0;a:2:{s:4:\"file\";s:52:\"/skeleton/code/templates/default/css/plugins.min.css\";s:3:\"opt\";s:8:\"prefetch\";}i:1;a:2:{s:4:\"file\";s:50:\"/skeleton/code/templates/default/css/style.min.css\";s:3:\"opt\";s:7:\"preload\";}i:2;a:2:{s:4:\"file\";s:55:\"/skeleton/code/templates/default/css/responsive.min.css\";s:3:\"opt\";s:0:\"\";}}s:2:\"js\";a:3:{i:0;a:3:{s:4:\"file\";s:50:\"/skeleton/code/templates/default/js/plugins.min.js\";s:3:\"opt\";s:5:\"defer\";s:6:\"append\";s:4:\"head\";}i:1;a:3:{s:4:\"file\";s:78:\"https://www.google.com/recaptcha/api.js?onload=CaptchaCall&amp;render=explicit\";s:3:\"opt\";s:4:\"wait\";s:6:\"append\";s:4:\"body\";}i:2;a:3:{s:4:\"file\";s:49:\"/skeleton/code/templates/default/js/custom.min.js\";s:3:\"opt\";s:4:\"wait\";s:6:\"append\";s:4:\"body\";}}s:11:\"environment\";s:1:\"0\";}'),
(14, 'theme', 'a:10:{s:8:\"isSlider\";i:1;s:8:\"sliderNo\";s:1:\"3\";s:11:\"sliderWidth\";s:4:\"1600\";s:12:\"sliderHeight\";s:3:\"735\";s:8:\"isBanner\";i:1;s:11:\"bannerWidth\";s:4:\"1600\";s:12:\"bannerHeight\";s:3:\"310\";s:15:\"isBannerCaption\";i:1;s:13:\"bannerCaption\";s:12:\"Inner Banner\";s:11:\"innerBanner\";s:0:\"\";}'),
(15, 'captcha', 'a:2:{s:13:\"googleSiteKey\";s:40:\"6LeXBH8bAAAAAFprl9ery2cbn8epd1KwFD4y8sut\";s:15:\"googleSecretKey\";s:40:\"6LeXBH8bAAAAAOyR6FGWYeopAUIvrNaO0o0juFU-\";}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site`
--

CREATE TABLE `tbl_site` (
  `siteId` int(11) NOT NULL,
  `siteUrl` varchar(250) NOT NULL DEFAULT '',
  `siteName` varchar(250) NOT NULL DEFAULT '',
  `tagline` varchar(255) NOT NULL,
  `siteEmail` varchar(100) DEFAULT NULL,
  `sitePhone` varchar(100) DEFAULT NULL,
  `siteMobile` varchar(100) NOT NULL,
  `siteFax` varchar(255) NOT NULL,
  `siteAddress` text DEFAULT NULL,
  `siteOpeningHours` varchar(255) NOT NULL,
  `siteCurrency` varchar(255) NOT NULL,
  `siteCurrencySymbol` varchar(255) NOT NULL,
  `smtpHost` varchar(255) NOT NULL,
  `smtpEncryption` varchar(7) NOT NULL,
  `smtpPort` varchar(7) NOT NULL,
  `smtpUserName` varchar(255) NOT NULL,
  `smtpUserPassword` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `cache` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_site`
--

INSERT INTO `tbl_site` (`siteId`, `siteUrl`, `siteName`, `tagline`, `siteEmail`, `sitePhone`, `siteMobile`, `siteFax`, `siteAddress`, `siteOpeningHours`, `siteCurrency`, `siteCurrencySymbol`, `smtpHost`, `smtpEncryption`, `smtpPort`, `smtpUserName`, `smtpUserPassword`, `status`, `cache`) VALUES
(35, '/skeleton/latest/code', 'Skeleton', '', 'skeleton@eclickapps.com', '9876543210', '9876543210', '9876543210', 'Godrej Genesis, 12th Floor, Unit - 1207\r\nPlot - XI, Block - EP &amp; GP, Sector V, Salt Lake, \r\nKolkata -700091, \r\nWest Bengal, India.', 'Monday - Friday 9:00AM - 6:00PM', 'USD', '$', 'smtp.gmail.com', 'tls', '587', 'noreplyeclick@gmail.com', 'eclick@@321', 'Y', 'a:2:{s:7:\"caching\";s:5:\"false\";s:12:\"cacheRefresh\";s:5:\"86400\";}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slider`
--

CREATE TABLE `tbl_slider` (
  `id` int(11) NOT NULL,
  `sliderName` varchar(100) NOT NULL DEFAULT '',
  `permalink` varchar(255) NOT NULL,
  `displayHeading` enum('Y','N') NOT NULL DEFAULT 'Y',
  `subHeading` varchar(255) NOT NULL,
  `sliderDescription` varchar(255) NOT NULL,
  `imageName` varchar(250) DEFAULT NULL,
  `buttonName` varchar(255) NOT NULL,
  `redirectUrl` varchar(250) DEFAULT NULL,
  `redirectUrlTarget` varchar(100) NOT NULL,
  `displayOrder` int(11) NOT NULL DEFAULT 0,
  `status` enum('Y','N') DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social`
--

CREATE TABLE `tbl_social` (
  `id` int(11) NOT NULL,
  `socialName` varchar(255) NOT NULL,
  `socialLink` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `displayOrder` int(11) NOT NULL,
  `entryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_social`
--

INSERT INTO `tbl_social` (`id`, `socialName`, `socialLink`, `status`, `displayOrder`, `entryDate`) VALUES
(1, 'Facebook', 'https://www.facebook.com', 'Y', 1, '2018-06-27 18:40:18'),
(2, 'Linkedin', 'https://www.linkedin.com', 'Y', 5, '2018-06-27 18:41:18'),
(3, 'Twitter', 'https://www.twitter.com', 'Y', 2, '2018-06-27 18:42:06'),
(4, 'Google Plus', 'https://plus.google.com', 'Y', 3, '2018-06-27 18:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_title_and_meta`
--

CREATE TABLE `tbl_title_and_meta` (
  `titleandMetaId` int(255) NOT NULL,
  `siteId` int(255) NOT NULL,
  `titleandMetaUrl` varchar(255) NOT NULL,
  `canonicalUrl` varchar(255) NOT NULL,
  `pageTitleText` text NOT NULL,
  `metaTag` text NOT NULL,
  `metaDescription` text NOT NULL,
  `metaRobots` varchar(250) NOT NULL,
  `titleandMetaType` enum('O','D','H') NOT NULL,
  `ogImage` text NOT NULL,
  `others` text NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_title_and_meta`
--

INSERT INTO `tbl_title_and_meta` (`titleandMetaId`, `siteId`, `titleandMetaUrl`, `canonicalUrl`, `pageTitleText`, `metaTag`, `metaDescription`, `metaRobots`, `titleandMetaType`, `ogImage`, `others`, `status`) VALUES
(1, 35, '/', '', 'Skeleton CMS', 'Skeleton CMS', 'Skeleton CMS', 'default, follow', 'H', '', '', 'Y'),
(2, 35, '/', '', 'Skeleton CMS', 'Skeleton CMS', 'Skeleton CMS', 'default, follow', 'D', '', '', 'Y'),
(90, 35, '/about-us/', '', 'Skeleton CMS', 'Skeleton CMS', 'Skeleton CMS', 'default, follow', 'O', '', '', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `siteId` int(11) NOT NULL DEFAULT 0,
  `usertype` enum('A','M') NOT NULL DEFAULT 'M',
  `email` varchar(250) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `passwordKey` text NOT NULL,
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(100) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `permission` longtext DEFAULT NULL,
  `createDate` date NOT NULL DEFAULT '0000-00-00',
  `ipAddress` varchar(255) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `siteId`, `usertype`, `email`, `username`, `password`, `passwordKey`, `fullname`, `phone`, `address`, `permission`, `createDate`, `ipAddress`, `lastLogin`, `status`) VALUES
(2, 35, 'A', 'skeleton@eclickapps.com', 'eclickapps', '79350dc14ffca8f9332b52a6c48733a6', '', 'Super Admin', '', 'kolkata', '1,3,99,319,339,343,353,516,523,526,540,544,123,213,214,322,338,100,101,320,321,324,340,342,525,534,344,345,346,347,348,535,354,355,356,517,518,524,527,528,529,531,541,542,543,545,546,547,548', '0000-00-00', '', '2022-03-30 11:36:02', 'Y'),
(33, 35, 'M', 'krishnendu@eclickapps.com', 'skeleton', 'd68e8a3ba3c8983ec264ebe9a7cee675', '', 'Skeleton CMS', '9874563210', 'Kolkata, West bengal, India', '99,100,101,339,340,342,525,534,1,123,319,324,320,321,3,322,213,214,338,526,527,528,529,531,360,361,362,532,516,517,518,353,354,355,356,523,524,343,344,345,346,347,348', '2018-02-07', '', '2022-03-30 11:17:58', 'Y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`contactId`);

--
-- Indexes for table `tbl_content`
--
ALTER TABLE `tbl_content`
  ADD PRIMARY KEY (`contentID`);

--
-- Indexes for table `tbl_email_body`
--
ALTER TABLE `tbl_email_body`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menu_category`
--
ALTER TABLE `tbl_menu_category`
  ADD PRIMARY KEY (`categoryId`),
  ADD KEY `permalink` (`permalink`);

--
-- Indexes for table `tbl_module`
--
ALTER TABLE `tbl_module`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `tbl_site`
--
ALTER TABLE `tbl_site`
  ADD PRIMARY KEY (`siteId`);

--
-- Indexes for table `tbl_slider`
--
ALTER TABLE `tbl_slider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permalink` (`permalink`);

--
-- Indexes for table `tbl_social`
--
ALTER TABLE `tbl_social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_title_and_meta`
--
ALTER TABLE `tbl_title_and_meta`
  ADD PRIMARY KEY (`titleandMetaId`),
  ADD KEY `siteId` (`siteId`),
  ADD KEY `siteId_2` (`siteId`),
  ADD KEY `titleandMetaUrl` (`titleandMetaUrl`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `status` (`status`),
  ADD KEY `siteId` (`siteId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `contactId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_content`
--
ALTER TABLE `tbl_content`
  MODIFY `contentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tbl_email_body`
--
ALTER TABLE `tbl_email_body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_menu_category`
--
ALTER TABLE `tbl_menu_category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tbl_module`
--
ALTER TABLE `tbl_module`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=535;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_site`
--
ALTER TABLE `tbl_site`
  MODIFY `siteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_slider`
--
ALTER TABLE `tbl_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_social`
--
ALTER TABLE `tbl_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_title_and_meta`
--
ALTER TABLE `tbl_title_and_meta`
  MODIFY `titleandMetaId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
