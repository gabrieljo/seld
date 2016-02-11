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
 * CodeIgniter Text Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/text_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Word Limiter
 *
 * Limits a string to X number of words.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */
if ( ! function_exists('word_limiter'))
{
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

		if (strlen($str) == strlen($matches[0]))
		{
			$end_char = '';
		}

		return rtrim($matches[0]).$end_char;
	}
}

// ------------------------------------------------------------------------

/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */
if ( ! function_exists('character_limiter'))
{
	function character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}

		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}

		$out = "";
		foreach (explode(' ', trim($str)) as $val)
		{
			$out .= $val.' ';

			if (strlen($out) >= $n)
			{
				$out = trim($out);
				return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
			}
		}
	}
}

// ------------------------------------------------------------------------

/**
 * High ASCII to Entities
 *
 * Converts High ascii text and MS Word special characters to character entities
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('ascii_to_entities'))
{
	function ascii_to_entities($str)
	{
		$count	= 1;
		$out	= '';
		$temp	= array();

		for ($i = 0, $s = strlen($str); $i < $s; $i++)
		{
			$ordinal = ord($str[$i]);

			if ($ordinal < 128)
			{
				/*
					If the $temp array has a value but we have moved on, then it seems only
					fair that we output that entity and restart $temp before continuing. -Paul
				*/
				if (count($temp) == 1)
				{
					$out  .= '&#'.array_shift($temp).';';
					$count = 1;
				}

				$out .= $str[$i];
			}
			else
			{
				if (count($temp) == 0)
				{
					$count = ($ordinal < 224) ? 2 : 3;
				}

				$temp[] = $ordinal;

				if (count($temp) == $count)
				{
					$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

					$out .= '&#'.$number.';';
					$count = 1;
					$temp = array();
				}
			}
		}

		return $out;
	}
}

// ------------------------------------------------------------------------

/**
 * Entities to ASCII
 *
 * Converts character entities back to ASCII
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	string
 */
if ( ! function_exists('entities_to_ascii'))
{
	function entities_to_ascii($str, $all = TRUE)
	{
		if (preg_match_all('/\&#(\d+)\;/', $str, $matches))
		{
			for ($i = 0, $s = count($matches['0']); $i < $s; $i++)
			{
				$digits = $matches['1'][$i];

				$out = '';

				if ($digits < 128)
				{
					$out .= chr($digits);

				}
				elseif ($digits < 2048)
				{
					$out .= chr(192 + (($digits - ($digits % 64)) / 64));
					$out .= chr(128 + ($digits % 64));
				}
				else
				{
					$out .= chr(224 + (($digits - ($digits % 4096)) / 4096));
					$out .= chr(128 + ((($digits % 4096) - ($digits % 64)) / 64));
					$out .= chr(128 + ($digits % 64));
				}

				$str = str_replace($matches['0'][$i], $out, $str);
			}
		}

		if ($all)
		{
			$str = str_replace(array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
								array("&","<",">","\"", "'", "-"),
								$str);
		}

		return $str;
	}
}

// ------------------------------------------------------------------------

/**
 * Word Censoring Function
 *
 * Supply a string and an array of disallowed words and any
 * matched words will be converted to #### or to the replacement
 * word you've submitted.
 *
 * @access	public
 * @param	string	the text string
 * @param	string	the array of censoered words
 * @param	string	the optional replacement value
 * @return	string
 */
if ( ! function_exists('word_censor'))
{
	function word_censor($str, $censored, $replacement = '')
	{
		if ( ! is_array($censored))
		{
			return $str;
		}

		$str = ' '.$str.' ';

		// \w, \b and a few others do not match on a unicode character
		// set for performance reasons. As a result words like über
		// will not match on a word boundary. Instead, we'll assume that
		// a bad word will be bookeneded by any of these characters.
		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword)
		{
			if ($replacement != '')
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			}
			else
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		return trim($str);
	}
}

// ------------------------------------------------------------------------

/**
 * Code Highlighter
 *
 * Colorizes code strings
 *
 * @access	public
 * @param	string	the text string
 * @return	string
 */
