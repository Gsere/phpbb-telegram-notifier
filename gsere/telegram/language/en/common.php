<?php
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}


/*
 * TELEGRAM_* params: Site Name, username, [author], subject
 *      'TELEGRAM_GROUP_REQUEST' => site name, username, author, group name
 *      'TELEGRAM_NEW_MESSAGE'   => site name, username, author, subject
 *      'TELEGRAM_PRIVATE_MSG'   => site name, username, author, subject
 *      'TELEGRAM_OTHER'         => site name, username, (comma separated params)
 */
$lang = array_merge($lang, array(
	'NOTIFICATION_METHOD_TELEGRAM'	=> 'Telegram',
	'ACP_SETTINGS'			=> 'Settings',
	'ACP_SENDER'			=> 'Telegram Bot Token',
	'ACP_SENDER_EXPLAIN'		=> 'Enter the Bot ID from where the messages will be sent',
	'ACP_SUBMIT'			=> 'Update settings',
	'ACP_SAVED'			=> 'Telegram Bot ID updated.',
	'TELEGRAM_ID'			=> 'Your Telegram ID',
	'TELEGRAM_ID_EXPLAIN'		=> '<b>Notifications by Telegram</b><br>Add your bot "BOTNAME" to your Telegram contacts and enter your Telegram ID below.
After that you can go to "Forum preferences->Edit notificacion options" and select the alerts you want to receive in your phone',
	'TELEGRAM_GROUP_REQUEST' => "Message on %s:\nHello %s, the user %s requests to join the group <b>\"%s\"</b>.", 
	'TELEGRAM_NEW_MESSAGE'   => "Message on %s:\nHello %s, user %s has sent a message on %s with the subject <b>\"%s\"</b>.", 
	'TELEGRAM_OTHER'         => "Message on %s:\nHello %s, %s.",
	'TELEGRAM_PRIVATE_MSG'   => "Message on %s:\nHello %s, user %s has sent a private message to you with the subject <b>\"%s\"</b>.",
));

