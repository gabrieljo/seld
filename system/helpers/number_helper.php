<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * CodeIgniter Number Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/number_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */
if ( ! function_exists('byte_format'))
{
	function byte_format($num, $precision = 1)
	{
		$CI =& get_instance();
		$CI->lang->load('number');

		if ($num >= 1000000000000)
		{
			$num = round($num / 1099511627776, $precision);
			$unit = $CI->lang->line('terabyte_abbr');
		}
		elseif ($num >= 1000000000)
		{
			$num = round($num / 1073741824, $precision);
			$unit = $CI->lang->line('gigabyte_abbr');
		}
		elseif ($num >= 1000000)
		{
			$num = round($num / 1048576, $precision);
			$unit = $CI->lang->line('megabyte_abbr');
		}
		elseif ($num >= 1000)
		{
			$num = round($num / 1024, $precision);
			$unit = $CI->lang->line('kilobyte_abbr');
		}
		else
		{
			$unit = $CI->lang->line('bytes');
			return number_format($num).' '.$unit;
		}

		return number_format($num, $precision).' '.$unit;
	}
}

if ( ! function_exists('minute_format'))
{
	function minute_format($num, $precision = 1)
	{
		$CI =& get_instance();

		if ($num >= 60)
		{
			$hours = number_format(floor($num / 60));
			$mins = number_format(floor($num % 60));
			$hrs = $hours > 1 ? 'hrs' : 'hr';

			return $mins==0 ? $hours." {$hrs}" : $hours." {$hrs} ".$mins." mins";
		}
		else
		{
			return number_format($num).' mins';
		}
	}
}

if ( ! function_exists('amount'))
{
	function amount($amount=0, $decimal=2)
	{
		$CI =& get_instance();
		// get prefix and suffix from db
		$r = $CI->config_model->get_all();
        return $r->amount_prefix.number_format($amount, $decimal).$r->amount_suffix;
	}
}

/* End of file number_helper.php */
/* Location: ./system/helpers/number_helper.php */