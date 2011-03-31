<?php  if ( ! defined('EXT')) exit('No direct script access allowed');

/**
 * Pagenumber
 *
 * Displays current page number
 *
 * @package DevDemon:Pagenumber
 * @version 2.0.0
 * @author Daniel Howells, originally by DevDemon
 * @see http://www.devdemon.com/
 * @copyright Copyright (c) 2009-2010 DevDemon
 */


/**
 * Plugin information used by ExpressionEngine
 *
 * @global array $plugin_info
 */
$plugin_info = array(
	'pi_name' 			=> 'Pagenumber',
	'pi_version' 		=> '2.0.0',
	'pi_author' 		=> 'Daniel Howells, originally DevDemon',
	'pi_author_url' 	=> 'http://www.devdemon.com/',
	'pi_description'	=> 'Display current page number',
	'pi_usage' 			=> Pagenumber::usage()
);

/**
 * Pagenumber Class
 *
 * @package DevDemon:Pagenumber
 * @version 1.0.0
 * @author DevDemon
 * @see http://www.devdemon.com/
 * @copyright Copyright (c) 2009-2010 DevDemon
 */
class Pagenumber
{
	/**
	 * The return data
	 *
	 * @var string
	 * @access public
	 **/
	public $return_data = '';

	/**
	 * Mailsend
	 *
	 * @access	public
	 * @return	void
	 */
	public function Pagenumber()
	{
		$this->EE =& get_instance();

		$results = '';

		//	First do a check for the required params, else return empty string, and log to $this->EE->TMPL->log_item
		if ($this->EE->TMPL->fetch_param('items_per_page') !== FALSE)
		{
			//	We need to make a temp variable, using substr() would be slower.
			if ($this->EE->TMPL->fetch_param('url_segment') !== FALSE)
			{
				$url_segment = $this->EE->TMPL->fetch_param('url_segment');
			}
			else
			{
				$url_segment = end($this->EE->uri->segment_array());
			}

			//	We need to check if this is pagination type string and if items_per_page="" param is numeric
			if (preg_match('/^[P][0-9]+$/i', $url_segment) != FALSE && is_numeric($this->EE->TMPL->fetch_param('items_per_page')) === TRUE)
			{
				// Calculate the current pagenumber
				$pagenum = ceil(substr($url_segment, 1) / $this->EE->TMPL->fetch_param('items_per_page')) + 1;

				// Is this the first page? and is the show_page_1="" parameter not filled?
				if ($this->EE->TMPL->fetch_param('show_first_page') === FALSE && $pagenum == 1)
				{
					// Then results is empty! OK!
					$results = '';
				}
				else
				{
					$results = $pagenum;

					// Grab the params
					$prefix = $this->EE->TMPL->fetch_param('prefix') ? $this->EE->TMPL->fetch_param('prefix') : '';
					$suffix = $this->EE->TMPL->fetch_param('suffix') ? $this->EE->TMPL->fetch_param('suffix') : '';

					// Do we have prefix ?
					if ($prefix != '')
					{
						$prefix = str_replace('+', ' ', $prefix);
						$results = $prefix . $results;
					}

					// Do we have suffix?
					if ($suffix != '')
					{
						$suffix = str_replace('+', ' ', $suffix);
						$results = $results . $suffix;
					}
				}
			}
			else
			{
				$this->EE->TMPL->log_item('Pagenumber: The last url_segment is not a pagination type segment, or items_per_page="'.$this->EE->TMPL->fetch_param('items_per_page').'" is not a numeric value');
				return;
			}
		}
		else
		{
			//	$this->EE->TMPL->log_item('Pagnumber: url_segment="" or  items_per_page="" param not found.');
			$this->EE->TMPL->log_item('Pagenumber: items_per_page="" param not found.');
			return;
		}

		$this->return_data = $results;
	}

	// ********************************************************************************* //

	/**
	 * Plugin usage documentation
	 *
	 * @return	string - Plugin usage instructions
	 */
	public function usage()
	{
		return 'For usage visit: http://www.devdemon.com/';
	}

	// ********************************************************************************* //

} // END CLASS


/* End of file pi.pagenumber.php */
/* Location: ./system/plugins/pi.pagenumber.php */