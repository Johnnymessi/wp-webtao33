<?php
/**
 * The Video Parser
 *
 * @since      2.0.0
 * @package    RankMath
 * @subpackage RankMath\Schema\Video
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMathPro\Schema\Video;

use RankMath\Helper;
use RankMath\Schema\DB;
use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Parser class.
 */
class Parser {

	/**
	 * Post.
	 *
	 * @var WP_Post
	 */
	private $post;

	/**
	 * Stored Video URLs.
	 *
	 * @var array
	 */
	private $urls;

	/**
	 * The Constructor.
	 *
	 * @param  WP_Post $post Post to parse.
	 */
	public function __construct( $post ) {
		$this->post = $post;
	}

	/**
	 * Save video object.
	 */
	public function save() {
		if (
			! ( $this->post instanceof \WP_Post ) ||
			wp_is_post_revision( $this->post->ID ) ||
			! Helper::get_settings( "titles.pt_{$this->post->post_type}_autodetect_video", 'on' )
		) {
			return;
		}

		$content = trim( $this->post->post_content . ' ' . $this->get_custom_fields_data() );
		if ( empty( $content ) ) {
			return;
		}

		$this->urls    = $this->get_video_urls();
		$content       = apply_filters( 'the_content', $content );
		$allowed_types = apply_filters( 'media_embedded_in_content_allowed_types', [ 'video', 'embed', 'iframe' ] );
		$tags          = implode( '|', $allowed_types );
		$videos        = [];

		preg_match_all( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $content, $matches );
		if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
			foreach ( $matches[0] as $html ) {
				$videos[] = $this->get_metadata( $html );
			}
		}

		$videos = array_merge( $videos, $this->get_links_from_shortcode( $content ) );
		$videos = array_filter(
			$videos,
			function( $video ) {
				return ! empty( $video['src'] ) ? $video['src'] : false;
			}
		);

		if ( empty( $videos ) ) {
			return;
		}

		$schemas = $this->get_default_schema_data();
		foreach ( $videos as $video ) {
			$schemas[] = [
				'@type'            => 'VideoObject',
				'metadata'         => [
					'title'                   => 'Video',
					'type'                    => 'template',
					'shortcode'               => uniqid( 's-' ),
					'isPrimary'               => empty( DB::get_schemas( $this->post->ID ) ),
					'reviewLocationShortcode' => '[rank_math_rich_snippet]',
					'category'                => '%categories%',
					'tags'                    => '%tags%',
					'isAutoGenerated'         => true,
				],
				'name'             => ! empty( $video['name'] ) ? $video['name'] : '%seo_title%',
				'description'      => ! empty( $video['description'] ) ? $video['description'] : '%seo_description%',
				'uploadDate'       => ! empty( $video['uploadDate'] ) ? $video['uploadDate'] : '%date(Y-m-dTH:i:sP)%',
				'thumbnailUrl'     => ! empty( $video['thumbnail'] ) ? $video['thumbnail'] : '%post_thumbnail%',
				'embedUrl'         => ! empty( $video['embed'] ) ? $video['src'] : '',
				'contentUrl'       => empty( $video['embed'] ) ? $video['src'] : '',
				'duration'         => ! empty( $video['duration'] ) ? $video['duration'] : '',
				'width'            => ! empty( $video['width'] ) ? $video['width'] : '',
				'height'           => ! empty( $video['height'] ) ? $video['height'] : '',
				'isFamilyFriendly' => ! empty( $video['isFamilyFriendly'] ) ? (bool) $video['isFamilyFriendly'] : true,
			];
		}