if ( ! function_exists('highlight_code'))
{
	function highlight_code($str)
	{
		// The highlight string function encodes and highlights
		// brackets so we need them to start raw
		$str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

		// Replace any existing PHP tags to temporary markers so they don't accidentally
		// break the string out of PHP, and thus, thwart the highlighting.

		$str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'),
							array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);

		// The highlight_string function requires that the text be surrounded
		// by PHP tags, which we will remove later
		$str = '<?php '.$str.' ?>'; // <?

		// All the magic happens here, baby!
		$str = highlight_string($str, TRUE);

		// Prior to PHP 5, the highligh function used icky <font> tags
		// so we'll replace them with <span> tags.

		if (abs(PHP_VERSION) < 5)
		{
			$str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
			$str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
		}

		// Remove our artificially added PHP, and the syntax highlighting that came with it
		$str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
		$str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
		$str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);

		// Replace our markers back to PHP tags.
		$str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'),
							array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);

		return $str;
	}
}

// ------------------------------------------------------------------------

/**
 * Phrase Highlighter
 *
 * Highlights a phrase within a text string
 *
 * @access	public
 * @param	string	the text string
 * @param	string	the phrase you'd like to highlight
 * @param	string	the openging tag to precede the phrase with
 * @param	string	the closing tag to end the phrase with
 * @return	string
 */
if ( ! function_exists('highlight_phrase'))
{
	function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
	{
		if ($str == '')
		{
			return '';
		}

		if ($phrase != '')
		{
			return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
		}

		return $str;
	}
}

// ------------------------------------------------------------------------

/**
 * Convert Accented Foreign Characters to ASCII
 *
 * @access	public
 * @param	string	the text string
 * @return	string
 */
if ( ! function_exists('convert_accented_characters'))
{
	function convert_accented_characters($str)
	{
		if ( ! file_exists(APPPATH.'config/foreign_chars'.EXT))
		{
			return $str;
		}

		include APPPATH.'config/foreign_chars'.EXT;

		if ( ! isset($foreign_characters))
		{
			return $str;
		}

		return preg_replace(array_keys($foreign_characters), array_values($foreign_characters), $str);
	}
}

// ------------------------------------------------------------------------

/**
 * Word Wrap
 *
 * Wraps text at the specified character.  Maintains the integrity of words.
 * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
 * will URLs.
 *
 * @access	public
 * @param	string	the text string
 * @param	integer	the number of characters to wrap at
 * @return	string
 */
if ( ! function_exists('word_wrap'))
{
	function word_wrap($str, $charlim = '76')
	{
		// Se the character limit
		if ( ! is_numeric($charlim))
			$charlim = 76;

		// Reduce multiple spaces
		$str = preg_replace("| +|", " ", $str);

		// Standardize newlines
		if (strpos($str, "\r") !== FALSE)
		{
			$str = str_replace(array("\r\n", "\r"), "\n", $str);
		}

		// If the current word is surrounded by {unwrap} tags we'll
		// strip the entire chunk and replace it with a marker.
		$unwrap = array();
		if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches))
		{
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$unwrap[] = $matches['1'][$i];
				$str = str_replace($matches['1'][$i], "{{unwrapped".$i."}}", $str);
			}
		}

		// Use PHP's native function to do the initial wordwrap.
		// We set the cut flag to FALSE so that any individual words that are
		// too long get left alone.  In the next step we'll deal with them.
		$str = wordwrap($str, $charlim, "\n", FALSE);

		// Split the string into individual lines of text and cycle through them
		$output = "";
		foreach (explode("\n", $str) as $line)
		{
			// Is the line within the allowed character count?
			// If so we'll join it to the output and continue
			if (strlen($line) <= $charlim)
			{
				$output .= $line."\n";
				continue;
			}

			$temp = '';
			while((strlen($line)) > $charlim)
			{
				// If the over-length word is a URL we won't wrap it
				if (preg_match("!\[url.+\]|://|wwww.!", $line))
				{
					break;
				}

				// Trim the word down
				$temp .= substr($line, 0, $charlim-1);
				$line = substr($line, $charlim-1);
			}

			// If $temp contains data it means we had to split up an over-length
			// word into smaller chunks so we'll add it back to our current line
			if ($temp != '')
			{
				$output .= $temp."\n".$line;
			}
			else
			{
				$output .= $line;
			}

			$output .= "\n";
		}

		// Put our markers back
		if (count($unwrap) > 0)
		{
			foreach ($unwrap as $key => $val)
			{
				$output = str_replace("{{unwrapped".$key."}}", $val, $output);
			}
		}

		// Remove the unwrap tags
		$output = str_replace(array('{unwrap}', '{/unwrap}'), '', $output);

		return $output;
	}
}

// ------------------------------------------------------------------------

