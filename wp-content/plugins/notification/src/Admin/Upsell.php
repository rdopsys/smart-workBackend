<?php
/**
 * Upsell class
 * Used to promote free and paid extensions.
 *
 * @package notification
 */

namespace BracketSpace\Notification\Admin;

use BracketSpace\Notification\Core\Settings;
use BracketSpace\Notification\Core\Templates;
use BracketSpace\Notification\Utils\Settings\CoreFields;
use BracketSpace\Notification\Store\Carrier as CarrierStore;

/**
 * Upsell class
 */
class Upsell {

	/**
	 * Adds conditionals metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function add_conditionals_meta_box() {
		if ( class_exists( 'NotificationConditionals' ) ) {
			return;
		}

		add_meta_box(
			'notification_conditionals',
			__( 'Conditionals', 'notification' ),
			[ $this, 'conditionals_metabox' ],
			'notification',
			'advanced',
			'default'
		);

		// Enable metabox.
		add_filter( 'notification/admin/allow_metabox/notification_conditionals', '__return_true' );
	}

	/**
	 * Conditionals metabox content
	 *
	 * @since  8.0.0
	 * @param  object $post current WP_Post.
	 * @return void
	 */
	public function conditionals_metabox( $post ) {
		Templates::render( 'upsell/conditionals-metabox' );
	}

	/**
	 * Prints additional Merge Tag group in Merge Tags metabox
	 * Note: Used when there are Merge Tag groups
	 *
	 * @action notification/metabox/trigger/tags/groups/after
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function custom_fields_merge_tag_group() {
		if ( class_exists( 'NotificationCustomFields' ) ) {
			return;
		}

		Templates::render( 'upsell/custom-fields-mergetag-group' );
	}

	/**
	 * Renders review queue switch
	 *
	 * @action notification/admin/metabox/save/post
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function review_queue_switch() {
		if ( class_exists( 'NotificationReviewQueue' ) ) {
			return;
		}

		Templates::render( 'upsell/review-queue-switch' );
	}

	/**
	 * Registers Scheduled Triggers settings
	 *
	 * @action notification/settings/register 200
	 *
	 * @since  8.0.0
	 * @param  Settings $settings Settings API object.
	 * @return void
	 */
	public function scheduled_triggers_settings( $settings ) {
		if ( class_exists( 'NotificationScheduledTriggers' ) ) {
			return;
		}

		$section = $settings->add_section( __( 'Triggers', 'notification' ), 'triggers' );

		$section->add_group( __( 'Scheduled Triggers', 'notification' ), 'scheduled_triggers' )
			->add_field( [
				'name'     => __( 'Features', 'notification' ),
				'slug'     => 'upsell',
				'addons'   => [
					'message' => Templates::get( 'upsell/scheduled-triggers-setting' ),
				],
				'render'   => [ new CoreFields\Message(), 'input' ],
				'sanitize' => [ new CoreFields\Message(), 'sanitize' ],
			] );

	}

	/**
	 * Adds Trigger upselling.
	 *
	 * @action notification/settings/section/triggers/before
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function triggers_settings_upsell() {
		Templates::render( 'upsell/triggers-upsell' );
	}

	/**
	 * Adds Carrier upselling.
	 *
	 * @action notification/settings/section/carriers/before
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function carriers_settings_upsell() {
		Templates::render( 'upsell/carriers-upsell' );
	}

	/**
	 * Adds missing Carriers to the List.
	 *
	 * @action notification/carrier/list/after
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function carriers_list() {
		Templates::render( 'upsell/carriers-list', [
			'carriers' => self::get_missing_carriers(),
		] );
	}

	/**
	 * Adds custom development CTA
	 *
	 * @action notification/settings/sidebar/after
	 *
	 * @since  8.0.0
	 * @return void
	 */
	public function custom_development() {
		Templates::render( 'upsell/custom-development' );
	}

