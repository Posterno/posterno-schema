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
				'type'    => 'text',
				'default' => 'post_permalink',
			),
			'name'             => array(
				'label'    => esc_html__( 'Headline' ),
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'image'            => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'image',
				'default'  => 'featured_img',
				'required' => true,
			),
			'orgnization_name' => array(
				'label'    => esc_html__( 'Publisher Name' ),
				'type'     => 'text',
				'default'  => 'blogname',
				'required' => true,
			),
			'site_logo'        => array(
				'label'    => esc_html__( 'Publisher Logo' ),
				'type'     => 'image',
				'default'  => 'site_logo',
				'required' => true,
			),
			'published_date'   => array(
				'label'    => esc_html__( 'Published Date' ),
				'type'     => 'date',
				'default'  => 'post_date',
				'required' => true,
			),
			'modified_date'    => array(
				'label'    => esc_html__( 'Modified Date' ),
				'type'     => 'date',
				'default'  => 'post_modified',
				'required' => true,
			),
			'author'           => array(
				'label'    => esc_html__( 'Author Name' ),
				'type'     => 'text',
				'default'  => 'author_name',
				'required' => true,
			),
			'description'      => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => 'textarea',
				'default' => 'post_excerpt',
			),
			'rating'           => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'     => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => 'number',
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'image'        => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'image',
				'default' => 'featured_img',
			),
			'author'       => array(
				'label'    => esc_html__( 'Author Name' ),
				'type'     => 'text',
				'required' => true,
			),
			'url'          => array(
				'label'    => esc_html__( 'URL' ),
				'type'     => 'text',
				'default'  => 'post_permalink',
				'required' => true,
			),
			'work_example' => array(
				'label'    => esc_html__( 'Work Example' ),
				'type'     => 'repeater',
				'required' => true,
				'fields'   => array(
					'serial_number'   => array(
						'label'       => esc_html__( 'ISBN' ),
						'type'        => 'number',
						'required'    => true,
						'description' => esc_html__( 'The International Standard Book Number (ISBN) is a unique numeric commercial book identifier. ISBN having 10 or 13 digit number.' ),
					),
					'book_edition'    => array(
						'label' => esc_html__( 'Book Edition' ),
						'type'  => 'text',
					),
					'book_format'     => array(
						'label'         => esc_html__( 'Book Format' ),
						'type'          => 'dropdown',
						'dropdown_type' => 'book_format',
						'required'      => true,
					),
					'url_template'    => array(
						'label'       => esc_html__( 'URL Template' ),
						'type'        => 'text',
						'required'    => true,
						'description' => esc_html__( 'Link(s) to content. Proper deep link format for each platform.' ),
					),
					'action_platform' => array(
						'label'         => esc_html__( 'Action Platform' ),
						'type'          => 'multi_select',
						'dropdown_type' => 'action_platform',
						'required'      => true,
					),
					'price'           => array(
						'label'    => esc_html__( 'Price' ),
						'type'     => 'number',
						'required' => true,
						'attrs'    => array(
							'min'  => '0',
							'step' => 'any',
						),
					),
					'currency'        => array(
						'label'         => esc_html__( 'Currency' ),
						'type'          => 'dropdown',
						'required'      => true,
						'dropdown_type' => 'currency',
					),
					'country'         => array(
						'label'         => esc_html__( 'Eligible Region' ),
						'type'          => 'multi_select',
						'dropdown_type' => 'country',
					),
					'avail'           => array(
						'label'         => esc_html__( 'Availability' ),
						'type'          => 'dropdown',
						'dropdown_type' => 'availability',
					),
				),
			),
			'same_as'      => array(
				'label'       => esc_html__( 'Same As' ),
				'type'        => 'text',
				'description' => esc_html__( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.' ),
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'description'      => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => 'textarea',
				'default'  => 'post_content',
				'required' => true,
			),
			'course_code'      => array(
				'label'       => esc_html__( 'Course Code' ),
				'type'        => 'text',
				'description' => esc_html__( 'The identifier for the Course used by the course provider (e.g. CS101 or 6.001).' ),
			),
			'course_instance'  => array(
				'label'  => esc_html__( 'Course Instance' ),
				'type'   => 'repeater',
				'fields' => array(
					'name'             => array(
						'label'    => esc_html__( 'Instance Name' ),
						'type'     => 'text',
						'required' => true,
					),
					'description'      => array(
						'label'    => esc_html__( 'Instance Description' ),
						'type'     => 'textarea',
						'required' => true,
					),
					'course_mode'      => array(
						'label'       => esc_html__( 'Course Mode' ),
						'type'        => 'text',
						'description' => esc_html__( 'The medium or means of delivery of the course instance or the mode of study, either as a text label (e.g. "online", "onsite" or "blended"; "synchronous" or "asynchronous"; "full_time" or "part_time") or as a URL reference to a term from a controlled vocabulary (e.g. https://ceds.ed.gov/element/001311#Asynchronous )' ),
					),
					'image'            => array(
						'label' => esc_html__( 'Image' ),
						'type'  => 'image',
					),
					'start_date'       => array(
						'label'    => esc_html__( 'Start Date' ),
						'type'     => 'date',
						'required' => true,
					),
					'end_date'         => array(
						'label' => esc_html__( 'End Date' ),
						'type'  => 'date',
					),
					'location_name'    => array(
						'label' => esc_html__( 'Location Name' ),
						'type'  => 'text',
					),
					'location_address' => array(
						'label'    => esc_html__( 'Location Address' ),
						'type'     => 'text',
						'required' => true,
					),
					'price'            => array(
						'label' => esc_html__( 'Price' ),
						'type'  => 'number',
						'attrs' => array(
							'min'  => '0',
							'step' => 'any',
						),
					),
					'currency'         => array(
						'label'         => esc_html__( 'Currency' ),
						'type'          => 'dropdown',
						'dropdown_type' => 'currency',
					),
					'valid_from'       => array(
						'label' => esc_html__( 'Valid From' ),
						'type'  => 'date',
					),
					'url'              => array(
						'label' => esc_html__( 'Offer URL' ),
						'type'  => 'text',
					),
					'avail'            => array(
						'label'         => esc_html__( 'Availability' ),
						'type'          => 'dropdown',
						'dropdown_type' => 'availability',
					),
					'performer'        => array(
						'label' => esc_html__( 'Performer' ),
						'type'  => 'text',
					),
				),
			),
			'orgnization_name' => array(
				'label'   => esc_html__( 'Organization Name' ),
				'type'    => 'text',
				'default' => 'blogname',
			),
			'rating'           => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'     => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => 'number',
			),
			'same_as'          => array(
				'label'       => esc_html__( 'Same As' ),
				'type'        => 'text',
				'description' => esc_html__( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.' ),
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'image'             => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'image',
				'default' => 'featured_img',
			),
			'location'          => array(
				'label' => esc_html__( 'Location Name' ),
				'type'  => 'text',
			),
			'location_street'   => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_locality' => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_postal'   => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_region'   => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_country'  => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => 'dropdown',
				'required'      => true,
				'dropdown_type' => 'country',
			),
			'start_date'        => array(
				'label'    => esc_html__( 'Start Date' ),
				'type'     => 'datetime_local',
				'required' => true,
			),
			'end_date'          => array(
				'label' => esc_html__( 'End Date' ),
				'type'  => 'datetime_local',
			),
			'avail'             => array(
				'label'         => esc_html__( 'Offer Availability' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'availability',
			),
			'price'             => array(
				'label' => esc_html__( 'Offer Price' ),
				'type'  => 'number',
				'attrs' => array(
					'min'  => '0',
					'step' => 'any',
				),
			),
			'currency'          => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'currency',
			),
			'valid_from'        => array(
				'label' => esc_html__( 'Offer Valid From' ),
				'type'  => 'date',
			),
			'ticket_buy_url'    => array(
				'label' => esc_html__( 'Ticket Link' ),
				'type'  => 'text',
			),
			'performer'         => array(
				'label' => esc_html__( 'Performer' ),
				'type'  => 'text',
			),
			'description'       => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => 'textarea',
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
				'type'     => 'text',
				'required' => true,
			),
			'job_type'                => array(
				'label'         => esc_html__( 'Employment Type' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'employment',
			),
			'orgnization_name'        => array(
				'label'    => esc_html__( 'Organization Name' ),
				'type'     => 'text',
				'default'  => 'blogname',
				'required' => true,
			),
			'same_as'                 => array(
				'label'       => esc_html__( 'URL' ),
				'type'        => 'text',
				'required'    => true,
				'description' => esc_html__( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.' ),
			),
			'description'             => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => 'textarea',
				'default'  => 'post_content',
				'required' => true,
			),
			'education_requirements'  => array(
				'label' => esc_html__( 'Education Requirements' ),
				'type'  => 'text',
			),
			'experience_requirements' => array(
				'label' => esc_html__( 'Experience Requirements' ),
				'type'  => 'text',
			),
			'industry'                => array(
				'label' => esc_html__( 'Industry' ),
				'type'  => 'text',
			),
			'qualifications'          => array(
				'label' => esc_html__( 'Qualifications' ),
				'type'  => 'text',
			),
			'responsibilities'        => array(
				'label' => esc_html__( 'Responsibilities' ),
				'type'  => 'text',
			),
			'skills'                  => array(
				'label' => esc_html__( 'Skills' ),
				'type'  => 'text',
			),
			'work_hours'              => array(
				'label' => esc_html__( 'Work Hours' ),
				'type'  => 'text',
			),
			'start_date'              => array(
				'label'    => esc_html__( 'Date Posted' ),
				'type'     => 'date',
				'default'  => 'post_date',
				'required' => true,
			),
			'expiry_date'             => array(
				'label' => esc_html__( 'Valid Through' ),
				'type'  => 'date',
			),
			'location_street'         => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_locality'       => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_postal'         => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_region'         => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_country'        => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => 'dropdown',
				'required'      => true,
				'dropdown_type' => 'country',
			),
			'salary'                  => array(
				'label' => esc_html__( 'Base Salary' ),
				'type'  => 'number',
			),
			'salary_min_value'        => array(
				'label'   => esc_html__( 'Min Salary' ),
				'type'    => 'number',
				'default' => 'create_field',
			),
			'salary_max_value'        => array(
				'label'   => esc_html__( 'Max Salary' ),
				'type'    => 'number',
				'default' => 'create_field',
			),
			'salary_currency'         => array(
				'label'         => esc_html__( 'Salary In Currency' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'currency',
			),
			'salary_unit'             => array(
				'label'         => esc_html__( 'Salary Per Unit' ),
				'type'          => 'dropdown',
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
				'type'     => 'text',
				'default'  => 'blogname',
				'required' => true,
			),
			'image'               => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'image',
				'required' => true,
			),
			'telephone'           => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => 'tel',
			),
			'url'                 => array(
				'label'   => esc_html__( 'URL' ),
				'type'    => 'text',
				'default' => 'site_url',
			),
			'location_street'     => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_locality'   => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_postal'     => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_region'     => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => 'text',
				'required' => true,
			),
			'location_country'    => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'country',
			),
			'hours_specification' => array(
				'label'  => esc_html__( 'Hours Specification' ),
				'type'   => 'repeater',
				'fields' => array(
					'days'   => array(
						'label'         => esc_html__( 'Day Of Week' ),
						'type'          => 'multi_select',
						'required'      => true,
						'dropdown_type' => 'days',
					),
					'opens'  => array(
						'label'    => esc_html__( 'Opens' ),
						'type'     => 'time',
						'required' => true,
					),
					'closes' => array(
						'label'    => esc_html__( 'Closes' ),
						'type'     => 'time',
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
				'type'  => 'number',
			),
			'price_range'         => array(
				'label'    => esc_html__( 'Price Range' ),
				'type'     => 'text',
				'required' => true,
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
				'type'     => 'text',
				'required' => true,
			),
			'item_image'    => array(
				'label' => esc_html__( 'Review Item Image' ),
				'type'  => 'image',
			),
			'description'   => array(
				'label'    => esc_html__( 'Review Description' ),
				'type'     => 'textarea',
				'default'  => 'post_content',
				'required' => true,
			),
			'date'          => array(
				'label'    => esc_html__( 'Review Date' ),
				'type'     => 'date',
				'default'  => 'post_date',
				'required' => true,
			),
			'rating'        => array(
				'label' => esc_html__( 'Review Rating' ),
				'type'  => 'rating',
			),
			'reviewer_name' => array(
				'label'   => esc_html__( 'Reviewer Name' ),
				'type'    => 'text',
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
				'type'     => 'text',
				'required' => true,
			),
			'street'       => array(
				'label'    => esc_html__( 'Street Address' ),
				'type'     => 'text',
				'required' => false,
			),
			'locality'     => array(
				'label'    => esc_html__( 'Locality' ),
				'type'     => 'text',
				'required' => false,
			),
			'postal'       => array(
				'label'    => esc_html__( 'Postal Code' ),
				'type'     => 'text',
				'required' => false,
			),
			'region'       => array(
				'label'    => esc_html__( 'Region' ),
				'type'     => 'text',
				'required' => false,
			),
			'country'      => array(
				'label'         => esc_html__( 'Country' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'country',
			),
			'email'        => array(
				'label'    => esc_html__( 'Email' ),
				'type'     => 'text',
				'required' => false,
			),
			'gender'       => array(
				'label'         => esc_html__( 'Gender' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'gender_select',
			),
			'dob'          => array(
				'label' => esc_html__( 'Date of Birth' ),
				'type'  => 'date',
			),
			'member'       => array(
				'label' => esc_html__( 'Member of' ),
				'type'  => 'text',
			),
			'nationality'  => array(
				'label'    => esc_html__( 'Nationality' ),
				'type'     => 'text',
				'required' => false,
			),

			'image'        => array(
				'label' => esc_html__( 'Photograph' ),
				'type'  => 'image',
			),
			'job_title'    => array(
				'label' => esc_html__( 'Job Title' ),
				'type'  => 'text',
			),
			'homepage_url' => array(
				'label' => esc_html__( 'Homepage URL' ),
				'type'  => 'text',
			),
			'add_url'      => array(
				'label'  => esc_html__( 'Same As' ),
				'type'   => 'repeater',
				'fields' => array(
					'same_as' => array(
						'label'    => esc_html__( 'URL' ),
						'type'     => 'text',
						'required' => false,
					),
				),
			),
			'telephone'    => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => 'tel',
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'brand_name'   => array(
				'label'   => esc_html__( 'Brand Name' ),
				'type'    => 'text',
				'default' => 'none',
			),
			'image'        => array(
				'label'    => esc_html__( 'Image' ),
				'type'     => 'image',
				'default'  => 'featured_img',
				'required' => true,
			),
			'description'  => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => 'textarea',
				'default' => 'post_content',
			),
			'avail'        => array(
				'label'         => esc_html__( 'Availability' ),
				'type'          => 'dropdown',
				'dropdown_type' => 'availability',
				'required'      => true,
			),
			'price'        => array(
				'label'    => esc_html__( 'Price' ),
				'type'     => 'number',
				'required' => true,
				'attrs'    => array(
					'min'  => '0',
					'step' => '0.01',
				),
			),
			'currency'     => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => 'dropdown',
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
				'type'  => 'text',
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
				'type'     => 'text',
				'default'  => 'none',
				'required' => true,
			),
			'image'               => array(
				'label'    => esc_html__( 'Photo' ),
				'type'     => 'image',
				'default'  => 'featured_img',
				'required' => true,
			),
			'description'         => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => 'textarea',
				'default' => 'post_content',
			),
			'author'              => array(
				'label'   => esc_html__( 'Author Name' ),
				'type'    => 'text',
				'default' => 'author_name',
			),
			'preperation_time'    => array(
				'label'   => esc_html__( 'Preparation Time' ),
				'type'    => 'time_duration',
				'default' => 'none',
			),
			'cook_time'           => array(
				'label'   => esc_html__( 'Cook Time' ),
				'type'    => 'time_duration',
				'default' => 'none',
			),
			'recipe_keywords'     => array(
				'label'       => esc_html__( 'Keywords' ),
				'type'        => 'text',
				'default'     => 'create_field',
				'description' => esc_html__( 'e.g. "winter apple pie", "nutmeg crust"' ),
			),
			'recipe_category'     => array(
				'label'       => esc_html__( 'Category' ),
				'type'        => 'text',
				'default'     => 'create_field',
				'description' => esc_html__( 'e.g. "dinner", "entree", or "dessert"' ),
			),
			'recipe_cuisine'      => array(
				'label'       => esc_html__( 'Cuisine' ),
				'type'        => 'text',
				'default'     => 'create_field',
				'description' => esc_html__( 'e.g. "French", "Indian", or "American"' ),
			),
			'nutrition'           => array(
				'label'       => esc_html__( 'Calories' ),
				'type'        => 'text',
				'default'     => 'none',
				'description' => esc_html__( 'The number of calories in the recipe.' ),
			),
			'ingredients'         => array(
				'label'       => esc_html__( 'Ingredients' ),
				'type'        => 'text',
				'default'     => 'none',
				'description' => esc_html__( 'Ingredient used in the recipe. Separate multiple ingredients with comma(,).' ),
			),
			'recipe_instructions' => array(
				'label'  => esc_html__( 'Instructions' ),
				'type'   => 'repeater',
				'fields' => array(
					'steps' => array(
						'label'   => esc_html__( 'Instructions Step' ),
						'type'    => 'text',
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
						'type'     => 'text',
						'default'  => 'create_field',
						'required' => true,
					),
					'video_desc'                     => array(
						'label'    => esc_html__( 'Video Description' ),
						'type'     => 'textarea',
						'default'  => 'create_field',
						'required' => true,
					),
					'video_image'                    => array(
						'label'    => esc_html__( 'Thumbnail Url' ),
						'type'     => 'image',
						'default'  => 'create_field',
						'required' => true,
					),
					'recipe_video_content_url'       => array(
						'label'   => esc_html__( 'Content URL' ),
						'type'    => 'text',
						'default' => 'create_field',
					),
					'recipe_video_embed_url'         => array(
						'label'   => esc_html__( 'Embed URL' ),
						'type'    => 'text',
						'default' => 'create_field',
					),
					'recipe_video_duration'          => array(
						'label'   => esc_html__( 'Duration' ),
						'type'    => 'time_duration',
						'default' => 'create_field',
					),
					'recipe_video_upload_date'       => array(
						'label'    => esc_html__( 'Upload Date' ),
						'type'     => 'date',
						'default'  => 'post_date',
						'required' => true,
					),
					'recipe_video_expires_date'      => array(
						'label'   => esc_html__( 'Expires On' ),
						'type'    => 'date',
						'default' => 'create_field',
					),
					'recipe_video_interaction_count' => array(
						'label'   => esc_html__( 'Interaction Count' ),
						'type'    => 'number',
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
				'type'  => 'text',
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'type'              => array(
				'label'    => esc_html__( 'Type' ),
				'type'     => 'text',
				'required' => true,
			),
			'area'              => array(
				'label'       => esc_html__( 'Area' ),
				'type'        => 'text',
				'description' => esc_html__( 'The geographic area where a service or offered item is provided.' ),
			),
			'description'       => array(
				'label'   => esc_html__( 'Description' ),
				'type'    => 'textarea',
				'default' => 'post_content',
			),
			'image'             => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'image',
				'default' => 'featured_img',
			),
			'provider'          => array(
				'label'    => esc_html__( 'Provider Name' ),
				'type'     => 'text',
				'default'  => 'blogname',
				'required' => true,
			),
			'location_locality' => array(
				'label' => esc_html__( 'Locality' ),
				'type'  => 'text',
			),
			'location_region'   => array(
				'label' => esc_html__( 'Region' ),
				'type'  => 'text',
			),
			'location_street'   => array(
				'label' => esc_html__( 'Street Address' ),
				'type'  => 'text',
			),
			'location_image'    => array(
				'label'    => esc_html__( 'Location Image' ),
				'type'     => 'image',
				'required' => true,
			),
			'telephone'         => array(
				'label' => esc_html__( 'Telephone' ),
				'type'  => 'tel',
			),
			'price_range'       => array(
				'label' => esc_html__( 'Price Range' ),
				'type'  => 'text',
			),
			'rating'            => array(
				'label' => esc_html__( 'Rating' ),
				'type'  => 'rating',
			),
			'review_count'      => array(
				'label' => esc_html__( 'Review Count' ),
				'type'  => 'number',
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'operating_system' => array(
				'label'       => esc_html__( 'Operating System' ),
				'type'        => 'text',
				'required'    => true,
				'description' => esc_html__( 'Software for the operating system, for example, "Windows 7", "OSX 10.6", "Android 1.6"' ),
			),
			'category'         => array(
				'label'         => esc_html__( 'Category' ),
				'type'          => 'dropdown',
				'required'      => true,
				'dropdown_type' => 'software_category',
			),
			'image'            => array(
				'label'   => esc_html__( 'Image' ),
				'type'    => 'image',
				'default' => 'featured_img',
			),
			'price'            => array(
				'label'    => esc_html__( 'Price' ),
				'type'     => 'number',
				'required' => true,
				'attrs'    => array(
					'min'  => '0',
					'step' => 'any',
				),
			),
			'currency'         => array(
				'label'         => esc_html__( 'Currency' ),
				'type'          => 'dropdown',
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
				'type'  => 'number',
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
				'type'     => 'text',
				'default'  => 'post_title',
				'required' => true,
			),
			'description'       => array(
				'label'    => esc_html__( 'Description' ),
				'type'     => 'textarea',
				'default'  => 'post_content',
				'required' => true,
			),
			'image'             => array(
				'label'    => esc_html__( 'Thumbnail' ),
				'type'     => 'image',
				'default'  => 'featured_img',
				'required' => true,
			),
			'orgnization_name'  => array(
				'label'    => esc_html__( 'Publisher Name' ),
				'type'     => 'text',
				'default'  => 'blogname',
				'required' => true,
			),
			'site_logo'         => array(
				'label'    => esc_html__( 'Publisher Logo' ),
				'type'     => 'image',
				'default'  => 'site_logo',
				'required' => true,
			),
			'content_url'       => array(
				'label' => esc_html__( 'Content URL' ),
				'type'  => 'text',
			),
			'embed_url'         => array(
				'label' => esc_html__( 'Embed URL' ),
				'type'  => 'text',
			),
			'duration'          => array(
				'label' => esc_html__( 'Duration' ),
				'type'  => 'time_duration',
			),
			'upload_date'       => array(
				'label'    => esc_html__( 'Upload Date' ),
				'type'     => 'date',
				'default'  => 'post_date',
				'required' => true,
			),
			'expires_date'      => array(
				'label' => esc_html__( 'Expires On' ),
				'type'  => 'date',
			),
			'interaction_count' => array(
				'label' => esc_html__( 'Interaction Count' ),
				'type'  => 'number',
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

}