/**
 * Ellipsize String
 *
 * This function will strip tags from a string, split it at its max_length and ellipsize
 *
 * @param	string		string to ellipsize
 * @param	integer		max length of string
 * @param	mixed		int (1|0) or float, .5, .2, etc for position to split
 * @param	string		ellipsis ; Default '...'
 * @return	string		ellipsized string
 */
if ( ! function_exists('ellipsize'))
{
	function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')
	{
		// Strip tags
		$str = trim(strip_tags($str));

		// Is the string long enough to ellipsize?
		if (strlen($str) <= $max_length)
		{
			return $str;
		}

		$beg = substr($str, 0, floor($max_length * $position));

		$position = ($position > 1) ? 1 : $position;

		if ($position === 1)
		{
			$end = substr($str, 0, -($max_length - strlen($beg)));
		}
		else
		{
			$end = substr($str, -($max_length - strlen($beg)));
		}

		return $beg.$ellipsis.$end;
	}
}

function getApanelListMenu($parent, $menu, $controller, $perpage)
{
   $html = "";
   $CI =& get_instance();
   if (isset($menu['parents'][$parent]))
   {
	   foreach ($menu['parents'][$parent] as $itemId)
	   {
		   $ad = '';
		   $cnt = (intval($menu['items'][$itemId]['level']));
		   for($i=1; $i<$cnt; $i++)
		   {
			   $ad.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		   }
		   $ad.= ($cnt < 2) ? '' : '╚ ';
		   
		   $ckbxid = 'ckbx'.$itemId;
		   $publish = ($menu['items'][$itemId]['published'] == 1) ? 'Published' : 'Unpublished';
		   
		   $lnk = '';
		   if ($menu['items'][$itemId]['link_type'] == 'internal')
		   {
			   $pt = explode('|::|', $menu['items'][$itemId]['link_page'], 2);
			   $lnk = $pt[0];
		   }
		   $url = ($menu['items'][$itemId]['link_type'] == 'internal') ? base_url().$lnk.$menu['items'][$itemId]['link_url'] : $menu['items'][$itemId]['link_url'];
		   $lnktgt = ($menu['items'][$itemId]['link_target'] == '_parent') ? 'Current window' : 'New Window';
		   $lnktyp = ($menu['items'][$itemId]['link_type'] == 'internal') ? 'Inner Link' : 'External Link';
		   $pub_class = $menu['items'][$itemId]['published'];
		   $html.= '<tr>
					  <td><input type="checkbox" name="listrow[]" id="'.$ckbxid.'" class="ckbox" value="'.$itemId.'" /></td>
					  <td><label for="'.$ckbxid.'">'.$ad.anchor($controller.'/form/'.base64_encode($itemId), $menu['items'][$itemId]['name']).'</label></td>
					  <td><label for="'.$ckbxid.'">'.anchor($url, ellipsize($url, 45, 0.5), 'target="_blank"').'</label></td>
					  <td>'.form_input(array('name'=>'sortorder[]', 'value'=>$menu['items'][$itemId]['sortorder'], 'class'=>'orderbox')).form_hidden('recid[]', $itemId).'</td>
					  <td><label for="'.$ckbxid.'">'.$lnktyp.'</label></td>
					  <td><label for="'.$ckbxid.'">'.$lnktgt.'</label></td>
					  <td align="center">
					      <div class="icon-status '.$pub_class.'" ref="'.site_url().'/'.$controller.'/status/'.base64_encode($itemId).'/ajax/1"></div>
					      '.anchor($controller.'/form/'.base64_encode($itemId), inc('icon-tools-pencil.png', array('width'=>18, 'title'=>'Click to Edit'))).' 
						  '.anchor($controller.'/delete/'.base64_encode($itemId), inc('icon-tools-delete.png', array('width'=>18, 'title'=>'Click to Trash')), array('class'=>'ico_trash')).'</td>
					</tr>';
				   
		    if(isset($menu['parents'][$itemId]))
		    {
			   $html .= getApanelListMenu($itemId, $menu, $controller, $perpage);
		    }
	   }
   }
   return $html;
}