	/**
	 * Gets the missing carriers
	 *
	 * @since  8.0.0
	 * @return array<string,array{name: string, pro: bool, link: string, icon: string}>
	 */
	public static function get_missing_carriers() {
		$carriers = [];

		if ( ! CarrierStore::has( 'discord' ) ) {
			$carriers['discord'] = [
				'name' => 'Discord',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-discord/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 245 240"><path d="M104.4 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1.1-6.1-4.5-11.1-10.2-11.1zM140.9 103.9c-5.7 0-10.2 5-10.2 11.1s4.6 11.1 10.2 11.1c5.7 0 10.2-5 10.2-11.1s-4.5-11.1-10.2-11.1z"/><path d="M189.5 20h-134C44.2 20 35 29.2 35 40.6v135.2c0 11.4 9.2 20.6 20.5 20.6h113.4l-5.3-18.5 12.8 11.9 12.1 11.2 21.5 19V40.6c0-11.4-9.2-20.6-20.5-20.6zm-38.6 130.6s-3.6-4.3-6.6-8.1c13.1-3.7 18.1-11.9 18.1-11.9-4.1 2.7-8 4.6-11.5 5.9-5 2.1-9.8 3.5-14.5 4.3-9.6 1.8-18.4 1.3-25.9-.1-5.7-1.1-10.6-2.7-14.7-4.3-2.3-.9-4.8-2-7.3-3.4-.3-.2-.6-.3-.9-.5-.2-.1-.3-.2-.4-.3-1.8-1-2.8-1.7-2.8-1.7s4.8 8 17.5 11.8c-3 3.8-6.7 8.3-6.7 8.3-22.1-.7-30.5-15.2-30.5-15.2 0-32.2 14.4-58.3 14.4-58.3 14.4-10.8 28.1-10.5 28.1-10.5l1 1.2c-18 5.2-26.3 13.1-26.3 13.1s2.2-1.2 5.9-2.9c10.7-4.7 19.2-6 22.7-6.3.6-.1 1.1-.2 1.7-.2 6.1-.8 13-1 20.2-.2 9.5 1.1 19.7 3.9 30.1 9.6 0 0-7.9-7.5-24.9-12.7l1.4-1.6s13.7-.3 28.1 10.5c0 0 14.4 26.1 14.4 58.3 0 0-8.5 14.5-30.6 15.2z"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'slack_api' ) ) {
			$carriers['slack_api'] = [
				'name' => 'Slack',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-slack/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg viewBox="0 0 124 124" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26.3996 78.2C26.3996 85.3 20.5996 91.1001 13.4996 91.1001C6.39961 91.1001 0.599609 85.3 0.599609 78.2C0.599609 71.1 6.39961 65.3 13.4996 65.3H26.3996V78.2Z" fill="black"/><path d="M32.8994 78.2C32.8994 71.1 38.6994 65.3 45.7994 65.3C52.8994 65.3 58.6994 71.1 58.6994 78.2V110.5C58.6994 117.6 52.8994 123.4 45.7994 123.4C38.6994 123.4 32.8994 117.6 32.8994 110.5V78.2Z" fill="black"/><path d="M45.7994 26.4001C38.6994 26.4001 32.8994 20.6001 32.8994 13.5001C32.8994 6.4001 38.6994 0.600098 45.7994 0.600098C52.8994 0.600098 58.6994 6.4001 58.6994 13.5001V26.4001H45.7994Z" fill="black"/><path d="M45.7996 32.9001C52.8996 32.9001 58.6996 38.7001 58.6996 45.8001C58.6996 52.9001 52.8996 58.7001 45.7996 58.7001H13.4996C6.39961 58.7001 0.599609 52.9001 0.599609 45.8001C0.599609 38.7001 6.39961 32.9001 13.4996 32.9001H45.7996Z" fill="black"/><path d="M97.5996 45.8001C97.5996 38.7001 103.4 32.9001 110.5 32.9001C117.6 32.9001 123.4 38.7001 123.4 45.8001C123.4 52.9001 117.6 58.7001 110.5 58.7001H97.5996V45.8001Z" fill="black"/><path d="M91.0998 45.8001C91.0998 52.9001 85.2998 58.7001 78.1998 58.7001C71.0998 58.7001 65.2998 52.9001 65.2998 45.8001V13.5001C65.2998 6.4001 71.0998 0.600098 78.1998 0.600098C85.2998 0.600098 91.0998 6.4001 91.0998 13.5001V45.8001Z" fill="black"/><path d="M78.1998 97.6001C85.2998 97.6001 91.0998 103.4 91.0998 110.5C91.0998 117.6 85.2998 123.4 78.1998 123.4C71.0998 123.4 65.2998 117.6 65.2998 110.5V97.6001H78.1998Z" fill="black"/><path d="M78.1998 91.1001C71.0998 91.1001 65.2998 85.3 65.2998 78.2C65.2998 71.1 71.0998 65.3 78.1998 65.3H110.5C117.6 65.3 123.4 71.1 123.4 78.2C123.4 85.3 117.6 91.1001 110.5 91.1001H78.1998Z" fill="black"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'push' ) ) {
			$carriers['push'] = [
				'name' => 'Push',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-push/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg width="134" height="151" viewBox="0 0 134 151" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M43.5981 140.426H52.9092C54.1367 140.426 55.1352 139.425 55.1352 138.194C55.1352 136.963 54.1367 135.962 52.9092 135.962H43.5981C42.3706 135.962 41.3721 136.963 41.3721 138.194C41.3721 139.425 42.3706 140.426 43.5981 140.426Z" fill="black"/><path d="M64.5542 10.4399H42.4721C41.2446 10.4399 40.2461 11.4412 40.2461 12.6721C40.2461 13.9029 41.2446 14.9042 42.4721 14.9042H64.5542C65.7817 14.9042 66.7802 13.9029 66.7802 12.6721C66.7802 11.4412 65.7817 10.4399 64.5542 10.4399Z" fill="black"/><path d="M34.0069 11.8175C33.9496 11.6836 33.886 11.5497 33.8034 11.4285C33.7207 11.3073 33.6316 11.1925 33.5299 11.0905C33.4218 10.9884 33.3136 10.8928 33.1864 10.8162C33.0656 10.7333 32.9384 10.6632 32.8048 10.6058C32.6713 10.5548 32.5313 10.5101 32.3914 10.4782C31.66 10.3379 30.8968 10.5739 30.3816 11.0905C30.2799 11.1925 30.1845 11.3073 30.1018 11.4285C30.0191 11.5497 29.9555 11.6836 29.8983 11.8175C29.841 11.9514 29.7965 12.0917 29.7711 12.232C29.7393 12.3787 29.7266 12.5254 29.7266 12.6721C29.7266 12.8124 29.7393 12.9591 29.7711 13.1058C29.7965 13.2461 29.841 13.3864 29.8983 13.5203C29.9555 13.6606 30.0191 13.7882 30.1018 13.9093C30.1845 14.0305 30.2799 14.1453 30.3816 14.2473C30.7951 14.6619 31.3675 14.9042 31.9526 14.9042C32.0989 14.9042 32.2451 14.8851 32.3851 14.8596C32.5313 14.8277 32.6713 14.7894 32.8048 14.732C32.9384 14.6746 33.0656 14.6045 33.1864 14.5279C33.3073 14.445 33.4218 14.3494 33.5299 14.2473C33.6316 14.1453 33.7207 14.0305 33.8034 13.9093C33.886 13.7882 33.9496 13.6606 34.0069 13.5203C34.0641 13.3864 34.1086 13.2461 34.1341 13.1058C34.1659 12.9591 34.1786 12.8124 34.1786 12.6721C34.1786 12.5254 34.1659 12.3787 34.1341 12.232C34.1086 12.0917 34.0641 11.9514 34.0069 11.8175Z" fill="black"/><path d="M19.0742 84.1192C19.0742 84.2659 19.0933 84.4126 19.1187 84.5592C19.1505 84.6995 19.1951 84.8399 19.2459 84.9738C19.3032 85.1077 19.3731 85.2416 19.4495 85.3564C19.5321 85.484 19.6275 85.5988 19.7293 85.7008C19.8311 85.8029 19.9455 85.8921 20.0664 85.975C20.1872 86.0579 20.3144 86.1281 20.4544 86.1791C20.5879 86.2365 20.7278 86.2812 20.8678 86.3067C21.014 86.3386 21.1603 86.3513 21.3002 86.3513C21.8917 86.3513 22.4641 86.1154 22.8775 85.7008C22.9793 85.5988 23.0747 85.484 23.151 85.3564C23.2337 85.2416 23.3037 85.1077 23.3609 84.9738C23.4118 84.8399 23.4563 84.6995 23.4881 84.5592C23.5135 84.4126 23.5326 84.2659 23.5326 84.1192C23.5326 83.5325 23.2909 82.9585 22.8775 82.5439C22.0507 81.7085 20.5561 81.7085 19.7293 82.5439C19.3159 82.9585 19.0742 83.5325 19.0742 84.1192Z" fill="black"/><path d="M18.2661 69.7762H40.3482C41.5757 69.7762 42.5742 68.7749 42.5742 67.5441C42.5742 66.3132 41.5757 65.312 40.3482 65.312H18.2661C17.0386 65.312 16.04 66.3132 16.04 67.5441C16.04 68.7749 17.0386 69.7762 18.2661 69.7762Z" fill="black"/><path d="M46.1991 68.3923C46.2563 68.5326 46.3263 68.6601 46.4026 68.7813C46.4853 68.9025 46.5743 69.0173 46.6824 69.1193C46.7842 69.2214 46.8987 69.317 47.0195 69.3999C47.1404 69.4765 47.2676 69.5466 47.4011 69.604C47.5347 69.6614 47.6746 69.6997 47.8209 69.7316C47.9608 69.7571 48.1071 69.7762 48.2534 69.7762C48.8385 69.7762 49.4109 69.5339 49.8307 69.1193C49.9324 69.0173 50.0215 68.9025 50.1041 68.7813C50.1868 68.6601 50.2568 68.5326 50.3077 68.3923C50.3649 68.2584 50.4094 68.1181 50.4349 67.9778C50.4667 67.8375 50.4794 67.6908 50.4794 67.5441C50.4794 67.3974 50.4667 67.2507 50.4349 67.104C50.4094 66.9637 50.3649 66.8234 50.3077 66.6895C50.2568 66.5556 50.1868 66.4216 50.1041 66.3005C50.0215 66.1793 49.9324 66.0645 49.8307 65.9625C49.7289 65.8604 49.6144 65.7648 49.4872 65.6882C49.3664 65.6053 49.2392 65.5352 49.1056 65.4778C48.972 65.4268 48.8321 65.3821 48.6922 65.3566C48.3996 65.2928 48.1071 65.2928 47.8209 65.3566C47.6746 65.3821 47.5347 65.4268 47.4011 65.4778C47.2676 65.5352 47.1404 65.6053 47.0195 65.6882C46.8987 65.7648 46.7842 65.8604 46.6824 65.9625C46.5743 66.0645 46.4853 66.1793 46.4026 66.3005C46.3263 66.4216 46.2563 66.5556 46.1991 66.6895C46.1418 66.8234 46.0973 66.9637 46.0719 67.104C46.0401 67.2507 46.0273 67.3974 46.0273 67.5441C46.0273 67.6844 46.0401 67.8375 46.0719 67.9778C46.0973 68.1181 46.1418 68.2584 46.1991 68.3923Z" fill="black"/><path d="M96.5075 34.9933V13.8519C96.5075 6.21168 90.3064 0 82.6871 0H13.8204C6.20105 0 0 6.21168 0 13.8519V137.148C0 144.782 6.20105 151 13.8204 151H82.6871C90.3064 151 96.5075 144.782 96.5075 137.148V116.007C117.445 114.457 134 96.8806 134 75.4968C134 54.1194 117.445 36.5431 96.5075 34.9933ZM6.36005 13.8519C6.36005 9.73206 9.70544 6.3775 13.8204 6.3775H82.6871C86.802 6.3775 90.1474 9.73206 90.1474 13.8519V19.1325H6.36005V13.8519ZM90.1474 137.148C90.1474 141.268 86.802 144.623 82.6871 144.623H13.8204C9.70544 144.623 6.36005 141.268 6.36005 137.148V131.868H90.1474V137.148ZM90.1474 125.49H6.36005V25.51H90.1474V35.0188C75.9391 36.1859 63.785 44.7318 57.5203 56.8235H30.0067C28.7792 56.8235 27.7807 57.8248 27.7807 59.0556C27.7807 60.2929 28.7792 61.2877 30.0067 61.2877H55.536C54.0732 65.2099 53.1955 69.4127 53.0174 73.794H22.7754C21.5479 73.794 20.5493 74.7953 20.5493 76.0261C20.5493 77.257 21.5479 78.2583 22.7754 78.2583H53.0747C53.1573 79.4827 53.2909 80.6945 53.4817 81.8871H28.7856C27.5581 81.8871 26.5596 82.8883 26.5596 84.1192C26.5596 85.35 27.5581 86.3513 28.7856 86.3513H54.4484C54.7601 87.4929 55.1226 88.6089 55.536 89.7059H12.9872C11.7597 89.7059 10.7612 90.7071 10.7612 91.938C10.7612 93.1752 11.7597 94.1701 12.9872 94.1701H57.5203C63.785 106.262 75.9391 114.814 90.1474 115.981V125.49ZM96.5075 109.61C95.5153 109.699 94.5104 109.744 93.4928 109.744C92.3607 109.744 91.2477 109.687 90.1474 109.578C79.0809 108.494 69.5472 102.091 64.1221 92.9584L62.4303 89.725C60.8085 86.1855 59.7718 82.3207 59.4538 78.2583C59.3775 77.3463 59.3393 76.4279 59.3393 75.4968C59.3393 74.9292 59.352 74.3616 59.3775 73.794C59.6001 69.3489 60.6622 65.127 62.4239 61.2877C63.1299 59.7316 63.9504 58.2393 64.8789 56.8235C70.4058 48.3414 79.577 42.455 90.1474 41.4218C91.2477 41.3134 92.3607 41.256 93.4928 41.256C94.5104 41.256 95.5153 41.3007 96.5075 41.39C113.928 42.9269 127.64 57.6398 127.64 75.4968C127.64 93.3602 113.928 108.073 96.5075 109.61Z" fill="black"/><path d="M109.157 80.2608V69.655C109.157 64.4573 105.876 59.5977 100.838 57.1232C100.571 54.6551 98.8605 52.5697 96.5073 51.5876C95.5851 51.2113 94.5674 50.9944 93.4926 50.9944C92.2905 50.9944 91.1521 51.2623 90.1472 51.7342C87.9657 52.761 86.4075 54.7635 86.1404 57.1168C81.1732 59.5339 77.8214 64.4892 77.8214 69.655V80.2608C74.5969 81.3067 72.7334 84.4317 73.4902 87.563C74.1644 90.3627 76.9755 92.3972 80.181 92.3972H84.6203C85.3072 95.6178 87.4124 98.1624 90.1472 99.3231C91.1839 99.7568 92.316 99.9991 93.4926 99.9991C94.5484 99.9991 95.5596 99.8078 96.5073 99.4507C99.4011 98.3601 101.646 95.739 102.365 92.3972H106.804C110.003 92.3972 112.814 90.3627 113.489 87.563C114.245 84.4317 112.382 81.3067 109.157 80.2608ZM96.5073 94.2594C95.6932 95.063 94.6374 95.5349 93.4926 95.5349C92.1951 95.5349 90.9995 94.9226 90.1472 93.9086C89.7783 93.475 89.473 92.9648 89.2441 92.3972H90.1472H96.5073H97.7348C97.4486 93.1242 97.0288 93.7556 96.5073 94.2594ZM109.164 86.5171C108.973 87.3079 107.936 87.9329 106.804 87.9329H96.5073H90.1472H80.181C79.0426 87.9329 78.0059 87.3079 77.8151 86.5171C77.5734 85.5031 78.3747 84.7633 79.2206 84.4954C81.0523 83.9278 82.2735 82.3398 82.2735 80.5414V69.655C82.2735 65.8285 85.1037 62.1423 89.155 60.6755L90.1472 60.3183L90.9359 60.0377L90.5924 58.1691C90.567 58.0352 90.5543 57.9204 90.5543 57.8247C90.5543 56.5237 91.8708 55.4587 93.4926 55.4587C95.1081 55.4587 96.4246 56.5237 96.4246 57.8247C96.4246 57.9204 96.4119 58.0352 96.3864 58.1691L96.0493 60.0186L96.5073 60.1908L97.8047 60.6691C101.932 62.2125 104.705 65.8221 104.705 69.655V80.5414C104.705 82.3398 105.933 83.9278 107.758 84.4954C108.502 84.7314 109.208 85.3309 109.208 86.1536C109.208 86.2748 109.196 86.3896 109.164 86.5171Z" fill="black"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'twilio/sms' ) ) {
			$carriers['twilio/sms'] = [
				'name' => 'Twilio - SMS',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-twilio/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg fill="#0D122B" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M15 0C6.7 0 0 6.7 0 15s6.7 15 15 15 15-6.7 15-15S23.3 0 15 0zm0 26C8.9 26 4 21.1 4 15S8.9 4 15 4s11 4.9 11 11-4.9 11-11 11zm6.8-14.7c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1 1.4-3.1 3.1-3.1 3.1 1.4 3.1 3.1zm0 7.4c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1c0-1.7 1.4-3.1 3.1-3.1s3.1 1.4 3.1 3.1zm-7.4 0c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1c0-1.7 1.4-3.1 3.1-3.1s3.1 1.4 3.1 3.1zm0-7.4c0 1.7-1.4 3.1-3.1 3.1S8.2 13 8.2 11.3s1.4-3.1 3.1-3.1 3.1 1.4 3.1 3.1z"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'buddypress-notification' ) ) {
			$carriers['buddypress-notification'] = [
				'name' => 'BuddyPress Notification',
				'pro'  => false,
				'link' => 'https://wordpress.org/plugins/notification-buddypress/',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" preserveAspectRatio="xMidYMid meet" enable-background="new 0 0 128 128"><g transform="translate(0,-924.36218)"><path d="m 126.5,988.37986 a 62.5,62.5 0 0 1 -124.999995,0 62.5,62.5 0 1 1 124.999995,0 z" style="fill:#ffffff" /><g transform="matrix(0.02335871,0,0,-0.02334121,-0.11965895,1052.4471)" style="fill:#d84800"><path d="M 2515,5484 C 1798,5410 1171,5100 717,4595 332,4168 110,3689 23,3105 -1,2939 -1,2554 24,2385 111,1783 363,1266 774,842 1492,102 2529,-172 3521,116 c 448,130 858,379 1195,726 413,426 667,949 751,1548 24,173 24,548 -1,715 -91,625 -351,1150 -781,1580 -425,425 -943,685 -1555,780 -101,16 -520,29 -615,19 z m 611,-143 C 4158,5186 4999,4440 5275,3435 5501,2611 5302,1716 4747,1055 4319,547 3693,214 3028,141 c -125,-14 -441,-14 -566,0 -140,15 -338,55 -468,95 C 722,621 -58,1879 161,3188 c 41,249 115,474 234,717 310,631 860,1110 1528,1330 213,70 374,102 642,129 96,10 436,-4 561,-23 z" /><path d="M 2575,5090 C 1629,5020 813,4386 516,3490 384,3089 362,2641 456,2222 643,1386 1307,696 2134,479 c 233,-61 337,-73 611,-73 274,0 378,12 611,73 548,144 1038,500 1357,986 193,294 315,629 363,995 20,156 15,513 -10,660 -42,241 -108,448 -215,665 -421,857 -1325,1375 -2276,1305 z m 820,-491 c 270,-48 512,-261 608,-537 26,-76 31,-104 35,-222 4,-115 1,-149 -17,-220 -62,-250 -237,-457 -467,-553 -63,-27 -134,-48 -134,-41 0,2 15,35 34,72 138,274 138,610 0,883 -110,220 -334,412 -564,483 -30,10 -62,20 -70,23 -21,7 77,56 175,88 126,41 255,49 400,24 z m -610,-285 c 310,-84 541,-333 595,-641 18,-101 8,-278 -20,-368 -75,-236 -220,-401 -443,-505 -109,-51 -202,-70 -335,-70 -355,0 -650,217 -765,563 -28,84 -31,104 -31,232 -1,118 3,152 22,220 89,306 335,528 650,585 67,13 257,3 327,-16 z M 4035,2940 c 301,-95 484,-325 565,-710 21,-103 47,-388 37,-414 -6,-14 -30,-16 -182,-16 -96,0 -175,3 -175,6 0,42 -37,236 -60,313 -99,334 -315,586 -567,661 -24,7 -43,17 -43,21 0,5 32,45 72,90 l 72,82 106,-6 c 67,-3 130,-13 175,-27 z m -1703,-510 258,-255 92,90 c 51,49 183,178 293,286 l 200,197 75,-9 c 207,-26 404,-116 547,-252 170,-161 267,-361 308,-632 15,-100 21,-394 9,-454 l -6,-31 -1519,0 c -1074,0 -1520,3 -1524,11 -14,21 -18,297 -6,407 59,561 364,896 866,950 97,10 55,41 407,-308 z" /></g></g></svg>',
			];
		}

		if ( ! CarrierStore::has( 'filelog' ) ) {
			$carriers['filelog'] = [
				'name' => __( 'File Log', 'notification' ),
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-file-log/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg width="177" height="194" viewBox="0 0 177 194" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M58.7485 150.476C59.5325 149.368 59.9245 147.64 59.9245 145.291C59.9245 142.942 59.5276 141.2 58.7338 140.066C58.3337 139.506 57.7971 139.057 57.1747 138.761C56.5522 138.465 55.8644 138.333 55.1762 138.375C51.9715 138.375 50.3691 140.681 50.3691 145.291C50.3691 149.901 51.9617 152.181 55.1468 152.132C56.7639 152.137 57.9644 151.58 58.7485 150.476Z" fill="black"/><path d="M174.711 43.6699L132.623 1.95479C132.011 1.33598 131.282 0.844537 130.478 0.50898C129.674 0.173422 128.811 0.000427574 127.939 0H57.3762C50.5557 0.00776061 44.0167 2.71334 39.1938 7.5232C34.371 12.3331 31.6581 18.8544 31.6503 25.6566V120.513H22.5702C16.5842 120.513 10.8434 122.884 6.61067 127.105C2.37793 131.327 0 137.052 0 143.022V146.565C0.00779102 152.53 2.38916 158.248 6.62106 162.463C10.853 166.678 16.5893 169.045 22.5702 169.045H31.7434C32.1733 175.544 35.063 181.637 39.8275 186.091C44.5919 190.545 50.8753 193.027 57.4056 193.035H150.509C157.328 193.029 163.865 190.326 168.688 185.519C173.51 180.712 176.225 174.193 176.235 167.393L176.656 48.337C176.658 47.4694 176.486 46.6103 176.152 45.8092C175.818 45.0081 175.329 44.281 174.711 43.6699V43.6699ZM128.561 8.26385L166.655 46.0206H145.281C140.848 46.0154 136.598 44.257 133.464 41.131C130.33 38.0051 128.566 33.767 128.561 29.3462V8.26385ZM25.2016 133.512H31.6013V151.975H40.7156V157.126H25.2016V133.512ZM168.875 167.398C168.87 172.257 166.932 176.915 163.487 180.351C160.042 183.787 155.371 185.719 150.499 185.724H57.3958C52.8096 185.719 48.3908 184.006 45.0052 180.92C41.6196 177.835 39.5114 173.6 39.0937 169.045H94.1227C100.104 169.046 105.842 166.679 110.075 162.464C114.308 158.249 116.69 152.531 116.698 146.565V143.051C116.698 137.081 114.32 131.356 110.087 127.135C105.854 122.913 100.114 120.542 94.1276 120.542H39.0202V25.6761C39.0253 20.8173 40.963 16.159 44.408 12.7233C47.853 9.2876 52.5239 7.35516 57.3958 7.34999H121.211V29.3413C121.219 35.7054 123.757 41.8066 128.269 46.3067C132.782 50.8068 138.899 53.3384 145.281 53.3461H169.292L168.875 167.398ZM43.6312 145.255C43.6312 141.326 44.6113 138.326 46.5713 136.254C48.5314 134.181 51.3947 133.144 55.1614 133.141C58.928 133.141 61.7848 134.17 63.7318 136.229C65.6788 138.288 66.6506 141.308 66.6474 145.29C66.6474 149.261 65.6673 152.281 63.7073 154.35C61.7472 156.419 58.8871 157.452 55.1271 157.448C51.4127 157.448 48.5657 156.409 46.586 154.33C44.6064 152.252 43.6214 149.227 43.6312 145.255V145.255ZM78.0501 150.475C78.9762 151.633 80.3042 152.21 82.0438 152.21C82.9249 152.218 83.8043 152.127 84.6654 151.941V148.241H80.4414V143.315H90.6583V156.021C87.7088 156.997 84.6171 157.478 81.5096 157.443C77.8574 157.443 75.0365 156.388 73.047 154.277C71.0575 152.166 70.0628 149.157 70.0628 145.25C70.0628 141.439 71.1523 138.472 73.3312 136.351C75.5102 134.23 78.5663 133.17 82.4995 133.17C83.915 133.163 85.3274 133.304 86.7136 133.59C87.9002 133.827 89.0595 134.184 90.1732 134.655L88.1494 139.665C86.3998 138.814 84.4752 138.382 82.5289 138.404C80.6603 138.404 79.2163 139.01 78.1971 140.222C77.1779 141.429 76.6683 143.154 76.6683 145.412C76.6683 147.669 77.1289 149.307 78.0501 150.475Z" fill="black"/><path d="M56.2782 88.1375H78.3927C79.3674 88.1375 80.3022 87.7513 80.9914 87.0639C81.6806 86.3766 82.0678 85.4443 82.0678 84.4722C82.0678 83.5002 81.6806 82.5679 80.9914 81.8805C80.3022 81.1932 79.3674 80.807 78.3927 80.807H56.2782C55.3035 80.807 54.3687 81.1932 53.6794 81.8805C52.9902 82.5679 52.603 83.5002 52.603 84.4722C52.603 85.4443 52.9902 86.3766 53.6794 87.0639C54.3687 87.7513 55.3035 88.1375 56.2782 88.1375Z" fill="black"/><path d="M152.052 109.64C153.027 109.64 153.962 109.254 154.651 108.567C155.34 107.879 155.727 106.947 155.727 105.975C155.727 105.003 155.34 104.071 154.651 103.383C153.962 102.696 153.027 102.31 152.052 102.31H139.62C138.646 102.31 137.711 102.696 137.022 103.383C136.333 104.071 135.945 105.003 135.945 105.975C135.945 106.947 136.333 107.879 137.022 108.567C137.711 109.254 138.646 109.64 139.62 109.64H152.052Z" fill="black"/><path d="M112.4 67.7149C112.6 67.8463 112.812 67.9592 113.032 68.0521C113.256 68.1435 113.487 68.2138 113.723 68.2622C113.959 68.311 114.198 68.3356 114.439 68.3355C114.681 68.3356 114.922 68.311 115.159 68.2622C115.394 68.2145 115.624 68.1441 115.845 68.0521C116.067 67.9592 116.281 67.8464 116.482 67.7149C116.683 67.5839 116.868 67.4315 117.036 67.2604C117.724 66.5728 118.111 65.6417 118.114 64.6703C118.113 64.4292 118.09 64.1888 118.045 63.9519C117.997 63.7176 117.927 63.4885 117.835 63.2677C117.743 63.0457 117.629 62.8329 117.497 62.6324C117.227 62.2333 116.882 61.8898 116.482 61.6208C116.281 61.4894 116.067 61.3765 115.845 61.2836C115.175 61.0034 114.436 60.9302 113.723 61.0735C113.487 61.1219 113.256 61.1923 113.032 61.2836C112.812 61.3765 112.6 61.4894 112.4 61.6208C112.199 61.7533 112.012 61.9055 111.842 62.0753C111.671 62.2451 111.519 62.4318 111.386 62.6324C111.252 62.8324 111.139 63.0452 111.048 63.2677C110.956 63.4885 110.885 63.7176 110.837 63.9519C110.788 64.1883 110.764 64.429 110.764 64.6703C110.766 65.6417 111.154 66.5728 111.842 67.2604C112.011 67.4316 112.198 67.584 112.4 67.7149V67.7149Z" fill="black"/><path d="M124.357 108.008C124.491 108.208 124.646 108.395 124.817 108.565C124.987 108.736 125.174 108.889 125.376 109.02C125.574 109.154 125.786 109.267 126.008 109.357C126.231 109.449 126.462 109.519 126.699 109.567C126.934 109.616 127.174 109.64 127.414 109.64C127.656 109.64 127.898 109.615 128.135 109.567C128.37 109.519 128.599 109.449 128.821 109.357C129.266 109.17 129.672 108.902 130.016 108.565C130.185 108.394 130.338 108.207 130.472 108.008C130.605 107.809 130.718 107.598 130.81 107.378C130.9 107.155 130.969 106.924 131.016 106.688C131.065 106.454 131.09 106.215 131.09 105.975C131.089 105.734 131.065 105.493 131.016 105.257C130.969 105.023 130.9 104.793 130.81 104.572C130.718 104.352 130.605 104.141 130.472 103.942C130.338 103.741 130.185 103.553 130.016 103.38C129.844 103.21 129.655 103.058 129.453 102.925C129.054 102.659 128.606 102.474 128.135 102.383C127.661 102.285 127.173 102.285 126.699 102.383C126.462 102.427 126.23 102.496 126.008 102.588C125.787 102.68 125.575 102.793 125.376 102.925C125.175 103.058 124.988 103.21 124.817 103.38C124.645 103.552 124.491 103.74 124.357 103.942C124.224 104.14 124.112 104.351 124.023 104.572C123.93 104.793 123.859 105.022 123.813 105.257C123.764 105.493 123.739 105.734 123.739 105.975C123.742 106.456 123.839 106.933 124.023 107.378C124.112 107.599 124.224 107.81 124.357 108.008Z" fill="black"/><path d="M56.2782 68.3306H101.531C102.506 68.3306 103.441 67.9444 104.13 67.2571C104.819 66.5697 105.206 65.6374 105.206 64.6653C105.206 63.6933 104.819 62.761 104.13 62.0737C103.441 61.3863 102.506 61.0001 101.531 61.0001H56.2782C55.3035 61.0001 54.3687 61.3863 53.6794 62.0737C52.9902 62.761 52.603 63.6933 52.603 64.6653C52.603 65.6374 52.9902 66.5697 53.6794 67.2571C54.3687 67.9444 55.3035 68.3306 56.2782 68.3306Z" fill="black"/><path d="M87.3999 84.4722C87.3999 85.4443 87.7871 86.3766 88.4763 87.0639C89.1656 87.7513 90.1003 88.1375 91.075 88.1375H152.053C153.028 88.1375 153.962 87.7513 154.652 87.0639C155.341 86.3766 155.728 85.4443 155.728 84.4722C155.728 83.5002 155.341 82.5679 154.652 81.8805C153.962 81.1932 153.028 80.807 152.053 80.807H91.075C90.1003 80.807 89.1656 81.1932 88.4763 81.8805C87.7871 82.5679 87.3999 83.5002 87.3999 84.4722Z" fill="black"/><path d="M115.237 102.31H56.2782C55.3035 102.31 54.3687 102.696 53.6794 103.383C52.9902 104.071 52.603 105.003 52.603 105.975C52.603 106.947 52.9902 107.879 53.6794 108.567C54.3687 109.254 55.3035 109.64 56.2782 109.64H115.237C116.212 109.64 117.147 109.254 117.836 108.567C118.525 107.879 118.912 106.947 118.912 105.975C118.912 105.003 118.525 104.071 117.836 103.383C117.147 102.696 116.212 102.31 115.237 102.31Z" fill="black"/><path d="M63.555 43.9838C63.8942 44.3337 64.2999 44.6127 64.7484 44.8046C65.1968 44.9964 65.6791 45.0973 66.1671 45.1013C66.6551 45.1053 67.139 45.0124 67.5906 44.8279C68.0421 44.6434 68.4523 44.371 68.7972 44.0267C69.1421 43.6824 69.4148 43.273 69.5994 42.8225C69.784 42.372 69.8768 41.8893 69.8723 41.4027C69.8678 40.916 69.7662 40.4351 69.5734 39.988C69.3806 39.5409 69.1004 39.1366 68.7492 38.7987L63.5452 33.6087L68.5532 28.6191C68.8959 28.2787 69.168 27.8742 69.3539 27.4289C69.5398 26.9836 69.636 26.5061 69.6369 26.0237C69.6378 25.5414 69.5435 25.0635 69.3592 24.6175C69.1749 24.1715 68.9044 23.766 68.563 23.4243C68.2217 23.0825 67.8161 22.8112 67.3696 22.6258C66.9231 22.4403 66.4443 22.3444 65.9606 22.3435C65.4769 22.3426 64.9978 22.4367 64.5506 22.6205C64.1033 22.8042 63.6968 23.074 63.3541 23.4145L55.7539 31.0186C55.0669 31.7065 54.6812 32.6378 54.6812 33.6087C54.6812 34.5796 55.0669 35.5109 55.7539 36.1988L63.555 43.9838Z" fill="black"/><path d="M75.6931 48.0449C76.126 48.2598 76.5971 48.3873 77.0796 48.4201C77.562 48.4529 78.0461 48.3903 78.5042 48.2359C78.9623 48.0815 79.3852 47.8383 79.7488 47.5204C80.1124 47.2025 80.4094 46.816 80.6227 46.3833L91.6579 24.2698C91.8732 23.8388 92.0012 23.3698 92.0347 22.8895C92.0682 22.4092 92.0065 21.9271 91.8532 21.4705C91.6998 21.014 91.4578 20.5921 91.141 20.2288C90.8242 19.8656 90.4387 19.5682 90.0066 19.3535C89.5745 19.1388 89.1042 19.0111 88.6226 18.9777C88.141 18.9443 87.6575 19.0058 87.1998 19.1587C86.742 19.3117 86.3189 19.553 85.9547 19.869C85.5905 20.185 85.2922 20.5694 85.077 21.0004L74.0418 43.1188C73.8249 43.5502 73.6955 44.0201 73.6611 44.5014C73.6268 44.9828 73.6881 45.4662 73.8415 45.9239C73.9949 46.3816 74.2375 46.8046 74.5552 47.1685C74.873 47.5325 75.2597 47.8303 75.6931 48.0449Z" fill="black"/><path d="M139.371 131.754C138.498 131.322 137.488 131.253 136.564 131.562C135.639 131.872 134.876 132.535 134.441 133.405L123.411 155.519C123.192 155.95 123.06 156.421 123.024 156.903C122.988 157.385 123.048 157.87 123.201 158.329C123.353 158.788 123.596 159.213 123.913 159.578C124.231 159.943 124.618 160.242 125.053 160.458C125.487 160.673 125.959 160.801 126.443 160.833C126.927 160.865 127.413 160.802 127.872 160.646C128.331 160.491 128.755 160.246 129.119 159.926C129.483 159.606 129.779 159.218 129.992 158.783L141.018 136.67C141.453 135.8 141.524 134.794 141.215 133.872C140.906 132.95 140.243 132.188 139.371 131.754V131.754Z" fill="black"/><path d="M143.722 139.148C143.381 139.488 143.11 139.892 142.925 140.337C142.74 140.782 142.645 141.259 142.645 141.74C142.645 142.222 142.74 142.698 142.925 143.143C143.11 143.588 143.381 143.992 143.722 144.333L148.926 149.523L143.923 154.512C143.582 154.853 143.311 155.257 143.126 155.702C142.942 156.147 142.846 156.623 142.846 157.105C142.846 157.586 142.942 158.063 143.126 158.508C143.311 158.953 143.582 159.357 143.923 159.697C144.265 160.038 144.67 160.308 145.116 160.492C145.562 160.676 146.04 160.771 146.523 160.771C147.006 160.771 147.484 160.676 147.93 160.492C148.376 160.308 148.781 160.038 149.122 159.697L156.722 152.113C157.064 151.773 157.335 151.369 157.519 150.925C157.704 150.48 157.799 150.004 157.799 149.523C157.799 149.042 157.704 148.565 157.519 148.121C157.335 147.676 157.064 147.273 156.722 146.933L148.921 139.148C148.231 138.462 147.296 138.077 146.322 138.077C145.348 138.077 144.413 138.462 143.722 139.148V139.148Z" fill="black"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'mailgun' ) ) {
			$carriers['mailgun'] = [
				'name' => 'Mailgun',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-mailgun/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" style="enable-background:new 0 0 1000 1000;" xml:space="preserve"><path class="st0" d="M493,305.7c-88.9,0-161,72.1-161,161c0,88.9,72.1,161,161,161c88.9,0,161-72.1,161-161C654,377.8,582,305.7,493,305.7z M242,466.7c0-138.7,112.4-251,251-251c138.7,0,251.1,112.4,251.1,251c0,9.2-0.5,18.2-1.4,27.1c-1.9,24.5,16.1,43.2,40.4,43.2c41.3,0,45.7-53.2,45.7-70.3c0-185.4-150.3-335.6-335.6-335.6S157.4,281.4,157.4,466.7c0,185.4,150.3,335.6,335.6,335.6c98.4,0,187-42.4,248.4-109.9l69,57.9c-77.9,87.1-191.3,142-317.4,142c-235.1,0-425.7-190.6-425.7-425.7S257.9,41,493,41c235.1,0,425.7,190.6,425.7,425.7c0,94.5-45,171.2-135.4,171.2c-39.8,0-64-18.2-77.2-38.6C661.9,670.5,583,717.8,493,717.8C354.4,717.8,242,605.4,242,466.7z M493,393.1c40.7,0,73.7,33,73.7,73.7c0,40.7-33,73.7-73.7,73.7c-40.7,0-73.7-33-73.7-73.7S452.3,393.1,493,393.1z"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'pushbullet/push' ) ) {
			$carriers['pushbullet/push'] = [
				'name' => 'Pushbullet - push',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-pushbullet/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg width="2500" height="2500" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid"><defs><path id="a" d="M256 128c0 70.692-57.308 128-128 128C57.308 256 0 198.692 0 128 0 57.308 57.308 0 128 0c70.692 0 128 57.308 128 128"/><linearGradient x1="8.59%" y1="1.954%" x2="77.471%" y2="73.896%" id="c"><stop stop-color="#4CB36B" offset="0%"/><stop stop-color="#3EA16F" offset="100%"/></linearGradient></defs><mask id="b" fill="#fff"><use xlink:href="#a"/></mask><use fill="#67BF79" xlink:href="#a"/><path d="M256 128c0 70.692-57.308 128-128 128C57.308 256 0 198.692 0 128 0 57.308 57.308 0 128 0c70.692 0 128 57.308 128 128" fill="#67BF79" mask="url(#b)"/><path d="M63.111 187.022L96.178 72l64.533 60.978L200 90.133l87.533 86.289-110.844 124.889L63.111 187.022" fill="url(#c)" mask="url(#b)"/><path d="M77 189.6c-16.733 0-16.733 0-16.733-16.733V81c0-16.733 0-16.733 16.733-16.733h3.334c16.733 0 16.733 0 16.733 16.733v91.867c0 16.733 0 16.733-16.733 16.733H77zM121.041 189.6c-5.699 0-8.508-2.809-8.508-8.508V72.774c0-5.698 2.809-8.507 8.508-8.507h37.537c32.178 0 52.628 32.273 52.628 63.025 0 30.752-20.628 62.308-52.628 62.308h-37.537z" fill="#FFF" style="fill: #fff"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'pushbullet/sms' ) ) {
			$carriers['pushbullet/sms'] = [
				'name' => 'Pushbullet - SMS',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-pushbullet/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg width="2500" height="2500" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid"><defs><path id="a" d="M256 128c0 70.692-57.308 128-128 128C57.308 256 0 198.692 0 128 0 57.308 57.308 0 128 0c70.692 0 128 57.308 128 128"/><linearGradient x1="8.59%" y1="1.954%" x2="77.471%" y2="73.896%" id="c"><stop stop-color="#4CB36B" offset="0%"/><stop stop-color="#3EA16F" offset="100%"/></linearGradient></defs><mask id="b" fill="#fff"><use xlink:href="#a"/></mask><use fill="#67BF79" xlink:href="#a"/><path d="M256 128c0 70.692-57.308 128-128 128C57.308 256 0 198.692 0 128 0 57.308 57.308 0 128 0c70.692 0 128 57.308 128 128" fill="#67BF79" mask="url(#b)"/><path d="M63.111 187.022L96.178 72l64.533 60.978L200 90.133l87.533 86.289-110.844 124.889L63.111 187.022" fill="url(#c)" mask="url(#b)"/><path d="M77 189.6c-16.733 0-16.733 0-16.733-16.733V81c0-16.733 0-16.733 16.733-16.733h3.334c16.733 0 16.733 0 16.733 16.733v91.867c0 16.733 0 16.733-16.733 16.733H77zM121.041 189.6c-5.699 0-8.508-2.809-8.508-8.508V72.774c0-5.698 2.809-8.507 8.508-8.507h37.537c32.178 0 52.628 32.273 52.628 63.025 0 30.752-20.628 62.308-52.628 62.308h-37.537z" fill="#FFF" style="fill: #fff"/></svg>',
			];
		}

		if ( ! CarrierStore::has( 'pushover/push' ) ) {
			$carriers['pushover/push'] = [
				'name' => 'Pushover - push',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-pushover/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<?xml version="1.0" encoding="utf-8"?><svg width="602px" height="602px" viewBox="57 57 602 602" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="layer1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(58.964119, 58.887520)" opacity="0.91"><ellipse style="fill: rgb(36, 157, 241); fill-rule: evenodd; stroke: rgb(255, 255, 255); stroke-width: 0;" transform="matrix(-0.674571, 0.73821, -0.73821, -0.674571, 556.833239, 241.613465)" cx="216.308" cy="152.076" rx="296.855" ry="296.855"/><path d="M 280.949 172.514 L 355.429 162.714 L 282.909 326.374 L 282.909 326.374 C 295.649 325.394 308.142 321.067 320.389 313.394 L 320.389 313.394 L 320.389 313.394 C 332.642 305.714 343.916 296.077 354.209 284.484 L 354.209 284.484 L 354.209 284.484 C 364.496 272.884 373.396 259.981 380.909 245.774 L 380.909 245.774 L 380.909 245.774 C 388.422 231.561 393.812 217.594 397.079 203.874 L 397.079 203.874 L 397.079 203.874 C 399.039 195.381 399.939 187.214 399.779 179.374 L 399.779 179.374 L 399.779 179.374 C 399.612 171.534 397.569 164.674 393.649 158.794 L 393.649 158.794 L 393.649 158.794 C 389.729 152.914 383.766 148.177 375.759 144.584 L 375.759 144.584 L 375.759 144.584 C 367.759 140.991 356.899 139.194 343.179 139.194 L 343.179 139.194 L 343.179 139.194 C 327.172 139.194 311.409 141.807 295.889 147.034 L 295.889 147.034 L 295.889 147.034 C 280.376 152.261 266.002 159.857 252.769 169.824 L 252.769 169.824 L 252.769 169.824 C 239.542 179.784 228.029 192.197 218.229 207.064 L 218.229 207.064 L 218.229 207.064 C 208.429 221.924 201.406 238.827 197.159 257.774 L 197.159 257.774 L 197.159 257.774 C 195.526 263.981 194.546 268.961 194.219 272.714 L 194.219 272.714 L 194.219 272.714 C 193.892 276.474 193.812 279.577 193.979 282.024 L 193.979 282.024 L 193.979 282.024 C 194.139 284.477 194.462 286.357 194.949 287.664 L 194.949 287.664 L 194.949 287.664 C 195.442 288.971 195.852 290.277 196.179 291.584 L 196.179 291.584 L 196.179 291.584 C 179.519 291.584 167.349 288.234 159.669 281.534 L 159.669 281.534 L 159.669 281.534 C 151.996 274.841 150.119 263.164 154.039 246.504 L 154.039 246.504 L 154.039 246.504 C 157.959 229.191 166.862 212.694 180.749 197.014 L 180.749 197.014 L 180.749 197.014 C 194.629 181.334 211.122 167.531 230.229 155.604 L 230.229 155.604 L 230.229 155.604 C 249.342 143.684 270.249 134.214 292.949 127.194 L 292.949 127.194 L 292.949 127.194 C 315.656 120.167 337.789 116.654 359.349 116.654 L 359.349 116.654 L 359.349 116.654 C 378.296 116.654 394.219 119.347 407.119 124.734 L 407.119 124.734 L 407.119 124.734 C 420.026 130.127 430.072 137.234 437.259 146.054 L 437.259 146.054 L 437.259 146.054 C 444.446 154.874 448.936 165.164 450.729 176.924 L 450.729 176.924 L 450.729 176.924 C 452.529 188.684 451.959 200.934 449.019 213.674 L 449.019 213.674 L 449.019 213.674 C 445.426 229.027 438.646 244.464 428.679 259.984 L 428.679 259.984 L 428.679 259.984 C 418.719 275.497 406.226 289.544 391.199 302.124 L 391.199 302.124 L 391.199 302.124 C 376.172 314.697 358.939 324.904 339.499 332.744 L 339.499 332.744 L 339.499 332.744 C 320.066 340.584 299.406 344.504 277.519 344.504 L 277.519 344.504 L 275.069 344.504 L 212.839 484.154 L 142.279 484.154 L 280.949 172.514 Z" transform="matrix(1, 0, 0, 1, 0, 0)" style="fill: rgb(255, 255, 255); fill-rule: nonzero; white-space: pre;"/></g></svg>',
			];
		}

		if ( ! CarrierStore::has( 'sendgrid' ) ) {
			$carriers['sendgrid'] = [
				'name' => 'SendGrid',
				'pro'  => true,
				'link' => 'https://bracketspace.com/downloads/notification-sendgrid/?utm_source=wp&utm_medium=notification-carriers&utm_id=upsell',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 127.3 127.3"><g style="isolation:isolate"><g id="Layer_1" data-name="Layer 1"><polygon points="127.3 0 42.43 0 42.43 42.43 0 42.43 0 127.3 84.87 127.3 84.87 84.87 127.3 84.87 127.3 0" fill="#fff"/><polygon points="0 42.43 0 84.87 42.43 84.87 42.43 127.3 84.87 127.3 84.87 42.43 0 42.43" fill="#00b2e3" opacity="0.4"/><rect y="84.87" width="42.43" height="42.43" fill="#1a82e2"/><polygon points="84.87 42.43 84.87 0 42.43 0 42.43 42.43 42.43 84.87 84.87 84.87 127.3 84.87 127.3 42.43 84.87 42.43" fill="#00b2e3" style="mix-blend-mode:multiply"/><rect x="84.87" width="42.43" height="42.43" fill="#1a82e2"/></g></g></svg>',
			];
		}

		return $carriers;
	}

}