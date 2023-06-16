-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-05-01 07:28:40
-- 服务器版本： 5.6.50-log
-- PHP 版本： 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `pay_vmianqian_co`
--

-- --------------------------------------------------------

--
-- 表的结构 `pay_order`
--

CREATE TABLE `pay_order` (
  `id` bigint(20) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `close_date` bigint(20) NOT NULL,
  `create_date` bigint(20) NOT NULL,
  `is_auto` int(1) NOT NULL,
  `notify_url` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `param` varchar(255) DEFAULT NULL,
  `pay_date` bigint(20) NOT NULL,
  `pay_id` varchar(255) DEFAULT NULL,
  `pay_url` varchar(255) DEFAULT NULL,
  `price` double NOT NULL,
  `really_price` double NOT NULL,
  `return_url` varchar(255) DEFAULT NULL,
  `state` int(1) NOT NULL,
  `type` int(1) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `send_mail` int(1) NOT NULL DEFAULT '0',
  `ip` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pay_qrcode`
--

CREATE TABLE `pay_qrcode` (
  `id` bigint(20) NOT NULL,
  `pay_url` varchar(255) DEFAULT NULL,
  `price` double NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `setting`
--

CREATE TABLE `setting` (
  `vkey` varchar(255) NOT NULL,
  `vvalue` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `setting`
--

INSERT INTO `setting` (`vkey`, `vvalue`) VALUES
('user', 'admin'),
('pass', '$2y$10$t0BlL4SB7Hi6UfQEJogtZuAqyLFnTVs2tGANmIAnnueZNXcR.KMSW'),
('notifyUrl', ''),
('returnUrl', ''),
('key', 'b95fc22f02ab22dc8684af371a91ca23'),
('lastheart', '1651357386'),
('lastpay', '1632966901'),
('jkstate', '1'),
('close', '5'),
('payQf', '1'),
('wxpay', ''),
('zfbpay', ''),
('pid', '123456'),
('email', '123456@qq.com'),
('alipay_id', ''),
('time_interval', '10'),
('pay_num', '5'),
('bd_num', '3'),
('ali_cookie', ''),
('ali_cookie_state', '0'),
('cron_key', 'b95fc22f02ab22dc8684af371a91ca23'),
('passageway', '2'),
('ali_cookie_time', '1632966600'),
('qq_cookie_state', '0'),
('qq_cookie_time', NULL),
('qq_cookie', NULL),
('qqpay', ''),
('qq_running_time', NULL),
('ali_running_time', NULL),
('e_host', NULL),
('e_port', NULL),
('e_from_name', NULL),
('e_user_name', NULL),
('e_password', NULL),
('e_from', NULL),
('home_jump', ''),
('transfer', '1'),
('confirm', '0'),
('ali_home', NULL),
('qq_home', NULL),
('ali_frequency', '10'),
('pay_voice', '0'),
('qq_frequency', '5');

-- --------------------------------------------------------

--
-- 表的结构 `tmp_price`
--

CREATE TABLE `tmp_price` (
  `price` varchar(255) NOT NULL,
  `oid` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `pay_order`
--
ALTER TABLE `pay_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `pay_qrcode`
--
ALTER TABLE `pay_qrcode`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`vkey`);

--
-- 表的索引 `tmp_price`
--
ALTER TABLE `tmp_price`
  ADD PRIMARY KEY (`price`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `pay_order`
--
ALTER TABLE `pay_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `pay_qrcode`
--
ALTER TABLE `pay_qrcode`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
