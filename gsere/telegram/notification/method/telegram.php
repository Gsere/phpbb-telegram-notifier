<?php
/**
*
* @package phpBB Extension - gsere Telegram Notifier Method
* @copyright (c) 2018 gsere gabriel@internet.com.uy
* @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3
*
*/
namespace gsere\telegram\notification\method;

/**
* Telegram notification method class
* This class handles sending Telegram messages for notifications
*/
class telegram extends \phpbb\notification\method\messenger_base
{

        protected $user;
        protected $config;
        protected $language;
        public function __construct(
		\phpbb\user_loader $user_loader, 
		\phpbb\user $user, 
		\phpbb\language\language $language, 
		\phpbb\config\config $config, 
		$phpbb_root_path, 
		$php_ext)
        {
                parent::__construct($user_loader, $phpbb_root_path, $php_ext);
                $this->user 	= $user;
                $this->config 	= $config;
                $this->language = $language;
                $this->language->add_lang('common', 'gsere/telegram');
        }

	/**
	* Get notification method name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'notification.method.telegram';
	}

	/**
	* Is this method available for the user?
	* This is checked on the notifications options
	*/
	public function is_available()
	{
		return ($this->global_available() && (strlen($this->user->data['user_telegram']) > 2));
	}

	/**
	* Is this method available at all?
	* This is checked before notifications are sent
	*/
	public function global_available()
	{
		return !(empty($this->config['gsere_telegram_bot']));
	}

	public function notify()
	{
		$template_dir_prefix = '';

		if (!$this->global_available())
		{
			return;
		}

		if (empty($this->queue))
		{
			 return;
		}

		// Load all users we want to notify (we need their email address)
		$user_ids = $users = array();
		foreach ($this->queue as $notification)
		{
			$user_ids[] = $notification->user_id;
		}

		// We do not send telegram to banned users
		if (!function_exists('phpbb_get_banned_user_ids'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$banned_users = phpbb_get_banned_user_ids($user_ids);

		// Load all the users we need
		$this->user_loader->load_users($user_ids);

		global $config, $phpbb_container;

		// Time to go through the queue and send emails
		foreach ($this->queue as $notification)
		{

			if ($notification->get_email_template() === false)
			{
				continue;
			}

			$user = $this->user_loader->get_user($notification->user_id);

			if ($user['user_type'] == USER_IGNORE || in_array($notification->user_id, $banned_users))
			{
				continue;
			}

			$this->form_variables = $notification->get_email_template_variables();
			if ( isset($this->form_variables['SUBJECT']) ) {
        		   // 'TELEGRAM_PRIVATE_MSG'   => 'Novedades en %s: %s, %s te ha enviado un mensaje titulado %s',
			   // Site Name, username, author, subject
			   $this->msg_fmt = $this->language->lang('TELEGRAM_PRIVATE_MSG');
			   $this->msg = sprintf ($this->msg_fmt,
					htmlspecialchars_decode($config['sitename']), 
					$user['username'],
					$this->form_variables['AUTHOR_NAME'],
					$this->form_variables['SUBJECT']
				   );
			} else if ( isset($this->form_variables['AUTHOR_NAME']) ) {
        		   // 'TELEGRAM_NEW_MESSAGE'   => 'Novedades en %s: %s, %s ha publicado en %s un mensaje titulado %s',
			   // Site Name, username, author, tema, subject
			   $this->msg_fmt = $this->language->lang('TELEGRAM_NEW_MESSAGE');
			   $this->msg = sprintf ($this->msg_fmt,
					htmlspecialchars_decode($config['sitename']), 
					$user['username'],
					$this->form_variables['AUTHOR_NAME'],
					$this->form_variables['TOPIC_TITLE'],
					$this->form_variables['POST_SUBJECT']
				   );
			} else if ( isset($this->form_variables['GROUP_NAME']) ) {
			   // Site Name, username, who, group name
        		   // 'TELEGRAM_GROUP_REQUEST' => 'Novedades en %s: %s, el usuario %s solicita unirse al grupo %s.', 
			   $this->msg_fmt = $this->language->lang('TELEGRAM_GROUP_REQUEST');
			   $this->msg = sprintf ($this->msg_fmt,
					htmlspecialchars_decode($config['sitename']), 
					$user['username'],
					$this->form_variables['REQUEST_USERNAME'],
					$this->form_variables['GROUP_NAME']
				   );
			} else {
			   // what?? 
        		   // 'TELEGRAM_OTHER'         => 'Novedades en %s: %s, %s',
			   // Site Name, username, foo
			   $this->foo = implode(':',$this->form_variables);
			   $this->msg_fmt = $this->language->lang('TELEGRAM_OTHER');
			   $this->msg = sprintf ($this->msg_fmt,
					htmlspecialchars_decode($config['sitename']), 
					$user['username'],
					$this->foo
				   );
			}

			// Lets send the Telegram
			$this->send($user['user_telegram'], $this->msg);
		}
		$this->empty_queue();
	}

	/*
	 * Send a message to a telegram user
	 *
	 * @param	string	$telegram_id
	 * @param	string	$msg
	 */
	public function send($telegram_id, $msg)
	{
		error_log("Telegram: $telegram_id,$msg",0);

		$auth = $this->config['gsere_telegram_bot'];

		if (!$telegram_id) {  
		   error_log("Error, Telegram ID is needed",0);
		   return;
		}

		if (empty($auth)) {  
		   error_log("Error, Telegram Bot ID is needed",0);
		   return;
		}

		if (empty($msg)) {  
		   error_log("Error, No message to send",0);
		   return;
		}

		if (!function_exists('curl_version')) {
		   error_log("Error, is curl installed?",0);
		   return;
		}

		$data = array (
			'chat_id' => $telegram_id,
			'disable_web_page_preview' => 'true',
			'parse_mode' => 'HTML',
			'text' => $msg );

		$url = 'https://api.telegram.org/bot'.urlencode($auth).'/sendMessage';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$result = curl_exec($curl);
		$curl_error = curl_error($curl);
		curl_close($curl);

		if ($result === false) {
		   error_log("Error, $curl_error",0);
		   return;
		}

		$result_json = json_decode($result, true);
		if ($result_json === NULL) {
		   error_log("Error, ".json_last_error_msg(),0);
		   return;
		}
		return;
	}
}