function getApanelListCategory($parent, $menu, $controller, $perpage)
{
   $html = "";
   $CI =& get_instance();
   if (isset($menu['parents'][$parent]))
   {
	   foreach ($menu['parents'][$parent] as $itemId)
	   {
		   $ad = '';
		   $cnt = (intval($menu['items'][$itemId]['level']));
		   for($i=1; $i<$cnt; $i++)
		   {
			   $ad.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		   }
		   $ad.= ($cnt < 2) ? '' : '╚ ';
		   
		   $ckbxid = 'ckbx'.$itemId;
		   $publish = ($menu['items'][$itemId]['published'] == 1) ? 'Published' : 'Unpublished';
		   
		   $lnk = '';
		   if ($menu['items'][$itemId]['link_type'] == 'internal')
		   {
			   $pt = explode('|::|', $menu['items'][$itemId]['link_page'], 2);
			   $lnk = 'view/'.$pt[0];
		   }
		   $url = ($menu['items'][$itemId]['link_type'] == 'internal') ? base_url().$lnk.$menu['items'][$itemId]['link_url'] : $menu['items'][$itemId]['link_url'];
		   $lnktgt = ($menu['items'][$itemId]['link_target'] == '_parent') ? 'Current window' : 'New Window';
		   $lnktyp = ($menu['items'][$itemId]['link_type'] == 'internal') ? 'Inner Link' : 'External Link';
		   
		   $html.= '<tr>
					  <td><input type="checkbox" name="listrow[]" id="'.$ckbxid.'" class="ckbox" value="'.$itemId.'" /></td>
					  <td><label for="'.$ckbxid.'">'.$ad.$menu['items'][$itemId]['name'].'</label></td>
					  <td>'.form_input(array('name'=>'sortorder[]', 'value'=>$menu['items'][$itemId]['sortorder'], 'class'=>'orderbox')).form_hidden('recid[]', $itemId).'</td>
					  <td><label for="'.$ckbxid.'">'.$publish.'</label></td>
					  <td align="center">'.anchor('administrator/'.$controller.'/form/'.base64_encode($itemId).'/'.$perpage, inc('icon-edit.png', array('width'=>18))).' '.anchor('administrator/'.$controller.'/delete/'.base64_encode($itemId).'/'.$perpage, inc('icon-trash.png', array('width'=>18)), array('class'=>'ico_trash')).'</td>
					</tr>';
				   
		    if(isset($menu['parents'][$itemId]))
		    {
			   $html .= getApanelListCategory($itemId, $menu, $controller, $perpage);
		    }
	   }
   }
   return $html;
}
function getMenuList($parent, $menu, $parent_of=0)
{
   $html = "";
   if (isset($menu['parents'][$parent]))
   {
	   foreach ($menu['parents'][$parent] as $itemId)
	   {
		   $ad = '';
		   $cnt = (intval($menu['items'][$itemId]['level']));
		   for($i=0; $i<$cnt; $i++)
		   {
			   $ad.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		   }
		   
		   $ck = ($itemId == $parent_of) ? 'selected="selected"' : '';
		   //echo $itemId.':'.$parent_of.' - ['.$menu['items'][$itemId]['name'].'] ('.$menu['items'][$itemId]['level'].')<br />';
		   $html .= "<option value='".$menu['items'][$itemId]['id'].'-'.($menu['items'][$itemId]['level']+1)."' {$ck}> ".$ad.$menu['items'][$itemId]['name']."</option> \r\n";
		   if(isset($menu['parents'][$itemId]))
		   {
			  $html .= getMenuList($itemId, $menu, $parent_of);
		   }
	   }
   }
   return $html;
}


function buildMenu($parent, $menu, $cur_id=0, $cur_class='current')
{
   $html = "";
   if (isset($menu['parents'][$parent]))
   {
	  $html .= "
	  <ul>\n";
	   foreach ($menu['parents'][$parent] as $itemId)
	   {
		   $ln_id = $menu['items'][$itemId]['id'];
		   $ln_type = $menu['items'][$itemId]['link_type'];
		   $ln_name = $menu['items'][$itemId]['name'];
		   $ln_url = $menu['items'][$itemId]['link_url'];
		   $ln_img = '';
		   $ln_target = $menu['items'][$itemId]['target'];
		   $ln_level = $menu['items'][$itemId]['access_level'];
		   $ln_enable = $menu['items'][$itemId]['enable_image'];
		   
		   $attr = '';
		   $target = ($ln_target == '') ? '' : 'target="'.$ln_target.'"';
		   $lnk = '';
		   if ($menu['items'][$itemId]['link_type'] == 'internal')
		   {
			   $pt = explode('|::|', $menu['items'][$itemId]['link_page'], 2);
			   $lnk = $pt[0];
		   }
		   $url = ($menu['items'][$itemId]['link_type'] == 'internal') ? base_url().$lnk.$menu['items'][$itemId]['link_url'] : $menu['items'][$itemId]['link_url'];
		   
		   switch($ln_type)
		   {
			   case 'internal':
				 $ln_slug = '';//$menu['items'][$itemId]['slug'];
				 $ln_code = '';//$menu['items'][$itemId]['code'];
				 $slug = ($ln_url == '') ? $ln_slug : $ln_url;
				 $attr = 'href="'.$url.'"';
				 break;
				 
			   case 'external':
				 $attr = 'href="'.$ln_url.'"';
				 break;
				 
			   case 'seperator':
				 break;
				 
			   default:
				 break;
		   }
		   
		   $attr.= ($cur_id == $itemId) ? ' class="'.$cur_class.'"' : '';
		   
		  if( !isset($menu['parents'][$itemId]) )
			  $html .= "<li>\n  <a {$attr} {$target}> {$ln_name} </a> \n</li> \n";
		  else
			 $html .= "<li>\n  <a {$attr} {$target}> {$ln_name} </a> \n " . buildMenu($itemId, $menu, $cur_id, $cur_class) . "</li> \n";
	   }
	   $html .= "</ul> \n";
   }
   return $html;
}

