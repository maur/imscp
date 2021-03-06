<?php
/**
 * i-MSCP a internet Multi Server Control Panel
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "VHCS - Virtual Hosting Control System".
 *
 * The Initial Developer of the Original Code is moleSoftware GmbH.
 * Portions created by Initial Developer are Copyright (C) 2001-2006
 * by moleSoftware GmbH. All Rights Reserved.
 *
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 *
 * Portions created by the i-MSCP Team are Copyright (C) 2010-2012 by
 * i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 *
 * @category	i-MSCP
 * @package		iMSCP_Core
 * @subpackage	Admin
 * @copyright	2001-2006 by moleSoftware GmbH
 * @copyright	2006-2010 by ispCP | http://isp-control.net
 * @copyright	2010-2012 by i-MSCP | http://i-mscp.net
 * @author		ispCP Team
 * @author		i-MSCP Team
 * @link		http://i-mscp.net
 */

/****************************************************************
 * script functions
 */

/**
 * Generates page.
 *
 * @param iMSCP_pTemplate $tpl Template engine instance
 * @return void
 */
function admin_generatePage($tpl)
{
	$query = "SELECT `admin_id`, `admin_name` FROM `admin` WHERE `admin_type` = 'reseller'";
	$stmt = execute_query($query);

	if ($stmt->rowCount()) {
		foreach ($stmt->fetchAll() as $resellerData) {
			_admin_generateResellerStatisticsEntry($tpl, $resellerData['admin_id'], $resellerData['admin_name']);
			$tpl->parse('RESELLER_STATISTICS_ENTRY_BLOCK', '.reseller_statistics_entry_block');
		}
	} else {
		$tpl->assign('RESELLER_STATISTICS_ENTRIES_BLOCK', '');
		set_page_message('No reseller statistics to display.');
	}
}

/**
 * Generates statistics entry for the given reseller.
 *
 * @access private
 * @param iMSCP_pTemplate $tpl Template engine instance
 * @param int $resellerId Reseller unique identifier
 * @param string $resellerName Reseller name
 * @return void
 */
