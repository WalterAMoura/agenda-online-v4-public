-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db-server:3306
-- Tempo de geração: 03/03/2025 às 13:49
-- Versão do servidor: 11.6.2-MariaDB-ubu2404
-- Versão do PHP: 8.2.27

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u301289665_agenda`
--
CREATE DATABASE IF NOT EXISTS `u301289665_agenda` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `u301289665_agenda`;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_access_modules`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_access_modules`;
CREATE TABLE IF NOT EXISTS `cnt_access_modules` (
`id` int(11)
,`created_at` timestamp
,`module_id` int(11)
,`type_id_module` int(11)
,`type_module` varchar(255)
,`allow` varchar(5)
,`module` varchar(255)
,`label` varchar(255)
,`icon` varchar(255)
,`path_module` varchar(255)
,`updated_at` timestamp
,`level_id` int(11)
,`level` int(11)
,`description` varchar(255)
,`home_path` varchar(255)
,`current` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_access_modules_v2`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_access_modules_v2`;
CREATE TABLE IF NOT EXISTS `cnt_access_modules_v2` (
`id` int(11)
,`created_at` timestamp
,`module_id` int(11)
,`module` mediumtext
,`updated_at` timestamp
,`level_id` int(11)
,`level` int(11)
,`description` varchar(255)
,`home_path` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_access_token_whatsapp`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_access_token_whatsapp`;
CREATE TABLE IF NOT EXISTS `cnt_access_token_whatsapp` (
`id` int(11)
,`created_at` timestamp
,`business_phone_number_id` bigint(20)
,`graph_api_token` varchar(1020)
,`expiration_at` timestamp
,`status_id` int(11)
,`updated_at` timestamp
,`status_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_active_account_users`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_active_account_users`;
CREATE TABLE IF NOT EXISTS `cnt_active_account_users` (
`id` int(11)
,`created_at` timestamp
,`token` varchar(1020)
,`id_user` int(11)
,`name_user` varchar(255)
,`email` varchar(255)
,`status_token` int(11)
,`decription_status_token` varchar(255)
,`expiration_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_agenda_by_department`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_agenda_by_department`;
CREATE TABLE IF NOT EXISTS `cnt_agenda_by_department` (
`id` int(11)
,`department` varchar(255)
,`year` varchar(4)
,`total` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_agenda_by_program`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_agenda_by_program`;
CREATE TABLE IF NOT EXISTS `cnt_agenda_by_program` (
`id` int(11)
,`description` varchar(255)
,`year` varchar(4)
,`total` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_agenda_by_status`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_agenda_by_status`;
CREATE TABLE IF NOT EXISTS `cnt_agenda_by_status` (
`id` int(11)
,`status` varchar(255)
,`color` varchar(255)
,`year` varchar(4)
,`total` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_apis`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_apis`;
CREATE TABLE IF NOT EXISTS `cnt_apis` (
`user_id` int(11)
,`user_name` varchar(255)
,`user_email` varchar(255)
,`id` int(11)
,`api_key` varchar(255)
,`api_name` varchar(255)
,`api_description` varchar(255)
,`api_path` varchar(255)
,`active` int(1)
,`status_id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_ask_to_change`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_ask_to_change`;
CREATE TABLE IF NOT EXISTS `cnt_ask_to_change` (
`id` int(11)
,`created_at` timestamp
,`current_linked_user_id` int(11)
,`scheduler_id` int(11)
,`new_linked_user_id` int(11)
,`status` int(11)
,`comments` varchar(510)
,`updated_at` timestamp
,`current_linked_user_name` varchar(255)
,`new_linked_user_name` varchar(255)
,`status_name` varchar(255)
,`scheduler_date` timestamp
,`scheduler_day_long_description` varchar(255)
,`scheduler_sound_device_id` int(11)
,`scheduler_sound_device_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_ask_to_change_reception`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_ask_to_change_reception`;
CREATE TABLE IF NOT EXISTS `cnt_ask_to_change_reception` (
`id` int(11)
,`created_at` timestamp
,`current_linked_user_id` int(11)
,`scheduler_id` int(11)
,`new_linked_user_id` int(11)
,`status` int(11)
,`comments` varchar(510)
,`updated_at` timestamp
,`current_linked_user_name` varchar(255)
,`new_linked_user_name` varchar(255)
,`status_name` varchar(255)
,`scheduler_date` timestamp
,`scheduler_day_long_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_ask_to_change_worship`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_ask_to_change_worship`;
CREATE TABLE IF NOT EXISTS `cnt_ask_to_change_worship` (
`id` int(11)
,`created_at` timestamp
,`current_linked_user_id` int(11)
,`scheduler_id` int(11)
,`new_linked_user_id` int(11)
,`status` int(11)
,`comments` varchar(510)
,`updated_at` timestamp
,`current_linked_user_name` varchar(255)
,`new_linked_user_name` varchar(255)
,`status_name` varchar(255)
,`scheduler_date` timestamp
,`scheduler_day_long_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_aux_worship_team_scheduler_lineup`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_aux_worship_team_scheduler_lineup`;
CREATE TABLE IF NOT EXISTS `cnt_aux_worship_team_scheduler_lineup` (
`id` int(11)
,`worship_team_scheduler_id` int(11)
,`worship_team_id` int(11)
,`created_at` timestamp
,`updated_at` timestamp
,`group_complete_names` mediumtext
,`group_names` mediumtext
,`user_id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_control_accepted_invite`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_control_accepted_invite`;
CREATE TABLE IF NOT EXISTS `cnt_control_accepted_invite` (
`id` int(11)
,`created_at` timestamp
,`scheduler_id` int(11)
,`soundteam_id` int(11)
,`message_id` varchar(255)
,`status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO')
,`timestamp_accepted` timestamp
,`updated_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`scheduler_date` timestamp
,`device` varchar(255)
,`day_long_description` varchar(255)
,`suggested_time` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_control_accepted_invite_all`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_control_accepted_invite_all`;
CREATE TABLE IF NOT EXISTS `cnt_control_accepted_invite_all` (
`id` int(11)
,`created_at` timestamp /* mariadb-5.3 */
,`scheduler_id` int(11)
,`team_id` int(11)
,`message_id` varchar(255)
,`status` varchar(9)
,`timestamp_accepted` timestamp /* mariadb-5.3 */
,`updated_at` timestamp /* mariadb-5.3 */
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`scheduler_date` timestamp /* mariadb-5.3 */
,`device` varchar(255)
,`day_long_description` varchar(255)
,`suggested_time` varchar(255)
,`team_type` varchar(13)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_control_accepted_invite_reception`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_control_accepted_invite_reception`;
CREATE TABLE IF NOT EXISTS `cnt_control_accepted_invite_reception` (
`id` int(11)
,`created_at` timestamp
,`scheduler_id` int(11)
,`receptionteam_id` int(11)
,`message_id` varchar(255)
,`status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO')
,`timestamp_accepted` timestamp
,`updated_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`scheduler_date` timestamp
,`day_long_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_control_accepted_invite_worship`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_control_accepted_invite_worship`;
CREATE TABLE IF NOT EXISTS `cnt_control_accepted_invite_worship` (
`id` int(11)
,`created_at` timestamp
,`scheduler_id` int(11)
,`worshipteam_id` int(11)
,`message_id` varchar(255)
,`status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO')
,`timestamp_accepted` timestamp
,`updated_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`scheduler_date` timestamp
,`day_long_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_control_access_token`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_control_access_token`;
CREATE TABLE IF NOT EXISTS `cnt_control_access_token` (
`id` int(11)
,`created_at` timestamp
,`token` varchar(1020)
,`id_user` int(11)
,`name_user` varchar(255)
,`email` varchar(255)
,`status_token` int(11)
,`expiration_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_departments`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_departments`;
CREATE TABLE IF NOT EXISTS `cnt_departments` (
`id` int(11)
,`created_at` timestamp
,`department` varchar(255)
,`department_director` varchar(255)
,`phone_number` varchar(255)
,`updated_at` timestamp
,`phone_number_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_elders`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_elders`;
CREATE TABLE IF NOT EXISTS `cnt_elders` (
`id` int(11)
,`created_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`updated_at` timestamp
,`phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_elder_for_department`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_elder_for_department`;
CREATE TABLE IF NOT EXISTS `cnt_elder_for_department` (
`id` int(11)
,`created_at` timestamp
,`department_id` int(11)
,`elder_id` int(11)
,`updated_at` timestamp
,`department_name` varchar(255)
,`department_director` varchar(255)
,`director_phone_number` varchar(255)
,`director_phone_number_mask` varchar(272)
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_elder_for_department_v2`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_elder_for_department_v2`;
CREATE TABLE IF NOT EXISTS `cnt_elder_for_department_v2` (
`elder_id` int(11)
,`elder_complete_name` varchar(255)
,`elder_name` varchar(255)
,`elder_phone` varchar(255)
,`elder_phone_mask` varchar(272)
,`department_ids` mediumtext
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_elder_month`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_elder_month`;
CREATE TABLE IF NOT EXISTS `cnt_elder_month` (
`id` int(11)
,`elder_id` int(11)
,`name` varchar(255)
,`month_id` int(11)
,`month` int(11)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year_id` int(11)
,`year` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_elder_month_v2`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_elder_month_v2`;
CREATE TABLE IF NOT EXISTS `cnt_elder_month_v2` (
`id` int(11)
,`elder_id` int(11)
,`name` mediumtext
,`month_id` int(11)
,`month` int(11)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year_id` int(11)
,`year` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_email_alarmes`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_email_alarmes`;
CREATE TABLE IF NOT EXISTS `cnt_email_alarmes` (
`id` int(11)
,`name` varchar(255)
,`email` varchar(255)
,`email_verified` int(11)
,`created_at` timestamp
,`updated_at` timestamp
,`status_id` int(11)
,`status` int(11)
,`description` varchar(255)
,`status_verified_id` int(11)
,`status_verified` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_events`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_events`;
CREATE TABLE IF NOT EXISTS `cnt_events` (
`id` bigint(11)
,`created_at` varchar(25)
,`original_created_at` timestamp
,`title` varchar(255)
,`description` varchar(255)
,`color` varchar(255)
,`start` varchar(25)
,`original_start` timestamp
,`month_start` varchar(2)
,`year_start` varchar(4)
,`end` varchar(25)
,`original_end` timestamp
,`day_of_week` int(11)
,`day_of_week_short_description` varchar(255)
,`day_of_week_long_description` varchar(255)
,`month` int(11)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`phone_mask` varchar(28)
,`contato` varchar(11)
,`hino_inicial` varchar(255)
,`hino_final` varchar(255)
,`status_id` int(11)
,`description_status` varchar(255)
,`status` varchar(255)
,`orador` varchar(255)
,`textColor` varchar(255)
,`department_id` int(11)
,`department` varchar(255)
,`program_id` int(11)
,`program` varchar(255)
,`observacoes` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_events_church`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_events_church`;
CREATE TABLE IF NOT EXISTS `cnt_events_church` (
`id` bigint(11)
,`created_at` varchar(25)
,`original_created_at` timestamp
,`title` varchar(255)
,`description` varchar(255)
,`color` varchar(255)
,`start` varchar(25)
,`original_start` timestamp
,`month_start` varchar(2)
,`year_start` varchar(4)
,`end` varchar(25)
,`original_end` timestamp
,`day_of_week` int(11)
,`day_of_week_short_description` varchar(255)
,`day_of_week_long_description` varchar(255)
,`month` int(11)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`phone_mask` varchar(28)
,`contato` varchar(11)
,`status_id` int(11)
,`description_status` varchar(255)
,`status` varchar(255)
,`owner` varchar(255)
,`textColor` varchar(255)
,`department_id` int(11)
,`department` varchar(255)
,`program_id` int(11)
,`program` varchar(255)
,`observacoes` varchar(255)
,`elder_id` int(11)
,`elder_complete_name` varchar(255)
,`elder_name` varchar(255)
,`elder_phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_events_status`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_events_status`;
CREATE TABLE IF NOT EXISTS `cnt_events_status` (
`id` int(11)
,`status` varchar(255)
,`created_at` timestamp
,`updated_at` timestamp
,`description` varchar(255)
,`color_id` int(11)
,`color` varchar(255)
,`text_color_id` int(11)
,`text_color` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_logs`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_logs`;
CREATE TABLE IF NOT EXISTS `cnt_logs` (
`id` int(11)
,`id_user` int(11)
,`application` varchar(255)
,`created_at` timestamp
,`data` mediumtext
,`token` varchar(255)
,`name` varchar(255)
,`login` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_modules`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_modules`;
CREATE TABLE IF NOT EXISTS `cnt_modules` (
`id` int(11)
,`created_at` timestamp
,`module` varchar(255)
,`label` varchar(255)
,`icon` varchar(255)
,`path_module` varchar(255)
,`updated_at` timestamp
,`type_id` int(11)
,`current` varchar(255)
,`allow_sysadmin` tinyint(1)
,`type` varchar(255)
,`description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_reception_team`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_reception_team`;
CREATE TABLE IF NOT EXISTS `cnt_reception_team` (
`id` int(11)
,`created_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`email` varchar(255)
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`updated_at` timestamp
,`phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_reception_team_schedule`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_reception_team_schedule`;
CREATE TABLE IF NOT EXISTS `cnt_reception_team_schedule` (
`id` int(11)
,`created_at` timestamp
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`scheduler_date` timestamp
,`reception_team_id` int(11)
,`updated_at` timestamp
,`day_id` int(11)
,`day_of_week` int(1)
,`day_short_description` varchar(255)
,`day_long_description` varchar(255)
,`day` int(3)
,`month` int(3)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year` int(5)
,`completed_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`phone_mask` varchar(272)
,`email` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_send_message_whatsapp`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_send_message_whatsapp`;
CREATE TABLE IF NOT EXISTS `cnt_send_message_whatsapp` (
`id` int(11)
,`created_at` timestamp
,`soundteam_id` int(11)
,`phone_number_sent` varchar(13)
,`message_id` varchar(255)
,`message_status` enum('accepted','sent','delivered','read')
,`timestamp_message` timestamp
,`payload` longtext
,`updated_at` timestamp
,`complete_name` varchar(255)
,`short_name` varchar(255)
,`phone_number` varchar(255)
,`linked_user_id` int(11)
,`linked_user_login` varchar(255)
,`linked_user_level` int(11)
,`linked_user_email` varchar(255)
,`linked_user_status_id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_send_message_whatsapp_all`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_send_message_whatsapp_all`;
CREATE TABLE IF NOT EXISTS `cnt_send_message_whatsapp_all` (
`id` int(11)
,`created_at` timestamp /* mariadb-5.3 */
,`team_id` int(11)
,`phone_number_sent` varchar(13)
,`message_id` varchar(255)
,`message_status` varchar(9)
,`timestamp_message` timestamp /* mariadb-5.3 */
,`payload` longtext
,`updated_at` timestamp /* mariadb-5.3 */
,`complete_name` varchar(255)
,`short_name` varchar(255)
,`phone_number` varchar(255)
,`linked_user_id` int(11)
,`linked_user_login` varchar(255)
,`linked_user_level` int(11)
,`linked_user_email` varchar(255)
,`linked_user_status_id` int(11)
,`team_type` varchar(13)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_send_message_whatsapp_reception`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_send_message_whatsapp_reception`;
CREATE TABLE IF NOT EXISTS `cnt_send_message_whatsapp_reception` (
`id` int(11)
,`created_at` timestamp
,`receptionteam_id` int(11)
,`phone_number_sent` varchar(13)
,`message_id` varchar(255)
,`message_status` enum('accepted','sent','delivered','read')
,`timestamp_message` timestamp
,`payload` longtext
,`updated_at` timestamp
,`complete_name` varchar(255)
,`short_name` varchar(255)
,`phone_number` varchar(255)
,`linked_user_id` int(11)
,`linked_user_login` varchar(255)
,`linked_user_level` int(11)
,`linked_user_email` varchar(255)
,`linked_user_status_id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_send_message_whatsapp_worship`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_send_message_whatsapp_worship`;
CREATE TABLE IF NOT EXISTS `cnt_send_message_whatsapp_worship` (
`id` int(11)
,`created_at` timestamp
,`worshipteam_id` int(11)
,`phone_number_sent` varchar(13)
,`message_id` varchar(255)
,`message_status` enum('accepted','sent','delivered','read')
,`timestamp_message` timestamp
,`payload` longtext
,`updated_at` timestamp
,`complete_name` varchar(255)
,`short_name` varchar(255)
,`phone_number` varchar(255)
,`linked_user_id` int(11)
,`linked_user_login` varchar(255)
,`linked_user_level` int(11)
,`linked_user_email` varchar(255)
,`linked_user_status_id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_settings_smtp`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_settings_smtp`;
CREATE TABLE IF NOT EXISTS `cnt_settings_smtp` (
`id` int(11)
,`created_at` timestamp
,`host` varchar(255)
,`port` int(11)
,`username` varchar(255)
,`password` varchar(255)
,`from_name` varchar(255)
,`id_apikey` int(11)
,`status_id` int(11)
,`status_description` varchar(255)
,`updated_at` timestamp
,`api_key` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_sound_team`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_sound_team`;
CREATE TABLE IF NOT EXISTS `cnt_sound_team` (
`id` int(11)
,`created_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`email` varchar(255)
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`updated_at` timestamp
,`phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_sound_team_schedule`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_sound_team_schedule`;
CREATE TABLE IF NOT EXISTS `cnt_sound_team_schedule` (
`id` int(11)
,`created_at` timestamp
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`scheduler_date` timestamp
,`sound_team_id` int(11)
,`sound_device_id` int(11)
,`updated_at` timestamp
,`suggested_time` varchar(255)
,`day_id` int(11)
,`day_of_week` int(1)
,`day_short_description` varchar(255)
,`day_long_description` varchar(255)
,`day` int(3)
,`month` int(3)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year` int(5)
,`completed_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`phone_mask` varchar(272)
,`email` varchar(255)
,`device` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_suggested_time`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_suggested_time`;
CREATE TABLE IF NOT EXISTS `cnt_suggested_time` (
`created_at` timestamp
,`id` int(11)
,`day_of_week_id` int(11)
,`suggested_time` varchar(255)
,`updated_at` timestamp
,`number_day` int(11)
,`short_description` varchar(255)
,`long_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_temp_users`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_temp_users`;
CREATE TABLE IF NOT EXISTS `cnt_temp_users` (
`id` int(11)
,`name` varchar(255)
,`login` varchar(255)
,`email` varchar(255)
,`user_id` int(11)
,`department_id` int(11)
,`department` varchar(255)
,`department_director` varchar(255)
,`phone_number` varchar(255)
,`phone_number_mask` varchar(272)
,`password` varchar(255)
,`created_at` timestamp
,`updated_at` timestamp
,`id_status` int(11)
,`status_user` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_users`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_users`;
CREATE TABLE IF NOT EXISTS `cnt_users` (
`id` int(11)
,`name` varchar(255)
,`login` varchar(255)
,`email` varchar(255)
,`password` varchar(255)
,`id_nivel` int(11)
,`access` int(11)
,`created_at` timestamp
,`updated_at` timestamp
,`level_description` varchar(255)
,`home_path` varchar(255)
,`id_status` int(11)
,`status_user` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_worship_team`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_worship_team`;
CREATE TABLE IF NOT EXISTS `cnt_worship_team` (
`id` int(11)
,`created_at` timestamp
,`complete_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`email` varchar(255)
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`updated_at` timestamp
,`phone_mask` varchar(272)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_worship_team_schedule`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_worship_team_schedule`;
CREATE TABLE IF NOT EXISTS `cnt_worship_team_schedule` (
`id` int(11)
,`created_at` timestamp
,`linked_user_id` int(11)
,`linked_user_name` varchar(255)
,`scheduler_date` timestamp
,`worship_team_id` int(11)
,`updated_at` timestamp
,`day_id` int(11)
,`day_of_week` int(1)
,`day_short_description` varchar(255)
,`day_long_description` varchar(255)
,`day` int(3)
,`month` int(3)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year` int(5)
,`completed_name` varchar(255)
,`name` varchar(255)
,`contato` varchar(255)
,`phone_mask` varchar(255)
,`email` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `cnt_worship_team_schedule_v2`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `cnt_worship_team_schedule_v2`;
CREATE TABLE IF NOT EXISTS `cnt_worship_team_schedule_v2` (
`id` int(11)
,`created_at` timestamp
,`scheduler_date` timestamp
,`updated_at` timestamp
,`day_id` int(11)
,`day_of_week` int(1)
,`day_short_description` varchar(255)
,`day_long_description` varchar(255)
,`day` int(3)
,`month` int(3)
,`month_short_description` varchar(255)
,`month_long_description` varchar(255)
,`year` int(5)
,`group_complete_names` mediumtext
,`group_names` mediumtext
,`group_singer_ids` mediumtext
,`group_singer_names` mediumtext
,`worship_music` varchar(1020)
,`singer_music` varchar(1020)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_access_level`
--

DROP TABLE IF EXISTS `tb_access_level`;
CREATE TABLE IF NOT EXISTS `tb_access_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `home_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_access_level`:
--

--
-- Despejando dados para a tabela `tb_access_level`
--

INSERT INTO `tb_access_level` (`id`, `level`, `description`, `created_at`, `updated_at`, `home_path`) VALUES
(-1, -1, 'sysadmin', '2023-06-06 12:57:43', '2023-12-18 18:35:26', '/application');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_access_modules`
--

DROP TABLE IF EXISTS `tb_access_modules`;
CREATE TABLE IF NOT EXISTS `tb_access_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `module_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `level_id` (`level_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_access_modules`:
--   `level_id`
--       `tb_access_level` -> `id`
--   `module_id`
--       `tb_modules` -> `id`
--

--
-- Despejando dados para a tabela `tb_access_modules`
--

INSERT INTO `tb_access_modules` (`id`, `created_at`, `module_id`, `level_id`, `allow`, `updated_at`) VALUES
(2909, '2025-02-11 00:16:37', 0, -1, 1, '2025-02-11 00:16:37'),
(2910, '2025-02-11 00:16:37', 1, -1, 1, '2025-02-11 00:16:37'),
(2911, '2025-02-11 00:16:37', 2, -1, 1, '2025-02-11 00:16:37'),
(2912, '2025-02-11 00:16:37', 3, -1, 1, '2025-02-11 00:16:37'),
(2913, '2025-02-11 00:16:37', 43, -1, 1, '2025-02-11 00:16:37'),
(2914, '2025-02-11 00:16:37', 44, -1, 1, '2025-02-11 00:16:37'),
(2915, '2025-02-11 00:16:37', 99, -1, 1, '2025-02-11 00:16:37'),
(2916, '2025-02-11 00:16:37', 103, -1, 1, '2025-02-11 00:16:37'),
(2917, '2025-02-11 00:16:37', 128, -1, 1, '2025-02-11 00:16:37'),
(2918, '2025-02-11 00:16:37', 132, -1, 1, '2025-02-11 00:16:37'),
(2919, '2025-02-11 00:16:37', 144, -1, 1, '2025-02-11 00:16:37'),
(2920, '2025-02-11 00:16:37', 4, -1, 1, '2025-02-11 00:16:37'),
(2921, '2025-02-11 00:16:37', 5, -1, 1, '2025-02-11 00:16:37'),
(2922, '2025-02-11 00:16:37', 6, -1, 1, '2025-02-11 00:16:37'),
(2923, '2025-02-11 00:16:37', 12, -1, 1, '2025-02-11 00:16:37'),
(2924, '2025-02-11 00:16:37', 17, -1, 1, '2025-02-11 00:16:37'),
(2925, '2025-02-11 00:16:37', 18, -1, 1, '2025-02-11 00:16:37'),
(2926, '2025-02-11 00:16:37', 19, -1, 1, '2025-02-11 00:16:37'),
(2927, '2025-02-11 00:16:37', 21, -1, 1, '2025-02-11 00:16:37'),
(2928, '2025-02-11 00:16:37', 22, -1, 1, '2025-02-11 00:16:37'),
(2929, '2025-02-11 00:16:37', 23, -1, 1, '2025-02-11 00:16:37'),
(2930, '2025-02-11 00:16:37', 29, -1, 1, '2025-02-11 00:16:37'),
(2931, '2025-02-11 00:16:37', 30, -1, 1, '2025-02-11 00:16:37'),
(2932, '2025-02-11 00:16:37', 31, -1, 1, '2025-02-11 00:16:37'),
(2933, '2025-02-11 00:16:37', 35, -1, 1, '2025-02-11 00:16:37'),
(2934, '2025-02-11 00:16:37', 36, -1, 1, '2025-02-11 00:16:37'),
(2935, '2025-02-11 00:16:37', 37, -1, 1, '2025-02-11 00:16:37'),
(2936, '2025-02-11 00:16:37', 40, -1, 1, '2025-02-11 00:16:37'),
(2937, '2025-02-11 00:16:37', 41, -1, 1, '2025-02-11 00:16:37'),
(2938, '2025-02-11 00:16:37', 42, -1, 1, '2025-02-11 00:16:37'),
(2939, '2025-02-11 00:16:37', 46, -1, 1, '2025-02-11 00:16:37'),
(2940, '2025-02-11 00:16:37', 52, -1, 1, '2025-02-11 00:16:37'),
(2941, '2025-02-11 00:16:37', 53, -1, 1, '2025-02-11 00:16:37'),
(2942, '2025-02-11 00:16:37', 54, -1, 1, '2025-02-11 00:16:37'),
(2943, '2025-02-11 00:16:37', 55, -1, 1, '2025-02-11 00:16:37'),
(2944, '2025-02-11 00:16:37', 56, -1, 1, '2025-02-11 00:16:37'),
(2945, '2025-02-11 00:16:37', 57, -1, 1, '2025-02-11 00:16:37'),
(2946, '2025-02-11 00:16:37', 58, -1, 1, '2025-02-11 00:16:37'),
(2947, '2025-02-11 00:16:37', 59, -1, 1, '2025-02-11 00:16:37'),
(2948, '2025-02-11 00:16:37', 60, -1, 1, '2025-02-11 00:16:37'),
(2949, '2025-02-11 00:16:37', 61, -1, 1, '2025-02-11 00:16:37'),
(2950, '2025-02-11 00:16:37', 62, -1, 1, '2025-02-11 00:16:37'),
(2951, '2025-02-11 00:16:37', 63, -1, 1, '2025-02-11 00:16:37'),
(2952, '2025-02-11 00:16:37', 64, -1, 1, '2025-02-11 00:16:37'),
(2953, '2025-02-11 00:16:37', 65, -1, 1, '2025-02-11 00:16:37'),
(2954, '2025-02-11 00:16:37', 66, -1, 1, '2025-02-11 00:16:37'),
(2955, '2025-02-11 00:16:37', 78, -1, 1, '2025-02-11 00:16:37'),
(2956, '2025-02-11 00:16:37', 79, -1, 1, '2025-02-11 00:16:37'),
(2957, '2025-02-11 00:16:37', 80, -1, 1, '2025-02-11 00:16:37'),
(2958, '2025-02-11 00:16:37', 81, -1, 1, '2025-02-11 00:16:37'),
(2959, '2025-02-11 00:16:37', 82, -1, 1, '2025-02-11 00:16:37'),
(2960, '2025-02-11 00:16:37', 83, -1, 1, '2025-02-11 00:16:37'),
(2961, '2025-02-11 00:16:37', 84, -1, 1, '2025-02-11 00:16:37'),
(2962, '2025-02-11 00:16:37', 85, -1, 1, '2025-02-11 00:16:37'),
(2963, '2025-02-11 00:16:37', 86, -1, 1, '2025-02-11 00:16:37'),
(2964, '2025-02-11 00:16:37', 87, -1, 1, '2025-02-11 00:16:37'),
(2965, '2025-02-11 00:16:37', 88, -1, 1, '2025-02-11 00:16:37'),
(2966, '2025-02-11 00:16:37', 89, -1, 1, '2025-02-11 00:16:37'),
(2967, '2025-02-11 00:16:37', 91, -1, 1, '2025-02-11 00:16:37'),
(2968, '2025-02-11 00:16:37', 98, -1, 1, '2025-02-11 00:16:37'),
(2969, '2025-02-11 00:16:37', 105, -1, 1, '2025-02-11 00:16:37'),
(2970, '2025-02-11 00:16:37', 107, -1, 1, '2025-02-11 00:16:37'),
(2971, '2025-02-11 00:16:37', 108, -1, 1, '2025-02-11 00:16:37'),
(2972, '2025-02-11 00:16:37', 109, -1, 1, '2025-02-11 00:16:37'),
(2973, '2025-02-11 00:16:37', 112, -1, 1, '2025-02-11 00:16:37'),
(2974, '2025-02-11 00:16:37', 113, -1, 1, '2025-02-11 00:16:37'),
(2975, '2025-02-11 00:16:37', 114, -1, 1, '2025-02-11 00:16:37'),
(2976, '2025-02-11 00:16:37', 119, -1, 1, '2025-02-11 00:16:37'),
(2977, '2025-02-11 00:16:37', 120, -1, 1, '2025-02-11 00:16:37'),
(2978, '2025-02-11 00:16:37', 121, -1, 1, '2025-02-11 00:16:37'),
(2979, '2025-02-11 00:16:37', 124, -1, 1, '2025-02-11 00:16:37'),
(2980, '2025-02-11 00:16:37', 125, -1, 1, '2025-02-11 00:16:37'),
(2981, '2025-02-11 00:16:37', 126, -1, 1, '2025-02-11 00:16:37'),
(2982, '2025-02-11 00:16:37', 137, -1, 1, '2025-02-11 00:16:37'),
(2983, '2025-02-11 00:16:37', 138, -1, 1, '2025-02-11 00:16:37'),
(2984, '2025-02-11 00:16:37', 139, -1, 1, '2025-02-11 00:16:37'),
(2985, '2025-02-11 00:16:37', 140, -1, 1, '2025-02-11 00:16:37'),
(2986, '2025-02-11 00:16:37', 141, -1, 1, '2025-02-11 00:16:37'),
(2987, '2025-02-11 00:16:37', 142, -1, 1, '2025-02-11 00:16:37'),
(2988, '2025-02-11 00:16:37', 149, -1, 1, '2025-02-11 00:16:37'),
(2989, '2025-02-11 00:16:37', 150, -1, 1, '2025-02-11 00:16:37'),
(2990, '2025-02-11 00:16:37', 151, -1, 1, '2025-02-11 00:16:37'),
(2991, '2025-02-11 00:16:37', 152, -1, 1, '2025-02-11 00:16:37'),
(2992, '2025-02-11 00:16:37', 153, -1, 1, '2025-02-11 00:16:37'),
(2993, '2025-02-11 00:16:37', 154, -1, 1, '2025-02-11 00:16:37'),
(2994, '2025-02-11 00:16:37', 7, -1, 1, '2025-02-11 00:16:37'),
(2995, '2025-02-11 00:16:37', 8, -1, 1, '2025-02-11 00:16:37'),
(2996, '2025-02-11 00:16:37', 10, -1, 1, '2025-02-11 00:16:37'),
(2997, '2025-02-11 00:16:37', 11, -1, 1, '2025-02-11 00:16:37'),
(2998, '2025-02-11 00:16:37', 20, -1, 1, '2025-02-11 00:16:37'),
(2999, '2025-02-11 00:16:37', 24, -1, 1, '2025-02-11 00:16:37'),
(3000, '2025-02-11 00:16:37', 25, -1, 1, '2025-02-11 00:16:37'),
(3001, '2025-02-11 00:16:37', 26, -1, 1, '2025-02-11 00:16:37'),
(3002, '2025-02-11 00:16:37', 32, -1, 1, '2025-02-11 00:16:37'),
(3003, '2025-02-11 00:16:37', 34, -1, 1, '2025-02-11 00:16:37'),
(3004, '2025-02-11 00:16:37', 39, -1, 1, '2025-02-11 00:16:37'),
(3005, '2025-02-11 00:16:37', 45, -1, 1, '2025-02-11 00:16:37'),
(3006, '2025-02-11 00:16:37', 67, -1, 1, '2025-02-11 00:16:37'),
(3007, '2025-02-11 00:16:37', 68, -1, 1, '2025-02-11 00:16:37'),
(3008, '2025-02-11 00:16:37', 69, -1, 1, '2025-02-11 00:16:37'),
(3009, '2025-02-11 00:16:37', 70, -1, 1, '2025-02-11 00:16:37'),
(3010, '2025-02-11 00:16:37', 71, -1, 1, '2025-02-11 00:16:37'),
(3011, '2025-02-11 00:16:37', 90, -1, 1, '2025-02-11 00:16:37'),
(3012, '2025-02-11 00:16:37', 92, -1, 1, '2025-02-11 00:16:37'),
(3013, '2025-02-11 00:16:37', 93, -1, 1, '2025-02-11 00:16:37'),
(3014, '2025-02-11 00:16:37', 94, -1, 1, '2025-02-11 00:16:37'),
(3015, '2025-02-11 00:16:37', 95, -1, 1, '2025-02-11 00:16:37'),
(3016, '2025-02-11 00:16:37', 97, -1, 1, '2025-02-11 00:16:37'),
(3017, '2025-02-11 00:16:37', 100, -1, 1, '2025-02-11 00:16:37'),
(3018, '2025-02-11 00:16:37', 104, -1, 1, '2025-02-11 00:16:37'),
(3019, '2025-02-11 00:16:37', 110, -1, 1, '2025-02-11 00:16:37'),
(3020, '2025-02-11 00:16:37', 115, -1, 1, '2025-02-11 00:16:37'),
(3021, '2025-02-11 00:16:37', 116, -1, 1, '2025-02-11 00:16:37'),
(3022, '2025-02-11 00:16:37', 117, -1, 1, '2025-02-11 00:16:37'),
(3023, '2025-02-11 00:16:37', 122, -1, 1, '2025-02-11 00:16:37'),
(3024, '2025-02-11 00:16:37', 127, -1, 1, '2025-02-11 00:16:37'),
(3025, '2025-02-11 00:16:37', 131, -1, 1, '2025-02-11 00:16:37'),
(3026, '2025-02-11 00:16:37', 136, -1, 1, '2025-02-11 00:16:37'),
(3027, '2025-02-11 00:16:37', 143, -1, 1, '2025-02-11 00:16:37'),
(3028, '2025-02-11 00:16:37', 148, -1, 1, '2025-02-11 00:16:37'),
(3029, '2025-02-11 00:16:37', 155, -1, 1, '2025-02-11 00:16:37'),
(3030, '2025-02-11 00:16:37', 47, -1, 1, '2025-02-11 00:16:37'),
(3031, '2025-02-11 00:16:37', 48, -1, 1, '2025-02-11 00:16:37'),
(3032, '2025-02-11 00:16:37', 49, -1, 1, '2025-02-11 00:16:37'),
(3033, '2025-02-11 00:16:37', 50, -1, 1, '2025-02-11 00:16:37'),
(3034, '2025-02-11 00:16:37', 51, -1, 1, '2025-02-11 00:16:37'),
(3035, '2025-02-11 00:16:37', 106, -1, 1, '2025-02-11 00:16:37'),
(3036, '2025-02-11 00:16:37', 13, -1, 1, '2025-02-11 00:16:37'),
(3037, '2025-02-11 00:16:37', 14, -1, 1, '2025-02-11 00:16:37'),
(3038, '2025-02-11 00:16:37', 15, -1, 1, '2025-02-11 00:16:37'),
(3039, '2025-02-11 00:16:37', 16, -1, 1, '2025-02-11 00:16:37'),
(3040, '2025-02-11 00:16:37', 27, -1, 1, '2025-02-11 00:16:37'),
(3041, '2025-02-11 00:16:37', 28, -1, 1, '2025-02-11 00:16:37'),
(3042, '2025-02-11 00:16:37', 33, -1, 1, '2025-02-11 00:16:37'),
(3043, '2025-02-11 00:16:37', 96, -1, 1, '2025-02-11 00:16:37'),
(3044, '2025-02-11 00:16:37', 111, -1, 1, '2025-02-11 00:16:37'),
(3045, '2025-02-11 00:16:37', 118, -1, 1, '2025-02-11 00:16:37'),
(3046, '2025-02-11 00:16:37', 123, -1, 1, '2025-02-11 00:16:37'),
(3047, '2025-02-11 00:16:37', 72, -1, 1, '2025-02-11 00:16:37'),
(3048, '2025-02-11 00:16:37', 73, -1, 1, '2025-02-11 00:16:37'),
(3049, '2025-02-11 00:16:37', 74, -1, 1, '2025-02-11 00:16:37'),
(3050, '2025-02-11 00:16:37', 75, -1, 1, '2025-02-11 00:16:37'),
(3051, '2025-02-11 00:16:37', 76, -1, 1, '2025-02-11 00:16:37'),
(3052, '2025-02-11 00:16:37', 77, -1, 1, '2025-02-11 00:16:37'),
(3053, '2025-02-11 00:16:37', 101, -1, 1, '2025-02-11 00:16:37'),
(3054, '2025-02-11 00:16:37', 102, -1, 1, '2025-02-11 00:16:37'),
(3055, '2025-02-11 00:16:37', 129, -1, 1, '2025-02-11 00:16:37'),
(3056, '2025-02-11 00:16:37', 130, -1, 1, '2025-02-11 00:16:37'),
(3057, '2025-02-11 00:16:37', 133, -1, 1, '2025-02-11 00:16:37'),
(3058, '2025-02-11 00:16:37', 134, -1, 1, '2025-02-11 00:16:37'),
(3059, '2025-02-11 00:16:37', 135, -1, 1, '2025-02-11 00:16:37'),
(3060, '2025-02-11 00:16:37', 145, -1, 1, '2025-02-11 00:16:37'),
(3061, '2025-02-11 00:16:37', 146, -1, 1, '2025-02-11 00:16:37'),
(3062, '2025-02-11 00:16:37', 147, -1, 1, '2025-02-11 00:16:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_access_token_whatsapp`
--

DROP TABLE IF EXISTS `tb_access_token_whatsapp`;
CREATE TABLE IF NOT EXISTS `tb_access_token_whatsapp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `business_phone_number_id` bigint(20) NOT NULL,
  `graph_api_token` varchar(1020) NOT NULL,
  `expiration_at` timestamp NULL DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_status_id_token_whatsapp` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_access_token_whatsapp`:
--   `status_id`
--       `tb_status_token` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_active_account_users`
--

DROP TABLE IF EXISTS `tb_active_account_users`;
CREATE TABLE IF NOT EXISTS `tb_active_account_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `token` varchar(1020) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiration_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_token` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status_token` (`status_token`),
  KEY `fk_id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_active_account_users`:
--   `id_user`
--       `tb_users` -> `id`
--   `status_token`
--       `tb_status_token` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_apis`
--

DROP TABLE IF EXISTS `tb_apis`;
CREATE TABLE IF NOT EXISTS `tb_apis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `api_key` varchar(255) NOT NULL,
  `api_name` varchar(255) NOT NULL,
  `api_description` varchar(255) DEFAULT NULL,
  `api_path` varchar(255) DEFAULT NULL,
  `active` int(1) NOT NULL,
  `status_id` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_status_id_api` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_apis`:
--   `status_id`
--       `tb_status_apikey` -> `id`
--   `user_id`
--       `tb_users` -> `id`
--

--
-- Despejando dados para a tabela `tb_apis`
--

INSERT INTO `tb_apis` (`id`, `created_at`, `user_id`, `api_key`, `api_name`, `api_description`, `api_path`, `active`, `status_id`, `updated_at`) VALUES
(1, '2023-06-22 10:33:22', -1, 'cbde0f8d35b6443168614339a04fd739', 'Api Auth Token', 'Apikey usada para gerar o token JWE', '/api/v1/auth', 1, 1, NULL),
(2, '2023-06-22 17:46:56', -1, 'bSf9Dp6gqZuxHXBjJW4hW9vK8VX2u', 'Apikey Active Account', 'Apikey usada exclusivamante para ativação de conta', '/email/active-account', 1, 1, '2023-12-21 14:44:29'),
(3, '2023-06-22 10:33:22', -1, 'cbde0f8d35b6443168614339a04fd739', 'Apikey Personal Finance Tracker', 'Apikey usada consumir a rota que retorna as informações da API', '/api/v1', 1, 1, NULL),
(4, '2023-06-22 10:33:22', -1, 'cbde0f8d35b6443168614339a04fd739', 'Testes', 'Apikey testes', '/api/v1/email/active-account', 1, 1, NULL),
(5, '2023-08-14 21:21:44', -1, 'S5BZ9w67GCimUprQzStF8XjWiCQhlnhB', 'Apikey Orders', 'Apikey Orders', '/api/v1/orders', 1, 1, NULL),
(6, '2023-06-22 10:33:22', -1, 'cbde0f8d35b6443168614339a04fd739', 'Api Auth Token', 'Apikey usada para validar o token JWE', '/api/v1/check', 1, 1, NULL),
(8, '2024-09-02 18:28:01', -1, '6c03388a1bd3c1f8d9d4a8750cebd829', 'Webhook WhatsApp', 'Webhook WhatsApp', '/api/v1/whatsapp/webhook', 1, 1, NULL),
(9, '2025-01-03 18:38:11', -1, 'bSf9Dp6gqZuxHXBjJW4hW9vK8VX2u', 'Account E-mail', 'APIKEY usanda exclusivamente para conta de envios de e-mail', '/email', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_ask_to_change`
--

DROP TABLE IF EXISTS `tb_ask_to_change`;
CREATE TABLE IF NOT EXISTS `tb_ask_to_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `current_linked_user_id` int(11) NOT NULL,
  `scheduler_id` int(11) NOT NULL,
  `new_linked_user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comments` varchar(510) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_current_user_id` (`current_linked_user_id`),
  KEY `fk_new_user_id` (`new_linked_user_id`),
  KEY `fk_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_ask_to_change`:
--   `current_linked_user_id`
--       `tb_sound_team` -> `id`
--   `new_linked_user_id`
--       `tb_sound_team` -> `id`
--   `status`
--       `tb_status_ask_to_change` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_aux_worship_team_scheduler`
--

DROP TABLE IF EXISTS `tb_aux_worship_team_scheduler`;
CREATE TABLE IF NOT EXISTS `tb_aux_worship_team_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worship_team_scheduler_id` int(11) NOT NULL,
  `worship_music` varchar(1020) DEFAULT NULL,
  `singer_music` varchar(1020) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_worship_team_scheduler_id` (`worship_team_scheduler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_aux_worship_team_scheduler`:
--   `worship_team_scheduler_id`
--       `tb_worship_team_schedule` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_aux_worship_team_scheduler_lineup`
--

DROP TABLE IF EXISTS `tb_aux_worship_team_scheduler_lineup`;
CREATE TABLE IF NOT EXISTS `tb_aux_worship_team_scheduler_lineup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worship_team_scheduler_id` int(11) NOT NULL,
  `worship_team_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_aux_worship_team_id` (`worship_team_id`),
  KEY `fk_aux_worship_team_scheduler_id` (`worship_team_scheduler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_aux_worship_team_scheduler_lineup`:
--   `worship_team_id`
--       `tb_worship_team` -> `id`
--   `worship_team_scheduler_id`
--       `tb_worship_team_schedule` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_churches`
--

DROP TABLE IF EXISTS `tb_churches`;
CREATE TABLE IF NOT EXISTS `tb_churches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shepherd` varchar(255) DEFAULT NULL,
  `unique_name` varchar(255) NOT NULL,
  `church_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `number` int(11) DEFAULT NULL,
  `neighborhood` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_churches`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_color`
--

DROP TABLE IF EXISTS `tb_color`;
CREATE TABLE IF NOT EXISTS `tb_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_color`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_control_accepted_invite`
--

DROP TABLE IF EXISTS `tb_control_accepted_invite`;
CREATE TABLE IF NOT EXISTS `tb_control_accepted_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_id` int(11) NOT NULL,
  `soundteam_id` int(11) NOT NULL,
  `message_id` varchar(255) NOT NULL,
  `status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO') NOT NULL,
  `timestamp_accepted` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_id` (`message_id`),
  KEY `fk_accepted_invite_whatsapp` (`soundteam_id`),
  KEY `fk_accepted_invite_scheduler_id` (`scheduler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=270 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_control_accepted_invite`:
--   `scheduler_id`
--       `tb_sound_team_schedule` -> `id`
--   `soundteam_id`
--       `tb_sound_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_control_accepted_invite_reception`
--

DROP TABLE IF EXISTS `tb_control_accepted_invite_reception`;
CREATE TABLE IF NOT EXISTS `tb_control_accepted_invite_reception` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_id` int(11) NOT NULL,
  `receptionteam_id` int(11) NOT NULL,
  `message_id` varchar(255) NOT NULL,
  `status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO') NOT NULL,
  `timestamp_accepted` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_reception_accepted_invite_whatsapp` (`receptionteam_id`),
  KEY `fk_reception_accepted_invite_scheduler_id` (`scheduler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_control_accepted_invite_reception`:
--   `scheduler_id`
--       `tb_reception_team_schedule` -> `id`
--   `receptionteam_id`
--       `tb_reception_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_control_accepted_invite_worship`
--

DROP TABLE IF EXISTS `tb_control_accepted_invite_worship`;
CREATE TABLE IF NOT EXISTS `tb_control_accepted_invite_worship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_id` int(11) NOT NULL,
  `worshipteam_id` int(11) NOT NULL,
  `message_id` varchar(255) NOT NULL,
  `status` enum('PENDENTE','EXPIRADO','ACEITO','REJEITADO') NOT NULL,
  `timestamp_accepted` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_worship_accepted_invite_whatsapp` (`worshipteam_id`),
  KEY `fk_worship_accepted_invite_scheduler_id` (`scheduler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_control_accepted_invite_worship`:
--   `scheduler_id`
--       `tb_worship_team_schedule` -> `id`
--   `worshipteam_id`
--       `tb_worship_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_control_access_token`
--

DROP TABLE IF EXISTS `tb_control_access_token`;
CREATE TABLE IF NOT EXISTS `tb_control_access_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `token` varchar(1020) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiration_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_token` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_access_token_status_token` (`status_token`),
  KEY `fk_access_token_id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_control_access_token`:
--   `id_user`
--       `tb_users` -> `id`
--   `status_token`
--       `tb_status_token` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_control_token`
--

DROP TABLE IF EXISTS `tb_control_token`;
CREATE TABLE IF NOT EXISTS `tb_control_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encrypted_key` varchar(510) NOT NULL,
  `hash_token` varchar(255) NOT NULL,
  `token` varchar(510) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_control_token`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_days_of_week`
--

DROP TABLE IF EXISTS `tb_days_of_week`;
CREATE TABLE IF NOT EXISTS `tb_days_of_week` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number_day` int(11) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `long_description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `number_day` (`number_day`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_days_of_week`:
--

--
-- Despejando dados para a tabela `tb_days_of_week`
--

INSERT INTO `tb_days_of_week` (`id`, `number_day`, `short_description`, `long_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dom', 'Domingo', '2023-05-30 15:28:27', '2023-05-30 11:30:38'),
(2, 2, 'Seg', 'Segunda-Feira', '2023-05-30 15:30:26', '2023-05-30 12:29:43'),
(3, 3, 'Ter', 'Terça-Feira', '2023-05-30 15:30:26', '2023-05-30 12:34:48'),
(4, 4, 'Qua', 'Quarta-Feira', '2023-05-30 15:30:26', '2023-05-30 12:33:52'),
(5, 5, 'Qui', 'Quinta-Feira', '2023-05-30 15:30:26', '2023-05-30 12:27:56'),
(6, 6, 'Sex', 'Sexta-Feira', '2023-05-30 15:30:26', '2023-05-30 12:23:59'),
(7, 7, 'Sab', 'Sábado', '2023-05-30 15:30:26', '2023-05-30 12:22:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_departments`
--

DROP TABLE IF EXISTS `tb_departments`;
CREATE TABLE IF NOT EXISTS `tb_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `department` varchar(255) NOT NULL,
  `department_director` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_departments`:
--

--
-- Despejando dados para a tabela `tb_departments`
--

INSERT INTO `tb_departments` (`id`, `created_at`, `department`, `department_director`, `phone_number`, `updated_at`) VALUES
(0, '2024-01-23 18:01:36', 'Selecione o departamento...', NULL, NULL, '2024-01-23 21:02:05'),
(1, '2023-02-27 00:00:00', '-', 'Não atribuido', '11999999999', '2024-01-14 14:34:17'),
(2, '2023-02-27 00:00:00', 'TESOURARIA', 'Elias Evaristo', '11984029695', '2024-01-12 20:19:34'),
(3, '2023-02-27 00:00:00', 'ASA', 'Danielle Aparecida', '11913576681', '2024-01-12 00:09:59'),
(4, '2023-02-27 00:00:00', 'MORDOMIA', 'Loudes Silva', '11969521890', '2024-01-12 00:09:59'),
(5, '2023-02-27 00:00:00', 'MINISTÉRIO PESSOAL', 'Antonio Marcos', '11983059236', '2024-01-12 00:09:59'),
(7, '2023-02-27 00:00:00', 'MINISTÉRIO DA FAMÍLIA', 'Rosana Reis e Marcio Reis', '11986129408', '2024-01-12 00:09:59'),
(8, '2023-02-27 00:00:00', 'MINISTÉRIO DA MULHER', 'Maria do Céu', '11983028466', '2024-01-12 00:09:59'),
(9, '2023-02-27 00:00:00', 'MINISTÉRIO DO IDOSO', 'Maruluce Barbosa', '11966894151', '2024-01-12 00:09:59'),
(10, '2023-02-27 00:00:00', 'ESCOLA SABATINA', 'Zélia Henrique', '11961104379', '2024-01-12 00:09:59'),
(11, '2023-02-27 00:00:00', 'MINISTÉRIO DA MÚSICA', 'Lara Milena', '11977963318', '2024-01-12 00:09:59'),
(12, '2023-02-27 00:00:00', 'MINISTÉRIO DA CRIANÇA', 'Analice Gomes', '11959014746', '2024-01-12 00:09:59'),
(13, '2023-02-27 00:00:00', 'AVENTUREIROS', 'Daniela Barbosa', '11959401967', '2024-01-12 00:09:59'),
(14, '2023-02-27 00:00:00', 'MINISTÉRIO DA SAÚDE', 'Emilly Samelo', '31995062211', '2024-01-12 00:09:59'),
(15, '2023-02-27 00:00:00', 'MINISTÉRIO JOVEM', 'Eduardo Chaves', '11934429937', '2024-01-12 00:09:59'),
(16, '2023-02-27 00:00:00', 'DESBRAVADORES', 'Diego Paiva', '11967000741', '2024-01-12 00:09:59'),
(17, '2023-02-27 00:00:00', 'LIBERDADE RELIGIOSA', 'José Alves', '11981054601', '2024-01-12 00:09:59'),
(18, '2023-02-27 00:00:00', 'MINISTÉRIO DO HOMEM', 'Leornardo Carvalho', '11972788785', '2024-01-12 00:09:59'),
(19, '2023-02-27 00:00:00', 'COMUNICAÇÃO', 'Roberto Eisenhut', '11998382694', '2024-01-12 00:09:59'),
(20, '2023-02-27 00:00:00', 'RECEPÇÃO', 'Lourdes Silva', '11969521890', '2024-01-12 00:09:59'),
(21, '2023-02-27 00:00:00', 'SOM, VIDEO E INTERNET', 'Walter Moura', '11968180824', '2024-01-12 20:26:38'),
(22, '2024-01-03 18:50:57', 'ANCIONATO', 'Pr Ismael Kauffman', '11995172618', '2024-01-12 00:09:59'),
(23, '2024-01-03 18:53:25', 'SECRETARIA', 'Daniela Barbosa', '11959401967', '2024-01-12 00:09:59'),
(24, '2024-01-03 18:54:04', 'DIACONISA', 'Adinoelia', '11994722118', '2024-01-12 00:09:59'),
(26, '2024-01-03 18:58:47', 'MINISTÉRIO DOS ADOLECENTES', 'Rosana Reis', '11986129408', '2024-01-12 00:10:08'),
(27, '2024-01-03 18:59:41', 'EVANGELISMO PÚBLICO', 'Antonio Marcos', '11983059236', '2024-01-12 00:10:26'),
(28, '2024-01-03 19:00:13', 'COORDENADOR DE INTERASSADOS', 'Lourdes Silva', '11969521890', '2024-01-12 00:10:26'),
(29, '2024-01-03 19:00:53', 'COORDERNAÇÃO ESCOLA BIBLICA', 'Antonio Marcos', '11983059236', '2024-01-12 00:10:26'),
(30, '2024-01-03 19:02:44', 'PUBLICAÇÕES E ESPÍRITO DE PROFECIA', 'José Neris', '11989316467', '2024-01-12 00:10:26'),
(31, '2024-01-03 19:04:09', 'MINISTÉRIO EM FAVOR DAS PESSOAS COM DEFICIÊNCIA', 'Francisco Cleivan', NULL, '2024-01-11 23:19:01'),
(32, '2024-01-12 20:22:46', 'DIACONATO', 'Francico', '', '2024-01-12 20:22:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_elder`
--

DROP TABLE IF EXISTS `tb_elder`;
CREATE TABLE IF NOT EXISTS `tb_elder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fkc_linked_user_id_elder` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_elder`:
--   `user_id`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_elder_for_department`
--

DROP TABLE IF EXISTS `tb_elder_for_department`;
CREATE TABLE IF NOT EXISTS `tb_elder_for_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `department_id` int(11) NOT NULL,
  `elder_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_dp_id` (`department_id`),
  KEY `fk_dp_elder` (`elder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_elder_for_department`:
--   `elder_id`
--       `tb_elder` -> `id`
--   `department_id`
--       `tb_departments` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_elder_month`
--

DROP TABLE IF EXISTS `tb_elder_month`;
CREATE TABLE IF NOT EXISTS `tb_elder_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `elder_id` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `month_id` (`month_id`),
  KEY `year_id` (`year_id`),
  KEY `elder_id` (`elder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_elder_month`:
--   `elder_id`
--       `tb_elder` -> `id`
--   `month_id`
--       `tb_month` -> `id`
--   `year_id`
--       `tb_years` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_email_alarmes`
--

DROP TABLE IF EXISTS `tb_email_alarmes`;
CREATE TABLE IF NOT EXISTS `tb_email_alarmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `email_verified` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_status_email` (`status`),
  KEY `id_status_verified` (`email_verified`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_email_alarmes`:
--   `status`
--       `tb_status_email` -> `id`
--   `email_verified`
--       `tb_status_token` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_events`
--

DROP TABLE IF EXISTS `tb_events`;
CREATE TABLE IF NOT EXISTS `tb_events` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contato` varchar(11) NOT NULL,
  `hino_inicial` varchar(255) NOT NULL,
  `hino_final` varchar(255) NOT NULL,
  `status_id` int(11) NOT NULL,
  `orador` varchar(255) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  KEY `department_id` (`department_id`),
  KEY `program_id` (`program_id`)
) ENGINE=InnoDB AUTO_INCREMENT=566 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_events`:
--   `department_id`
--       `tb_departments` -> `id`
--   `program_id`
--       `tb_programs` -> `id`
--   `status_id`
--       `tb_events_status` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_events_church`
--

DROP TABLE IF EXISTS `tb_events_church`;
CREATE TABLE IF NOT EXISTS `tb_events_church` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contato` varchar(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  KEY `department_id` (`department_id`),
  KEY `program_id` (`program_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_events_church`:
--   `department_id`
--       `tb_departments` -> `id`
--   `program_id`
--       `tb_programs` -> `id`
--   `status_id`
--       `tb_events_status` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_events_status`
--

DROP TABLE IF EXISTS `tb_events_status`;
CREATE TABLE IF NOT EXISTS `tb_events_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `description` varchar(255) NOT NULL,
  `color_id` int(11) NOT NULL,
  `text_color_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_event` (`status`),
  KEY `color_id` (`color_id`),
  KEY `text_color_id` (`text_color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_events_status`:
--   `color_id`
--       `tb_color` -> `id`
--   `text_color_id`
--       `tb_text_color` -> `id`
--

--
-- Despejando dados para a tabela `tb_events_status`
--

INSERT INTO `tb_events_status` (`id`, `status`, `created_at`, `updated_at`, `description`, `color_id`, `text_color_id`) VALUES
(1, 'PENDENTE_CONFIRMAR', '2023-02-20 14:20:21', '2023-12-19 20:37:02', 'PENDENTE CONFIRMAR', 1, 10),
(2, 'AGENDADO', '2023-02-20 14:20:21', '2023-02-27 20:48:52', 'AGENDADO', 6, 9),
(3, 'CONFIRMADO', '2023-02-20 14:20:53', '2023-02-27 20:48:56', 'CONFIRMADO', 2, 9),
(4, 'CANCELADO', '2023-05-27 22:47:16', '0000-00-00 00:00:00', 'CANCELADO', 3, 9),
(6, 'EM_ABERTO', '2024-01-12 13:36:58', '0000-00-00 00:00:00', 'EM ABERTO', 8, 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_log`
--

DROP TABLE IF EXISTS `tb_log`;
CREATE TABLE IF NOT EXISTS `tb_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `application` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `data` mediumtext DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=133521 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_log`:
--   `id_user`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_modules`
--

DROP TABLE IF EXISTS `tb_modules`;
CREATE TABLE IF NOT EXISTS `tb_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `module` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `path_module` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type_id` int(11) NOT NULL,
  `allow_sysadmin` tinyint(1) NOT NULL DEFAULT 0,
  `current` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_modules`:
--   `type_id`
--       `tb_type_module` -> `id`
--

--
-- Despejando dados para a tabela `tb_modules`
--

INSERT INTO `tb_modules` (`id`, `created_at`, `module`, `label`, `icon`, `path_module`, `updated_at`, `type_id`, `allow_sysadmin`, `current`) VALUES
(0, '2023-03-27 11:58:15', 'home', 'Home', 'fas  fa-home', '/home', '2024-01-15 19:02:24', 1, 0, NULL),
(1, '2023-03-27 11:59:53', 'dashboards', 'Dashboards', 'fas fa-chart-line', '/dashboards', '2024-01-15 19:02:20', 1, 0, NULL),
(2, '2023-08-08 19:57:24', 'event', 'Agenda', 'fas  fa-calendar-alt', '/event', '2023-12-19 19:09:51', 1, 0, NULL),
(3, '2024-01-03 14:23:18', 'events-church', 'Agenda Departamentos', 'fas fa-calendar-minus', '/events-church', '2024-01-15 19:13:09', 1, 0, NULL),
(4, '2023-03-27 11:59:53', 'btn-cadastrar-user', 'Cadastrar Usuários', 'null', 'null', '2023-03-28 00:28:15', 2, 0, NULL),
(5, '2023-03-27 11:59:53', 'btn-edit-user', 'Editar Usuário', 'null', 'null', '2023-03-28 00:28:15', 2, 0, NULL),
(6, '2023-03-27 11:59:53', 'btn-delete-user', 'Excluir Usuário', 'null', 'null', '2023-03-28 00:28:15', 2, 0, NULL),
(7, '2023-03-27 11:59:53', 'page-manager-user', 'Página Gestão Usuários', 'null', 'null', '2023-03-28 00:28:15', 3, 0, NULL),
(8, '2023-03-27 11:59:53', 'page-config-event', 'Página Configuração Eventos', 'null', 'null', '2023-03-28 00:28:15', 3, 0, NULL),
(10, '2023-03-28 23:33:27', 'page-advanced-settings', 'Página Configurações Avançadas', 'null', 'null', '2023-06-21 01:17:02', 3, 0, NULL),
(11, '2023-03-31 00:12:26', 'page-home', 'Home', 'null', 'null', '2023-06-23 21:11:58', 3, 0, NULL),
(12, '2023-05-09 22:31:57', 'btn-update-password', 'Alterar Senha', 'null', 'null', '2023-05-09 22:31:57', 2, 0, NULL),
(13, '2023-06-05 22:18:34', 'level', 'Nível', 'null', '#dadosNivel', '2023-06-05 22:19:27', 5, 0, NULL),
(14, '2023-06-05 22:20:18', 'type-module', 'Tipo Módulos', 'null', '#dadosTypeModules', '2023-06-05 23:19:56', 5, 1, NULL),
(15, '2023-06-05 22:21:14', 'modules', 'Módulos', 'null', '#dadosModules', '2023-06-05 23:20:14', 5, 1, NULL),
(16, '2023-06-05 22:22:46', 'access-modules', 'Associar Módulos', 'null', '#dadosAccessModules', '2023-06-06 00:23:13', 5, 0, NULL),
(17, '2023-06-05 22:27:25', 'btn-edit-level', 'Editar Nivel', 'null', 'null', '2023-06-05 22:27:25', 2, 0, NULL),
(18, '2023-06-05 22:28:07', 'btn-delete-level', 'Excluir Nivel', 'null', 'null', '2023-06-05 22:28:07', 2, 0, NULL),
(19, '2023-06-05 22:29:18', 'btn-create-level', 'Cadastrar Nível', 'null', 'null', '2023-06-05 22:29:18', 2, 0, NULL),
(20, '2023-06-05 22:30:34', 'page-manager-level', 'Página Gestão Niveis', 'null', 'null', '2023-06-05 22:31:01', 3, 0, NULL),
(21, '2023-06-05 22:31:52', 'btn-create-access-module', 'Associar Módulos', 'null', 'null', '2023-06-06 00:23:52', 2, 0, NULL),
(22, '2023-06-05 22:33:11', 'btn-edit-access-module', 'Editar Módulo Associado', 'null', 'null', '2023-06-06 00:23:50', 2, 0, NULL),
(23, '2023-06-05 22:33:53', 'btn-delete-access-module', 'Excluir Módulo Associado', 'null', 'null', '2023-06-06 00:23:36', 2, 0, NULL),
(24, '2023-06-05 22:35:00', 'page-manager-type-module', 'Página Gestão Tipo Módulo', 'null', 'null', '2023-06-05 23:20:42', 3, 1, NULL),
(25, '2023-06-05 23:33:02', 'page-manager-module', 'Página Gestão De Módulos', 'null', 'null', '2023-06-06 00:23:39', 3, 1, NULL),
(26, '2023-06-06 00:17:30', 'page-manager-access-modules', 'Página Associar Módulos', 'null', 'null', '2023-06-21 01:18:55', 3, 0, NULL),
(27, '2023-06-20 17:47:52', 'users', 'Gestão Usuários', 'null', '#dadosCreateUser', '2023-12-19 00:05:58', 5, 0, NULL),
(28, '2023-06-23 00:42:18', 'smtp-settings', 'Configuração Smtp', 'null', '#dadosSmtpSettings', '2023-12-19 00:05:58', 5, 0, NULL),
(29, '2023-06-23 01:05:59', 'btn-create-smtp', 'Cadastrar Smtp', 'null', 'null', '2023-12-19 00:05:58', 2, 0, NULL),
(30, '2023-06-23 01:13:56', 'btn-edit-smtp', 'Editar Configuração Smtp', 'null', 'null', '2023-12-19 00:05:58', 2, 0, NULL),
(31, '2023-06-23 01:14:23', 'btn-delete-smtp', 'Excluir Configuração Smtp', 'null', 'null', '2023-12-19 00:05:58', 2, 0, NULL),
(32, '2023-06-23 12:45:51', 'page-manager-settings-smtp', 'Página Configuração Smtp', 'null', 'null', '2023-12-19 00:05:58', 3, 0, NULL),
(33, '2023-06-23 17:12:23', 'email-alert-configuration', 'Emails Alertas', 'null', '#dadosEmailAlertConfiguration', '2023-06-23 17:15:00', 5, 0, NULL),
(34, '2023-06-26 20:10:30', 'page-manger-email-alarmes', 'Página Configuração E-mail Alarmes', 'null', 'null', '2023-12-19 00:05:58', 3, 0, NULL),
(35, '2023-06-26 20:56:27', 'btn-create-email-alerts', 'Cadastrar Email Alertas', 'null', 'null', '2023-06-26 20:56:49', 2, 0, NULL),
(36, '2023-06-26 20:59:33', 'btn-edit-email-alert', 'Editar E-mail Alertas', 'null', 'null', '2023-12-19 00:05:58', 2, 0, NULL),
(37, '2023-06-26 21:00:02', 'btn-delete-email-alert', 'Excluir Email Alertas', 'null', 'null', '2023-12-19 00:05:58', 2, 0, NULL),
(39, '2023-08-08 21:30:19', 'page-manager-sound-team', 'Página Gestão Sonoplastia', 'null', 'null', '2023-12-20 14:56:11', 3, 0, NULL),
(40, '2023-08-08 22:44:46', 'btn-created-apikey', 'Criar Apikeu', 'null', 'null', '2023-12-20 21:57:45', 2, 0, NULL),
(41, '2023-08-08 22:45:19', 'btn-edit-apikey', 'Editar Apiky', 'null', 'null', '2023-12-20 21:58:31', 2, 0, NULL),
(42, '2023-08-08 22:56:09', 'btn-delete-apikey', 'Deletar Apikey', 'null', 'null', '2023-12-20 21:58:56', 2, 0, NULL),
(43, '2023-12-19 00:34:21', 'config-event', 'Ajustes Evento', 'fas fa-cog', '/config-event', '2023-12-19 19:01:43', 1, 0, NULL),
(44, '2023-12-19 00:37:08', 'manager-sound-team', 'Sonoplastia', 'fas fa-headphones', '/manager-sound-team', '2023-12-19 19:01:46', 1, 0, NULL),
(45, '2023-12-19 19:00:19', 'page-manager-event', 'Página Gestão Eventos', 'null', 'null', '2023-12-19 19:01:50', 3, 0, NULL),
(46, '2023-12-19 19:07:12', 'btn-manager-event', 'Gestão Eventos', 'null', 'null', '2023-12-19 19:07:12', 2, 0, NULL),
(47, '2023-12-19 19:57:53', 'status-event', 'Status Eventos', 'null', '#dadosStatusEvents', '2023-12-19 19:57:53', 4, 0, NULL),
(48, '2023-12-19 19:59:30', 'departments', 'Departamentos', 'null', '#dadosDepartamentos', '2023-12-19 19:59:30', 4, 0, NULL),
(49, '2023-12-19 20:01:18', 'programs', 'Programas/Eventos Especiais', 'null', '#dadosProgramas', '2023-12-19 20:01:18', 4, 0, NULL),
(50, '2023-12-19 20:02:04', 'elder', 'Ancionato', 'null', '#dadosAncionato', '2023-12-19 20:02:04', 4, 0, NULL),
(51, '2023-12-19 20:03:08', 'elder-month', 'Ancião do Mês', 'null', '#dadosAnciaoMes', '2023-12-19 20:03:08', 4, 0, NULL),
(52, '2023-12-19 20:08:03', 'btn-create-status-event', 'Cadastrar Status Evento', 'null', 'null', '2023-12-19 20:08:03', 2, 0, NULL),
(53, '2023-12-19 20:08:35', 'btn-create-departments', 'Cadastrar Departamentos', 'null', 'null', '2023-12-19 20:08:35', 2, 0, NULL),
(54, '2023-12-19 20:09:15', 'btn-create-programs', 'Cadastrar Programas/Eventos Especiais', 'null', 'null', '2023-12-19 20:09:15', 2, 0, NULL),
(55, '2023-12-19 20:09:48', 'btn-create-elder', 'Cadastrar Ancião', 'null', 'null', '2023-12-19 20:09:48', 2, 0, NULL),
(56, '2023-12-19 20:10:24', 'btn-create-elder-month', 'Cadastrar Ancião do Mês', 'null', 'null', '2023-12-19 20:10:24', 2, 0, NULL),
(57, '2023-12-19 20:14:51', 'btn-edit-status-event', 'Editar Status Evento', 'null', 'null', '2023-12-19 20:14:51', 2, 0, NULL),
(58, '2023-12-19 20:15:33', 'btn-delete-status-event', 'Deletar Status Evento', 'null', 'null', '2023-12-19 20:15:33', 2, 0, NULL),
(59, '2023-12-19 20:27:13', 'btn-edit-departments', 'Editar Departamento', 'null', 'null', '2023-12-19 20:27:13', 2, 0, NULL),
(60, '2023-12-19 20:27:46', 'btn-delete-department', 'Deletar Departamento', 'null', 'null', '2023-12-19 20:27:46', 2, 0, NULL),
(61, '2023-12-19 20:28:27', 'btn-edit-programs', 'Editar Programas/Eventos Especiais', 'null', 'null', '2023-12-19 20:28:27', 2, 0, NULL),
(62, '2023-12-19 20:29:05', 'btn-delete-programs', 'Deletar Progrmas/Eventos Especiais', 'null', 'null', '2023-12-19 20:29:05', 2, 0, NULL),
(63, '2023-12-19 20:30:03', 'btn-edit-elder', 'Editar Ancião', 'null', 'null', '2023-12-19 20:30:03', 2, 0, NULL),
(64, '2023-12-19 20:30:30', 'btn-delete-elder', 'Deletar Ancião', 'null', 'null', '2023-12-19 20:30:30', 2, 0, NULL),
(65, '2023-12-19 20:31:47', 'btn-edit-elder-month', 'Editar Ancião do Mês', 'null', 'null', '2023-12-19 20:31:47', 2, 0, NULL),
(66, '2023-12-19 20:33:30', 'delete-elder-montth', 'Deletar Ancião do Mês', 'null', 'null', '2023-12-19 20:33:30', 2, 0, NULL),
(67, '2023-12-19 20:55:15', 'page-status-event', 'Página Status Evento', 'null', 'null', '2023-12-19 20:55:15', 3, 0, NULL),
(68, '2023-12-19 20:56:02', 'page-departments', 'Página Departamentos', 'null', 'null', '2023-12-19 20:56:02', 3, 0, NULL),
(69, '2023-12-19 20:56:51', 'page-programs', 'Página Programas/Eventos Especiais', 'null', 'null', '2023-12-19 20:56:51', 3, 0, NULL),
(70, '2023-12-19 20:57:41', 'page-elder', 'Página Ancionato', 'null', 'null', '2023-12-19 20:57:41', 3, 0, NULL),
(71, '2023-12-19 20:58:26', 'page-elder-month', 'Página Ancião do Mês', 'null', 'null', '2023-12-19 20:58:26', 3, 0, NULL),
(72, '2023-12-19 21:44:58', 'sound-team-lineup', 'Escala Sonoplastia', 'null', '#dadosSoundTeamLineup', '2023-12-19 21:44:58', 6, 0, NULL),
(73, '2023-12-19 21:45:48', 'my-scheduler', 'Minha Escala', 'null', '#dadosMyScheduler', '2023-12-19 21:45:48', 6, 0, NULL),
(74, '2023-12-19 21:46:37', 'manager-team', 'Equipe Sonoplastia', 'null', '#dadosManagerTeam', '2023-12-19 21:52:26', 6, 0, NULL),
(75, '2023-12-19 21:47:37', 'sound-device', 'Equipamento Sonoplastia', 'null', '#dadosSoundDevice', '2023-12-19 21:47:37', 6, 0, NULL),
(76, '2023-12-19 21:48:23', 'suggested-time', 'Horários Sugeridos', 'null', '#dadosSuggestedTime', '2023-12-19 21:48:23', 6, 0, NULL),
(77, '2023-12-19 21:49:07', 'dashboard', 'Dashboard', 'null', '#dadosDashboard', '2023-12-19 21:49:07', 6, 0, NULL),
(78, '2023-12-19 21:53:45', 'btn-create-sound-team-lineup', 'Definir Escala', 'null', 'null', '2023-12-19 21:53:45', 2, 0, NULL),
(79, '2023-12-19 21:54:57', 'btn-create-manager-team', 'Cadastrar Membro', 'null', 'null', '2023-12-19 21:54:57', 2, 0, NULL),
(80, '2023-12-19 21:55:39', 'btn-create-sound-device', 'Cadastrar Equipamento', 'null', 'null', '2023-12-19 21:55:39', 2, 0, NULL),
(81, '2023-12-19 21:56:08', 'btn-create-suggested-time', 'Cadastrar Horário Sugerido', 'null', 'null', '2023-12-19 21:56:08', 2, 0, NULL),
(82, '2023-12-20 12:44:17', 'btn-edit-team-manager', 'Editar Equipe', 'null', 'null', '2023-12-20 12:44:17', 2, 0, NULL),
(83, '2023-12-20 12:44:59', 'btn-remove-manager-team', 'Deletar Equipe', 'null', 'null', '2023-12-20 12:44:59', 2, 0, NULL),
(84, '2023-12-20 12:50:49', 'btn-edit-sound-device', 'Editar Equipamento', 'null', 'null', '2023-12-20 12:50:49', 2, 0, NULL),
(85, '2023-12-20 12:51:58', 'btn-delete-sound-device', 'Deletar Equipamento Som', 'null', 'null', '2023-12-20 12:51:58', 2, 0, NULL),
(86, '2023-12-20 13:01:05', 'btn-edit-suggested-time', 'Editar Horário Sugerido', 'null', 'null', '2023-12-20 13:01:05', 2, 0, NULL),
(87, '2023-12-20 13:01:44', 'btn-delete-suggested-time', 'Deletar Horário Sugerido', 'null', 'null', '2023-12-20 13:01:44', 2, 0, NULL),
(88, '2023-12-20 13:48:06', 'btn-edit-team-lineup', 'Editar Escala', 'null', 'null', '2023-12-20 13:48:06', 2, 0, NULL),
(89, '2023-12-20 13:48:34', 'bt-delete-team-lineup', 'Deletar Escala', 'null', 'null', '2023-12-20 13:48:34', 2, 0, NULL),
(90, '2023-12-20 14:24:17', 'page-ask-to-chanege', 'Página de Solicitação de troca escala', 'null', 'null', '2023-12-20 14:24:17', 3, 0, NULL),
(91, '2023-12-20 15:04:06', 'btn-cancel-my-ask-to-change', 'Cancelar Solictação de Troca', 'null', 'null', '2023-12-20 15:04:06', 2, 0, NULL),
(92, '2023-12-20 21:12:21', 'page-device-sound', 'Página Equipamentos de Som', 'null', 'null', '2023-12-20 21:12:21', 3, 0, NULL),
(93, '2023-12-20 21:13:54', 'page-suggested-time', 'Página Horários Sugeridos', 'null', 'null', '2023-12-20 21:13:54', 3, 0, NULL),
(94, '2023-12-20 21:15:15', 'page-team-lineup', 'Página Escala Equipe de Som', 'null', 'null', '2023-12-20 21:15:15', 3, 0, NULL),
(95, '2023-12-20 21:16:26', 'page-api-key', 'Página ApiKey', 'null', 'null', '2023-12-20 21:16:26', 3, 0, NULL),
(96, '2023-12-20 21:20:58', 'apikey', 'Apikey', 'null', '#dadosApikey', '2023-12-20 21:25:49', 5, 0, NULL),
(97, '2023-12-21 19:03:21', 'page-update-password', 'Update Password', 'null', 'null', '2023-12-21 19:12:35', 3, 0, NULL),
(98, '2023-12-21 19:09:18', 'btn-list-my-ask-to-change', 'Visulizar Solicitações de Troca', 'null', 'null', '2023-12-21 19:09:18', 2, 0, NULL),
(99, '2023-03-27 11:59:53', 'advanced-settings', 'Configurações Avançadas', 'fas fa-cogs', '/advanced-settings', '2024-01-15 19:13:24', 1, 0, NULL),
(100, '2023-12-21 21:52:16', 'page-monitoring', 'Página de Monitoramento', 'null', 'null', '2023-12-21 23:03:38', 3, 0, NULL),
(101, '2023-12-21 22:19:22', 'session-login', 'Sessões de usuário', 'null', '#dadosSessionLogin', '2023-12-21 22:19:22', 8, 0, NULL),
(102, '2023-12-21 22:20:28', 'log', 'Logs Aplicação', 'null', '#dadosLogsSistema', '2023-12-21 22:20:28', 8, 0, NULL),
(103, '2023-12-21 23:00:26', 'monitoring', 'Monitoramento', 'fas fa-gauge', '/monitoring', '2024-01-15 19:13:15', 1, 0, NULL),
(104, '2024-01-03 14:24:50', 'page-manager-events-church', 'Página Eventos Departamentos', 'null', 'null', '2024-01-03 14:24:50', 3, 0, NULL),
(105, '2024-01-03 14:26:17', 'btn-manager-events-church', 'Gestão Eventos Departamentos', 'null', 'null', '2024-01-03 14:26:17', 2, 0, NULL),
(106, '2024-01-03 14:36:27', 'elder-for-department', 'Ancião Conselheiro Departamento', 'null', '#dadosAnciaoPorDepartamento', '2024-01-03 19:17:02', 4, 0, NULL),
(107, '2024-01-03 15:02:59', 'btn-create-elder-for-department', 'Cadastrar Ancião Conselheiro', 'null', 'null', '2024-01-03 15:02:59', 2, 0, NULL),
(108, '2024-01-03 15:04:11', 'btn-edit-elder-for-department', 'Editar Ancião Conselheiro', 'null', 'null', '2024-01-03 15:04:11', 2, 0, NULL),
(109, '2024-01-03 15:05:03', 'btn-delete-elder-for-department', 'Excluir Ancião Conselheiro', 'null', 'null', '2024-01-03 15:05:03', 2, 0, NULL),
(110, '2024-01-03 17:31:29', 'page-elder-for-department', 'Página Ancião Por Departamento', 'null', 'null', '2024-01-03 17:31:29', 3, 0, NULL),
(111, '2024-01-22 23:31:47', 'temp-users', 'Aprovar Usuários', 'null', '#tempUsers', '2024-01-22 23:31:47', 5, 0, NULL),
(112, '2024-01-22 23:55:31', 'btn-create-temp-users', 'Cadastrar Usuário Temp', 'null', 'null', '2024-01-22 23:55:31', 2, 0, NULL),
(113, '2024-01-22 23:57:37', 'btn-edit-temp-users', 'Editar Usuários Temp', 'null', 'null', '2024-01-22 23:57:37', 2, 0, NULL),
(114, '2024-01-22 23:59:21', 'btn-delete-temp-users', 'Deletar Usuário Temp', 'null', 'null', '2024-01-22 23:59:21', 2, 0, NULL),
(115, '2024-01-23 00:24:38', 'page-manager-temp-users', 'Página Usuários Temp', 'null', 'null', '2024-01-23 00:24:38', 3, 0, NULL),
(116, '2024-01-23 20:57:05', 'page-approved-temp-users', 'Página Aprovar Usuário Temp', 'null', 'null', '2024-01-23 20:57:05', 3, 0, NULL),
(117, '2024-01-23 20:57:39', 'page-reproved-temp-user', 'Página Reprovar Usuário Temp', 'null', 'null', '2024-01-23 20:57:39', 3, 0, NULL),
(118, '2024-05-28 23:47:32', 'organization', 'Configurações Organização', 'null', '#dadosOrganization', '2024-05-28 23:47:32', 5, 0, NULL),
(119, '2024-05-28 23:48:01', 'btn-create-organization', 'Cadastrar Organização', 'null', 'null', '2024-05-28 23:48:01', 2, 0, NULL),
(120, '2024-05-28 23:48:32', 'btn-edit-organization', 'Editar Organização', 'null', 'null', '2024-05-28 23:48:32', 2, 0, NULL),
(121, '2024-05-28 23:48:57', 'btn-delete-organization', 'Excluir Organização', 'null', 'null', '2024-05-28 23:48:57', 2, 0, NULL),
(122, '2024-05-28 23:49:25', 'page-organization', 'Página Organização', 'null', 'null', '2024-05-28 23:49:25', 3, 0, NULL),
(123, '2024-09-05 13:03:15', 'whatsapp', 'WhatsApp', 'null', '#DadosWhatsApp', '2024-09-05 13:03:15', 5, 0, NULL),
(124, '2024-09-05 14:33:18', 'btn-cadastrar-token-whatsapp', 'Cadastrar Token WhatsApp', 'null', 'null', '2024-09-05 14:33:18', 2, 0, NULL),
(125, '2024-09-05 14:35:59', 'btn-edit-token-whatsapp', 'Editar Token WhatsApp', 'null', 'null', '2024-09-05 14:35:59', 2, 0, NULL),
(126, '2024-09-05 14:37:30', 'btn-delete-token-whatsapp', 'Excluir Token WhatsApp', 'null', 'null', '2024-09-05 14:37:30', 2, 0, NULL),
(127, '2024-09-05 15:28:52', 'page-manager-access-token-whatsapp', 'Gestão Token WhatsApp', 'null', 'null', '2024-09-05 15:28:52', 3, 0, NULL),
(128, '2024-09-05 22:40:21', 'monitoring-whatsapp', 'WhastApp', 'fa-brands fa-whatsapp', '/monitoring-whatsapp', '2024-09-05 22:46:52', 1, 0, NULL),
(129, '2024-09-05 22:42:30', 'control-invite', 'Controle de Notificações', 'null', '#DataControlInviteWhatsApp', '2024-09-06 21:40:23', 9, 0, NULL),
(130, '2024-09-05 22:43:08', 'control-messages', 'Controle de envio de mensagens', 'null', '#DataControlSendMessage', '2024-09-06 21:40:56', 9, 0, NULL),
(131, '2024-09-06 17:10:05', 'page-monitoring-whatsapp', 'Monitoramento Mensagens WhatsApp', 'null', 'null', '2024-09-06 17:10:05', 3, 0, NULL),
(132, '2025-01-09 21:58:01', 'reception', 'Recepção', 'fa-solid fa-person', '/reception', '2025-01-28 20:35:27', 1, 0, NULL),
(133, '2025-01-09 22:01:03', 'reception-team-lineup', 'Escala Recepção', 'null', '#dataReceptionTeamLineup', '2025-01-28 20:35:43', 10, 0, NULL),
(134, '2025-01-09 22:02:54', 'my-scheduler-reception', 'Minha Escala', 'null', '#dataMySchedulerReception', '2025-01-28 20:35:40', 10, 0, NULL),
(135, '2025-01-09 22:04:20', 'manager-team-reception', 'Equipe Recepção', 'null', '#dataManagerTeamReception', '2025-01-28 20:35:37', 10, 0, NULL),
(136, '2025-01-31 14:15:04', 'page-manage-team-lineup-reception', 'Página Gestão Recepção', 'null', 'null', '2025-01-31 14:15:04', 3, 0, NULL),
(137, '2025-01-31 17:54:05', 'btn-create-reception-team', 'Cadastrar Equipe Recepção', 'null', ' null', '2025-01-31 17:54:05', 2, 0, NULL),
(138, '2025-01-31 17:55:51', 'btn-edit-reception-team', 'Editar Equipe Recepção', 'null', 'null', '2025-01-31 17:55:51', 2, 0, NULL),
(139, '2025-01-31 17:56:29', 'btn-delete-reception-team', 'Remover Equipe Recepção', 'null', 'null', '2025-01-31 17:56:29', 2, 0, NULL),
(140, '2025-01-31 17:58:41', 'btn-edit-reception-team-lineup', 'Editar Escala Recepção', 'null', 'null', '2025-01-31 17:58:41', 2, 0, NULL),
(141, '2025-01-31 17:59:12', 'btn-delete-reception-team-lineup', 'Remover Escala Recepção', 'null', 'null', '2025-01-31 17:59:12', 2, 0, NULL),
(142, '2025-01-31 18:04:28', 'btn-create-reception-team-lineup', 'Definir Escala Recepção', 'null', 'null', '2025-01-31 18:04:28', 2, 0, NULL),
(143, '2025-01-31 18:07:38', 'page-team-lineup-reception', 'Página Definir Escala Recepção', 'null', 'null', '2025-01-31 18:07:38', 3, 0, NULL),
(144, '2025-02-10 21:29:28', 'worship', 'Louvor', 'fa-solid fa-icons', '/worship', '2025-02-10 21:44:07', 1, 0, NULL),
(145, '2025-02-10 22:01:03', 'worship-team-lineup', 'Escala Louvor', 'null', '#dataWorshipTeamLineup', '2025-02-10 23:01:00', 11, 0, NULL),
(146, '2025-02-10 22:02:54', 'my-scheduler-worship', 'Minha Escala', 'null', '#dataMySchedulerWorship', '2025-02-10 23:01:07', 11, 0, NULL),
(147, '2025-02-10 22:04:20', 'manager-team-worship', 'Equipe Louvor', 'null', '#dataManagerTeamWorship', '2025-02-10 23:01:14', 11, 0, NULL),
(148, '2025-02-10 14:15:04', 'page-manage-team-lineup-worship', 'Página Gestão Louvor', 'null', 'null', '2025-02-10 14:15:04', 3, 0, NULL),
(149, '2025-02-10 17:54:05', 'btn-create-worship-team', 'Cadastrar Equipe Louvor', 'null', ' null', '2025-02-10 17:54:05', 2, 0, NULL),
(150, '2025-02-10 17:55:51', 'btn-edit-worship-team', 'Editar Equipe Louvor', 'null', 'null', '2025-02-10 17:55:51', 2, 0, NULL),
(151, '2025-02-10 17:56:29', 'btn-delete-worship-team', 'Remover Equipe Louvor', 'null', 'null', '2025-02-10 17:56:29', 2, 0, NULL),
(152, '2025-02-10 17:58:41', 'btn-edit-worship-team-lineup', 'Editar Escala Louvor', 'null', 'null', '2025-02-10 17:58:41', 2, 0, NULL),
(153, '2025-02-10 17:59:12', 'btn-delete-worship-team-lineup', 'Remover Escala Louvor', 'null', 'null', '2025-02-10 17:59:12', 2, 0, NULL),
(154, '2025-02-10 18:04:28', 'btn-create-worship-team-lineup', 'Definir Escala Louvor', 'null', 'null', '2025-02-10 18:04:28', 2, 0, NULL),
(155, '2025-02-10 18:07:38', 'page-team-lineup-worship', 'Página Definir Escala Louvor', 'null', 'null', '2025-02-10 18:07:38', 3, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_month`
--

DROP TABLE IF EXISTS `tb_month`;
CREATE TABLE IF NOT EXISTS `tb_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `number_month` int(11) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `long_description` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_month`:
--

--
-- Despejando dados para a tabela `tb_month`
--

INSERT INTO `tb_month` (`id`, `created_at`, `number_month`, `short_description`, `long_description`, `updated_at`) VALUES
(1, '2023-03-08 21:01:58', 1, 'Jan', 'Janeiro', '2023-03-09 00:02:12'),
(2, '2023-03-08 21:01:58', 2, 'Fev', 'Fevereiro', '2023-03-09 00:06:42'),
(3, '2023-03-08 21:01:58', 3, 'Mar', 'Março', '2023-03-09 00:06:42'),
(4, '2023-03-08 21:01:58', 4, 'Abr', 'Abril', '2023-03-09 00:06:42'),
(5, '2023-03-08 21:01:58', 5, 'Mai', 'Maio', '2023-03-09 00:06:42'),
(6, '2023-03-08 21:01:58', 6, 'Jun', 'Junho', '2023-03-09 00:06:42'),
(7, '2023-03-08 21:01:58', 7, 'Jul', 'Julho', '2023-03-09 00:06:42'),
(8, '2023-03-08 21:01:58', 8, 'Ago', 'Agosto', '2023-03-09 00:06:42'),
(9, '2023-03-08 21:01:58', 9, 'Set', 'Setembro', '2023-03-09 00:06:42'),
(10, '2023-03-08 21:01:58', 10, 'Out', 'Outubro', '2023-03-09 00:06:42'),
(11, '2023-03-08 21:01:58', 11, 'Nov', 'Novembro', '2023-03-09 00:06:42'),
(12, '2023-03-08 21:01:58', 12, 'Dez', 'Dezembro', '2023-03-09 00:06:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_organization`
--

DROP TABLE IF EXISTS `tb_organization`;
CREATE TABLE IF NOT EXISTS `tb_organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `development` varchar(255) NOT NULL DEFAULT 'Developed By SleyersX',
  `version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_name` (`full_name`),
  UNIQUE KEY `short_name` (`short_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_organization`:
--

--
-- Despejando dados para a tabela `tb_organization`
--

INSERT INTO `tb_organization` (`id`, `short_name`, `full_name`, `site`, `description`, `created_at`, `updated_at`, `development`, `version`) VALUES
(1, 'Agenda Online', 'Adventistas Parque Regina', 'https://www.iasd-pqregina.com.br/app-agenda', 'Seja bem-vindo ao painel de administração da Agenda Online Adventistas Parque Regina.', '0000-00-00 00:00:00', '2024-06-04 12:46:39', 'Walter Moura', '0.08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_password_temp`
--

DROP TABLE IF EXISTS `tb_password_temp`;
CREATE TABLE IF NOT EXISTS `tb_password_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_password_temp`:
--   `id_user`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_password_temp_users`
--

DROP TABLE IF EXISTS `tb_password_temp_users`;
CREATE TABLE IF NOT EXISTS `tb_password_temp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_password_temp_users`:
--   `id_user`
--       `tb_temp_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_preacher`
--

DROP TABLE IF EXISTS `tb_preacher`;
CREATE TABLE IF NOT EXISTS `tb_preacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `letter_recommendation` varchar(255) NOT NULL,
  `church_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `church_id` (`church_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_preacher`:
--   `church_id`
--       `tb_churches` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_programs`
--

DROP TABLE IF EXISTS `tb_programs`;
CREATE TABLE IF NOT EXISTS `tb_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `descricao` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_programs`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_reception_ask_to_change`
--

DROP TABLE IF EXISTS `tb_reception_ask_to_change`;
CREATE TABLE IF NOT EXISTS `tb_reception_ask_to_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `current_linked_user_id` int(11) NOT NULL,
  `scheduler_id` int(11) NOT NULL,
  `new_linked_user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comments` varchar(510) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_current_user_id_reception` (`current_linked_user_id`),
  KEY `fk_new_user_id_reception` (`new_linked_user_id`),
  KEY `fk_status_reception` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_reception_ask_to_change`:
--   `current_linked_user_id`
--       `tb_reception_team` -> `id`
--   `new_linked_user_id`
--       `tb_reception_team` -> `id`
--   `status`
--       `tb_status_ask_to_change` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_reception_team`
--

DROP TABLE IF EXISTS `tb_reception_team`;
CREATE TABLE IF NOT EXISTS `tb_reception_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_id_reception` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_reception_team`:
--   `user_id`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_reception_team_schedule`
--

DROP TABLE IF EXISTS `tb_reception_team_schedule`;
CREATE TABLE IF NOT EXISTS `tb_reception_team_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reception_team_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reception_team_id` (`reception_team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_reception_team_schedule`:
--   `reception_team_id`
--       `tb_reception_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_send_message_whatsapp`
--

DROP TABLE IF EXISTS `tb_send_message_whatsapp`;
CREATE TABLE IF NOT EXISTS `tb_send_message_whatsapp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `soundteam_id` int(11) NOT NULL,
  `phone_number_sent` varchar(13) NOT NULL,
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_status` enum('accepted','sent','delivered','read') NOT NULL,
  `timestamp_message` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_send_message_whats_soundteam_id` (`soundteam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_send_message_whatsapp`:
--   `soundteam_id`
--       `tb_sound_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_send_message_whatsapp_reception`
--

DROP TABLE IF EXISTS `tb_send_message_whatsapp_reception`;
CREATE TABLE IF NOT EXISTS `tb_send_message_whatsapp_reception` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `receptionteam_id` int(11) NOT NULL,
  `phone_number_sent` varchar(13) NOT NULL,
  `type_message` enum('NOTIFICATION','REMINDER') DEFAULT NULL,
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_status` enum('accepted','sent','delivered','read') NOT NULL,
  `timestamp_message` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_send_message_whats_reception_id` (`receptionteam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_send_message_whatsapp_reception`:
--   `receptionteam_id`
--       `tb_reception_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_send_message_whatsapp_worship`
--

DROP TABLE IF EXISTS `tb_send_message_whatsapp_worship`;
CREATE TABLE IF NOT EXISTS `tb_send_message_whatsapp_worship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `worshipteam_id` int(11) NOT NULL,
  `phone_number_sent` varchar(13) NOT NULL,
  `type_message` enum('NOTIFICATION','REMINDER') DEFAULT NULL,
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_status` enum('accepted','sent','delivered','read') NOT NULL,
  `timestamp_message` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_send_message_whats_worship_id` (`worshipteam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_send_message_whatsapp_worship`:
--   `worshipteam_id`
--       `tb_worship_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sessions_login`
--

DROP TABLE IF EXISTS `tb_sessions_login`;
CREATE TABLE IF NOT EXISTS `tb_sessions_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_inicio` timestamp NULL DEFAULT NULL,
  `token` varchar(999) NOT NULL,
  `id_user` int(11) NOT NULL,
  `login_user` text NOT NULL,
  `name_user` text NOT NULL,
  `user_agent` text DEFAULT NULL,
  `remote_addr` text DEFAULT NULL,
  `remote_host` text DEFAULT NULL,
  `remote_port` text DEFAULT NULL,
  `tempo_inativo` int(11) NOT NULL DEFAULT 0,
  `data_fim` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `tempo_final` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`) USING HASH,
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2349 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_sessions_login`:
--   `id_user`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_settings_smtp`
--

DROP TABLE IF EXISTS `tb_settings_smtp`;
CREATE TABLE IF NOT EXISTS `tb_settings_smtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `host` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `id_apikey` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_apikey` (`id_apikey`),
  KEY `fk_status_smtp` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_settings_smtp`:
--   `status_id`
--       `tb_status_smtp` -> `id`
--   `id_apikey`
--       `tb_apis` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_singers`
--

DROP TABLE IF EXISTS `tb_singers`;
CREATE TABLE IF NOT EXISTS `tb_singers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `singer` varchar(510) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_singers`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_singer_scheduler`
--

DROP TABLE IF EXISTS `tb_singer_scheduler`;
CREATE TABLE IF NOT EXISTS `tb_singer_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `worship_team_schedule_id` int(11) NOT NULL,
  `singer_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_worship_team_schedule_id` (`worship_team_schedule_id`),
  KEY `fk_singer_id_` (`singer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_singer_scheduler`:
--   `singer_id`
--       `tb_singers` -> `id`
--   `worship_team_schedule_id`
--       `tb_worship_team_schedule` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sound_device`
--

DROP TABLE IF EXISTS `tb_sound_device`;
CREATE TABLE IF NOT EXISTS `tb_sound_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `device` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_sound_device`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sound_equipment`
--

DROP TABLE IF EXISTS `tb_sound_equipment`;
CREATE TABLE IF NOT EXISTS `tb_sound_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `equipament` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_sound_equipment`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sound_team`
--

DROP TABLE IF EXISTS `tb_sound_team`;
CREATE TABLE IF NOT EXISTS `tb_sound_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_sound_team`:
--   `user_id`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sound_team_schedule`
--

DROP TABLE IF EXISTS `tb_sound_team_schedule`;
CREATE TABLE IF NOT EXISTS `tb_sound_team_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sound_team_id` int(11) NOT NULL,
  `sound_device_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sound_team_id` (`sound_team_id`,`sound_device_id`),
  KEY `equipament_sound_team` (`sound_device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1282 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_sound_team_schedule`:
--   `sound_device_id`
--       `tb_sound_device` -> `id`
--   `sound_team_id`
--       `tb_sound_team` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_apikey`
--

DROP TABLE IF EXISTS `tb_status_apikey`;
CREATE TABLE IF NOT EXISTS `tb_status_apikey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_apikey`:
--

--
-- Despejando dados para a tabela `tb_status_apikey`
--

INSERT INTO `tb_status_apikey` (`id`, `created_at`, `status`, `description`, `updated_at`) VALUES
(1, '0000-00-00 00:00:00', 1, 'ACTIVE', '2023-12-20 21:36:38'),
(2, '0000-00-00 00:00:00', 2, 'INACTIVE', '2023-12-20 21:36:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_ask_to_change`
--

DROP TABLE IF EXISTS `tb_status_ask_to_change`;
CREATE TABLE IF NOT EXISTS `tb_status_ask_to_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_ask_to_change`:
--

--
-- Despejando dados para a tabela `tb_status_ask_to_change`
--

INSERT INTO `tb_status_ask_to_change` (`id`, `created_at`, `status`, `updated_at`) VALUES
(1, '2023-11-17 18:00:21', 'PENDENTE', '2023-11-17 21:00:51'),
(2, '2023-11-17 18:00:21', 'ACCEPTED', '2023-11-17 21:00:51'),
(3, '2023-11-17 18:01:14', 'REJECTED', '2023-11-17 21:01:32'),
(4, '2023-11-24 15:49:23', 'CANCELED', '2023-11-24 18:49:32'),
(5, '2023-11-27 17:16:41', 'OVERDUE', '2023-11-27 20:16:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_email`
--

DROP TABLE IF EXISTS `tb_status_email`;
CREATE TABLE IF NOT EXISTS `tb_status_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_email`:
--

--
-- Despejando dados para a tabela `tb_status_email`
--

INSERT INTO `tb_status_email` (`id`, `status`, `description`) VALUES
(1, 1, 'ACTIVED'),
(2, 2, 'INACTIVED');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_smtp`
--

DROP TABLE IF EXISTS `tb_status_smtp`;
CREATE TABLE IF NOT EXISTS `tb_status_smtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_smtp`:
--

--
-- Despejando dados para a tabela `tb_status_smtp`
--

INSERT INTO `tb_status_smtp` (`id`, `created_at`, `status`, `description`, `updated_at`) VALUES
(1, '2023-12-21 10:57:28', 1, 'ACTIVE', '2023-12-20 21:36:38'),
(2, '2023-12-21 10:57:32', 2, 'INACTIVE', '2023-12-20 21:36:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_token`
--

DROP TABLE IF EXISTS `tb_status_token`;
CREATE TABLE IF NOT EXISTS `tb_status_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_token`:
--

--
-- Despejando dados para a tabela `tb_status_token`
--

INSERT INTO `tb_status_token` (`id`, `status`, `description`) VALUES
(1, 1, 'VERIFIED'),
(2, 2, 'PENDENTE'),
(3, 3, 'ATIVO'),
(4, 4, 'INATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_status_users`
--

DROP TABLE IF EXISTS `tb_status_users`;
CREATE TABLE IF NOT EXISTS `tb_status_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_name` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_status_users`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_suggested_time`
--

DROP TABLE IF EXISTS `tb_suggested_time`;
CREATE TABLE IF NOT EXISTS `tb_suggested_time` (
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day_of_week_id` int(11) NOT NULL,
  `suggested_time` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `day_of_week` (`day_of_week_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_suggested_time`:
--   `day_of_week_id`
--       `tb_days_of_week` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_temp_users`
--

DROP TABLE IF EXISTS `tb_temp_users`;
CREATE TABLE IF NOT EXISTS `tb_temp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_status` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_id` (`id_status`) USING BTREE,
  KEY `fk_temp_user_department_id` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_temp_users`:
--   `department_id`
--       `tb_departments` -> `id`
--   `id_status`
--       `tb_status_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_text_color`
--

DROP TABLE IF EXISTS `tb_text_color`;
CREATE TABLE IF NOT EXISTS `tb_text_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_text_color`:
--

--
-- Despejando dados para a tabela `tb_text_color`
--

INSERT INTO `tb_text_color` (`id`, `color`) VALUES
(1, 'yellow'),
(2, 'green'),
(3, 'red'),
(4, 'blue'),
(5, 'gray'),
(6, 'blue'),
(7, 'pink'),
(8, 'purple'),
(9, 'white'),
(10, 'black');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_type_module`
--

DROP TABLE IF EXISTS `tb_type_module`;
CREATE TABLE IF NOT EXISTS `tb_type_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_type_module`:
--

--
-- Despejando dados para a tabela `tb_type_module`
--

INSERT INTO `tb_type_module` (`id`, `created_at`, `type`, `description`, `updated_at`) VALUES
(1, '2023-03-27 21:02:04', 'menu', 'Menu Lateral', '2023-04-10 23:56:19'),
(2, '2023-03-27 21:02:04', 'button', 'Botão', '2023-04-10 23:56:27'),
(3, '2023-03-28 15:36:56', 'page', 'Página', '2023-04-10 23:56:31'),
(4, '2023-03-28 15:36:56', 'tab-pane-config-event', 'Menu Configuração Evento', '2023-12-19 19:51:05'),
(5, '2023-03-28 15:36:56', 'tab-pane-advanced-settings', 'Menu Configuração Avançada', '2023-12-20 21:20:06'),
(6, '2023-06-14 15:32:06', 'tab-pane-manager-sound-team', 'Menus de Navegação Sonoplastia', '2023-06-14 15:32:31'),
(8, '2023-12-21 22:58:24', 'tab-pane-monitoring', 'Menus de Monitoramento', '2023-12-21 22:58:24'),
(9, '2024-09-05 22:41:24', 'tab-pane-monitoring-whatsapp', 'Menus de monitoramento WhatsApp', '2024-09-07 22:41:24'),
(10, '2025-01-09 21:54:28', 'tab-pane-reception', 'Recepção', '2025-01-09 21:54:28'),
(11, '2025-02-10 21:23:39', 'tab-pane-worship', 'Louvor', '2025-02-10 21:23:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_users`
--

DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `acessos` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_nivel` (`id_nivel`) USING BTREE,
  KEY `status_id` (`id_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_users`:
--   `id_nivel`
--       `tb_access_level` -> `id`
--   `id_status`
--       `tb_status_users` -> `id`
--

--
-- Despejando dados para a tabela `tb_users`
--

INSERT INTO `tb_users` (`id`, `name`, `login`, `email`, `password`, `id_nivel`, `acessos`, `created_at`, `updated_at`, `id_status`) VALUES
(-1, 'Sys Admin', 'sysadmin', 'sysadmin@sleyersx.com.br', '$2y$12$sRuOdW3rXYa5.Zbh2FWtC.l9Ho3wxjLKpNK7EkYATBSLtZ/eXWwum', -1, NULL, '2023-01-31 23:10:57', '2023-12-21 19:30:36', 1),
-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_visitor`
--

DROP TABLE IF EXISTS `tb_visitor`;
CREATE TABLE IF NOT EXISTS `tb_visitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_user` int(11) DEFAULT NULL,
  `login` text NOT NULL,
  `name_user` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1580 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_visitor`:
--   `id_user`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_worship_ask_to_change`
--

DROP TABLE IF EXISTS `tb_worship_ask_to_change`;
CREATE TABLE IF NOT EXISTS `tb_worship_ask_to_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `current_linked_user_id` int(11) NOT NULL,
  `scheduler_id` int(11) NOT NULL,
  `new_linked_user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comments` varchar(510) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_current_user_id_worship` (`current_linked_user_id`),
  KEY `fk_new_user_id_worship` (`new_linked_user_id`),
  KEY `fk_status_worship` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_worship_ask_to_change`:
--   `current_linked_user_id`
--       `tb_worship_team` -> `id`
--   `new_linked_user_id`
--       `tb_worship_team` -> `id`
--   `status`
--       `tb_status_ask_to_change` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_worship_team`
--

DROP TABLE IF EXISTS `tb_worship_team`;
CREATE TABLE IF NOT EXISTS `tb_worship_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_id_worship` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_worship_team`:
--   `user_id`
--       `tb_users` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_worship_team_schedule`
--

DROP TABLE IF EXISTS `tb_worship_team_schedule`;
CREATE TABLE IF NOT EXISTS `tb_worship_team_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduler_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `worship_team_id` int(11) DEFAULT NULL,
  `singer_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `worship_team_id` (`worship_team_id`),
  KEY `fk_singer_id` (`singer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_worship_team_schedule`:
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_years`
--

DROP TABLE IF EXISTS `tb_years`;
CREATE TABLE IF NOT EXISTS `tb_years` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONAMENTOS PARA TABELAS `tb_years`:
--

--
-- Despejando dados para a tabela `tb_years`
--

INSERT INTO `tb_years` (`id`, `year`) VALUES
(1, '2022'),
(2, '2023'),
(3, '2024'),
(4, '2025'),
(5, '2026'),
(6, '2027'),
(7, '2028'),
(8, '2029'),
(9, '2030');

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_access_modules`
--
DROP TABLE IF EXISTS `cnt_access_modules`;

DROP VIEW IF EXISTS `cnt_access_modules`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_access_modules`  AS SELECT `tb_access_modules`.`id` AS `id`, `tb_access_modules`.`created_at` AS `created_at`, `tb_access_modules`.`module_id` AS `module_id`, `tb_modules`.`type_id` AS `type_id_module`, `tb_type_module`.`type` AS `type_module`, CASE WHEN `tb_access_modules`.`allow` = 1 THEN 'true' ELSE 'false' END AS `allow`, `tb_modules`.`module` AS `module`, `tb_modules`.`label` AS `label`, `tb_modules`.`icon` AS `icon`, `tb_modules`.`path_module` AS `path_module`, `tb_access_modules`.`updated_at` AS `updated_at`, `tb_access_modules`.`level_id` AS `level_id`, `tb_access_level`.`level` AS `level`, `tb_access_level`.`description` AS `description`, `tb_access_level`.`home_path` AS `home_path`, `tb_modules`.`current` AS `current` FROM (((`tb_access_modules` join `tb_access_level` on(`tb_access_level`.`id` = `tb_access_modules`.`level_id`)) join `tb_modules` on(`tb_modules`.`id` = `tb_access_modules`.`module_id`)) join `tb_type_module` on(`tb_type_module`.`id` = `tb_modules`.`type_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_access_modules_v2`
--
DROP TABLE IF EXISTS `cnt_access_modules_v2`;

DROP VIEW IF EXISTS `cnt_access_modules_v2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_access_modules_v2`  AS SELECT `tb_access_modules`.`id` AS `id`, `tb_access_modules`.`created_at` AS `created_at`, `tb_access_modules`.`module_id` AS `module_id`, group_concat(`tb_modules`.`module` separator ', ') AS `module`, `tb_access_modules`.`updated_at` AS `updated_at`, `tb_access_modules`.`level_id` AS `level_id`, `tb_access_level`.`level` AS `level`, `tb_access_level`.`description` AS `description`, `tb_access_level`.`home_path` AS `home_path` FROM (((`tb_access_modules` join `tb_access_level` on(`tb_access_level`.`id` = `tb_access_modules`.`level_id`)) join `tb_modules` on(`tb_modules`.`id` = `tb_access_modules`.`module_id`)) join `tb_type_module` on(`tb_type_module`.`id` = `tb_modules`.`type_id`)) GROUP BY `tb_access_level`.`level` ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_access_token_whatsapp`
--
DROP TABLE IF EXISTS `cnt_access_token_whatsapp`;

DROP VIEW IF EXISTS `cnt_access_token_whatsapp`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_access_token_whatsapp`  AS SELECT `atw`.`id` AS `id`, `atw`.`created_at` AS `created_at`, `atw`.`business_phone_number_id` AS `business_phone_number_id`, `atw`.`graph_api_token` AS `graph_api_token`, `atw`.`expiration_at` AS `expiration_at`, `atw`.`status_id` AS `status_id`, `atw`.`updated_at` AS `updated_at`, `st`.`description` AS `status_description` FROM (`tb_access_token_whatsapp` `atw` join `tb_status_token` `st` on(`st`.`id` = `atw`.`status_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_active_account_users`
--
DROP TABLE IF EXISTS `cnt_active_account_users`;

DROP VIEW IF EXISTS `cnt_active_account_users`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_active_account_users`  AS SELECT `tb_active_account_users`.`id` AS `id`, `tb_active_account_users`.`created_at` AS `created_at`, `tb_active_account_users`.`token` AS `token`, `tb_users`.`id` AS `id_user`, `tb_users`.`name` AS `name_user`, `tb_users`.`email` AS `email`, `tb_status_token`.`status` AS `status_token`, `tb_status_token`.`description` AS `decription_status_token`, `tb_active_account_users`.`expiration_at` AS `expiration_at`, `tb_active_account_users`.`updated_at` AS `updated_at` FROM ((`tb_active_account_users` join `tb_users` on(`tb_active_account_users`.`id_user` = `tb_users`.`id`)) join `tb_status_token` on(`tb_status_token`.`id` = `tb_active_account_users`.`status_token`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_agenda_by_department`
--
DROP TABLE IF EXISTS `cnt_agenda_by_department`;

DROP VIEW IF EXISTS `cnt_agenda_by_department`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_agenda_by_department`  AS SELECT `tb_departments`.`id` AS `id`, `tb_departments`.`department` AS `department`, ifnull(date_format(`tb_events`.`start`,'%Y'),date_format(current_timestamp(),'%Y')) AS `year`, ifnull(count(`tb_events`.`id`),0) AS `total` FROM (`tb_departments` left join `tb_events` on(`tb_departments`.`id` = `tb_events`.`department_id`)) WHERE `tb_departments`.`id` > 1 GROUP BY `tb_departments`.`id`, date_format(`tb_events`.`start`,'%Y') ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_agenda_by_program`
--
DROP TABLE IF EXISTS `cnt_agenda_by_program`;

DROP VIEW IF EXISTS `cnt_agenda_by_program`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_agenda_by_program`  AS SELECT `tb_programs`.`id` AS `id`, `tb_programs`.`description` AS `description`, ifnull(date_format(`tb_events`.`start`,'%Y'),date_format(current_timestamp(),'%Y')) AS `year`, ifnull(count(`tb_events`.`id`),0) AS `total` FROM (`tb_programs` left join `tb_events` on(`tb_programs`.`id` = `tb_events`.`program_id`)) WHERE `tb_programs`.`id` > 0 GROUP BY `tb_programs`.`id`, date_format(`tb_events`.`start`,'%Y') ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_agenda_by_status`
--
DROP TABLE IF EXISTS `cnt_agenda_by_status`;

DROP VIEW IF EXISTS `cnt_agenda_by_status`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_agenda_by_status`  AS SELECT `tb_events_status`.`id` AS `id`, `tb_events_status`.`status` AS `status`, `tb_color`.`color` AS `color`, ifnull(date_format(`tb_events`.`start`,'%Y'),date_format(current_timestamp(),'%Y')) AS `year`, ifnull(count(`tb_events`.`id`),0) AS `total` FROM ((`tb_events_status` left join `tb_events` on(`tb_events_status`.`id` = `tb_events`.`status_id`)) left join `tb_color` on(`tb_events_status`.`color_id` = `tb_color`.`id`)) WHERE `tb_events_status`.`id` > 0 GROUP BY `tb_events_status`.`id`, date_format(`tb_events`.`start`,'%Y') ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_apis`
--
DROP TABLE IF EXISTS `cnt_apis`;

DROP VIEW IF EXISTS `cnt_apis`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_apis`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `user_name`, `u`.`email` AS `user_email`, `a`.`id` AS `id`, `a`.`api_key` AS `api_key`, `a`.`api_name` AS `api_name`, `a`.`api_description` AS `api_description`, `a`.`api_path` AS `api_path`, `a`.`active` AS `active`, `a`.`status_id` AS `status_id` FROM (`tb_users` `u` join `tb_apis` `a` on(`u`.`id` = `a`.`user_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_ask_to_change`
--
DROP TABLE IF EXISTS `cnt_ask_to_change`;

DROP VIEW IF EXISTS `cnt_ask_to_change`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_ask_to_change`  AS SELECT `atc`.`id` AS `id`, `atc`.`created_at` AS `created_at`, `atc`.`current_linked_user_id` AS `current_linked_user_id`, `atc`.`scheduler_id` AS `scheduler_id`, `atc`.`new_linked_user_id` AS `new_linked_user_id`, `atc`.`status` AS `status`, `atc`.`comments` AS `comments`, `atc`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `current_linked_user_name`, `st2`.`complete_name` AS `new_linked_user_name`, `s`.`status` AS `status_name`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`day_long_description` AS `scheduler_day_long_description`, `sts`.`sound_device_id` AS `scheduler_sound_device_id`, `sts`.`device` AS `scheduler_sound_device_name` FROM ((((`tb_ask_to_change` `atc` join `tb_sound_team` `st` on(`atc`.`current_linked_user_id` = `st`.`id`)) join `tb_sound_team` `st2` on(`atc`.`new_linked_user_id` = `st2`.`id`)) join `tb_status_ask_to_change` `s` on(`atc`.`status` = `s`.`id`)) join `cnt_sound_team_schedule` `sts` on(`atc`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_ask_to_change_reception`
--
DROP TABLE IF EXISTS `cnt_ask_to_change_reception`;

DROP VIEW IF EXISTS `cnt_ask_to_change_reception`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_ask_to_change_reception`  AS SELECT `atc`.`id` AS `id`, `atc`.`created_at` AS `created_at`, `atc`.`current_linked_user_id` AS `current_linked_user_id`, `atc`.`scheduler_id` AS `scheduler_id`, `atc`.`new_linked_user_id` AS `new_linked_user_id`, `atc`.`status` AS `status`, `atc`.`comments` AS `comments`, `atc`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `current_linked_user_name`, `st2`.`complete_name` AS `new_linked_user_name`, `s`.`status` AS `status_name`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`day_long_description` AS `scheduler_day_long_description` FROM ((((`tb_reception_ask_to_change` `atc` join `tb_reception_team` `st` on(`atc`.`current_linked_user_id` = `st`.`id`)) join `tb_reception_team` `st2` on(`atc`.`new_linked_user_id` = `st2`.`id`)) join `tb_status_ask_to_change` `s` on(`atc`.`status` = `s`.`id`)) join `cnt_reception_team_schedule` `sts` on(`atc`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_ask_to_change_worship`
--
DROP TABLE IF EXISTS `cnt_ask_to_change_worship`;

DROP VIEW IF EXISTS `cnt_ask_to_change_worship`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_ask_to_change_worship`  AS SELECT `atc`.`id` AS `id`, `atc`.`created_at` AS `created_at`, `atc`.`current_linked_user_id` AS `current_linked_user_id`, `atc`.`scheduler_id` AS `scheduler_id`, `atc`.`new_linked_user_id` AS `new_linked_user_id`, `atc`.`status` AS `status`, `atc`.`comments` AS `comments`, `atc`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `current_linked_user_name`, `st2`.`complete_name` AS `new_linked_user_name`, `s`.`status` AS `status_name`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`day_long_description` AS `scheduler_day_long_description` FROM ((((`tb_worship_ask_to_change` `atc` join `tb_worship_team` `st` on(`atc`.`current_linked_user_id` = `st`.`id`)) join `tb_worship_team` `st2` on(`atc`.`new_linked_user_id` = `st2`.`id`)) join `tb_status_ask_to_change` `s` on(`atc`.`status` = `s`.`id`)) join `cnt_worship_team_schedule` `sts` on(`atc`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_aux_worship_team_scheduler_lineup`
--
DROP TABLE IF EXISTS `cnt_aux_worship_team_scheduler_lineup`;

DROP VIEW IF EXISTS `cnt_aux_worship_team_scheduler_lineup`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_aux_worship_team_scheduler_lineup`  AS SELECT `awtsl`.`id` AS `id`, `awtsl`.`worship_team_scheduler_id` AS `worship_team_scheduler_id`, `awtsl`.`worship_team_id` AS `worship_team_id`, `awtsl`.`created_at` AS `created_at`, `awtsl`.`updated_at` AS `updated_at`, group_concat(distinct `wt`.`complete_name` order by `wt`.`complete_name` ASC separator ', ') AS `group_complete_names`, group_concat(distinct `wt`.`name` order by `wt`.`name` ASC separator ', ') AS `group_names`, `wt`.`user_id` AS `user_id` FROM (`tb_aux_worship_team_scheduler_lineup` `awtsl` join `tb_worship_team` `wt` on(`awtsl`.`worship_team_id` = `wt`.`id`)) GROUP BY `awtsl`.`worship_team_scheduler_id` ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_control_accepted_invite`
--
DROP TABLE IF EXISTS `cnt_control_accepted_invite`;

DROP VIEW IF EXISTS `cnt_control_accepted_invite`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_control_accepted_invite`  AS SELECT `ai`.`id` AS `id`, `ai`.`created_at` AS `created_at`, `ai`.`scheduler_id` AS `scheduler_id`, `ai`.`soundteam_id` AS `soundteam_id`, `ai`.`message_id` AS `message_id`, `ai`.`status` AS `status`, `ai`.`timestamp_accepted` AS `timestamp_accepted`, `ai`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `name`, `st`.`contato` AS `contato`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`device` AS `device`, `sts`.`day_long_description` AS `day_long_description`, `sts`.`suggested_time` AS `suggested_time` FROM ((`tb_control_accepted_invite` `ai` join `tb_sound_team` `st` on(`ai`.`soundteam_id` = `st`.`id`)) join `cnt_sound_team_schedule` `sts` on(`ai`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_control_accepted_invite_all`
--
DROP TABLE IF EXISTS `cnt_control_accepted_invite_all`;

DROP VIEW IF EXISTS `cnt_control_accepted_invite_all`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_control_accepted_invite_all`  AS SELECT `cnt_control_accepted_invite`.`id` AS `id`, `cnt_control_accepted_invite`.`created_at` AS `created_at`, `cnt_control_accepted_invite`.`scheduler_id` AS `scheduler_id`, `cnt_control_accepted_invite`.`soundteam_id` AS `team_id`, `cnt_control_accepted_invite`.`message_id` AS `message_id`, `cnt_control_accepted_invite`.`status` AS `status`, `cnt_control_accepted_invite`.`timestamp_accepted` AS `timestamp_accepted`, `cnt_control_accepted_invite`.`updated_at` AS `updated_at`, `cnt_control_accepted_invite`.`complete_name` AS `complete_name`, `cnt_control_accepted_invite`.`name` AS `name`, `cnt_control_accepted_invite`.`contato` AS `contato`, `cnt_control_accepted_invite`.`scheduler_date` AS `scheduler_date`, `cnt_control_accepted_invite`.`device` AS `device`, `cnt_control_accepted_invite`.`day_long_description` AS `day_long_description`, `cnt_control_accepted_invite`.`suggested_time` AS `suggested_time`, 'soundteam' AS `team_type` FROM `cnt_control_accepted_invite` WHERE `cnt_control_accepted_invite`.`scheduler_date` >= curdate() - interval 45 dayunion allselect `cnt_control_accepted_invite_reception`.`id` AS `id`,`cnt_control_accepted_invite_reception`.`created_at` AS `created_at`,`cnt_control_accepted_invite_reception`.`scheduler_id` AS `scheduler_id`,`cnt_control_accepted_invite_reception`.`receptionteam_id` AS `team_id`,`cnt_control_accepted_invite_reception`.`message_id` AS `message_id`,`cnt_control_accepted_invite_reception`.`status` AS `status`,`cnt_control_accepted_invite_reception`.`timestamp_accepted` AS `timestamp_accepted`,`cnt_control_accepted_invite_reception`.`updated_at` AS `updated_at`,`cnt_control_accepted_invite_reception`.`complete_name` AS `complete_name`,`cnt_control_accepted_invite_reception`.`name` AS `name`,`cnt_control_accepted_invite_reception`.`contato` AS `contato`,`cnt_control_accepted_invite_reception`.`scheduler_date` AS `scheduler_date`,NULL AS `device`,`cnt_control_accepted_invite_reception`.`day_long_description` AS `day_long_description`,NULL AS `suggested_time`,'receptionteam' AS `team_type` from `cnt_control_accepted_invite_reception` where `cnt_control_accepted_invite_reception`.`scheduler_date` >= curdate() - interval 45 day union all select `cnt_control_accepted_invite_worship`.`id` AS `id`,`cnt_control_accepted_invite_worship`.`created_at` AS `created_at`,`cnt_control_accepted_invite_worship`.`scheduler_id` AS `scheduler_id`,`cnt_control_accepted_invite_worship`.`worshipteam_id` AS `team_id`,`cnt_control_accepted_invite_worship`.`message_id` AS `message_id`,`cnt_control_accepted_invite_worship`.`status` AS `status`,`cnt_control_accepted_invite_worship`.`timestamp_accepted` AS `timestamp_accepted`,`cnt_control_accepted_invite_worship`.`updated_at` AS `updated_at`,`cnt_control_accepted_invite_worship`.`complete_name` AS `complete_name`,`cnt_control_accepted_invite_worship`.`name` AS `name`,`cnt_control_accepted_invite_worship`.`contato` AS `contato`,`cnt_control_accepted_invite_worship`.`scheduler_date` AS `scheduler_date`,NULL AS `device`,`cnt_control_accepted_invite_worship`.`day_long_description` AS `day_long_description`,NULL AS `suggested_time`,'worshipteam' AS `team_type` from `cnt_control_accepted_invite_worship` where `cnt_control_accepted_invite_worship`.`scheduler_date` >= curdate() - interval 45 day  ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_control_accepted_invite_reception`
--
DROP TABLE IF EXISTS `cnt_control_accepted_invite_reception`;

DROP VIEW IF EXISTS `cnt_control_accepted_invite_reception`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_control_accepted_invite_reception`  AS SELECT `ai`.`id` AS `id`, `ai`.`created_at` AS `created_at`, `ai`.`scheduler_id` AS `scheduler_id`, `ai`.`receptionteam_id` AS `receptionteam_id`, `ai`.`message_id` AS `message_id`, `ai`.`status` AS `status`, `ai`.`timestamp_accepted` AS `timestamp_accepted`, `ai`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `name`, `st`.`contato` AS `contato`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`day_long_description` AS `day_long_description` FROM ((`tb_control_accepted_invite_reception` `ai` join `tb_reception_team` `st` on(`ai`.`receptionteam_id` = `st`.`id`)) join `cnt_reception_team_schedule` `sts` on(`ai`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_control_accepted_invite_worship`
--
DROP TABLE IF EXISTS `cnt_control_accepted_invite_worship`;

DROP VIEW IF EXISTS `cnt_control_accepted_invite_worship`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_control_accepted_invite_worship`  AS SELECT `ai`.`id` AS `id`, `ai`.`created_at` AS `created_at`, `ai`.`scheduler_id` AS `scheduler_id`, `ai`.`worshipteam_id` AS `worshipteam_id`, `ai`.`message_id` AS `message_id`, `ai`.`status` AS `status`, `ai`.`timestamp_accepted` AS `timestamp_accepted`, `ai`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `name`, `st`.`contato` AS `contato`, `sts`.`scheduler_date` AS `scheduler_date`, `sts`.`day_long_description` AS `day_long_description` FROM ((`tb_control_accepted_invite_worship` `ai` join `tb_worship_team` `st` on(`ai`.`worshipteam_id` = `st`.`id`)) join `cnt_worship_team_schedule` `sts` on(`ai`.`scheduler_id` = `sts`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_control_access_token`
--
DROP TABLE IF EXISTS `cnt_control_access_token`;

DROP VIEW IF EXISTS `cnt_control_access_token`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_control_access_token`  AS SELECT `tb_control_access_token`.`id` AS `id`, `tb_control_access_token`.`created_at` AS `created_at`, `tb_control_access_token`.`token` AS `token`, `tb_users`.`id` AS `id_user`, `tb_users`.`name` AS `name_user`, `tb_users`.`email` AS `email`, `tb_control_access_token`.`status_token` AS `status_token`, `tb_control_access_token`.`expiration_at` AS `expiration_at`, `tb_control_access_token`.`updated_at` AS `updated_at` FROM ((`tb_control_access_token` join `tb_users` on(`tb_control_access_token`.`id_user` = `tb_users`.`id`)) join `tb_status_token` on(`tb_status_token`.`id` = `tb_control_access_token`.`status_token`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_departments`
--
DROP TABLE IF EXISTS `cnt_departments`;

DROP VIEW IF EXISTS `cnt_departments`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_departments`  AS SELECT `tb_departments`.`id` AS `id`, `tb_departments`.`created_at` AS `created_at`, `tb_departments`.`department` AS `department`, `tb_departments`.`department_director` AS `department_director`, `tb_departments`.`phone_number` AS `phone_number`, `tb_departments`.`updated_at` AS `updated_at`, CASE `phone_number_mask` ELSE `tb_departments`.`phone_number` AS `end` END FROM `tb_departments` ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_elders`
--
DROP TABLE IF EXISTS `cnt_elders`;

DROP VIEW IF EXISTS `cnt_elders`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_elders`  AS SELECT `tb_elder`.`id` AS `id`, `tb_elder`.`created_at` AS `created_at`, `tb_elder`.`complete_name` AS `complete_name`, `tb_elder`.`name` AS `name`, `tb_elder`.`contato` AS `contato`, `tb_elder`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_elder`.`updated_at` AS `updated_at`, CASE `phone_mask` ELSE `tb_elder`.`contato` AS `end` END FROM (`tb_elder` join `tb_users` on(`tb_users`.`id` = `tb_elder`.`user_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_elder_for_department`
--
DROP TABLE IF EXISTS `cnt_elder_for_department`;

DROP VIEW IF EXISTS `cnt_elder_for_department`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_elder_for_department`  AS SELECT `efp`.`id` AS `id`, `efp`.`created_at` AS `created_at`, `efp`.`department_id` AS `department_id`, `efp`.`elder_id` AS `elder_id`, `efp`.`updated_at` AS `updated_at`, `dp`.`department` AS `department_name`, `dp`.`department_director` AS `department_director`, `dp`.`phone_number` AS `director_phone_number`, `dp`.`phone_number_mask` AS `director_phone_number_mask`, `e`.`complete_name` AS `complete_name`, `e`.`name` AS `name`, `e`.`contato` AS `contato`, CASE `phone_mask` ELSE `e`.`contato` AS `end` END FROM ((`tb_elder_for_department` `efp` join `tb_elder` `e` on(`efp`.`elder_id` = `e`.`id`)) join `cnt_departments` `dp` on(`efp`.`department_id` = `dp`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_elder_for_department_v2`
--
DROP TABLE IF EXISTS `cnt_elder_for_department_v2`;

DROP VIEW IF EXISTS `cnt_elder_for_department_v2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_elder_for_department_v2`  AS SELECT `cnt_elder_for_department`.`elder_id` AS `elder_id`, `cnt_elder_for_department`.`complete_name` AS `elder_complete_name`, `cnt_elder_for_department`.`name` AS `elder_name`, `cnt_elder_for_department`.`contato` AS `elder_phone`, `cnt_elder_for_department`.`phone_mask` AS `elder_phone_mask`, group_concat(`cnt_elder_for_department`.`department_id` separator ', ') AS `department_ids` FROM `cnt_elder_for_department` GROUP BY `cnt_elder_for_department`.`complete_name` ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_elder_month`
--
DROP TABLE IF EXISTS `cnt_elder_month`;

DROP VIEW IF EXISTS `cnt_elder_month`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_elder_month`  AS SELECT `tb_elder_month`.`id` AS `id`, `tb_elder_month`.`elder_id` AS `elder_id`, `tb_elder`.`name` AS `name`, `tb_elder_month`.`month_id` AS `month_id`, `tb_month`.`number_month` AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, `tb_elder_month`.`year_id` AS `year_id`, `tb_years`.`year` AS `year` FROM (((`tb_elder_month` join `tb_years` on(`tb_elder_month`.`year_id` = `tb_years`.`id`)) join `tb_elder` on(`tb_elder_month`.`elder_id` = `tb_elder`.`id`)) join `tb_month` on(`tb_elder_month`.`month_id` = `tb_month`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_elder_month_v2`
--
DROP TABLE IF EXISTS `cnt_elder_month_v2`;

DROP VIEW IF EXISTS `cnt_elder_month_v2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_elder_month_v2`  AS SELECT `tb_elder_month`.`id` AS `id`, `tb_elder_month`.`elder_id` AS `elder_id`, group_concat(`tb_elder`.`name` separator ', ') AS `name`, `tb_elder_month`.`month_id` AS `month_id`, `tb_month`.`number_month` AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, `tb_elder_month`.`year_id` AS `year_id`, `tb_years`.`year` AS `year` FROM (((`tb_elder_month` join `tb_years` on(`tb_elder_month`.`year_id` = `tb_years`.`id`)) join `tb_elder` on(`tb_elder_month`.`elder_id` = `tb_elder`.`id`)) join `tb_month` on(`tb_elder_month`.`month_id` = `tb_month`.`id`)) GROUP BY `tb_elder_month`.`month_id`, `tb_elder_month`.`year_id` ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_email_alarmes`
--
DROP TABLE IF EXISTS `cnt_email_alarmes`;

DROP VIEW IF EXISTS `cnt_email_alarmes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_email_alarmes`  AS SELECT `tb_email_alarmes`.`id` AS `id`, `tb_email_alarmes`.`name` AS `name`, `tb_email_alarmes`.`email` AS `email`, `tb_email_alarmes`.`email_verified` AS `email_verified`, `tb_email_alarmes`.`created_at` AS `created_at`, `tb_email_alarmes`.`updated_at` AS `updated_at`, `tb_status_email`.`id` AS `status_id`, `tb_status_email`.`status` AS `status`, `tb_status_email`.`description` AS `description`, `tb_status_token`.`id` AS `status_verified_id`, `tb_status_token`.`description` AS `status_verified` FROM ((`tb_email_alarmes` join `tb_status_email` on(`tb_email_alarmes`.`status` = `tb_status_email`.`id`)) join `tb_status_token` on(`tb_email_alarmes`.`email_verified` = `tb_status_token`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_events`
--
DROP TABLE IF EXISTS `cnt_events`;

DROP VIEW IF EXISTS `cnt_events`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_events`  AS SELECT `tb_events`.`id` AS `id`, concat(`tb_events`.`created_at`,'+03:00') AS `created_at`, `tb_events`.`created_at` AS `original_created_at`, `tb_events`.`title` AS `title`, `tb_events`.`description` AS `description`, `tb_color`.`color` AS `color`, concat(`tb_events`.`start`,'+03:00') AS `start`, `tb_events`.`start` AS `original_start`, date_format(`tb_events`.`start`,'%m') AS `month_start`, date_format(`tb_events`.`start`,'%Y') AS `year_start`, concat(`tb_events`.`end`,'+03:00') AS `end`, `tb_events`.`end` AS `original_end`, `tb_days_of_week`.`number_day` AS `day_of_week`, `tb_days_of_week`.`short_description` AS `day_of_week_short_description`, `tb_days_of_week`.`long_description` AS `day_of_week_long_description`, `tb_month`.`number_month` AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, CASE `phone_mask` ELSE `tb_events`.`contato` AS `end` END FROM (((((((`tb_events` join `tb_events_status` on(`tb_events`.`status_id` = `tb_events_status`.`id`)) join `tb_color` on(`tb_events_status`.`color_id` = `tb_color`.`id`)) join `tb_text_color` on(`tb_events_status`.`text_color_id` = `tb_text_color`.`id`)) join `tb_programs` on(`tb_events`.`program_id` = `tb_programs`.`id`)) join `tb_departments` on(`tb_departments`.`id` = `tb_events`.`department_id`)) join `tb_month` on(`tb_month`.`number_month` = date_format(`tb_events`.`start`,'%m'))) join `tb_days_of_week` on(`tb_days_of_week`.`number_day` = dayofweek(`tb_events`.`start`))) ORDER BY `tb_events`.`start` DESC ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_events_church`
--
DROP TABLE IF EXISTS `cnt_events_church`;

DROP VIEW IF EXISTS `cnt_events_church`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_events_church`  AS SELECT `tb_events_church`.`id` AS `id`, concat(`tb_events_church`.`created_at`,'+03:00') AS `created_at`, `tb_events_church`.`created_at` AS `original_created_at`, `tb_events_church`.`title` AS `title`, `tb_events_church`.`description` AS `description`, `tb_color`.`color` AS `color`, concat(`tb_events_church`.`start`,'+03:00') AS `start`, `tb_events_church`.`start` AS `original_start`, date_format(`tb_events_church`.`start`,'%m') AS `month_start`, date_format(`tb_events_church`.`start`,'%Y') AS `year_start`, concat(`tb_events_church`.`end`,'+03:00') AS `end`, `tb_events_church`.`end` AS `original_end`, `tb_days_of_week`.`number_day` AS `day_of_week`, `tb_days_of_week`.`short_description` AS `day_of_week_short_description`, `tb_days_of_week`.`long_description` AS `day_of_week_long_description`, `tb_month`.`number_month` AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, CASE `phone_mask` ELSE `tb_events_church`.`contato` AS `end` END FROM ((((((((`tb_events_church` join `tb_events_status` on(`tb_events_church`.`status_id` = `tb_events_status`.`id`)) join `tb_color` on(`tb_events_status`.`color_id` = `tb_color`.`id`)) join `tb_text_color` on(`tb_events_status`.`text_color_id` = `tb_text_color`.`id`)) join `tb_programs` on(`tb_events_church`.`program_id` = `tb_programs`.`id`)) join `tb_departments` on(`tb_departments`.`id` = `tb_events_church`.`department_id`)) join `tb_month` on(`tb_month`.`number_month` = date_format(`tb_events_church`.`start`,'%m'))) join `tb_days_of_week` on(`tb_days_of_week`.`number_day` = dayofweek(`tb_events_church`.`start`))) left join `cnt_elder_for_department` on(`tb_departments`.`id` = `cnt_elder_for_department`.`department_id`)) ORDER BY `tb_events_church`.`start` DESC ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_events_status`
--
DROP TABLE IF EXISTS `cnt_events_status`;

DROP VIEW IF EXISTS `cnt_events_status`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_events_status`  AS SELECT `tb_events_status`.`id` AS `id`, `tb_events_status`.`status` AS `status`, `tb_events_status`.`created_at` AS `created_at`, `tb_events_status`.`updated_at` AS `updated_at`, `tb_events_status`.`description` AS `description`, `tb_events_status`.`color_id` AS `color_id`, `tb_color`.`color` AS `color`, `tb_events_status`.`text_color_id` AS `text_color_id`, `tb_text_color`.`color` AS `text_color` FROM ((`tb_events_status` join `tb_text_color` on(`tb_events_status`.`text_color_id` = `tb_text_color`.`id`)) join `tb_color` on(`tb_events_status`.`color_id` = `tb_color`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_logs`
--
DROP TABLE IF EXISTS `cnt_logs`;

DROP VIEW IF EXISTS `cnt_logs`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_logs`  AS SELECT `l`.`id` AS `id`, `l`.`id_user` AS `id_user`, `l`.`application` AS `application`, `l`.`created_at` AS `created_at`, `l`.`data` AS `data`, `l`.`token` AS `token`, `u`.`name` AS `name`, `u`.`login` AS `login` FROM (`tb_log` `l` join `tb_users` `u` on(`u`.`id` = `l`.`id_user`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_modules`
--
DROP TABLE IF EXISTS `cnt_modules`;

DROP VIEW IF EXISTS `cnt_modules`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_modules`  AS SELECT `tb_modules`.`id` AS `id`, `tb_modules`.`created_at` AS `created_at`, `tb_modules`.`module` AS `module`, `tb_modules`.`label` AS `label`, `tb_modules`.`icon` AS `icon`, `tb_modules`.`path_module` AS `path_module`, `tb_modules`.`updated_at` AS `updated_at`, `tb_modules`.`type_id` AS `type_id`, `tb_modules`.`current` AS `current`, `tb_modules`.`allow_sysadmin` AS `allow_sysadmin`, `tb_type_module`.`type` AS `type`, `tb_type_module`.`description` AS `description` FROM (`tb_modules` join `tb_type_module` on(`tb_modules`.`type_id` = `tb_type_module`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_reception_team`
--
DROP TABLE IF EXISTS `cnt_reception_team`;

DROP VIEW IF EXISTS `cnt_reception_team`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_reception_team`  AS SELECT `tb_reception_team`.`id` AS `id`, `tb_reception_team`.`created_at` AS `created_at`, `tb_reception_team`.`complete_name` AS `complete_name`, `tb_reception_team`.`name` AS `name`, `tb_reception_team`.`contato` AS `contato`, `tb_reception_team`.`email` AS `email`, `tb_reception_team`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_reception_team`.`updated_at` AS `updated_at`, CASE `phone_mask` ELSE `tb_reception_team`.`contato` AS `end` END FROM (`tb_reception_team` join `tb_users` on(`tb_reception_team`.`user_id` = `tb_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_reception_team_schedule`
--
DROP TABLE IF EXISTS `cnt_reception_team_schedule`;

DROP VIEW IF EXISTS `cnt_reception_team_schedule`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_reception_team_schedule`  AS SELECT `tb_reception_team_schedule`.`id` AS `id`, `tb_reception_team_schedule`.`created_at` AS `created_at`, `tb_reception_team`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_reception_team_schedule`.`scheduler_date` AS `scheduler_date`, `tb_reception_team_schedule`.`reception_team_id` AS `reception_team_id`, `tb_reception_team_schedule`.`updated_at` AS `updated_at`, `tb_days_of_week`.`id` AS `day_id`, dayofweek(`tb_reception_team_schedule`.`scheduler_date`) AS `day_of_week`, `tb_days_of_week`.`short_description` AS `day_short_description`, `tb_days_of_week`.`long_description` AS `day_long_description`, dayofmonth(`tb_reception_team_schedule`.`scheduler_date`) AS `day`, month(`tb_reception_team_schedule`.`scheduler_date`) AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, year(`tb_reception_team_schedule`.`scheduler_date`) AS `year`, `tb_reception_team`.`complete_name` AS `completed_name`, `tb_reception_team`.`name` AS `name`, `tb_reception_team`.`contato` AS `contato`, CASE `phone_mask` ELSE `tb_reception_team`.`contato` AS `end` END FROM ((((`tb_reception_team_schedule` join `tb_reception_team` on(`tb_reception_team`.`id` = `tb_reception_team_schedule`.`reception_team_id`)) join `tb_days_of_week` on(`tb_days_of_week`.`number_day` = dayofweek(`tb_reception_team_schedule`.`scheduler_date`))) join `tb_month` on(`tb_month`.`number_month` = month(`tb_reception_team_schedule`.`scheduler_date`))) join `tb_users` on(`tb_reception_team`.`user_id` = `tb_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_send_message_whatsapp`
--
DROP TABLE IF EXISTS `cnt_send_message_whatsapp`;

DROP VIEW IF EXISTS `cnt_send_message_whatsapp`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_send_message_whatsapp`  AS SELECT `smw`.`id` AS `id`, `smw`.`created_at` AS `created_at`, `smw`.`soundteam_id` AS `soundteam_id`, `smw`.`phone_number_sent` AS `phone_number_sent`, `smw`.`message_id` AS `message_id`, `smw`.`message_status` AS `message_status`, `smw`.`timestamp_message` AS `timestamp_message`, `smw`.`payload` AS `payload`, `smw`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `short_name`, `st`.`contato` AS `phone_number`, `u`.`id` AS `linked_user_id`, `u`.`login` AS `linked_user_login`, `u`.`id_nivel` AS `linked_user_level`, `u`.`email` AS `linked_user_email`, `u`.`id_status` AS `linked_user_status_id` FROM ((`tb_send_message_whatsapp` `smw` join `tb_sound_team` `st` on(`smw`.`soundteam_id` = `st`.`id`)) join `tb_users` `u` on(`st`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_send_message_whatsapp_all`
--
DROP TABLE IF EXISTS `cnt_send_message_whatsapp_all`;

DROP VIEW IF EXISTS `cnt_send_message_whatsapp_all`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_send_message_whatsapp_all`  AS SELECT `cnt_send_message_whatsapp`.`id` AS `id`, `cnt_send_message_whatsapp`.`created_at` AS `created_at`, `cnt_send_message_whatsapp`.`soundteam_id` AS `team_id`, `cnt_send_message_whatsapp`.`phone_number_sent` AS `phone_number_sent`, `cnt_send_message_whatsapp`.`message_id` AS `message_id`, `cnt_send_message_whatsapp`.`message_status` AS `message_status`, `cnt_send_message_whatsapp`.`timestamp_message` AS `timestamp_message`, `cnt_send_message_whatsapp`.`payload` AS `payload`, `cnt_send_message_whatsapp`.`updated_at` AS `updated_at`, `cnt_send_message_whatsapp`.`complete_name` AS `complete_name`, `cnt_send_message_whatsapp`.`short_name` AS `short_name`, `cnt_send_message_whatsapp`.`phone_number` AS `phone_number`, `cnt_send_message_whatsapp`.`linked_user_id` AS `linked_user_id`, `cnt_send_message_whatsapp`.`linked_user_login` AS `linked_user_login`, `cnt_send_message_whatsapp`.`linked_user_level` AS `linked_user_level`, `cnt_send_message_whatsapp`.`linked_user_email` AS `linked_user_email`, `cnt_send_message_whatsapp`.`linked_user_status_id` AS `linked_user_status_id`, 'soundteam' AS `team_type` FROM `cnt_send_message_whatsapp` WHERE `cnt_send_message_whatsapp`.`created_at` >= curdate() - interval 45 dayunion allselect `cnt_send_message_whatsapp_reception`.`id` AS `id`,`cnt_send_message_whatsapp_reception`.`created_at` AS `created_at`,`cnt_send_message_whatsapp_reception`.`receptionteam_id` AS `team_id`,`cnt_send_message_whatsapp_reception`.`phone_number_sent` AS `phone_number_sent`,`cnt_send_message_whatsapp_reception`.`message_id` AS `message_id`,`cnt_send_message_whatsapp_reception`.`message_status` AS `message_status`,`cnt_send_message_whatsapp_reception`.`timestamp_message` AS `timestamp_message`,`cnt_send_message_whatsapp_reception`.`payload` AS `payload`,`cnt_send_message_whatsapp_reception`.`updated_at` AS `updated_at`,`cnt_send_message_whatsapp_reception`.`complete_name` AS `complete_name`,`cnt_send_message_whatsapp_reception`.`short_name` AS `short_name`,`cnt_send_message_whatsapp_reception`.`phone_number` AS `phone_number`,`cnt_send_message_whatsapp_reception`.`linked_user_id` AS `linked_user_id`,`cnt_send_message_whatsapp_reception`.`linked_user_login` AS `linked_user_login`,`cnt_send_message_whatsapp_reception`.`linked_user_level` AS `linked_user_level`,`cnt_send_message_whatsapp_reception`.`linked_user_email` AS `linked_user_email`,`cnt_send_message_whatsapp_reception`.`linked_user_status_id` AS `linked_user_status_id`,'receptionteam' AS `team_type` from `cnt_send_message_whatsapp_reception` where `cnt_send_message_whatsapp_reception`.`created_at` >= curdate() - interval 45 day union all select `cnt_send_message_whatsapp_worship`.`id` AS `id`,`cnt_send_message_whatsapp_worship`.`created_at` AS `created_at`,`cnt_send_message_whatsapp_worship`.`worshipteam_id` AS `team_id`,`cnt_send_message_whatsapp_worship`.`phone_number_sent` AS `phone_number_sent`,`cnt_send_message_whatsapp_worship`.`message_id` AS `message_id`,`cnt_send_message_whatsapp_worship`.`message_status` AS `message_status`,`cnt_send_message_whatsapp_worship`.`timestamp_message` AS `timestamp_message`,`cnt_send_message_whatsapp_worship`.`payload` AS `payload`,`cnt_send_message_whatsapp_worship`.`updated_at` AS `updated_at`,`cnt_send_message_whatsapp_worship`.`complete_name` AS `complete_name`,`cnt_send_message_whatsapp_worship`.`short_name` AS `short_name`,`cnt_send_message_whatsapp_worship`.`phone_number` AS `phone_number`,`cnt_send_message_whatsapp_worship`.`linked_user_id` AS `linked_user_id`,`cnt_send_message_whatsapp_worship`.`linked_user_login` AS `linked_user_login`,`cnt_send_message_whatsapp_worship`.`linked_user_level` AS `linked_user_level`,`cnt_send_message_whatsapp_worship`.`linked_user_email` AS `linked_user_email`,`cnt_send_message_whatsapp_worship`.`linked_user_status_id` AS `linked_user_status_id`,'worshipteam' AS `team_type` from `cnt_send_message_whatsapp_worship` where `cnt_send_message_whatsapp_worship`.`created_at` >= curdate() - interval 45 day  ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_send_message_whatsapp_reception`
--
DROP TABLE IF EXISTS `cnt_send_message_whatsapp_reception`;

DROP VIEW IF EXISTS `cnt_send_message_whatsapp_reception`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_send_message_whatsapp_reception`  AS SELECT `smw`.`id` AS `id`, `smw`.`created_at` AS `created_at`, `smw`.`receptionteam_id` AS `receptionteam_id`, `smw`.`phone_number_sent` AS `phone_number_sent`, `smw`.`message_id` AS `message_id`, `smw`.`message_status` AS `message_status`, `smw`.`timestamp_message` AS `timestamp_message`, `smw`.`payload` AS `payload`, `smw`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `short_name`, `st`.`contato` AS `phone_number`, `u`.`id` AS `linked_user_id`, `u`.`login` AS `linked_user_login`, `u`.`id_nivel` AS `linked_user_level`, `u`.`email` AS `linked_user_email`, `u`.`id_status` AS `linked_user_status_id` FROM ((`tb_send_message_whatsapp_reception` `smw` join `tb_reception_team` `st` on(`smw`.`receptionteam_id` = `st`.`id`)) join `tb_users` `u` on(`st`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_send_message_whatsapp_worship`
--
DROP TABLE IF EXISTS `cnt_send_message_whatsapp_worship`;

DROP VIEW IF EXISTS `cnt_send_message_whatsapp_worship`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_send_message_whatsapp_worship`  AS SELECT `smw`.`id` AS `id`, `smw`.`created_at` AS `created_at`, `smw`.`worshipteam_id` AS `worshipteam_id`, `smw`.`phone_number_sent` AS `phone_number_sent`, `smw`.`message_id` AS `message_id`, `smw`.`message_status` AS `message_status`, `smw`.`timestamp_message` AS `timestamp_message`, `smw`.`payload` AS `payload`, `smw`.`updated_at` AS `updated_at`, `st`.`complete_name` AS `complete_name`, `st`.`name` AS `short_name`, `st`.`contato` AS `phone_number`, `u`.`id` AS `linked_user_id`, `u`.`login` AS `linked_user_login`, `u`.`id_nivel` AS `linked_user_level`, `u`.`email` AS `linked_user_email`, `u`.`id_status` AS `linked_user_status_id` FROM ((`tb_send_message_whatsapp_worship` `smw` join `tb_worship_team` `st` on(`smw`.`worshipteam_id` = `st`.`id`)) join `tb_users` `u` on(`st`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_settings_smtp`
--
DROP TABLE IF EXISTS `cnt_settings_smtp`;

DROP VIEW IF EXISTS `cnt_settings_smtp`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_settings_smtp`  AS SELECT `tb_settings_smtp`.`id` AS `id`, `tb_settings_smtp`.`created_at` AS `created_at`, `tb_settings_smtp`.`host` AS `host`, `tb_settings_smtp`.`port` AS `port`, `tb_settings_smtp`.`username` AS `username`, `tb_settings_smtp`.`password` AS `password`, `tb_settings_smtp`.`from_name` AS `from_name`, `tb_settings_smtp`.`id_apikey` AS `id_apikey`, `tb_settings_smtp`.`status_id` AS `status_id`, `tb_status_smtp`.`description` AS `status_description`, `tb_settings_smtp`.`updated_at` AS `updated_at`, `tb_apis`.`api_key` AS `api_key` FROM ((`tb_settings_smtp` join `tb_apis` on(`tb_settings_smtp`.`id_apikey` = `tb_apis`.`id`)) join `tb_status_smtp` on(`tb_settings_smtp`.`status_id` = `tb_status_smtp`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_sound_team`
--
DROP TABLE IF EXISTS `cnt_sound_team`;

DROP VIEW IF EXISTS `cnt_sound_team`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_sound_team`  AS SELECT `tb_sound_team`.`id` AS `id`, `tb_sound_team`.`created_at` AS `created_at`, `tb_sound_team`.`complete_name` AS `complete_name`, `tb_sound_team`.`name` AS `name`, `tb_sound_team`.`contato` AS `contato`, `tb_sound_team`.`email` AS `email`, `tb_sound_team`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_sound_team`.`updated_at` AS `updated_at`, CASE `phone_mask` ELSE `tb_sound_team`.`contato` AS `end` END FROM (`tb_sound_team` join `tb_users` on(`tb_sound_team`.`user_id` = `tb_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_sound_team_schedule`
--
DROP TABLE IF EXISTS `cnt_sound_team_schedule`;

DROP VIEW IF EXISTS `cnt_sound_team_schedule`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_sound_team_schedule`  AS SELECT `tb_sound_team_schedule`.`id` AS `id`, `tb_sound_team_schedule`.`created_at` AS `created_at`, `tb_sound_team`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_sound_team_schedule`.`scheduler_date` AS `scheduler_date`, `tb_sound_team_schedule`.`sound_team_id` AS `sound_team_id`, `tb_sound_team_schedule`.`sound_device_id` AS `sound_device_id`, `tb_sound_team_schedule`.`updated_at` AS `updated_at`, `tb_suggested_time`.`suggested_time` AS `suggested_time`, `tb_days_of_week`.`id` AS `day_id`, dayofweek(`tb_sound_team_schedule`.`scheduler_date`) AS `day_of_week`, `tb_days_of_week`.`short_description` AS `day_short_description`, `tb_days_of_week`.`long_description` AS `day_long_description`, dayofmonth(`tb_sound_team_schedule`.`scheduler_date`) AS `day`, month(`tb_sound_team_schedule`.`scheduler_date`) AS `month`, `tb_month`.`short_description` AS `month_short_description`, `tb_month`.`long_description` AS `month_long_description`, year(`tb_sound_team_schedule`.`scheduler_date`) AS `year`, `tb_sound_team`.`complete_name` AS `completed_name`, `tb_sound_team`.`name` AS `name`, `tb_sound_team`.`contato` AS `contato`, CASE `phone_mask` ELSE `tb_sound_team`.`contato` AS `end` END FROM ((((((`tb_sound_team_schedule` join `tb_sound_team` on(`tb_sound_team`.`id` = `tb_sound_team_schedule`.`sound_team_id`)) join `tb_sound_device` on(`tb_sound_device`.`id` = `tb_sound_team_schedule`.`sound_device_id`)) join `tb_days_of_week` on(`tb_days_of_week`.`number_day` = dayofweek(`tb_sound_team_schedule`.`scheduler_date`))) join `tb_month` on(`tb_month`.`number_month` = month(`tb_sound_team_schedule`.`scheduler_date`))) join `tb_suggested_time` on(`tb_suggested_time`.`day_of_week_id` = `tb_days_of_week`.`id`)) join `tb_users` on(`tb_sound_team`.`user_id` = `tb_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_suggested_time`
--
DROP TABLE IF EXISTS `cnt_suggested_time`;

DROP VIEW IF EXISTS `cnt_suggested_time`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_suggested_time`  AS SELECT `tb_suggested_time`.`created_at` AS `created_at`, `tb_suggested_time`.`id` AS `id`, `tb_suggested_time`.`day_of_week_id` AS `day_of_week_id`, `tb_suggested_time`.`suggested_time` AS `suggested_time`, `tb_suggested_time`.`updated_at` AS `updated_at`, `tb_days_of_week`.`number_day` AS `number_day`, `tb_days_of_week`.`short_description` AS `short_description`, `tb_days_of_week`.`long_description` AS `long_description` FROM (`tb_suggested_time` join `tb_days_of_week` on(`tb_days_of_week`.`id` = `tb_suggested_time`.`day_of_week_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_temp_users`
--
DROP TABLE IF EXISTS `cnt_temp_users`;

DROP VIEW IF EXISTS `cnt_temp_users`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_temp_users`  AS SELECT `tb_temp_users`.`id` AS `id`, `tb_temp_users`.`name` AS `name`, `tb_temp_users`.`login` AS `login`, `tb_temp_users`.`email` AS `email`, `tb_temp_users`.`user_id` AS `user_id`, `tb_temp_users`.`department_id` AS `department_id`, `tb_departments`.`department` AS `department`, `tb_departments`.`department_director` AS `department_director`, `tb_departments`.`phone_number` AS `phone_number`, CASE `phone_number_mask` ELSE `tb_departments`.`phone_number` AS `end` END FROM ((`tb_temp_users` join `tb_status_users` on(`tb_temp_users`.`id_status` = `tb_status_users`.`id`)) join `tb_departments` on(`tb_temp_users`.`department_id` = `tb_departments`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_users`
--
DROP TABLE IF EXISTS `cnt_users`;

DROP VIEW IF EXISTS `cnt_users`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_users`  AS SELECT `tb_users`.`id` AS `id`, `tb_users`.`name` AS `name`, `tb_users`.`login` AS `login`, `tb_users`.`email` AS `email`, `tb_users`.`password` AS `password`, `tb_users`.`id_nivel` AS `id_nivel`, `tb_users`.`acessos` AS `access`, `tb_users`.`created_at` AS `created_at`, `tb_users`.`updated_at` AS `updated_at`, `tb_access_level`.`description` AS `level_description`, `tb_access_level`.`home_path` AS `home_path`, `tb_users`.`id_status` AS `id_status`, `tb_status_users`.`status` AS `status_user` FROM ((`tb_users` join `tb_access_level` on(`tb_users`.`id_nivel` = `tb_access_level`.`id`)) join `tb_status_users` on(`tb_users`.`id_status` = `tb_status_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_worship_team`
--
DROP TABLE IF EXISTS `cnt_worship_team`;

DROP VIEW IF EXISTS `cnt_worship_team`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_worship_team`  AS SELECT `tb_worship_team`.`id` AS `id`, `tb_worship_team`.`created_at` AS `created_at`, `tb_worship_team`.`complete_name` AS `complete_name`, `tb_worship_team`.`name` AS `name`, `tb_worship_team`.`contato` AS `contato`, `tb_worship_team`.`email` AS `email`, `tb_worship_team`.`user_id` AS `linked_user_id`, `tb_users`.`name` AS `linked_user_name`, `tb_worship_team`.`updated_at` AS `updated_at`, CASE `phone_mask` ELSE `tb_worship_team`.`contato` AS `end` END FROM (`tb_worship_team` join `tb_users` on(`tb_worship_team`.`user_id` = `tb_users`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_worship_team_schedule`
--
DROP TABLE IF EXISTS `cnt_worship_team_schedule`;

DROP VIEW IF EXISTS `cnt_worship_team_schedule`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_worship_team_schedule`  AS SELECT `ws`.`id` AS `id`, `ws`.`created_at` AS `created_at`, `wt`.`user_id` AS `linked_user_id`, `u`.`name` AS `linked_user_name`, `ws`.`scheduler_date` AS `scheduler_date`, `aux`.`worship_team_id` AS `worship_team_id`, `ws`.`updated_at` AS `updated_at`, `dow`.`id` AS `day_id`, dayofweek(`ws`.`scheduler_date`) AS `day_of_week`, `dow`.`short_description` AS `day_short_description`, `dow`.`long_description` AS `day_long_description`, dayofmonth(`ws`.`scheduler_date`) AS `day`, month(`ws`.`scheduler_date`) AS `month`, `m`.`short_description` AS `month_short_description`, `m`.`long_description` AS `month_long_description`, year(`ws`.`scheduler_date`) AS `year`, `wt`.`complete_name` AS `completed_name`, `wt`.`name` AS `name`, `wt`.`contato` AS `contato`, CASE `phone_mask` ELSE `wt`.`contato` AS `end` END FROM (((((`tb_worship_team_schedule` `ws` join `tb_aux_worship_team_scheduler_lineup` `aux` on(`aux`.`worship_team_scheduler_id` = `ws`.`id`)) join `tb_worship_team` `wt` on(`wt`.`id` = `aux`.`worship_team_id`)) join `tb_days_of_week` `dow` on(`dow`.`number_day` = dayofweek(`ws`.`scheduler_date`))) join `tb_month` `m` on(`m`.`number_month` = month(`ws`.`scheduler_date`))) join `tb_users` `u` on(`wt`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `cnt_worship_team_schedule_v2`
--
DROP TABLE IF EXISTS `cnt_worship_team_schedule_v2`;

DROP VIEW IF EXISTS `cnt_worship_team_schedule_v2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`u301289665_agenda`@`127.0.0.1` SQL SECURITY DEFINER VIEW `cnt_worship_team_schedule_v2`  AS SELECT `wts`.`id` AS `id`, `wts`.`created_at` AS `created_at`, `wts`.`scheduler_date` AS `scheduler_date`, `wts`.`updated_at` AS `updated_at`, `dow`.`id` AS `day_id`, dayofweek(`wts`.`scheduler_date`) AS `day_of_week`, `dow`.`short_description` AS `day_short_description`, `dow`.`long_description` AS `day_long_description`, dayofmonth(`wts`.`scheduler_date`) AS `day`, month(`wts`.`scheduler_date`) AS `month`, `m`.`short_description` AS `month_short_description`, `m`.`long_description` AS `month_long_description`, year(`wts`.`scheduler_date`) AS `year`, `awtsl`.`group_complete_names` AS `group_complete_names`, `awtsl`.`group_names` AS `group_names`, group_concat(distinct `ss`.`singer_id` order by `ss`.`singer_id` ASC separator ', ') AS `group_singer_ids`, group_concat(distinct `s`.`singer` order by `s`.`singer` ASC separator ', ') AS `group_singer_names`, `aux`.`worship_music` AS `worship_music`, `aux`.`singer_music` AS `singer_music` FROM ((((((`tb_worship_team_schedule` `wts` join `cnt_aux_worship_team_scheduler_lineup` `awtsl` on(`awtsl`.`worship_team_scheduler_id` = `wts`.`id`)) join `tb_days_of_week` `dow` on(`dow`.`number_day` = dayofweek(`wts`.`scheduler_date`))) join `tb_month` `m` on(`m`.`number_month` = month(`wts`.`scheduler_date`))) left join `tb_singer_scheduler` `ss` on(`ss`.`worship_team_schedule_id` = `wts`.`id`)) left join `tb_singers` `s` on(`ss`.`singer_id` = `s`.`id`)) left join `tb_aux_worship_team_scheduler` `aux` on(`aux`.`worship_team_scheduler_id` = `wts`.`id`)) GROUP BY `wts`.`scheduler_date` ;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_access_modules`
--
ALTER TABLE `tb_access_modules`
  ADD CONSTRAINT `level_id` FOREIGN KEY (`level_id`) REFERENCES `tb_access_level` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `module_id` FOREIGN KEY (`module_id`) REFERENCES `tb_modules` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_access_token_whatsapp`
--
ALTER TABLE `tb_access_token_whatsapp`
  ADD CONSTRAINT `fk_status_id_token_whatsapp` FOREIGN KEY (`status_id`) REFERENCES `tb_status_token` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_active_account_users`
--
ALTER TABLE `tb_active_account_users`
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `status_token` FOREIGN KEY (`status_token`) REFERENCES `tb_status_token` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_apis`
--
ALTER TABLE `tb_apis`
  ADD CONSTRAINT `fk_status_id_api` FOREIGN KEY (`status_id`) REFERENCES `tb_status_apikey` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_apis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`);

--
-- Restrições para tabelas `tb_ask_to_change`
--
ALTER TABLE `tb_ask_to_change`
  ADD CONSTRAINT `fk_current_user_id` FOREIGN KEY (`current_linked_user_id`) REFERENCES `tb_sound_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_new_user_id` FOREIGN KEY (`new_linked_user_id`) REFERENCES `tb_sound_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`status`) REFERENCES `tb_status_ask_to_change` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_aux_worship_team_scheduler`
--
ALTER TABLE `tb_aux_worship_team_scheduler`
  ADD CONSTRAINT `fk_worship_team_scheduler_id` FOREIGN KEY (`worship_team_scheduler_id`) REFERENCES `tb_worship_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_aux_worship_team_scheduler_lineup`
--
ALTER TABLE `tb_aux_worship_team_scheduler_lineup`
  ADD CONSTRAINT `fk_aux_worship_team_id` FOREIGN KEY (`worship_team_id`) REFERENCES `tb_worship_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aux_worship_team_scheduler_id` FOREIGN KEY (`worship_team_scheduler_id`) REFERENCES `tb_worship_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_control_accepted_invite`
--
ALTER TABLE `tb_control_accepted_invite`
  ADD CONSTRAINT `fk_accepted_invite_scheduler_id` FOREIGN KEY (`scheduler_id`) REFERENCES `tb_sound_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_accepted_invite_whatsapp` FOREIGN KEY (`soundteam_id`) REFERENCES `tb_sound_team` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_control_accepted_invite_reception`
--
ALTER TABLE `tb_control_accepted_invite_reception`
  ADD CONSTRAINT `fk_reception_accepted_invite_scheduler_id` FOREIGN KEY (`scheduler_id`) REFERENCES `tb_reception_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reception_accepted_invite_whatsapp` FOREIGN KEY (`receptionteam_id`) REFERENCES `tb_reception_team` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_control_accepted_invite_worship`
--
ALTER TABLE `tb_control_accepted_invite_worship`
  ADD CONSTRAINT `fk_worship_accepted_invite_scheduler_id` FOREIGN KEY (`scheduler_id`) REFERENCES `tb_worship_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_worship_accepted_invite_whatsapp` FOREIGN KEY (`worshipteam_id`) REFERENCES `tb_worship_team` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_control_access_token`
--
ALTER TABLE `tb_control_access_token`
  ADD CONSTRAINT `fk_access_token_id_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_token_status_token` FOREIGN KEY (`status_token`) REFERENCES `tb_status_token` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_elder`
--
ALTER TABLE `tb_elder`
  ADD CONSTRAINT `fkc_linked_user_id_elder` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`);

--
-- Restrições para tabelas `tb_elder_for_department`
--
ALTER TABLE `tb_elder_for_department`
  ADD CONSTRAINT `fk_dp_elder` FOREIGN KEY (`elder_id`) REFERENCES `tb_elder` (`id`),
  ADD CONSTRAINT `fk_dp_id` FOREIGN KEY (`department_id`) REFERENCES `tb_departments` (`id`);

--
-- Restrições para tabelas `tb_elder_month`
--
ALTER TABLE `tb_elder_month`
  ADD CONSTRAINT `elder_id` FOREIGN KEY (`elder_id`) REFERENCES `tb_elder` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `month_id` FOREIGN KEY (`month_id`) REFERENCES `tb_month` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `year_id` FOREIGN KEY (`year_id`) REFERENCES `tb_years` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_email_alarmes`
--
ALTER TABLE `tb_email_alarmes`
  ADD CONSTRAINT `id_status_email` FOREIGN KEY (`status`) REFERENCES `tb_status_email` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_status_verified` FOREIGN KEY (`email_verified`) REFERENCES `tb_status_token` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_events`
--
ALTER TABLE `tb_events`
  ADD CONSTRAINT `department_id` FOREIGN KEY (`department_id`) REFERENCES `tb_departments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `program_id` FOREIGN KEY (`program_id`) REFERENCES `tb_programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `status_id` FOREIGN KEY (`status_id`) REFERENCES `tb_events_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_events_church`
--
ALTER TABLE `tb_events_church`
  ADD CONSTRAINT `fk_evt_church_department_id` FOREIGN KEY (`department_id`) REFERENCES `tb_departments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_evt_church_program_id` FOREIGN KEY (`program_id`) REFERENCES `tb_programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_evt_church_status_id` FOREIGN KEY (`status_id`) REFERENCES `tb_events_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_events_status`
--
ALTER TABLE `tb_events_status`
  ADD CONSTRAINT `color_id` FOREIGN KEY (`color_id`) REFERENCES `tb_color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `text_color_id` FOREIGN KEY (`text_color_id`) REFERENCES `tb_text_color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_log`
--
ALTER TABLE `tb_log`
  ADD CONSTRAINT `fk_user_id_log` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_modules`
--
ALTER TABLE `tb_modules`
  ADD CONSTRAINT `type_id` FOREIGN KEY (`type_id`) REFERENCES `tb_type_module` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_password_temp`
--
ALTER TABLE `tb_password_temp`
  ADD CONSTRAINT `id_user_` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_password_temp_users`
--
ALTER TABLE `tb_password_temp_users`
  ADD CONSTRAINT `fk_password_temp_id_user` FOREIGN KEY (`id_user`) REFERENCES `tb_temp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_preacher`
--
ALTER TABLE `tb_preacher`
  ADD CONSTRAINT `church_id` FOREIGN KEY (`church_id`) REFERENCES `tb_churches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_reception_ask_to_change`
--
ALTER TABLE `tb_reception_ask_to_change`
  ADD CONSTRAINT `fk_current_user_id_reception` FOREIGN KEY (`current_linked_user_id`) REFERENCES `tb_reception_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_new_user_id_reception` FOREIGN KEY (`new_linked_user_id`) REFERENCES `tb_reception_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_reception` FOREIGN KEY (`status`) REFERENCES `tb_status_ask_to_change` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_reception_team`
--
ALTER TABLE `tb_reception_team`
  ADD CONSTRAINT `fk_user_id_reception` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_reception_team_schedule`
--
ALTER TABLE `tb_reception_team_schedule`
  ADD CONSTRAINT `reception_team_id` FOREIGN KEY (`reception_team_id`) REFERENCES `tb_reception_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_send_message_whatsapp`
--
ALTER TABLE `tb_send_message_whatsapp`
  ADD CONSTRAINT `fk_send_message_whats_soundteam_id` FOREIGN KEY (`soundteam_id`) REFERENCES `tb_sound_team` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_send_message_whatsapp_reception`
--
ALTER TABLE `tb_send_message_whatsapp_reception`
  ADD CONSTRAINT `fk_send_message_whats_reception_id` FOREIGN KEY (`receptionteam_id`) REFERENCES `tb_reception_team` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_send_message_whatsapp_worship`
--
ALTER TABLE `tb_send_message_whatsapp_worship`
  ADD CONSTRAINT `fk_send_message_whats_worship_id` FOREIGN KEY (`worshipteam_id`) REFERENCES `tb_worship_team` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_sessions_login`
--
ALTER TABLE `tb_sessions_login`
  ADD CONSTRAINT `login_session` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_settings_smtp`
--
ALTER TABLE `tb_settings_smtp`
  ADD CONSTRAINT `fk_status_smtp` FOREIGN KEY (`status_id`) REFERENCES `tb_status_smtp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_apikey` FOREIGN KEY (`id_apikey`) REFERENCES `tb_apis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_singer_scheduler`
--
ALTER TABLE `tb_singer_scheduler`
  ADD CONSTRAINT `fk_singer_id_` FOREIGN KEY (`singer_id`) REFERENCES `tb_singers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_worship_team_schedule_id` FOREIGN KEY (`worship_team_schedule_id`) REFERENCES `tb_worship_team_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_sound_team`
--
ALTER TABLE `tb_sound_team`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_sound_team_schedule`
--
ALTER TABLE `tb_sound_team_schedule`
  ADD CONSTRAINT `device_id` FOREIGN KEY (`sound_device_id`) REFERENCES `tb_sound_device` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sound_team_id` FOREIGN KEY (`sound_team_id`) REFERENCES `tb_sound_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_suggested_time`
--
ALTER TABLE `tb_suggested_time`
  ADD CONSTRAINT `day_of_week` FOREIGN KEY (`day_of_week_id`) REFERENCES `tb_days_of_week` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_temp_users`
--
ALTER TABLE `tb_temp_users`
  ADD CONSTRAINT `fk_temp_user_department_id` FOREIGN KEY (`department_id`) REFERENCES `tb_departments` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_temp_user_id_status` FOREIGN KEY (`id_status`) REFERENCES `tb_status_users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_users`
--
ALTER TABLE `tb_users`
  ADD CONSTRAINT `id_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `tb_access_level` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `id_status` FOREIGN KEY (`id_status`) REFERENCES `tb_status_users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_visitor`
--
ALTER TABLE `tb_visitor`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tb_worship_ask_to_change`
--
ALTER TABLE `tb_worship_ask_to_change`
  ADD CONSTRAINT `fk_current_user_id_worship` FOREIGN KEY (`current_linked_user_id`) REFERENCES `tb_worship_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_new_user_id_worship` FOREIGN KEY (`new_linked_user_id`) REFERENCES `tb_worship_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_worship` FOREIGN KEY (`status`) REFERENCES `tb_status_ask_to_change` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_worship_team`
--
ALTER TABLE `tb_worship_team`
  ADD CONSTRAINT `fk_user_id_worship` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
