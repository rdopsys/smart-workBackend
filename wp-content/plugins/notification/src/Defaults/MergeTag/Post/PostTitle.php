<?php
/**
 * Post title merge tag
 *
 * Requirements:
 * - Trigger property of the post type slug with WP_Post object
 *
 * @package notification
 */

namespace BracketSpace\Notification\Defaults\MergeTag\Post;

use BracketSpace\Notification\Defaults\MergeTag\StringTag;
use BracketSpace\Notification\Utils\WpObjectHelper;

/**
 * Post title merge tag class
 */
class PostTitle extends StringTag {
	/**
	 * Merge tag constructor
	 *
	 * @since 5.0.0
	 * @param array $params merge tag configuration params.
	 */
	public function __construct( $params = [] ) {

		$this->set_trigger_prop( $params['post_type'] ?? 'post' );

		$post_type_name = WpObjectHelper::get_post_type_name( $this->get_trigger_prop() );

		$args = wp_parse_args(
			$params,
			[
				'slug'        => sprintf( '%s_title', $this->get_trigger_prop() ),
				// translators: singular post name.
				'name'        => sprintf( __( '%s title', 'notification' ), $post_type_name ),
				'description' => __( 'Hello World', 'notification' ),
				'example'     => true,
				'group'       => $post_type_name,
				'resolver'    => function ( $trigger ) {
					return html_entity_decode( get_the_title( $trigger->{ $this->get_trigger_prop() } ) );
				},
			]
		);

		parent::__construct( $args );

	}

}