		foreach ( array_filter( $schemas ) as $schema ) {
			add_post_meta( $this->post->ID, "rank_math_schema_{$schema['@type']}", $schema );
		}
	}

	/**
	 * Get default schema data.
	 */
	private function get_default_schema_data() {
		if ( ! empty( DB::get_schemas( $this->post->ID ) ) ) {
			return [];
		}

		$default_type = Helper::get_default_schema_type( $this->post->ID, true );
		if ( ! $default_type ) {
			return [];
		}

		$is_article  = in_array( $default_type, [ 'Article', 'NewsArticle', 'BlogPosting' ], true );
		$schema_data = [];
		if ( $is_article ) {
			$schema_data = [
				'headline'      => Helper::get_settings( "titles.pt_{$this->post->post_type}_default_snippet_name" ),
				'description'   => Helper::get_settings( "titles.pt_{$this->post->post_type}_default_snippet_desc" ),
				'datePublished' => '%date(Y-m-dTH:i:sP)%',
				'dateModified'  => '%modified(Y-m-dTH:i:sP)%',
				'image'         => [
					'@type' => 'ImageObject',
					'url'   => '%post_thumbnail%',
				],
				'author'        => [
					'@type' => 'Person',
					'name'  => '%name%',
				],
			];
		}

		$schema_data['@type']    = $default_type;
		$schema_data['metadata'] = [
			'title'     => Helper::sanitize_schema_title( $default_type ),
			'type'      => 'template',
			'isPrimary' => true,
		];

		return [ $schema_data ];
	}

	/**
	 * Get Video source from the content.
	 *
	 * @param array $html Video Links.
	 *
	 * @return array
	 */
	public function get_metadata( $html ) {
		preg_match_all( '@src=[\'"]([^"]+)[\'"]@', $html, $matches );
		if ( empty( $matches ) || empty( $matches[1] ) ) {
			return false;
		}

		return $this->get_video_metadata( $matches[1][0] );
	}

	/**
	 * Validate Video source.
	 *
	 * @param  string $url Video Source.
	 * @return array
	 */
	private function get_video_metadata( $url ) {
		$url = preg_replace( '/\?.*/', '', $url ); // Remove query string from URL.
		if (
			$url &&
			(
				is_array( $this->urls ) &&
				(
					in_array( $url, $this->urls, true ) ||
					in_array( $url . '?feature=oembed', $this->urls, true )
				)
			)
		) {
			return false;
		}

		$this->urls[] = $url;
		$networks     = [
			'Video\Youtube',
			'Video\Vimeo',
			'Video\DailyMotion',
			'Video\TedVideos',
			'Video\VideoPress',
			'Video\WordPress',
		];

		$data = false;
		foreach ( $networks as $network ) {
			$data = \call_user_func( [ '\\RankMathPro\\Schema\\' . $network, 'match' ], $url );
			if ( is_array( $data ) ) {
				break;
			}
		}

		// Save image locally.
		if ( ! empty( $data['thumbnail'] ) ) {
			$data['thumbnail'] = $this->save_video_thumbnail( $data );
		}

		return $data;
	}

	/**
	 * Get Video Links from YouTube Embed plugin.
	 *
	 * @param  string $content Post Content.
	 * @return array
	 *
	 * Credit ridgerunner (https://stackoverflow.com/users/433790/ridgerunner)
	 */
	private function get_links_from_shortcode( $content ) {
		preg_match_all(
			'~
			https?://          # Required scheme. Either http or https.
			(?:[0-9A-Z-]+\.)?  # Optional subdomain.
			(?:                # Group host alternatives.
			youtu\.be/         # Either youtu.be,
			| youtube          # or youtube.com or
			(?:-nocookie)?     # youtube-nocookie.com
			\.com              # followed by
			\S*?               # Allow anything up to VIDEO_ID,
			[^\w\s-]           # but char before ID is non-ID char.
			)                  # End host alternatives.
			([\w-]{11})        # $1: VIDEO_ID is exactly 11 chars.
			(?=[^\w-]|$)       # Assert next char is non-ID or EOS.
			(?!                # Assert URL is not pre-linked.
			[?=&+%\w.-]*       # Allow URL (query) remainder.
			(?:                # Group pre-linked alternatives.
				[\'"][^<>]*>   # Either inside a start tag,
			| </a>             # or inside <a> element text contents.
			)                  # End recognized pre-linked alts.
			)                  # End negative lookahead assertion.
			[?=&+%\w.-]*       # Consume any URL (query) remainder.
			~ix',
			$content,
			$matches
		);

		if ( empty( $matches ) || empty( $matches[1] ) ) {
			return [];
		}

		$data = [];
		foreach ( $matches[1] as $video_id ) {
			$data[] = $this->get_video_metadata( "https://www.youtube.com/embed/{$video_id}" );
		}

		return $data;
	}

	/**
	 * Validate Video source.
	 *
	 * @param  array $data Video data.
	 * @return array
	 *
	 * Credits to m1r0 @ https://gist.github.com/m1r0/f22d5237ee93bcccb0d9
	 */
	private function save_video_thumbnail( $data ) {
		$url = $data['thumbnail'];
		if ( ! Helper::get_settings( "titles.pt_{$this->post->post_type}_autogenerate_image", 'off' ) ) {
			return false;
		}

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once( ABSPATH . WPINC . '/class-http.php' );
		}

		$url      = explode( '?', $url )[0];
		$http     = new \WP_Http();
		$response = $http->request( $url );
		if ( 200 !== $response['response']['code'] ) {
			return false;
		}

		$image_title = __( 'Video Thumbnail', 'rank-math-pro' );
		if ( ! empty( $data['name'] ) ) {
			$image_title = $data['name'];
		} elseif ( ! empty( $this->post->post_title ) ) {
			$image_title = $this->post->post_title;
		}
		$filename = substr( sanitize_title( $image_title, 'video-thumbnail' ), 0, 32 ) . '.jpg';

		/**
		 * Filter the filename of the video thumbnail.
		 *
		 * @param string $filename The filename of the video thumbnail.
		 * @param array  $data     The video data.
		 * @param object $post     The post object.
		 */
		$filename = apply_filters( 'rank_math/schema/video_thumbnail_filename', $filename, $data, $this->post );

		$upload = wp_upload_bits( sanitize_file_name( $filename ), null, $response['body'] );
		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path     = $upload['file'];
		$file_name     = basename( $file_path );
		$file_type     = wp_check_filetype( $file_name, null );
		$wp_upload_dir = wp_upload_dir();

		// Translators: Placeholder is the image title.
		$attachment_title = sprintf( __( 'Video Thumbnail: %s', 'rank-math-pro' ), $image_title );

		/**
		 * Filter the attachment title of the video thumbnail.
		 *
		 * @param string $attachment_title The attachment title of the video thumbnail.
		 * @param array  $data             The video data.
		 * @param object $post             The post object.
		 */
		$attachment_title = apply_filters( 'rank_math/schema/video_thumbnail_attachment_title', $attachment_title, $data, $this->post );

		$post_info = [
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		];

		$attach_id = wp_insert_attachment( $post_info, $file_path, $this->post->ID );

		// Include image.php.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return wp_get_attachment_url( $attach_id );
	}

	/**
	 * Get Video URls stored in VideoObject schema.
	 *
	 * @return array
	 */
	private function get_video_urls() {
		$schemas = DB::get_schemas( $this->post->ID );
		if ( empty( $schemas ) ) {
			return [];
		}

		$urls = [];
		foreach ( $schemas as $schema ) {
			if ( empty( $schema['@type'] ) || 'VideoObject' !== $schema['@type'] ) {
				continue;
			}

			$urls[] = ! empty( $schema['embedUrl'] ) ? $schema['embedUrl'] : '';
			$urls[] = ! empty( $schema['contentUrl'] ) ? $schema['contentUrl'] : '';
		}

		return array_filter( $urls );
	}

	/**
	 * Get Custom fields data.
	 */
	private function get_custom_fields_data() {
		$custom_fields = Str::to_arr_no_empty( Helper::get_settings( 'sitemap.video_sitemap_custom_fields' ) );
		if ( empty( $custom_fields ) ) {
			return;
		}

		$content = '';
		foreach ( $custom_fields as $custom_field ) {
			$content = $content . ' ' . get_post_meta( $this->post->ID, $custom_field, true );
		}

		return trim( $content );
	}
}
