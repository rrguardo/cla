<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_User extends ORM {

    /**
     * Status constants
     */
    const STATUS_INACTIVE       = 0;    // Inactive
    const STATUS_ACTIVE         = 1;   // Active (normal status) (displayed in SERP and can post/login)
    const STATUS_SPAM           = 5;   // tagged as spam

    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'users';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_user';

    protected $_has_many = array(
        'ads' => array(
            'model'       => 'ad',
            'foreign_key' => 'id_user',
        ),
    );

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'role' => array(
                'model'       => 'role',
                'foreign_key' => 'id_role',
            ),
        'location' => array(
                'model'       => 'location',
                'foreign_key' => 'id_location',
            ),
    );
    
    
    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
				        'id_user'	    => array(array('numeric')),
				        'name'	        => array(array('max_length', array(':value', 145))),
				        'email'	        => array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'password'	    => array(array('not_empty'), array('max_length', array(':value', 64)), ),
				        'status'	    => array(array('numeric')),
				        'id_role'	    => array(array('numeric')),
				        'id_location'   => array(),
				        'created'	    => array(),
				        'last_modified' => array(),
				        'logins'	    => array(),
				        'last_login'    => array(),
				        'last_ip'	    => array(),
				        'user_agent'	=> array(),
				        'token'	        => array(array('max_length', array(':value', 40))),
				        'token_created'	=> array(),
				        'token_expires'	=> array(),
				    );
    }
    
    

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
    					'id_user'	    => 'Id',
				        'name'	    	=> __('Name'),
				        'email'	    	=> __('Email'),
				        'password'		=> __('Password'),
				        'status'		=> __('Status'),
				        'id_role'		=> __('Role'),
				        'id_location'	=> __('Location'),
				        'created'	    => __('Created'),
				        'last_modified'	=> __('Last modified'),
				        'last_login'	=> __('Last login'),
				    );
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
    			'password' => array(
                                array(array(Auth::instance(), 'hash'))
                              ),
                'seoname' => array(
                                array(array($this, 'gen_seo_title'))
                              ),
        );
    }

    
	/**
	 * complete the login for a user
	 * incrementing the logins and saving login timestamp
	 * @param integer $lifetime Regenerates the token used for the autologin cookie
	 * 
	 */
	public function complete_login($lifetime=NULL)
	{
		if ($this->_loaded)
		{   
			//want to remember the login using cookie
		    if (is_numeric($lifetime))
		    	$this->create_token($lifetime);
		    
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = Date::unix2mysql(time());
			
			// Set the last ip address
			$this->last_ip = ip2long(Request::$client_ip);

			try 
			{
				// Save the user
				$this->update();
			}
			catch (ORM_Validation_Exception $e)
			{
				Form::set_errors($e->errors(''));
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
			
		}
	}
	
	/**
	 * Creates a unique token for the autologin
	 * @param integer $lifetime token alive
	 * @return string
	 */
	public function create_token($lifetime=NULL)
	{
		if ($this->_loaded)
		{
			//we need to be sure we have a lifetime
			if ($lifetime==NULL)
			{
				$config = Kohana::$config->load('auth');
				$lifetime = $config['lifetime'];
			}
			
			//we assure the token is unique
			do
			{
				$this->token = sha1(uniqid(Text::random('alnum', 32), TRUE));
			}
			while(ORM::factory('user', array('token' => $this->token))->limit(1)->loaded());
			
			// user Token data
			$this->user_agent    = sha1(Request::$user_agent);
			$this->token_created = Date::unix2mysql(time());
			$this->token_expires = Date::unix2mysql(time() + $lifetime);
			
			try
			{
				$this->update();
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
		
	    
	}
	/**
     * Create new User in database 
     * @param $name String
     * @param $email String
     * @return $id_user Int
     */ 
    public function create_new_user($name,$email)
    {
        // check validity of email
        if (Valid::email($email,TRUE))
        {
            $user_obj = new self;
            // search for email in DB
            $user = $user_obj->where('email', '=', $email)
                    ->limit(1)
                    ->find();
       
            if(!$user->loaded())
            { 
                $new_password_plain = Text::random();
                $user->email        = $email;
                $user->name         = $name;
                $user->status       = Model_User::STATUS_ACTIVE;
                $user->id_role      = Model_Role::ROLE_USER;//normal user
                $user->password     = $new_password_plain;
                $user->seoname      = $this->gen_seo_title($name);
                
                try
                {
                    $user->save();

                    Alert::set(Alert::SUCCESS, __('New profile has been created. Welcome ').$name.' !');
                    //we get the QL, and force the regen of token for security
                    $url_pwch = $user->ql('oc-panel',array('controller' => 'profile', 
                                                           'action'     => 'edit'),TRUE);
                    $ret = $user->email('user.new',array('[URL.PWCH]'=>$url_pwch,
                                                         '[USER.PWD]'=>$new_password_plain));                   
                }
                catch (ORM_Validation_Exception $e)
                {
                    throw new HTTP_Exception_500($e->getMessage());
                }
            }

            return $user->id_user;
        }
        else
        {
            Alert::set(Alert::ALERT, __('Invalid Email'));
            $this->request->redirect(Route::url('post_new'));
        }
    
    } 

    /**
     * Check the actual controller and action request and validates if the user has access to it
     * @todo    code something that you can show to your mom.
     * @param   string  $action
     * @return  boolean
     */
    public function has_access($controller, $action='index', $directory='')
    {
        $this->get_access_controllers();
        $this->get_access_actions();

        /* //if we want to control the directory also...not yet.
        if(strlen($directory))
        {
            $controller = $directory.'/'.$controller;
        }
        */

        $granted = $this->get_access_actions();

        if((in_array('*.*', $granted)) OR (in_array($controller.'.*', $granted)) 
        	OR (in_array($controller.'.'.$action, $granted)))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }

    }

    /**
     *
     * returns an array with all the actions that the backuser can do
     */
    private function get_access_actions()
    {
        $granted = Session::instance()->get('granted_actions');
        if( ! isset($granted))
        {
            $access = $this->role->access->find_all()->as_array();
            $granted = array();


            foreach($access as $k=>$v)
            {
                $granted[] = $v->access;
            }

            //@todo auto controller added
            
            /*
            foreach ($this->get_access_controllers() as $k=>$v)
            {
                $granted[] = $v.'.grid';
                $granted[] = $v.'.grid_js';
                $granted[] = $v.'.grid_data';
            }*/

            //$granted[] = 'auth.*';
            $granted[] = 'home.*';

            Session::instance()->set('granted_actions', $granted);
        }

        return $granted;
    }

    /**
     *
     * returns an array with the controllers within the user has any right
     */
    private function get_access_controllers()
    {
        $granted = Session::instance()->get('granted_controllers');
        if( ! isset($granted))
        {
            $access = $this->role->access->find_all()->as_array();
            $granted = array();


            foreach($access as $k=>$v)
            {
                //only woks in php 5.3 or higher
                //$granted[] = strstr($v->access, '.', TRUE);
                $granted[] = substr($v->access, 0, strpos($v->access, '.'));
            }

            Session::instance()->set('granted_controllers', $granted);
        }
        return $granted;
    }

    /**
     * Rudimentary access control list
     * @todo    code something that you can show to your mom.
     * @param   string  $action
     * @return  boolean
     */
    public function has_access_to_any($list)
    {
        $granted = $this->get_access_controllers();
        $controllers = explode(',',$list);
        $out = array_intersect($granted, $controllers);
        if(( ! empty($out) ) OR (in_array('*', $granted)))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * sends email to the current user replacing tags
     * @param  string $seotitle from Model_Content
     * @param  array $replace
     * @param  array $file  file to be uploaded
     * @return boolean
     */
    public function email($seotitle, array $replace = NULL, $from = NULL, $from_name =NULL, $file=NULL, $to = NULL)
    {
        if ($this->loaded())
        {
            return Email::content(($to == NULL)?$this->email:$to,$this->name,$from,$from_name,$seotitle,$replace, $file);  
        }
        return FALSE;
    }


    /**
     * get url with auto QL login and redirect
     * @param  string  $route            
     * @param  array  $params           
     * @param  boolean $regenerate_token 
     * @return string                    
     */
    public function ql($route = 'default', array $params = NULL, $regenerate_token = FALSE)
    {
        if ($this->loaded())
        {
            if ($regenerate_token==TRUE)//regenerating the token, for security or new user...
                $this->create_token();

            $ql = Auth::instance()->ql_encode($this->token,Route::url($route,$params,'http'));
            return Route::url('oc-panel',array('controller' => 'auth', 'action' => 'ql', 'id' =>$ql),'http');
        }
        return NULL;               
    }


    public function form_setup($form)
    {
        if(Request::current()->action() != 'update'){
            $form->fields['password']['display_as'] = 'password';
        }
        $form->fields['email']['caption'] = 'email';
        $form->fields['status']['display_as'] = 'select';
        $form->fields['status']['options'] = array('0','1','5');
    }

    public function exclude_fields()
    {
       return array('logins','last_login','hybridauth_provider_uid','password','last_modified','created','salt', 'ip_created', 'last_ip','token','token_created','token_expires','user_agent','id_location');
    }

    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seo_title($title)
    {
        //in case seoname is really small or null
        if (strlen($title)<3)
            $title = $this->name;

        $seotitle = URL::title($title);
        

        if ($seotitle != $this->seoname)
        {
            $user = new self;
            //find a user same seotitle
            $s = $user->where('seoname', '=', $seotitle)->where('id_user', '!=', $this->id_user)->limit(1)->find();

            //found, increment the last digit of the seotitle
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seotitle.'-'.$cont;
                    $user = new self;
                    unset($s);
                    $s = $user->where('seoname', '=', $attempt)->where('id_user', '!=', $this->id_user)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seotitle = $attempt;
                    }
                    else
                  {
                        $cont++;
                    }
                }
            }
        }
        
        return $seotitle;
    }

    /**
     * creates a user from email if exists doesn't...
     * @param  string $email 
     * @param  string $name  
     * @return integer        
     */
    public static function create_email($email,$name=NULL)
    {
        $user = new self();
        $user->where('email','=',$email)->limit(1)->find();

        if (!$user->loaded())
        {
            $user->email        = $email;
            $user->name         = $name;
            $user->status       = self::STATUS_ACTIVE;
            $user->id_role      = 1;
            $user->seoname      = $user->gen_seo_title($user->name);
            $user->password     = Text::random('alnum', 8);
            try
            {
                $user->save();
            }
            catch (ORM_Validation_Exception $e)
            {
                // d($e->errors(''));
            }
        }

        return $user->id_user;
    }

    /**
     * creates a User from social data
     * @param  string $email      
     * @param  string $name       
     * @param  string $provider   
     * @param  mixed $identifier 
     * @return Model_User             
     */
    public static function create_social($email,$name=NULL,$provider, $identifier)
    {
        $user = new self();
        $user->where('email','=',$email)->limit(1)->find();

        //doesnt exists
        if (!$user->loaded())
        {
            $password           = Text::random('alnum', 8);
            $user->email        = $email;
            $user->name         = $name;
            $user->status       = self::STATUS_ACTIVE;
            $user->id_role      = 1;
            $user->seoname      = $user->gen_seo_title($user->name);
            $user->password     = $password;
        }
        //always we set this values even if user existed
        $user->hybridauth_provider_name = $provider;
        $user->hybridauth_provider_uid  = $identifier;
        try
        {
            $user->save();
            //send welcome email
            $user->email('auth.register',array('[USER.PWD]'=>$password,
                                                    '[URL.QL]'=>$user->ql('default',NULL,TRUE))
                                            );
        }
        catch (ORM_Validation_Exception $e)
        {
            d($e->errors(''));
        }

        return $user;
    }

    /**
     * reurns the url of the users profile image
     * @return string url
     */
    public function get_profile_image()
    {

        if(is_file(DOCROOT."images/users/".$this->id_user.".png"))
            $imgurl = URL::base().'images/users/'.$this->id_user.'.png';
        else
            $imgurl = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s=200';

        return $imgurl;
    }

  /*  protected $_table_columns =  
array (
  'id_user' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_user',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'name' => 
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 2,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seoname' => 
  array (
    'type' => 'string',
    'column_name' => 'seoname',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'email' => 
  array (
    'type' => 'string',
    'column_name' => 'email',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'password' => 
  array (
    'type' => 'string',
    'column_name' => 'password',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'character_maximum_length' => '64',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' => 
  array (
    'type' => 'int',
    'min' => '-2147483648',
    'max' => '2147483647',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'int',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_role' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_role',
    'column_default' => '1',
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_location' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' => 
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'last_modified' => 
  array (
    'type' => 'string',
    'column_name' => 'last_modified',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 10,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'logins' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'logins',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 11,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'last_login' => 
  array (
    'type' => 'string',
    'column_name' => 'last_login',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 12,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'last_ip' => 
  array (
    'type' => 'float',
    'column_name' => 'last_ip',
    'column_default' => NULL,
    'data_type' => 'float',
    'is_nullable' => true,
    'ordinal_position' => 13,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'user_agent' => 
  array (
    'type' => 'string',
    'column_name' => 'user_agent',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 14,
    'character_maximum_length' => '40',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'token' => 
  array (
    'type' => 'string',
    'column_name' => 'token',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 15,
    'character_maximum_length' => '40',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'token_created' => 
  array (
    'type' => 'string',
    'column_name' => 'token_created',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 16,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'token_expires' => 
  array (
    'type' => 'string',
    'column_name' => 'token_expires',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 17,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'hybridauth_provider_name' => 
  array (
    'type' => 'string',
    'column_name' => 'hybridauth_provider_name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 18,
    'character_maximum_length' => '40',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'hybridauth_provider_uid' => 
  array (
    'type' => 'string',
    'column_name' => 'hybridauth_provider_uid',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 19,
    'character_maximum_length' => '245',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
);*/
} // END Model_User