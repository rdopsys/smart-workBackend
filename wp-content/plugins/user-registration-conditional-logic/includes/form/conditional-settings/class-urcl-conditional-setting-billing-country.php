<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URCL_Conditional_Setting_Billing_Country Class
 *
 * @version  1.0.0
 * @package  UserRegistrationContidtionalLogic/Form/Settings
 * @author   WPEverest
 */
class URCL_Conditional_Setting_Billing_Country extends URCL_Field_Settings {

	public function __construct() {
		$this->field_id = 'billing_country_advance_setting';
	}
}

return new URCL_Conditional_Setting_Billing_Country();
