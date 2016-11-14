-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-01-06 16:40:49
-- 服务器版本： 5.5.47-log
-- PHP Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii_admin`
--
CREATE DATABASE IF NOT EXISTS `yii_admin` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `yii_admin`;

-- --------------------------------------------------------

--
-- 表的结构 `yi_admin`
--

CREATE TABLE `yi_admin` (
  `ad_id` int(10) UNSIGNED NOT NULL COMMENT '自增ID',
  `ad_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `ad_password` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `adg_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员组ID',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '操作权限[,分割] ',
  `pvg_ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作权限组[,分割]',
  `ad_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `ad_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `ad_addtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ad_addip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加IP',
  `ad_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `ad_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后更新IP'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员表';

--
-- 转存表中的数据 `yi_admin`
--

INSERT INTO `yi_admin` (`ad_id`, `ad_username`, `ad_password`, `adg_id`, `pv_ids`, `pvg_ids`, `ad_status`, `ad_isdel`, `ad_addtime`, `ad_addip`, `ad_lasttime`, `ad_lastip`) VALUES
(1, '10000', 'dc483e80a7a0bd9ef71d8cf973673924', 1, '', '', 1, 0, 1452069123, 2130706433, 1452069123, 2130706433),
(2, '10001', 'dc483e80a7a0bd9ef71d8cf973673924', 2, ',1,2,', ',1,', 1, 0, 1452072492, 2130706433, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `yi_admin_group`
--

CREATE TABLE `yi_admin_group` (
  `adg_id` int(10) UNSIGNED NOT NULL COMMENT '自增ID',
  `adg_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户组名称',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID集[,分割]',
  `pvg_ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限组ID集[,分割]',
  `adg_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `adg_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `adg_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `adg_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员用户组';

--
-- 转存表中的数据 `yi_admin_group`
--

INSERT INTO `yi_admin_group` (`adg_id`, `adg_name`, `pv_ids`, `pvg_ids`, `adg_status`, `adg_isdel`, `adg_lasttime`, `adg_lastip`) VALUES
(1, '超级管理员', '', '', 1, 0, 1452069081, 2130706433),
(2, '观察员', '', ',1,', 1, 0, 1452071738, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `yi_admin_info`
--

CREATE TABLE `yi_admin_info` (
  `ai_id` bigint(20) UNSIGNED NOT NULL COMMENT '自增ID',
  `ad_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ai_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `ai_nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称|笔名|作者',
  `ai_phone` char(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '手机号',
  `ai_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '电子邮件',
  `ai_lasttime` int(10) NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员用户信息表';

--
-- 转存表中的数据 `yi_admin_info`
--

INSERT INTO `yi_admin_info` (`ai_id`, `ad_id`, `ai_name`, `ai_nickname`, `ai_phone`, `ai_email`, `ai_lasttime`) VALUES
(1, 1, '管理员', '', '', '', 1452069123),
(2, 2, '我是观察员', '只能看', '', '', 1452072386);

-- --------------------------------------------------------

--
-- 表的结构 `yi_admin_log`
--

