<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Cookie Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/cookie_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Set cookie
 *
 * Accepts six parameter, or you can submit an associative
 * array in the first parameter containing all the values.
 *
 * @access	public
 * @param	mixed
 * @param	string	the value of the cookie
 * @param	string	the number of seconds until expiration
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('set_cookie'))
{
	function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '')
	{
		// Set the config file options
		$CI =& get_instance();
		$CI->input->set_cookie($name, $value, $expire, $domain, $path, $prefix);
	}
}

// --------------------------------------------------------------------

/**
 * Fetch an item from the COOKIE array
 *
 * @access	public
 * @param	string 
 * @param	bool
 * @return	mixed
 */
if ( ! function_exists('get_cookie'))
{
	function get_cookie($index = '', $xss_clean = FALSE)
	{
		$CI =& get_instance();

		$prefix = '';

		if ( ! isset($_COOKIE[$index]) && config_item('cookie_prefix') != '')
		{
			$prefix = config_item('cookie_prefix');
		}

		return $CI->input->cookie($prefix.$index, $xss_clean);
	}
}

// --------------------------------------------------------------------

/**
 * Delete a COOKIE
 *
 * @param	mixed
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('delete_cookie'))
{
	function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
	{
		set_cookie($name, '', '', $domain, $path, $prefix);
	}
}


// check login function
if ( ! function_exists('check_login'))
{
	function check_login()
	{
		$id = get_cookie(APPID.'_admin');
		(!$id) && redirect('login');
		return $id;
	}
}

// check ADMIN login function
if ( ! function_exists('check_admin_login'))
{
	function check_admin_login()
	{
		$id = get_cookie(APP_ADMIN.'_admin');
		(!$id) && redirect('apanel/login');
		return $id;
	}
}

// check ADMIN login function
if ( ! function_exists('check_member_login'))
{
	function check_member_login()
	{
		$id = get_cookie(APP_MEMBER.'_member');
		(!$id) && redirect('p/login');
		return $id;
	}
}

// check login user ID, 0 if not valid
if ( ! function_exists('check_login_id'))
{
	function check_login_id()
	{
		$id = get_cookie(APPID.'_admin');
		return !$id ? 0 : $id;
	}
}

// check login function
if ( ! function_exists('check_user_access'))
{
	function check_user_access()
	{
		$id = get_cookie(APPID.'_access');
		(!$id || $id<1) && redirect('login');
		return $id;
	}
}

// Clear queue script
if ( ! function_exists('clear_queue'))
{
	function clear_queue($key='load_scripts')
	{
		$CI =& get_instance();
		$CI->session->unset_userdata($key);
	}
}

// Check and update thirdparty..
if ( ! function_exists('queue_script'))
{
	function queue_script($data='', $key='load_scripts')
	{
		$CI =& get_instance();
		
		$queue = array();
		if ($CI->session->userdata($key) != FALSE)
		{
			$queue = $CI->session->userdata($key);
		}
		
		if (is_string($data))
		    $queue[] = $data;
		
		if (is_array($data))
		{
			for ($i=0; $i<count($data); $i++)
			{
				$queue[] = $data[$i];
			}
		}
		$CI->session->set_userdata($key, $queue);
	}
}

// Return queued scripts 
if ( ! function_exists('fetch_queue_scripts'))
{
	function fetch_queue_scripts($key='load_scripts')
	{
		$CI =& get_instance();
		
		$ret = array();
		if ($CI->session->userdata($key) != FALSE)
		{
			$ret = $CI->session->userdata($key);
			$ret = array_unique($ret);
		}
		return $ret;
	}
}

/* End of file cookie_helper.php */
/* Location: ./system/helpers/cookie_helper.php */