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
 * @subpackage	Orderpanel
 * @copyright   2001-2006 by moleSoftware GmbH
 * @copyright   2006-2010 by ispCP | http://isp-control.net
 * @copyright   2010-2012 by i-MSCP | http://i-mscp.net
 * @author      ispCP Team
 * @author      i-MSCP Team
 * @link        http://i-mscp.net
 */

/************************************************************************************
 * Script functions
 */

/**
 * Generates user personal data
 *
 * @param iMSCP_pTemplate $tpl Template engine
 * @return void
 */
function GenerateUserPersonalData($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	if (isset($_POST['fname'])) {
		$first_name = clean_input($_POST['fname']);
	} else if (isset($_SESSION['fname'])) {
		$first_name = $_SESSION['fname'];
	} else {
		$first_name = '';
	}

	if (isset($_POST['lname'])) {
		$last_name = clean_input($_POST['lname']);
	} else if (isset($_SESSION['lname'])) {
		$last_name = $_SESSION['lname'];
	} else {
		$last_name = '';
	}

	if (isset($_POST['email'])) {
		$email = clean_input($_POST['email']);
	} else if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
	} else {
		$email = '';
	}

	if (isset($_POST['gender']) && (in_array($_POST['gender'], array('M', 'F', 'U')))) {
		$gender = $_POST['gender'];
	} else if (isset($_SESSION['gender'])) {
		$gender = $_SESSION['gender'];
	} else {
		$gender = 'U';
	}

	if (isset($_POST['firm'])) {
		$company = clean_input($_POST['firm']);
	} else if (isset($_SESSION['firm'])) {
		$company = $_SESSION['firm'];
	} else {
		$company = '';
	}

	if (isset($_POST['zip'])) {
		$postal_code = clean_input($_POST['zip']);
	} else if (isset($_SESSION['zip'])) {
		$postal_code = $_SESSION['zip'];
	} else {
		$postal_code = '';
	}

	if (isset($_POST['city'])) {
		$city = clean_input($_POST['city']);
	} else if (isset($_SESSION['city'])) {
		$city = $_SESSION['city'];
	} else {
		$city = '';
	}

	if (isset($_POST['state'])) {
		$state = clean_input($_POST['state']);
	} else if (isset($_SESSION['state'])) {
		$state = $_SESSION['state'];
	} else {
		$state = '';
	}

	if (isset($_POST['country'])) {
		$country = clean_input($_POST['country']);
	} else if (isset($_SESSION['country'])) {
		$country = $_SESSION['country'];
	} else {
		$country = '';
	}

	if (isset($_POST['street1'])) {
		$street1 = clean_input($_POST['street1']);
	} else if (isset($_SESSION['street1'])) {
		$street1 = $_SESSION['street1'];
	} else {
		$street1 = '';
	}

	if (isset($_POST['street2'])) {
		$street2 = clean_input($_POST['street2']);
	} else if (isset($_SESSION['street2'])) {
		$street2 = $_SESSION['street2'];
	} else {
		$street2 = '';
	}

	if (isset($_POST['phone'])) {
		$phone = clean_input($_POST['phone']);
	} else if (isset($_SESSION['phone'])) {
		$phone = $_SESSION['phone'];
	} else {
		$phone = '';
	}

	if (isset($_POST['fax'])) {
		$fax = clean_input($_POST['fax']);
	} else if (isset($_SESSION['fax'])) {
		$fax = $_SESSION['fax'];
	} else {
		$fax = '';
	}

	$tpl->assign(
		array(
			'VL_USR_NAME' => tohtml($first_name),
			'VL_LAST_USRNAME' => tohtml($last_name),
			'VL_EMAIL' => tohtml($email),
			'VL_USR_FIRM' => tohtml($company),
			'VL_USR_POSTCODE' => tohtml($postal_code),
			'VL_USRCITY' => tohtml($city),
			'VL_USRSTATE' => tohtml($state),
			'VL_COUNTRY' => tohtml($country),
			'VL_STREET1' => tohtml($street1),
			'VL_STREET2' => tohtml($street2),
			'VL_PHONE' => tohtml($phone),
			'VL_FAX' => tohtml($fax),
			'VL_MALE' => (($gender === 'M') ? $cfg->HTML_SELECTED : ''),
			'VL_FEMALE' => (($gender === 'F') ? $cfg->HTML_SELECTED : ''),
			'VL_UNKNOWN' => (($gender == 'U') ? $cfg->HTML_SELECTED : '')));
}

/**
 *  Checks user personal data.
 *
 * @return void
 */