CREATE TABLE `yi_admin_log` (
  `al_id` bigint(20) UNSIGNED NOT NULL COMMENT '日志ID[自增]',
  `ad_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `al_result` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作接果[1-成功,2-失败]',
  `al_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '日志内容',
  `al_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `al_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后操作时间',
  `al_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后操作IP'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员日志表';


-- --------------------------------------------------------

--
-- 表的结构 `yi_menu_nav`
--

CREATE TABLE `yi_menu_nav` (
  `mn_id` bigint(20) UNSIGNED NOT NULL COMMENT '自增ID',
  `mn_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `mn_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单地址',
  `mn_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标[ClassName]',
  `mn_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '3' COMMENT '菜单类别[1-左导航,2-上导航,3-其他]',
  `mn_sort` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序[由小到大优先排序]',
  `mn_pid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '菜单父ID[0-顶级]',
  `mn_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `mn_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `mn_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `mn_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单和导航表';

--
-- 转存表中的数据 `yi_menu_nav`
--

INSERT INTO `yi_menu_nav` (`mn_id`, `mn_name`, `mn_url`, `mn_icon`, `mn_type`, `mn_sort`, `mn_pid`, `mn_status`, `mn_isdel`, `mn_lasttime`, `mn_lastip`) VALUES
(1, '后台管理', 'top-sys', 'fa-chrome', 2, 0, 0, 1, 0, 1430280184, 2130706433),
(2, '默认首页', 'default/index', 'fa-home', 1, 0, 1, 1, 0, 1434610177, 2130706433),
(3, '导航列表', 'menu/index', 'fa-navicon', 1, 0, 1, 1, 0, 1434610638, 2130706433),
(4, '导航添加', 'menu/add', '', 0, 0, 3, 1, 0, 1430100982, 4294967295),
(5, '导航修改', 'menu/edit', '', 0, 0, 3, 1, 0, 1430101091, 4294967295),
(6, '导航删除', 'menu/del', '', 0, 0, 3, 1, 0, 1430101121, 4294967295),
(7, '管理员列表', 'admin/index', 'fa-user', 1, 0, 1, 1, 0, 1434610331, 2130706433),
(8, '管理员添加', 'admin/add', '', 0, 0, 7, 1, 0, 1430112759, 2130706433),
(9, '管理员修改', 'admin/edit', '', 0, 0, 7, 1, 0, 1430112772, 2130706433),
(10, '管理员删除', 'admin/del', '', 0, 0, 7, 1, 0, 1430112779, 2130706433),
(11, '管理员组列表', 'admin-group/index', 'fa-users', 1, 0, 1, 1, 0, 1434610336, 2130706433),
(12, '管理员组添加', 'admin-group/add', '', 3, 0, 11, 1, 0, 1430101275, 4294967295),
(13, '管理员组修改', 'admin-group/edit', '', 3, 0, 11, 1, 0, 1430101301, 4294967295),
(14, '管理员组删除', 'admin-group/del', '', 3, 0, 11, 1, 0, 1430101325, 4294967295),
(15, '权限列表', 'purview/index', 'fa-eye', 1, 0, 1, 1, 0, 1434610775, 2130706433),
(16, '权限添加', 'purview/add', '', 0, 0, 15, 1, 0, 1430101384, 4294967295),
(17, '权限修改', 'purview/edit', '', 0, 0, 15, 1, 0, 1430101412, 4294967295),
(18, '权限删除', 'purview/del', '', 0, 0, 15, 1, 0, 1430101439, 4294967295),
(19, '权限组列表', 'purview-group/index', 'fa-bullseye', 1, 0, 1, 1, 0, 1434610785, 2130706433),
(20, '权限组添加', 'purview-group/add', '', 0, 0, 19, 1, 0, 1430101505, 4294967295),
(21, '权限组修改', 'purview-group/edit', '', 0, 0, 19, 1, 0, 1430101495, 4294967295),
(22, '权限组删除', 'purview-group/del', '', 0, 0, 19, 1, 0, 1430101542, 4294967295),
(23, '管理员日志列表', 'admin-log/index', 'fa-list', 1, 0, 1, 1, 0, 1434611303, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `yi_purview`
--

CREATE TABLE `yi_purview` (
  `pv_id` bigint(20) UNSIGNED NOT NULL COMMENT '自增ID',
  `pv_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `pv_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限KEY[唯一]',
  `pv_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `pv_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `pv_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `pv_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理权限表';

--
-- 转存表中的数据 `yi_purview`
--

INSERT INTO `yi_purview` (`pv_id`, `pv_name`, `pv_key`, `pv_status`, `pv_isdel`, `pv_lasttime`, `pv_lastip`) VALUES
(1, '后台管理', 'top-sys', 1, 0, 1429079255, 0),
(2, '默认首页', 'default/index', 1, 0, 1429497209, 2130706433),
(3, '导航列表', 'menu/index', 1, 0, 1429079255, 0),
(4, '导航添加', 'menu/add', 1, 0, 1430100982, 4294967295),
(5, '导航修改', 'menu/edit', 1, 0, 1430101091, 4294967295),
(6, '导航删除', 'menu/del', 1, 0, 1430101121, 4294967295),
(7, '管理员列表', 'admin/index', 1, 0, 1429079255, 0),
(8, '管理员添加', 'admin/add', 1, 0, 1430101204, 4294967295),
(9, '管理员修改', 'admin/edit', 1, 0, 1430101224, 4294967295),
(10, '管理员删除', 'admin/del', 1, 0, 1430101248, 4294967295),
(11, '管理员组列表', 'admin-group/index', 1, 0, 1429079255, 0),
(12, '管理员组添加', 'admin-group/add', 1, 0, 1430101275, 4294967295),
(13, '管理员组修改', 'admin-group/edit', 1, 0, 1430101301, 4294967295),
(14, '管理员组删除', 'admin-group/del', 1, 0, 1430101325, 4294967295),
(15, '权限列表', 'purview/index', 1, 0, 1429079255, 0),
(16, '权限添加', 'purview/add', 1, 0, 1430101384, 4294967295),
(17, '权限修改', 'purview/edit', 1, 0, 1430101412, 4294967295),
(18, '权限删除', 'purview/del', 1, 0, 1430101439, 4294967295),
(19, '权限组列表', 'purview-group/index', 1, 0, 1429079255, 0),
(20, '权限组添加', 'purview-group/add', 1, 0, 1430101505, 4294967295),
(21, '权限组修改', 'purview-group/edit', 1, 0, 1430101495, 4294967295),
(22, '权限组删除', 'purview-group/del', 1, 0, 1430101542, 4294967295),
(23, '管理员日志列表', 'admin-log/index', 1, 0, 1429521743, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `yi_purview_group`
--

CREATE TABLE `yi_purview_group` (
  `pvg_id` bigint(20) UNSIGNED NOT NULL COMMENT '自增ID',
  `pvg_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限组名称',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID集[,分割]',
  `pvg_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `pvg_isdel` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `pvg_lasttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `pvg_lastip` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理权限组';

--
-- 转存表中的数据 `yi_purview_group`
--

INSERT INTO `yi_purview_group` (`pvg_id`, `pvg_name`, `pv_ids`, `pvg_status`, `pvg_isdel`, `pvg_lasttime`, `pvg_lastip`) VALUES
(1, '观察员', ',1,2,3,7,23,', 1, 0, 1452071748, 2130706433);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `yi_admin`
--
ALTER TABLE `yi_admin`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `ad_username` (`ad_username`),
  ADD KEY `adg_id` (`adg_id`);

--
-- Indexes for table `yi_admin_group`
--
ALTER TABLE `yi_admin_group`
  ADD PRIMARY KEY (`adg_id`),
  ADD UNIQUE KEY `adg_name` (`adg_name`);

--
-- Indexes for table `yi_admin_info`
--
ALTER TABLE `yi_admin_info`
  ADD PRIMARY KEY (`ai_id`),
  ADD UNIQUE KEY `ad_id` (`ad_id`);

--
-- Indexes for table `yi_admin_log`
--
ALTER TABLE `yi_admin_log`
  ADD PRIMARY KEY (`al_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `yi_menu_nav`
--
ALTER TABLE `yi_menu_nav`
  ADD PRIMARY KEY (`mn_id`),
  ADD KEY `mn_pid` (`mn_pid`);

--
-- Indexes for table `yi_purview`
--
ALTER TABLE `yi_purview`
  ADD PRIMARY KEY (`pv_id`),
  ADD KEY `pv_key` (`pv_key`);

--
-- Indexes for table `yi_purview_group`
--
ALTER TABLE `yi_purview_group`
  ADD PRIMARY KEY (`pvg_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `yi_admin`
--
ALTER TABLE `yi_admin`
  MODIFY `ad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `yi_admin_group`
--
ALTER TABLE `yi_admin_group`
  MODIFY `adg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `yi_admin_info`
--
ALTER TABLE `yi_admin_info`
  MODIFY `ai_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `yi_admin_log`
--
ALTER TABLE `yi_admin_log`
  MODIFY `al_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志ID[自增]', AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `yi_menu_nav`
--
ALTER TABLE `yi_menu_nav`
  MODIFY `mn_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `yi_purview`
--
ALTER TABLE `yi_purview`
  MODIFY `pv_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `yi_purview_group`
--
ALTER TABLE `yi_purview_group`
  MODIFY `pvg_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID', AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
