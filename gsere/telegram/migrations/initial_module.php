<?php
/**
*
* @package phpBB Extension - gsere Telegram Notifier Method
* @copyright (c) 2018 gsere gabriel@internet.com.uy
* @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3
*
*/
namespace gsere\telegram\migrations;

class initial_module extends \phpbb\db\migration\migration
{
    public function update_data()
    {
        return array(
            // Add configs
            array('config.add', array('gsere_telegram_bot', '')),

            // Add ACP module
            array('module.add', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'ACP_TELEGRAM_TITLE'
            )),
            array('module.add', array(
                'acp',
                'ACP_TELEGRAM_TITLE',
                array(
                    'module_basename'    => '\gsere\telegram\acp\telegram_module',
                    'modes'                => array('settings'),
                ),
            )),
        );
    }
    public function update_schema()
    {
        return array(
            'add_columns'    => array(
                $this->table_prefix . 'users'    => array(
                    'user_telegram'    => array('VCHAR:32', ''),
                ),
            ),
        );
    }
    public function revert_schema()
    {
        return array(
            'drop_columns' => array(
                $this->table_prefix . 'users'    => array(
                    'user_telegram',
                ),
            ),
        );
    }
}
