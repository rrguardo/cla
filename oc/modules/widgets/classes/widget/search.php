<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Search widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Search extends Widget
{

	public function __construct()
	{	

		$this->title 		= __('Search');
		$this->description 	= __('Advanced Search');

        $this->fields = array(	'text_title'    => array( 'type'      => 'text',
                                                        'display'   => 'text',
                                                        'default'   => __('Search'),
                                                        'label'     => __('Title displayed'),
                                                        'required'  => FALSE),

                                'advanced'      => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Advanced option'),
                                                        'options'   => array('0'   => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ), 
                                                        'default'   => 0,
                                                        'required'  => TRUE),

                                'custom'      => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Custom fields in search'),
                                                        'options'   => array('0'   => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ), 
                                                        'default'   => 0,
                                                        'required'  => TRUE),
						 		);
	}

	/**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->text_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {

       
        // get all categories
        if ($this->advanced != FALSE)
        {
            // loaded category
            list($categories,$order_categories)  = Model_Category::get_all();

            $arr_cat = array();
            foreach ($categories as $cat => $value) 
            {
                if($value['id'] != 1)
                    $arr_cat[$value['id']] = $value['name'];
            }
            
            $this->cat_items = $categories;
            $this->cat_order_items = $order_categories;

            // get all locations
            list($locations,$order_locations)  = Model_Location::get_all();

            $this->loc_items = $locations;
            $this->loc_order_items = $order_locations;

        }

        if($this->custom != FALSE)
        {
            $fields = Model_Field::get_all();
            $this->custom_fields = $fields;
        }
    }


}