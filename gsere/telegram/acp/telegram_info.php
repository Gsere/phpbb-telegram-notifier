<?php
/**
*
* @package phpBB Extension - gsere Telegram Notifier Method
* @copyright (c) 2018 gsere gabriel@internet.com.uy
* @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3
*
*/
namespace gsere\telegram\acp;

class telegram_info
{
  function module()
  {
      return array(
	'filename'	=> '\gsere\telegram\telegram_module',
	'title'		=> 'ACP_TELEGRAM_TITLE',
	'version'	=> '1.0.0',
	'modes'		=> array(
	    'settings'		=> array(
		'title'		=> 'ACP_TELEGRAM_TITLE',
		'auth'		=> 'ext_gsere/telegram && acl_a_board',
		'cat'		=> array('ACP_TELEGRAM_TITLE')
	    ),
	),
      );
   }
}