function checkUserPersonalData()
{
	unset($_GET['edit']);

	if ((isset($_POST['fname']) && $_POST['fname'] != '')
		&& (isset($_POST['email']) && $_POST['email'] != '') && chk_email($_POST['email'])
		&& (isset($_POST['lname']) && $_POST['lname'] != '') && (isset($_POST['zip']) && $_POST['zip'] != '')
		&& (isset($_POST['city']) && $_POST['city'] != '') && (isset($_POST['country']) && $_POST['country'] != '')
		&& (isset($_POST['street1']) && $_POST['street1'] != '') && (isset($_POST['phone']) && $_POST['phone'] != '')
	) {
		$_SESSION['fname'] = clean_input($_POST['fname']);
		$_SESSION['lname'] = clean_input($_POST['lname']);
		$_SESSION['email'] = clean_input($_POST['email']);
		$_SESSION['zip'] = clean_input($_POST['zip']);
		$_SESSION['city'] = clean_input($_POST['city']);
		$_SESSION['state'] = clean_input($_POST['state']);
		$_SESSION['country'] = clean_input($_POST['country']);
		$_SESSION['street1'] = clean_input($_POST['street1']);
		$_SESSION['phone'] = clean_input($_POST['phone']);

		if (isset($_POST['firm']) && $_POST['firm'] != '') {
			$_SESSION['firm'] = clean_input($_POST['firm']);
		}

		if (isset($_POST['gender'])
			&& get_gender_by_code($_POST['gender'], true) !== null
		) {
			$_SESSION['gender'] = $_POST['gender'];
		} else {
			$_SESSION['gender'] = '';
		}

		if (isset($_POST['street2']) && $_POST['street2'] != '') {
			$_SESSION['street2'] = clean_input($_POST['street2']);
		}

		if (isset($_POST['fax']) && $_POST['fax'] != '') {
			$_SESSION['fax'] = clean_input($_POST['fax']);
		}

		redirectTo('chart.php');
	} else {
		set_page_message(tr('You must fill out all required fields.'), 'error');
		$_GET['edit'] = "yes";
	}
}

/************************************************************************************
 * Main script
 */

// Include needed libraries
require 'imscp-lib.php';

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onOrderPanelScriptStart);

/** @var $cfg iMSCP_Config_Handler_File */
$cfg = iMSCP_Registry::get('config');

if (isset($_SESSION['user_id']) && isset($_SESSION['plan_id'])) {
	$userId = $_SESSION['user_id'];
	$hostingPlanId = $_SESSION['plan_id'];
} else {
	throw new iMSCP_Exception_Production(tr('You do not have permission to access this interface.'));
}

if (isset($_POST['uaction']) && $_POST['uaction'] == 'address')
	checkUserPersonalData();

if ((isset($_SESSION['fname']) && $_SESSION['fname'] != '')
	&& (isset($_SESSION['email']) && $_SESSION['email'] != '') && (isset($_SESSION['lname']) && $_SESSION['lname'] != '')
	&& (isset($_SESSION['zip']) && $_SESSION['zip'] != '') && (isset($_SESSION['city']) && $_SESSION['city'] != '')
	&& (isset($_SESSION['state']) && $_SESSION['state'] != '') && (isset($_SESSION['country']) && $_SESSION['country'] != '')
	&& (isset($_SESSION['street1']) && $_SESSION['street1'] != '') && (isset($_SESSION['phone']) && $_SESSION['phone'] != '')
	&& !isset($_GET['edit'])
) {
	redirectTo('chart.php');
}

$tpl = new iMSCP_pTemplate();
$tpl->define_no_file('layout', implode('', gen_purchase_haf($userId)));
$tpl->define_dynamic(
	array(
		'page' => 'orderpanel/address.tpl',
		'page_message' => 'layout'));

$tpl->assign(
	array(
		'TR_PAGE_TITLE' => tr('Order Panel / Personal Data'),
		'TR_PERSONAL_DATA' => tr('Personal Data'),
		'TR_FIRSTNAME' => tr('First name'),
		'TR_LASTNAME' => tr('Last name'),
		'TR_COMPANY' => tr('Company'),
		'TR_POST_CODE' => tr('Zip/Postal code'),
		'TR_CITY' => tr('City'),
		'TR_STATE' => tr('State/Province'),
		'TR_COUNTRY' => tr('Country'),
		'TR_STREET1' => tr('Street 1'),
		'TR_STREET2' => tr('Street 2'),
		'TR_EMAIL' => tr('Email'),
		'TR_PHONE' => tr('Phone'),
		'TR_GENDER' => tr('Gender'),
		'TR_MALE' => tr('Male'),
		'TR_FEMALE' => tr('Female'),
		'TR_UNKNOWN' => tr('Unknown'),
		'TR_FAX' => tr('Fax'),
		'TR_CONTINUE' => tr('Continue'),
		'NEED_FILLED' => tr('* denotes mandatory field.'),
		'THEME_CHARSET' => tr('encoding')));

GenerateUserPersonalData($tpl);
generatePageMessage($tpl);

$tpl->parse('LAYOUT_CONTENT', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onOrderPanelScriptEnd, array('templateEngine' => $tpl));

$tpl->prnt();

unsetMessages();
