<?php
/**
 * The class that holds all the settings for each schema type.
 *
 * @package     posterno_schema
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl_2.0.php GNU Public License
 */

namespace PNO\SchemaOrg\Settings;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Bootstraps the schema settings collection.
 */
class SettingsCollection {

	/**
	 * Retrieve settings that belong to a specific schema.
	 *
	 * @param string $schema the schema to look for.
	 * @return array
	 */
	public static function get_settings( $schema ) {

		$settings = [];

		switch ( $schema ) {
			case 'Article':
				$settings = self::get_article_settings();
				break;
			case 'Book':
				$settings = self::get_book_settings();
				break;
			case 'Course':
				$settings = self::get_course_settings();
				break;
			case 'Event':
				$settings = self::get_event_settings();
				break;
			case 'JobPosting':
				$settings = self::get_job_settings();
				break;
			case 'LocalBusiness':
				$settings = self::get_place_settings();
				break;
			case 'Review':
				$settings = self::get_review_settings();
				break;
			case 'Person':
				$settings = self::get_person_settings();
				break;
			case 'Product':
				$settings = self::get_product_settings();
				break;
			case 'Recipe':
				$settings = self::get_recipe_settings();
				break;
			case 'Service':
				$settings = self::get_service_settings();
				break;
			case 'SoftwareApplication':
				$settings = self::get_software_settings();
				break;
			case 'VideoObject':
				$settings = self::get_video_settings();
				break;
		}

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_article_settings() {
		$settings = [
			'main_entity'      => array(
				'label'   => esc_html__( 'URL' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'post_permalink',
			),
			'name'             => array(
				'label'    => esc_html__( 'Headline' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'image'            => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'file',
				'default'  => 'featured_img',
				'required' => true,
			),
			'orgnization_name' => array(
				'label'    => esc_html__( 'Publisher Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'site_title',
				'required' => true,
			),
			'site_logo'        => array(
				'label'    => esc_html__( 'Publisher Logo' ),
				'type'     => 'file',
				'default'  => 'site_logo',
				'required' => true,
			),
			'published_date'   => array(
				'label'    => esc_html__( 'Published Date' ),
				'type'     => self::get_date_value_field_types(),
				'default'  => 'post_date',
				'required' => true,
			),
			'modified_date'    => array(
				'label'    => esc_html__( 'Modified Date' ),
				'type'     => self::get_date_value_field_types(),
				'default'  => 'post_modified',
				'required' => true,
			),
			'author'           => array(
				'label'    => esc_html__( 'Author Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'author_name',
				'required' => true,
			),
			'description'      => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => self::get_multiline_value_field_types(),
				'default' => 'post_excerpt',
			),
			'rating'           => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'     => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_book_settings() {
		$settings = [
			'name'         => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'image'        => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'file',
				'default' => 'featured_img',
			),
			'author'       => array(
				'label'    => esc_html__( 'Author Name' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'url'          => array(
				'label'    => esc_html__( 'URL' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_permalink',
				'required' => true,
			),
			'work_example' => array(
				'label'    => esc_html__( 'Work Example' ),
				'type'     => 'repeater',
				'required' => true,
				'fields'   => array(
					'serial_number'   => array(
						'label'    => esc_html__( 'ISBN' ),
						'type'     => self::get_single_value_field_types(),
						'required' => true,
					),
					'book_edition'    => array(
						'label' => esc_html__( 'Book Edition' ),
						'type'  => self::get_single_value_field_types(),
					),
					'book_format'     => array(
						'label'         => esc_html__( 'Book Format' ),
						'type'          => self::get_single_value_field_types(),
						'dropdown_type' => 'book_format',
						'required'      => true,
					),
					'url_template'    => array(
						'label'    => esc_html__( 'URL Template' ),
						'type'     => self::get_single_value_field_types(),
						'required' => true,
					),
					'action_platform' => array(
						'label'         => esc_html__( 'Action Platform' ),
						'type'          => self::get_multiline_value_field_types(),
						'dropdown_type' => 'action_platform',
						'required'      => true,
					),
					'price'           => array(
						'label'    => esc_html__( 'Price' ),
						'type'     => self::get_single_value_field_types(),
						'attrs'    => array(
							'min'  => '0',
							'step' => 'any',
						),
					),
					'currency'        => array(
						'label'         => esc_html__( 'Currency' ),
						'type'          => self::get_single_value_field_types(),
						'required'      => true,
						'dropdown_type' => 'currency',
					),
					'country'         => array(
						'label'         => esc_html__( 'Eligible Region' ),
						'type'          => self::get_multiline_value_field_types(),
						'dropdown_type' => 'country',
					),
					'avail'           => array(
						'label'         => esc_html__( 'Availability' ),
						'type'          => self::get_single_value_field_types(),
						'dropdown_type' => 'availability',
					),
				),
			),
			'same_as'      => array(
				'label' => esc_html__( 'Same As' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_course_settings() {

		$settings = [
			'name'             => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'description'      => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => self::get_multiline_value_field_types(),
				'default'  => 'post_content',
				'required' => true,
			),
			'course_code'      => array(
				'label' => esc_html__( 'Course Code' ),
				'type'  => self::get_single_value_field_types(),
			),
			'course_instance'  => array(
				'label'  => esc_html__( 'Course Instance' ),
				'type'   => 'repeater',
				'fields' => array(
					'name'             => array(
						'label'    => esc_html__( 'Instance Name' ),
						'type'     => self::get_single_value_field_types(),
						'required' => true,
					),
					'description'      => array(
						'label'    => esc_html__( 'Instance Description' ),
						'type'     => self::get_multiline_value_field_types(),
						'required' => true,
					),
					'course_mode'      => array(
						'label' => esc_html__( 'Course Mode' ),
						'type'  => self::get_single_value_field_types(),
					),
					'image'            => array(
						'label' => esc_html__( 'Image' ),
						'type'  => 'file',
					),
					'start_date'       => array(
						'label'    => esc_html__( 'Start Date' ),
						'type'     => self::get_date_value_field_types(),
						'required' => true,
					),
					'end_date'         => array(
						'label' => esc_html__( 'End Date' ),
						'type'  => self::get_date_value_field_types(),
					),
					'location_name'    => array(
						'label' => esc_html__( 'Location Name' ),
						'type'  => self::get_single_value_field_types(),
					),
					'location_address' => array(
						'label'    => esc_html__( 'Location Address' ),
						'type'     => self::get_single_value_field_types(),
						'required' => true,
					),
					'price'            => array(
						'label' => esc_html__( 'Price' ),
						'type'  => self::get_single_value_field_types(),
						'attrs' => array(
							'min'  => '0',
							'step' => 'any',
						),
					),
					'currency'         => array(
						'label'         => esc_html__( 'Currency' ),
						'type'          => self::get_single_value_field_types(),
						'dropdown_type' => 'currency',
					),
					'valid_from'       => array(
						'label' => esc_html__( 'Valid From' ),
						'type'  => self::get_date_value_field_types(),
					),
					'url'              => array(
						'label' => esc_html__( 'Offer URL' ),
						'type'  => self::get_single_value_field_types(),
					),
					'avail'            => array(
						'label'         => esc_html__( 'Availability' ),
						'type'          => self::get_single_value_field_types(),
						'dropdown_type' => 'availability',
					),
					'performer'        => array(
						'label' => esc_html__( 'Performer' ),
						'type'  => self::get_single_value_field_types(),
					),
				),
			),
			'orgnization_name' => array(
				'label'   => esc_html__( 'Organization Name' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'site_title',
			),
			'rating'           => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'     => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
			'same_as'          => array(
				'label' => esc_html__( 'Same As' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;

	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_event_settings() {
		$settings = [
			'name'              => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'image'             => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'file',
				'default' => 'featured_img',
			),
			'location'          => array(
				'label' => esc_html__( 'Location Name' ),
				'type'  => self::get_single_value_field_types(),
			),
			'location_street'   => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_locality' => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_postal'   => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_region'   => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_country'  => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => self::get_single_value_field_types(),
				'required'      => true,
				'dropdown_type' => 'country',
			),
			'start_date'        => array(
				'label'    => esc_html__( 'Start Date' ),
				'type'     => self::get_date_value_field_types(),
				'required' => true,
			),
			'end_date'          => array(
				'label' => esc_html__( 'End Date' ),
				'type'  => self::get_date_value_field_types(),
			),
			'avail'             => array(
				'label'         => esc_html__( 'Offer Availability' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'availability',
			),
			'price'             => array(
				'label' => esc_html__( 'Offer Price' ),
				'type'  => self::get_single_value_field_types(),
				'attrs' => array(
					'min'  => '0',
					'step' => 'any',
				),
			),
			'currency'          => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'currency',
			),
			'valid_from'        => array(
				'label' => esc_html__( 'Offer Valid From' ),
				'type'  => self::get_date_value_field_types(),
			),
			'ticket_buy_url'    => array(
				'label' => esc_html__( 'Ticket Link' ),
				'type'  => self::get_single_value_field_types(),
			),
			'performer'         => array(
				'label' => esc_html__( 'Performer' ),
				'type'  => self::get_single_value_field_types(),
			),
			'description'       => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => self::get_multiline_value_field_types(),
				'default' => 'post_content',
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_job_settings() {
		$settings = [
			'title'                   => array(
				'label'    => esc_html__( 'Title' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'job_type'                => array(
				'label'         => esc_html__( 'Employment Type' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'employment',
			),
			'orgnization_name'        => array(
				'label'    => esc_html__( 'Organization Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'site_title',
				'required' => true,
			),
			'same_as'                 => array(
				'label'    => esc_html__( 'URL' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'description'             => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => self::get_multiline_value_field_types(),
				'default'  => 'post_content',
				'required' => true,
			),
			'education_requirements'  => array(
				'label' => esc_html__( 'Education Requirements' ),
				'type'  => self::get_single_value_field_types(),
			),
			'experience_requirements' => array(
				'label' => esc_html__( 'Experience Requirements' ),
				'type'  => self::get_single_value_field_types(),
			),
			'industry'                => array(
				'label' => esc_html__( 'Industry' ),
				'type'  => self::get_single_value_field_types(),
			),
			'qualifications'          => array(
				'label' => esc_html__( 'Qualifications' ),
				'type'  => self::get_single_value_field_types(),
			),
			'responsibilities'        => array(
				'label' => esc_html__( 'Responsibilities' ),
				'type'  => self::get_single_value_field_types(),
			),
			'skills'                  => array(
				'label' => esc_html__( 'Skills' ),
				'type'  => self::get_single_value_field_types(),
			),
			'work_hours'              => array(
				'label' => esc_html__( 'Work Hours' ),
				'type'  => self::get_single_value_field_types(),
			),
			'start_date'              => array(
				'label'    => esc_html__( 'Date Posted' ),
				'type'     => self::get_date_value_field_types(),
				'default'  => 'post_date',
				'required' => true,
			),
			'expiry_date'             => array(
				'label' => esc_html__( 'Valid Through' ),
				'type'  => self::get_date_value_field_types(),
			),
			'location_street'         => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_locality'       => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_postal'         => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_region'         => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_country'        => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => self::get_single_value_field_types(),
				'required'      => true,
				'dropdown_type' => 'country',
			),
			'salary'                  => array(
				'label' => esc_html__( 'Base Salary' ),
				'type'  => self::get_single_value_field_types(),
			),
			'salary_min_value'        => array(
				'label'   => esc_html__( 'Min Salary' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'create_field',
			),
			'salary_max_value'        => array(
				'label'   => esc_html__( 'Max Salary' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'create_field',
			),
			'salary_currency'         => array(
				'label'         => esc_html__( 'Salary In Currency' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'currency',
			),
			'salary_unit'             => array(
				'label'         => esc_html__( 'Salary Per Unit' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'time_unit',
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_place_settings() {

		$settings = [
			'name'                => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'site_title',
				'required' => true,
			),
			'image'               => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'file',
				'required' => true,
			),
			'telephone'           => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => self::get_single_value_field_types(),
			),
			'url'                 => array(
				'label'   => esc_html__( 'URL' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'site_url',
			),
			'location_street'     => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_locality'   => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_postal'     => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_region'     => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'location_country'    => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'country',
			),
			'hours_specification' => array(
				'label'  => esc_html__( 'Hours Specification' ),
				'type'   => 'repeater',
				'fields' => array(
					'days'   => array(
						'label'         => esc_html__( 'Day Of Week' ),
						'type'          => self::get_multiline_value_field_types(),
						'required'      => true,
						'dropdown_type' => 'days',
					),
					'opens'  => array(
						'label'    => esc_html__( 'Opens' ),
						'type'     => self::get_date_value_field_types(),
						'required' => true,
					),
					'closes' => array(
						'label'    => esc_html__( 'Closes' ),
						'type'     => self::get_date_value_field_types(),
						'required' => true,
					),
				),
			),
			'rating'              => array(
				'label'   => esc_html__( 'Rating' ),
				'type'    => 'rating',
				'default' => 'accept_user_rating',
			),
			'review_count'        => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
			'price_range'         => array(
				'label'    => esc_html__( 'Price Range' ),
				'type'     => self::get_single_value_field_types(),
			),
		];

		return $settings;

	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_review_settings() {

		$settings = [
			'item'          => array(
				'label'    => esc_html__( 'Review Item' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'item_image'    => array(
				'label' => esc_html__( 'Review Item Image' ),
				'type'  => 'file',
			),
			'description'   => array(
				'label'    => esc_html__( 'Review Description' ),
				'type'     => self::get_multiline_value_field_types(),
				'default'  => 'post_content',
				'required' => true,
			),
			'date'          => array(
				'label'    => esc_html__( 'Review Date' ),
				'type'     => self::get_date_value_field_types(),
				'default'  => 'post_date',
				'required' => true,
			),
			'rating'        => array(
				'label' => esc_html__( 'Review Rating' ),
				'type'  => 'rating',
			),
			'reviewer_name' => array(
				'label'   => esc_html__( 'Reviewer Name' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'author_name',
			),
		];

		return $settings;

	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_person_settings() {

		$settings = [
			'name'         => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'street'       => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),
			'locality'     => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),
			'postal'       => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),
			'region'       => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),
			'country'      => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'country',
			),
			'email'        => array(
				'label'    => esc_html__( 'Email' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),
			'gender'       => array(
				'label'         => esc_html__( 'Gender' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'gender_select',
			),
			'dob'          => array(
				'label' => esc_html__( 'Date of Birth' ),
				'type'  => self::get_date_value_field_types(),
			),
			'member'       => array(
				'label' => esc_html__( 'Member of' ),
				'type'  => self::get_single_value_field_types(),
			),
			'nationality'  => array(
				'label'    => esc_html__( 'Nationality' ),
				'type'     => self::get_single_value_field_types(),
				'required' => false,
			),

			'image'        => array(
				'label' => esc_html__( 'Photograph' ),
				'type'  => 'file',
			),
			'job_title'    => array(
				'label' => esc_html__( 'Job Title' ),
				'type'  => self::get_single_value_field_types(),
			),
			'homepage_url' => array(
				'label' => esc_html__( 'Homepage URL' ),
				'type'  => self::get_single_value_field_types(),
			),
			'add_url'      => array(
				'label'  => esc_html__( 'Same As' ),
				'type'   => 'repeater',
				'fields' => array(
					'same_as' => array(
						'label'    => esc_html__( 'URL' ),
						'type'     => self::get_single_value_field_types(),
						'required' => false,
					),
				),
			),
			'telephone'    => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;

	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_product_settings() {

		$settings = [
			'name'         => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'brand_name'   => array(
				'label'   => esc_html__( 'Brand Name' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'none',
			),
			'image'        => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'file',
				'default'  => 'featured_img',
				'required' => true,
			),
			'description'  => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => self::get_multiline_value_field_types(),
				'default' => 'post_content',
			),
			'avail'        => array(
				'label'         => esc_html__( 'Availability' ),
				'type'          => self::get_single_value_field_types(),
				'dropdown_type' => 'availability',
				'required'      => true,
			),
			'price'        => array(
				'label'    => esc_html__( 'Price' ),
				'type'     => self::get_single_value_field_types(),
				'attrs'    => array(
					'min'  => '0',
					'step' => '0.01',
				),
			),
			'currency'     => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => self::get_single_value_field_types(),
				'required'      => true,
				'dropdown_type' => 'currency',
			),
			'rating'       => array(
				'label'   => esc_html__( 'Rating' ),
				'type'    => 'rating',
				'default' => 'accept_user_rating',
			),
			'review_count' => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;

	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_recipe_settings() {
		$settings = [
			'name'                => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'none',
				'required' => true,
			),
			'image'               => array(
				'label'    => esc_html__( 'Photo' ),
				'type'     => 'file',
				'default'  => 'featured_img',
				'required' => true,
			),
			'description'         => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => self::get_multiline_value_field_types(),
				'default' => 'post_content',
			),
			'author'              => array(
				'label'   => esc_html__( 'Author Name' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'author_name',
			),
			'preperation_time'    => array(
				'label'   => esc_html__( 'Preparation Time' ),
				'type'    => self::get_date_value_field_types(),
				'default' => 'none',
			),
			'cook_time'           => array(
				'label'   => esc_html__( 'Cook Time' ),
				'type'    => self::get_date_value_field_types(),
				'default' => 'none',
			),
			'recipe_keywords'     => array(
				'label'   => esc_html__( 'Keywords' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'create_field',
			),
			'recipe_category'     => array(
				'label'   => esc_html__( 'Category' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'create_field',
			),
			'recipe_cuisine'      => array(
				'label'   => esc_html__( 'Cuisine' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'create_field',
			),
			'nutrition'           => array(
				'label'   => esc_html__( 'Calories' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'none',
			),
			'ingredients'         => array(
				'label'   => esc_html__( 'Ingredients' ),
				'type'    => self::get_single_value_field_types(),
				'default' => 'none',
			),
			'recipe_instructions' => array(
				'label'  => esc_html__( 'Instructions' ),
				'type'   => 'repeater',
				'fields' => array(
					'steps' => array(
						'label'   => esc_html__( 'Instructions Step' ),
						'type'    => self::get_single_value_field_types(),
						'default' => 'create_field',
					),
				),
			),
			'recipe_video'        => array(
				'label'  => esc_html__( 'Video' ),
				'type'   => 'repeater',
				'fields' => array(
					'video_name'                     => array(
						'label'    => esc_html__( 'Video Name' ),
						'type'     => self::get_single_value_field_types(),
						'default'  => 'create_field',
						'required' => true,
					),
					'video_desc'                     => array(
						'label'    => esc_html__( 'Video Description' ),
						'type'     => self::get_multiline_value_field_types(),
						'default'  => 'create_field',
						'required' => true,
					),
					'video_image'                    => array(
						'label'    => esc_html__( 'Thumbnail Url' ),
						'type'     => 'file',
						'default'  => 'create_field',
						'required' => true,
					),
					'recipe_video_content_url'       => array(
						'label'   => esc_html__( 'Content URL' ),
						'type'    => self::get_single_value_field_types(),
						'default' => 'create_field',
					),
					'recipe_video_embed_url'         => array(
						'label'   => esc_html__( 'Embed URL' ),
						'type'    => self::get_single_value_field_types(),
						'default' => 'create_field',
					),
					'recipe_video_duration'          => array(
						'label'   => esc_html__( 'Duration' ),
						'type'    => self::get_date_value_field_types(),
						'default' => 'create_field',
					),
					'recipe_video_upload_date'       => array(
						'label'    => esc_html__( 'Upload Date' ),
						'type'     => self::get_date_value_field_types(),
						'default'  => 'post_date',
						'required' => true,
					),
					'recipe_video_expires_date'      => array(
						'label'   => esc_html__( 'Expires On' ),
						'type'    => self::get_date_value_field_types(),
						'default' => 'create_field',
					),
					'recipe_video_interaction_count' => array(
						'label'   => esc_html__( 'Interaction Count' ),
						'type'    => self::get_single_value_field_types(),
						'default' => 'create_field',
					),
				),
			),
			'rating'              => array(
				'label'   => esc_html__( 'Rating' ),
				'type'    => 'rating',
				'default' => 'accept_user_rating',
			),
			'review_count'        => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_service_settings() {
		$settings = [
			'name'              => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'type'              => array(
				'label'    => esc_html__( 'Type' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'area'              => array(
				'label' => esc_html__( 'Area' ),
				'type'  => self::get_single_value_field_types(),
			),
			'description'       => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => self::get_multiline_value_field_types(),
				'default' => 'post_content',
			),
			'image'             => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'file',
				'default' => 'featured_img',
			),
			'provider'          => array(
				'label'    => esc_html__( 'Provider Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'site_title',
				'required' => true,
			),
			'location_locality' => array(
				'label' => esc_html__( 'Locality' ),
				'type'  => self::get_single_value_field_types(),
			),
			'location_region'   => array(
				'label' => esc_html__( 'Region' ),
				'type'  => self::get_single_value_field_types(),
			),
			'location_street'   => array(
				'label' => esc_html__( 'Street Address' ),
				'type'  => self::get_single_value_field_types(),
			),
			'location_image'    => array(
				'label'    => esc_html__( 'Location Image' ),
				'type'     => 'file',
				'required' => true,
			),
			'telephone'         => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => self::get_single_value_field_types(),
			),
			'price_range'       => array(
				'label' => esc_html__( 'Price Range' ),
				'type'  => self::get_single_value_field_types(),
			),
			'rating'            => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'      => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_software_settings() {
		$settings = [
			'name'             => array(
				'label'    => esc_html__( 'Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'operating_system' => array(
				'label'    => esc_html__( 'Operating System' ),
				'type'     => self::get_single_value_field_types(),
				'required' => true,
			),
			'category'         => array(
				'label'         => esc_html__( 'Category' ),
				'type'          => self::get_single_value_field_types(),
				'required'      => true,
				'dropdown_type' => 'software_category',
			),
			'image'            => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'file',
				'default' => 'featured_img',
			),
			'price'            => array(
				'label'    => esc_html__( 'Price' ),
				'type'     => self::get_single_value_field_types(),
				'attrs'    => array(
					'min'  => '0',
					'step' => 'any',
				),
			),
			'currency'         => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => self::get_single_value_field_types(),
				'required'      => true,
				'dropdown_type' => 'currency',
			),
			'rating'           => array(
				'label'   => esc_html__( 'Rating' ),
				'type'    => 'rating',
				'default' => 'accept_user_rating',
			),
			'review_count'     => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;
	}

	/**
	 * Settings list for this specific schema.
	 *
	 * @return array
	 */
	private static function get_video_settings() {

		$settings = [
			'name'              => array(
				'label'    => esc_html__( 'Title' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'post_title',
				'required' => true,
			),
			'description'       => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => self::get_multiline_value_field_types(),
				'default'  => 'post_content',
				'required' => true,
			),
			'image'             => array(
				'label'    => esc_html__( 'Thumbnail' ),
				'type'     => 'file',
				'default'  => 'featured_img',
				'required' => true,
			),
			'orgnization_name'  => array(
				'label'    => esc_html__( 'Publisher Name' ),
				'type'     => self::get_single_value_field_types(),
				'default'  => 'site_title',
				'required' => true,
			),
			'site_logo'         => array(
				'label'    => esc_html__( 'Publisher Logo' ),
				'type'     => 'file',
				'default'  => 'site_logo',
				'required' => true,
			),
			'content_url'       => array(
				'label' => esc_html__( 'Content URL' ),
				'type'  => self::get_single_value_field_types(),
			),
			'embed_url'         => array(
				'label' => esc_html__( 'Embed URL' ),
				'type'  => self::get_single_value_field_types(),
			),
			'duration'          => array(
				'label' => esc_html__( 'Duration' ),
				'type'  => self::get_date_value_field_types(),
			),
			'upload_date'       => array(
				'label'    => esc_html__( 'Upload Date' ),
				'type'     => self::get_date_value_field_types(),
				'default'  => 'post_date',
				'required' => true,
			),
			'expires_date'      => array(
				'label' => esc_html__( 'Expires On' ),
				'type'  => self::get_date_value_field_types(),
			),
			'interaction_count' => array(
				'label' => esc_html__( 'Interaction Count' ),
				'type'  => self::get_single_value_field_types(),
			),
		];

		return $settings;

	}

	/**
	 * Get additional settings that belong to listing post meta and site details.
	 *
	 * @return array
	 */
	public static function get_meta_settings() {

		$meta = [
			'site'         => [
				'label'    => esc_html__( 'Site details' ),
				'settings' => [
					'site_title' => esc_html__( 'Site title' ),
					'site_url'   => esc_html__( 'Site URL' ),
				],
			],
			'listing_meta' => [
				'label'    => esc_html__( 'Other listing details' ),
				'settings' => [
					'listing_url'               => esc_html__( 'Listing URL' ),
					'listing_author_name'       => esc_html__( 'Listing author name' ),
					'listing_author_first_name' => esc_html__( 'Listing author first name' ),
					'listing_author_last_name'  => esc_html__( 'Listing author last name' ),
					'listing_publish_date'      => esc_html__( 'Listing publish date' ),
					'listing_modified_date'     => esc_html__( 'Listing last modified date' ),
				],
			],
		];

		return $meta;

	}

	/**
	 * Retrieve allowed field types for single line value type of properties.
	 *
	 * @return array
	 */
	private static function get_single_value_field_types() {

		$types = [
			'text',
			'date',
			'number',
			'select',
			'radio',
			'checkbox',
			'term-select',
			'url',

		];

		return $types;

	}

	/**
	 * Retrieve allowed field types for multi line value type of properties.
	 *
	 * @return array
	 */
	private static function get_multiline_value_field_types() {

		$types = [
			'textarea',
			'editor',
			'multiselect',
			'multicheckbox',
			'term-multiselect',
			'term-checklist',
			'term-chain-dropdown',
		];

		return $types;

	}

	/**
	 * Retrieve allowed field types for date value type of properties.
	 *
	 * @return array
	 */
	private static function get_date_value_field_types() {

		$types = [
			'text',
			'date',
		];

		return $types;

	}

	/**
	 * Mock allowed field type for static meta fields displayed while
	 * assigning fields to a schema property.
	 *
	 * @param string $field_id the field for which we're generating mock types.
	 * @return string|boolean|array
	 */
	public static function get_type_for_meta_field( $field_id ) {

		$type = false;

		if ( ! $field_id ) {
			return;
		}

		switch ( $field_id ) {
			case 'site_title':
			case 'site_url':
			case 'listing_url':
			case 'listing_author_name':
			case 'listing_author_first_name':
			case 'listing_author_last_name':
				$type = self::get_single_value_field_types();
				break;
			case 'listing_publish_date':
			case 'listing_modified_date':
				$type = self::get_date_value_field_types();
				break;
		}

		return $type;

	}

}