function buildSubMenu($parent, $menu, $cur_id=0, $cur_class='current', $subs=0, $temp=0)
{
   $html = "";
   if (isset($menu['parents'][$parent]))
   {
	  $html .= "
	  <ul>\n";
	   foreach ($menu['parents'][$parent] as $itemId)
	   {
		   if ($subs != 0 && $temp != 0 && ($itemId == $subs || $subs == $menu['items'][$itemId]['parent_of'] || $temp == $menu['items'][$itemId]['parent_of']))
		   {
			   $temp = $menu['items'][$itemId]['id'];
			   
			   $ln_id = $menu['items'][$itemId]['id'];
			   $ln_type = $menu['items'][$itemId]['link_type'];
			   $ln_name = $menu['items'][$itemId]['name'];
			   $ln_url = $menu['items'][$itemId]['link_url'];
			   $ln_img = '';
			   $ln_target = $menu['items'][$itemId]['target'];
			   $ln_level = $menu['items'][$itemId]['access_level'];
			   $ln_enable = $menu['items'][$itemId]['enable_image'];
			   
			   $attr = '';
			   $target = ($ln_target == '') ? '' : 'target="'.$ln_target.'"';
			   $lnk = '';
			   if ($menu['items'][$itemId]['link_type'] == 'internal')
			   {
				   $pt = explode('|::|', $menu['items'][$itemId]['link_page'], 2);
				   $lnk = $pt[0];
			   }
			   $url = ($menu['items'][$itemId]['link_type'] == 'internal') ? base_url().$lnk.$menu['items'][$itemId]['link_url'] : $menu['items'][$itemId]['link_url'];
			   
			   switch($ln_type)
			   {
				   case 'internal':
					 $ln_slug = '';//$menu['items'][$itemId]['slug'];
					 $ln_code = '';//$menu['items'][$itemId]['code'];
					 $slug = ($ln_url == '') ? $ln_slug : $ln_url;
					 $attr = 'href="'.$url.'"';
					 break;
					 
				   case 'external':
					 $attr = 'href="'.$ln_url.'"';
					 break;
					 
				   case 'seperator':
					 break;
					 
				   default:
					 break;
			   }
			   
			   $attr.= ($cur_id == $itemId) ? ' class="'.$cur_class.'"' : '';
			   
			  if( !isset($menu['parents'][$itemId]) )
				  $html .= "<li>\n  <a {$attr} {$target}> {$ln_name} </a> \n</li> \n";
			  else
				 $html .= "<li>\n  <a {$attr} {$target}> {$ln_name} </a> \n " . buildMenu($itemId, $menu, $cur_id, $cur_class, $subs, $temp) . "</li> \n";
	       }
	   }
	   $html .= "</ul> \n";
   }
   return $html;
}

/*
 * Clear Images.
 */
function cleanImages($folder='')
{
	$DirHandle = @opendir("./files/".$folder."/");// or die($folder." could not be opened.");
	
	while($filename = readdir($DirHandle)):
		if($filename=="." || $filename==".." || $filename == ".htaccess" || $filename == 'index.html') {continue;}
		
		$ext = explode('.', $filename);
		if (count($ext) > 1)
		    @unlink("./files/{$folder}/".$filename);
	endwhile;
}

/* 
 * Generate Unique Public ID
 * md5 > time() and random #.
 */
function unique_public_id()
{
	return md5(time().rand(1000,500000));
}

