<?php
/**
*
* @package phpBB Extension - gsere Telegram Notifier Method
* @copyright (c) 2018 gsere gabriel@internet.com.uy
* @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3
*
*/
namespace gsere\telegram\event;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/* @var \phpbb\request\request */
	protected $request;

	/* @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/* @var Container */
	protected $phpbb_container;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/**
	* Constructor
	*
	* @param \phpbb\request\request		$request		Request object
	* @param \phpbb\user			$user			User Object
	* @param \phpbb\template\template	$template		Template Object
	* @param Container			$phpbb_container
	* @param string				$phpbb_root_path	phpbb_root_path
	* @access public
	*/
	public function __construct(\phpbb\request\request $request, \phpbb\user $user, \phpbb\template\template $template, Container $phpbb_container, $phpbb_root_path)
	{
		$user->add_lang_ext('gsere/telegram', 'common');
		$this->request = $request;
		$this->user = $user;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.ucp_profile_modify_profile_info'		=> 'ucp_profile_modify_profile_info',
			'core.ucp_profile_info_modify_sql_ary'		=> 'ucp_profile_info_modify_sql_ary',
			'core.acp_users_modify_profile'			=> 'acp_profile_modify_profile_info',
			'core.acp_users_profile_modify_sql_ary'		=> 'acp_profile_info_modify_sql_ary',
		);
	}
	/**
	* Add a new data field to the ACP
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_profile_modify_profile_info($event)
	{
		$telegram =  $this->request->variable('telegram', $event['user_row']['user_telegram']);
		$event['user_row'] = array_merge($event['user_row'], array(
			'user_telegram'	=> $telegram,
		));
		$this->add_field($event['user_row']['user_telegram']);
	}

	/**
	* Add a new data field to the UCP
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_modify_profile_info($event)
	{
		$telegram =  $this->request->variable('telegram', $this->user->data['user_telegram']);
		$event['data'] = array_merge($event['data'], array(
			'user_telegram'	=> $telegram,
		));

		$this->add_field($this->user->data['user_telegram']);
	}

	/**
	 * Add the TelegramID field to profile
	 */
	private function add_field($telegram)
	{
		$this->template->assign_vars(array(
			'TELEGRAM'		=> $telegram,
		));
	}


	/**
	* Validate users changes to their telegram id
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_validate_profile_info($event)
	{
			$array = $event['error'];
			if (!function_exists('validate_data'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			$validate_array = array(
				'user_telegram'	=> array('num', true, 0, 12),
			);
			$error = validate_data($event['data'], $validate_array);
			$event['error'] = array_merge($array, $error);
	}

	/**
	* User has changed his telegram id, update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_profile_info_modify_sql_ary($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_telegram' => $event['data']['user_telegram'],
		));
	}

	/**
	* Admin has changed his telegram id, update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function acp_profile_info_modify_sql_ary($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_telegram' => $event['user_row']['user_telegram'],
		));
	}
}