function _admin_generateResellerStatisticsEntry($tpl, $resellerId, $resellerName)
{
	$resellerProperties = imscp_getResellerProperties($resellerId);

	list(
		$udmn_current,,, $usub_current,,, $uals_current,,, $umail_current,,, $uftp_current,,, $usql_db_current,,,
		$usql_user_current,,, $utraff_current,,, $udisk_current,
	) = generate_reseller_users_props($resellerId);

	$rtraff_max = $resellerProperties['max_traff_amnt'] * 1024 * 1024;
	$rtraff_current = $resellerProperties['current_traff_amnt'] * 1024 * 1024;
	$rdisk_max = $resellerProperties['max_disk_amnt'] * 1024 * 1024;
	$rdisk_current = $resellerProperties['current_disk_amnt'] * 1024 * 1024;

	list($traff_percent) = make_usage_vals($utraff_current, $rtraff_max);
	list($disk_percent) = make_usage_vals($udisk_current, $rdisk_max);

	if ($traff_percent > 100) {
		$traff_percent = 100;
	}

	if ($disk_percent > 100) {
		$disk_percent = 100;
	}

	$tpl->assign(
		array(
			'RESELLER_NAME' => tohtml($resellerName),
			'RESELLER_ID' => $resellerId,
			'MONTH' => date('m'),
			'YEAR' => date('y'),
			'TRAFF_PERCENT' => $traff_percent,
			'TRAFF_MSG' => ($rtraff_max)
				? tr('%1$s / %2$s of %3$s', numberBytesHuman($utraff_current), numberBytesHuman($rtraff_current), numberBytesHuman($rtraff_max))
				: tr('%1$s / %2$s of unlimited', numberBytesHuman($utraff_current), numberBytesHuman($rtraff_current)),
			'DISK_PERCENT' => $disk_percent,
			'DISK_MSG' => ($rdisk_max)
				? tr('%1$s / %2$s of %3$s', numberBytesHuman($udisk_current), numberBytesHuman($rdisk_current), numberBytesHuman($rdisk_max))
				: tr('%1$s / %2$s of unlimited', numberBytesHuman($udisk_current), numberBytesHuman($rdisk_current)),
			'DMN_MSG' => ($resellerProperties['max_dmn_cnt'])
				? tr('%1$d / %2$d of %3$d', $udmn_current, $resellerProperties['current_dmn_cnt'], $resellerProperties['max_dmn_cnt'])
				: tr('%1$d / %2$d of unlimited', $udmn_current, $resellerProperties['current_dmn_cnt']),
			'SUB_MSG' => ($resellerProperties['max_sub_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $usub_current, $resellerProperties['current_sub_cnt'], $resellerProperties['max_sub_cnt'])
				: (($resellerProperties['max_sub_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $usub_current, $resellerProperties['current_sub_cnt'])),
			'ALS_MSG' => ($resellerProperties['max_als_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $uals_current, $resellerProperties['current_als_cnt'], $resellerProperties['max_als_cnt'])
				: (($resellerProperties['max_als_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $uals_current, $resellerProperties['current_als_cnt'])),
			'MAIL_MSG' => ($resellerProperties['max_mail_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $umail_current, $resellerProperties['current_mail_cnt'], $resellerProperties['max_mail_cnt'])
				: (($resellerProperties['max_mail_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $umail_current, $resellerProperties['current_mail_cnt'])),

			'FTP_MSG' => ($resellerProperties['max_ftp_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $uftp_current, $resellerProperties['current_ftp_cnt'], $resellerProperties['max_ftp_cnt'])
				: (($resellerProperties['max_ftp_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $uftp_current, $resellerProperties['current_ftp_cnt'])),

			'SQL_DB_MSG' => ($resellerProperties['max_sql_db_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $usql_db_current, $resellerProperties['current_sql_db_cnt'], $resellerProperties['max_sql_db_cnt'])
				: (($resellerProperties['max_sql_db_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $usql_db_current, $resellerProperties['current_sql_db_cnt'])),

			'SQL_USER_MSG' => ($resellerProperties['max_sql_user_cnt'] > 0)
				? tr('%1$d / %2$d of %3$d', $usql_user_current, $resellerProperties['current_sql_user_cnt'], $resellerProperties['max_sql_user_cnt'])
				: (($resellerProperties['max_sql_user_cnt'] == "-1") ? tr('disabled') : tr('%1$d / %2$d of unlimited', $usql_user_current, $resellerProperties['current_sql_user_cnt']))));
}

/****************************************************************
 * main script
 */
// Include core library
require 'imscp-lib.php';

check_login(__FILE__);

/** @var $cfg iMSCP_Config_Handler_File */
$cfg = iMSCP_Registry::get('config');

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onAdminScriptStart);

$tpl = new iMSCP_pTemplate();
$tpl->define_dynamic(
	array(
		'layout' => 'shared/layouts/ui.tpl',
		'page' => 'admin/reseller_statistics.tpl',
		'page_message' => 'layout',
		'reseller_statistics_entries_block' => 'page',
		'reseller_statistics_entry_block' => 'reseller_statistics_entries_block'));

$tpl->assign(
	array(
		'TR_PAGE_TITLE' => tr('i-MSCP - Admin / Reseller statistics'),
		'THEME_CHARSET' => tr('encoding'),
		'ISP_LOGO' => layout_getUserLogo(),
		'TR_RESELLER_NAME' => tr('Reseller name'),
		'TR_TRAFF' => tr('Traffic usage'),
		'TR_DISK' => tr('Disk usage'),
		'TR_DOMAIN' => tr('Domains'),
		'TR_SUBDOMAIN' => tr('Subdomains'),
		'TR_ALIAS' => tr('Aliases'),
		'TR_MAIL' => tr('Mail accounts'),
		'TR_FTP' => tr('FTP accounts'),
		'TR_SQL_DB' => tr('SQL databases'),
		'TR_SQL_USER' => tr('SQL users'),
		'TR_RESELLER_TOOLTIP' => tr('Show detailed statistics for this reseller'),
		'DATATABLE_TRANSLATIONS' => getDataTablesPluginTranslations()));

generateNavigation($tpl);
admin_generatePage($tpl);
generatePageMessage($tpl);

$tpl->parse('LAYOUT_CONTENT', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onAdminScriptEnd, array('templateEngine' => $tpl));

$tpl->prnt();

unsetMessages();
