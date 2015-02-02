<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
	
	public function fget_keywords($category_parent, $category, $location_parent, $location)
	{
		$main_keyw = array("","anuncios","clasificados");
		$base_arr = array("","dominicana", "dominicanos", "republica dominicana","compro","compra","vendo","venta","rento","renta","alquilo","alquiler");
		$base_info = array($category_parent, $category, $location_parent, $location);		
		$keywords = "";
		
		foreach ($main_keyw as $value_main) {
			foreach ($base_arr as $value) {
				$sub_base = $value_main ." ". $value . " ";
	    		//with main category	    		
	    		$keywords .= $sub_base;
				$keywords .= $base_info[0] ." ". $base_info[2] ." ". $base_info[3];
	    		$keywords .= ", ";
	    		//with main sub category	    		
	    		$keywords .= $sub_base;
				$keywords .= $base_info[1] ." ". $base_info[2] ." ". $base_info[3];
	    		$keywords .= ", ";
	    		//with 2 categories	    		
	    		$keywords .= $sub_base;
				$keywords .= $base_info[0] ." ". $base_info[1] ." ". $base_info[2] ." ". $base_info[3];
	    		$keywords .= ", ";
			}
		}
		$keywords = substr($keywords, 0, -2);
		$keywords = str_replace("  ", " ", $keywords);
		$keywords = str_replace("  ", " ", $keywords);
		$keywords = str_replace("  ", " ", $keywords);
		$keywords = str_replace(" ,", ",", $keywords);		
		return $keywords;
	}

	/**
	 * Publis all adver.-s without filter
	 */
	public function action_listing()
	{ 
		if(Theme::get('infinite_scroll'))
		{
			$this->template->scripts['footer'][] = 'http://cdn.jsdelivr.net/jquery.infinitescroll/2.0b2/jquery.infinitescroll.js';
			$this->template->scripts['footer'][] = 'js/listing.js';
		}
		$this->template->scripts['footer'][] = 'js/sort.js';
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		
        /**
         * we get the model of category and location from controller to filter and generate urls titles etc...
         */
        
        $location = NULL;
        $location_parent = NULL;
        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
            {
            	$location = Controller::$location;
                //adding the location parent
                if ($location->id_location_parent!=1 AND $location->parent->loaded())
                    $location_parent = $location->parent;
            }  
        }

        $category = NULL;
        $category_parent = NULL;
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
            {
                $category = Controller::$category;
                //adding the category parent
                if ($category->id_category_parent!=1 AND $category->parent->loaded())
                    $category_parent = $category->parent;

            }
        }

        //base title
        if ($category!==NULL)
            $this->template->title = $category->name;
        else
            $this->template->title = __('all');

        //adding location titles and breadcrumbs
        if ($location!==NULL)
        {
            $this->template->title .= ' - '.$location->name;

            if ($location_parent!==NULL)
            {
                $this->template->title .=' ('.$location_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
            }

            Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                
            if ($category_parent!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
        }
        else
        {
            if ($category_parent!==NULL)
            {
                $this->template->title .=' ('.$category_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
            }
                
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname))));
        }

        $category_parent_ = "";
		  $category_ = "";        
		  $location_ = "";
		  $location_parent_ = "";

		  if( !empty($category_parent) ) {
		  		$category_parent_ = $category_parent->name;
		  		$this->template->meta_description = $category_parent->description;
		  }
		  if( !empty($category) ) {
		  		$category_ = $category->name;
		  		$this->template->meta_description = $category->description;
		  }
		  if( !empty($location) ) {
		  		$location_ = $location->name;
		  }
		  if( !empty($location_parent) ) {
		  		$location_parent_ = $location_parent->name;
		  }

    	  $this->template->meta_keywords = $this->fget_keywords($category_parent_, $category_, $location_parent_, $location_);

        $data = $this->list_logic($category, $location);
   		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/ad/listing',$data);
 	}

    /**
     * gets data to the view and filters the ads
     * @param  Model_Category $category 
     * @param  Model_Location $location
     * @return array           
     */
	public function list_logic($category = NULL, $location = NULL)
	{

		//user recognition 
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		$ads = new Model_Ad();

		//filter by category or location
        if ($category!==NULL)
        {
            $ads->where('id_category', 'in', $category->get_siblings_ids());

            //category image
            if(file_exists(DOCROOT.'images/categories/'.$category->seoname.'.png'))
                    Controller::$image = URL::base().'images/categories/'.$category->seoname.'.png';
        }

        if ($location!==NULL)
        {
            $ads->where('id_location', 'in', $location->get_siblings_ids());
        }

		//only published ads
        $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);
		

        //if ad have passed expiration time dont show 
        if(core::config('advertisement.expire_date') > 0)
        {
            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', DB::expr('NOW()'));
        }
        
    
        // featured ads
        $featured = NULL; 
        if(Theme::get('listing_slider') == 2)
        {
                $featured = clone $ads;
                $featured = $featured->where('featured', '<=', 'NOW()')
                                ->limit(Theme::get('num_home_latest_ads', 4))
                                ->find_all();
        }

		$res_count = $ads->count_all();

		// check if there are some advet.-s
		if ($res_count > 0)
		{
   
       		// pagination module
       		$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                    'category' 			=> ($category!==NULL)?$category->seoname:NULL,
                    'location'			=> ($location!==NULL)?$location->seoname:NULL, 
    	    ));
    	   
     	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

            /**
             * order depending on the sort parameter
             */
            switch (core::request('sort',core::config('general.sort_by'))) 
            {
                //title z->a
                case 'title-asc':
                    $ads->order_by('title','asc')->order_by('published','desc');
                    break;
                //title a->z
                case 'title-desc':
                    $ads->order_by('title','desc')->order_by('published','desc');
                    break;
                //cheaper first
                case 'price-asc':
                    $ads->order_by('price','asc')->order_by('published','desc');
                    break;
                //expensive first
                case 'price-desc':
                    $ads->order_by('price','desc')->order_by('published','desc');
                    break;
                //featured
                case 'featured':
                    $ads->order_by('featured','desc')->order_by('published','desc');
                    break;
                //oldest first
                case 'published-asc':
                    $ads->order_by('published','asc');
                    break;
                //newest first
                case 'published-desc':
                default:
                    $ads->order_by('published','desc');
                    break;
            }


     	    //we sort all ads with few parameters
       		$ads = $ads ->limit($pagination->items_per_page)
        	            ->offset($pagination->offset)
        	            ->find_all();
		}
		else
		{
			// array of categories sorted for view
			return array('ads'			=> NULL,
						 'pagination'	=> NULL, 
						  'user'        => $user, 
                         'category'     => $category,
                         'location'     => $location,
                         'featured'		=> NULL);
		}
		
		// array of categories sorted for view
		return array('ads'			=> $ads,
					 'pagination'	=> $pagination, 
					 'user'			=> $user, 
					 'category'		=> $category,
					 'location'		=> $location,
					 'featured'		=> $featured);
	}

	/**
	 * 
	 * Display single advert. 
	 * @throws HTTP_Exception_404
	 * 
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
                ->where('status','!=',Model_Ad::STATUS_SPAM)
				->limit(1)->cached()->find();

			if ($ad->loaded())
			{
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));

                $location = NULL;
                $location_parent = NULL;
                if ($ad->location->loaded() AND $ad->id_location!=1)
                {
                    $location = $ad->location;
                    //adding the location parent
                    if ($location->id_location_parent!=1 AND $location->parent->loaded())
                        $location_parent = $location->parent;
                }  
                

                $category = NULL;
                $category_parent = NULL;
                if ($ad->category->loaded())
                {
                    $category = $ad->category;
                    //adding the category parent
                    if ($category->id_category_parent!=1 AND $category->parent->loaded())
                        $category_parent = $category->parent;

                }
                   
                

                //base category  title
                if ($category!==NULL)
                    $this->template->title = $category->name;
                else
                    $this->template->title = '';

                //adding location titles and breadcrumbs
                if ($location!==NULL)
                {
                    $this->template->title .= ' - '.$location->name;

                    if ($location_parent!==NULL)
                    {
                        $this->template->title .=' ('.$location_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
                    }

                    Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                        
                    if ($category_parent!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
                }
                else
                {
                    if ($category_parent!==NULL)
                    {
                        $this->template->title .=' ('.$category_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
                    }
                        
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname))));
                }



                $this->template->title = $ad->title.' - '. $this->template->title;
				
				Breadcrumbs::add(Breadcrumb::factory()->set_title($ad->title));   	

				
                $this->template->meta_description = text::removebbcode($ad->description);

				$permission = TRUE; //permission to add hit to advert and give access rights. 
				if(!Auth::instance()->logged_in() || 
					(Auth::instance()->get_user()->id_user != $ad->id_user && Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN) || 
					Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN)
				{	
					if(!Auth::instance()->logged_in())
						$visitor_id = NULL;
					else
						$visitor_id = Auth::instance()->get_user()->id_user;
					$do_hit = $ad->count_ad_hit($visitor_id, ip2long(Request::$client_ip)); // hits counter
					
					$permission = FALSE;
					$user = NULL;
					
				} 
                else 
                    $user = Auth::instance()->get_user()->id_user;


				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits = $hits->where('id_ad','=', $ad->id_ad)->count_all();

				$captcha_show = core::config('advertisement.captcha');	
				
				$cf_config = json_decode(core::config('advertisement.fields')) ;
				$active_custom_fields = $ad->custom_columns();

				// Custom fields to display
				$ad_custom_vals = array();
				foreach ($active_custom_fields as $name => $value) {
					$real_name = str_replace("cf_", "", $value['parameters']['column_name']);
					
					if(isset($value['value']))//value is set 
					{	
						if($cf_config->$real_name->type == 'checkbox') // checkbox is TRUE or FALSE
						{
							if(isset($value['value']) AND $value['value'])
								$ad_custom_vals[$cf_config->$real_name->label] = NULL;
						}
						elseif($cf_config->$real_name->type == 'radio') // Radio have list of choices, but is saved as int in DB
							$ad_custom_vals[$cf_config->$real_name->label] = $cf_config->$real_name->values[$value['value']-1];
						else
							$ad_custom_vals[$cf_config->$real_name->label] = $value['value'];

						//admin_privilege can be seen only by admin, so we check if its set / and is admin
						if(isset($cf_config->$real_name->admin_privilege) AND $cf_config->$real_name->admin_privilege)
							if(!Auth::instance()->logged_in() OR !Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN)
								if(isset($ad_custom_vals[$cf_config->$real_name->label]))
									unset($ad_custom_vals[$cf_config->$real_name->label]);
					}
					
				}

                if($ad->get_first_image() !== NULL)
                    Controller::$image = $ad->get_first_image();

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/ad/single',array('ad'				=>$ad,
																				   'permission'		=>$permission, 
																				   'hits'			=>$hits, 
																				   'captcha_show'	=>$captcha_show,
																				   'user'			=>$user,
																				   'cf_list'		=>$ad_custom_vals
																				   ));

			}
			//not found in DB
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}
	
	
	/**
	 * [image_path Get directory path of specific advert.]
	 * @param  [array] $data [all values of one advert.]
	 * @return [array]       [array of dir. path where images of advert. are ]
	 */
	public function image_path($data)
	{
		$obj_ad = new Model_Ad();
		$directory = $obj_ad->gen_img_path($data->id_ad, $data->created);

		$path = array();
		if(is_dir($directory))
		{	
			$filename = array_diff(scandir($directory, 1), array('..','.')); //return all file names , and store in array 

			foreach ($filename as $filename) {
				array_push($path, $directory.$filename);		
			}
		}
		else
		{ 	
			return FALSE ;
		}

		return $path;
	}

	/**
	 * [action_to_top] [pay to go on top, and make order]
	 *
	 * @TODO if paymant is corrent and done update order table(status, pay_date), and put it to top (change published date)
	 */
	public function action_to_top()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_top;
		$description 	= 'to_top';
		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
		
		//case when payment is set to 0, it gets published without payment
		if(core::config('payment.pay_to_go_on_top') == FALSE)
		{
			$ad->status = 1;
			$ad->published = Date::unix2mysql(time());

			try {
				$ad->save();
				$this->request->redirect(Route::url('list')); 

			} catch (Exception $e) {
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
		
		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_top'),
						  'description'	=> $description);

		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
	
		
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));

	}
	
	/**
	 * [action_to_featured] [pay to go in featured]
	 *
	 * @TODO - when paypal returns token, update
	 */
	public function action_to_featured()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_featured;
		$description 	= 'to_featured';

		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
	
		//case when payment is set to 0, it gets published without payment
		if(core::config('payment.pay_to_go_on_feature') == FALSE)
		{
			$ad->status = 1;
			$ad->featured = Date::unix2mysql(time() + (core::config('payment.featured_days') * 24 * 60 * 60));

			try {
				$ad->save();
				$this->request->redirect(Route::url('list')); 

			} catch (Exception $e) {
				throw new HTTP_Exception_500($e->getMessage());
			}
		}

		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_feature'),
						  'description'	=> $description);
		
		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));
	}
	
	public function action_confirm_post()
	{
		$advert_id = $this->request->param('id');

		$advert = new Model_Ad($advert_id);

		if($advert->loaded())
		{
			if(core::config('general.moderation') == Model_Ad::EMAIL_CONFIRMATION)
			{

				$advert->status = 1; // status active
				$advert->published = Date::unix2mysql(time());

				try 
				{
					$advert->save();

					//subscription is on
					$data = array(	'title' 		=> $title 		= 	$advert->title,
									'cat'			=> $cat 		= 	$advert->category,
									'loc'			=> $loc 		= 	$advert->location,	
								 );

					Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $advert->price)), $advert->seotitle, Auth::instance()->get_user()->email); // if subscription is on
					
					Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
					$this->request->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
			if(core::config('general.moderation') == Model_Ad::EMAIL_MODERATION)
			{

				$advert->status = 0; // status active

				try 
				{
					$advert->save();
					Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
					$this->request->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
		}
	}

	public function action_advanced_search()
	{
		//template header
		$this->template->title           	= __('Advanced Search');
		$this->template->meta_description	= __('Advanced Search');

		//breadcrumbs
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

        $this->template->scripts['footer'] = array('js/search.js');
		// $cat_obj = new Model_Category();
		// $loc_obj = new Model_Location();
		list($cat_obj,$order_categories)  = Model_Category::get_all();
		list($loc_obj,$order_locations)  = Model_Location::get_all();
		
		$pagination = NULL;
        $ads   = NULL;
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		if($this->request->query()) // after query has detected
		{			
        	// variables 
        	$search_advert 	= core::get('title');
        	$search_loc 	= core::get('location');

        	// filter by each variable
        	$ads = new Model_Ad();
        	
        	//if ad have passed expiration time dont show 
	        if(core::config('advertisement.expire_date') > 0)
	        {
	            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', DB::expr('NOW()'));
	        }

	        if(!empty($search_advert) OR (core::get('search')!==NULL AND strlen(core::get('search'))>=3))
	        {	
	        	// if user is using search from header
	        	if(core::get('search'))
	        		$search_advert = core::get('search');

	        	$ads
        			->where_open()
                	->where('title', 'like', '%'.$search_advert.'%')
                	->or_where('description', 'like', '%'.$search_advert.'%')
                	->where_close();
	        }

        	$cf_fields = array();
            foreach ($this->request->query() as $name => $field) 
            {
            	// get by prefix
				if (strpos($name,'cf_') !== false) 
				{
					$cf_fields[$name] = $field;
					//checkbox when selected return string 'on' as a value
					if($field == 'on')
						$cf_fields[$name] = 1;
					elseif(empty($field)){
						$cf_fields[$name] = NULL;
					}
				}
        	}

	        $category = NULL;
            //filter by category 
            if (core::get('category')!==NULL)
            {
                $category = new Model_Category();
                $category->where('seoname','=',core::get('category'))->limit(1)->find();
                if ($category->loaded())
                    $ads->where('id_category', 'IN', $category->get_siblings_ids());
            }

	        $location = NULL;
            //filter by location 
            if (core::get('location')!==NULL)
            {
                $location = new Model_location();
                $location->where('seoname','=',core::get('location'))->limit(1)->find();
                if ($location->loaded())
                    $ads->where('id_location', 'IN', $location->get_siblings_ids());
            }

            //filter by price
            if (is_numeric(core::get('price-min')) AND is_numeric(core::get('price-max')))
            {
                $ads->where('price', 'BETWEEN', array(core::get('price-min'),core::get('price-max')));
            }

	        foreach ($cf_fields as $key => $value) 
	        {	
	        	if(isset($value) AND $value != NULL)
	        	{
		        	if(is_numeric($value))
		        		$ads->where($key, '=', $value);
		        	elseif(is_string($value))
		        		$ads->where($key, 'like', '%'.$value.'%');
		        }
	        }

	        $ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);

	        // count them for pagination
			$res_count = $ads->count_all();

			if($res_count>0)
			{
			
				// pagination module
                $pagination = Pagination::factory(array(
                        'view'              => 'pagination',
                        'total_items'       => $res_count,
                        'items_per_page'    => core::config('general.advertisements_per_page'),
                ))->route_params(array(
                        'controller'        => $this->request->controller(),
                        'action'            => $this->request->action(),
                        'category'          => ($category!==NULL)?$category->seoname:NULL,
                ));

		        Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->offset));
				
				$ads = $ads->order_by('published','desc')
							   ->limit($pagination->items_per_page)
			        	       ->offset($pagination->offset)
			        	       ->find_all();
			}
				
		}

		$this->template->bind('content', $content);

		$this->template->content = View::factory('pages/ad/advanced_search', array('ads'		=> $ads, 
																		   'categories'	=> $cat_obj,
																		   'order_categories'=> $order_categories,
																		   'locations'	=> $loc_obj, 
																		   'order_locations'=>$order_locations,
																		   'pagination'	=> $pagination, 
																		   'user'		=> $user,
																		   'fields' 		=> Model_Field::get_all(),
																		   ));
        
		
	}

	
}// End ad controller