/* 
 * Generate Unique Public ID
 * md5 > time() and random #.
 */
function get_status($st=0)
{
	switch($st)
	{
		case 0:
		  $ret = '<span class="muted">Inactive</span>';
		  break;
		  
		 case 1:
		   $ret = '<span class="text-info">Active</span>';
		   break;
		   
		 case 2:
		   $ret = '<span class="text-success">Completed</span>';
		   break;
		   
		 case 3:
		   $ret = '<span class="text-error">Suspended</span>';
		   break; 
		  
		 default:
		   $ret = '';
		   break;
	}
	return $ret;
}

/* 
 * Generate Unique Public ID
 * md5 > time() and random #.
 */
function get_probono($st=0)
{
	switch($st)
	{
		case 0:
		  $ret = '';
		  break;
		  
		 default:
		   $ret = ' - <span class="muted">Probono</span>';
		   break;
	}
	return $ret;
}

/*
 * this function will return the status..
 */
function getStatus($msg=''){
	switch ($msg) {
		case 'success':
			return '<p class="bg-success my_status"><span class="glyphicon glyphicon-info-sign"></span> Your record has been saved.</p>';
			break;

		case 'deleted':
			return '<p class="bg-warning my_status"><span class="glyphicon glyphicon-info-sign"></span> Your record has been deleted.</p>';
			break;

		case 'fail':
			return '<p class="bg-danger my_status"><span class="glyphicon glyphicon-info-sign"></span> Unable to save your changes.</p>';
			break;
		
		case 'profile_update':
			return '<p class="bg-info text-center"><span class="glyphicon glyphicon-info-sign"></span> *Enter the password only if you want to update the current one.</p>';
			break;

		default:
			return '';
			break;
	}
}

/*
 * Show Title in terms of DEVELOPER MODE.
 */
function show_public_id($id='')
{
	if (defined('DEVELOPER') && DEVELOPER == true && trim($id) != '')
	    return " <span class=\"public_id\">Public ID : {$id}</span>";
	else 
	    return '';
}

/*
 * Module Properties Values
 * Check for existence and give a default value if not.
 */
function get_module_key($all_property='', $key='', $default_value='', $int_check=false)
{
	$ret = '';
	if (is_array($all_property) && $key != '')
	{
		$ret = isset($all_property[$key]) ? $all_property[$key]  : $default_value;
		$ret = ($int_check == true) ? intval($ret) : $ret;
	}
	return $ret;
}

/*
 * Get Tiny Editor Script
 * Pass 1 element id OR array containing ids ARRAY
 * Pass the editor mode in second param
 */
function tiny_mce($elm_id='', $mode='')
{
	$elms = $elm_id;
	if (is_array($elm_id))
	{
		$elms = '';
		$sep = '';
		foreach ($elm_id as $k):
		    $elms.= $sep.$k;
			$sep = ',';
		endforeach;
	}
	$ret = inc('tiny_mce/tiny_mce.js');
	$ret.= '<script type="text/javascript">
			  tinyMCE.init({
				  // General options
				  mode : "exact",
				  elements : "'.$elms.'",
				  theme : "advanced",
				  skin : "o2k7",
				  skin_variant : "silver",
				  plugins : "phpimage,safari,pagebreak,style,layer,save,table,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
		  
				  // Theme options
				  theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
				  theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,phpimage,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				  theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,|,sub,sup,|,charmap,media,|,fullscreen,attribs",
				  theme_advanced_buttons4 : "",
				  
				  theme_advanced_toolbar_location : "top",
				  theme_advanced_toolbar_align : "left",
				  theme_advanced_statusbar_location : false,
				  theme_advanced_resizing : false,
				  theme_advanced_disable: "image,advimage",
				  content_css : "'.base_url().'files/js/tiny_mce/wysiwyg.css",
		  
				  // Drop lists for link/image/media/template dialogs
				  template_external_list_url : "lists/template_list.js",
				  external_link_list_url : "lists/link_list.php",
				  external_image_list_url : "'.base_url().'files/js/tiny_mce/plugins/phpimage/link_image.php?t='.base64_encode(base_url()).'",
				  media_external_list_url : "lists/media_list.js",
				  relative_urls : false,
				  remove_script_host : false,
		  
				  // Replace values for the template plugin
				  template_replace_values : {
					  username : "Some User",
					  staffid : "991234"
				  }
			  });
		  </script>';
	return $ret;
}

/* End of file text_helper.php */
/* Location: ./system/helpers/text_helper.php */