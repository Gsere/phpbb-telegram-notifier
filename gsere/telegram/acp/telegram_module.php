<?php
/**
*
* @package phpBB Extension - gsere Telegram Notifier Method
* @copyright (c) 2018 gsere gabriel@internet.com.uy
* @license https://opensource.org/licenses/GPL-3.0 GNU General Public License v3
*
*/
namespace gsere\telegram\acp;

class telegram_module
{
    var $u_action;

    public function main($id, $mode)
    {
        global $config, $user, $template, $request, $phpbb_container, $phpbb_root_path, $phpEx;

        $user->add_lang_ext('gsere/telegram', 'common');
        $this->tpl_name = 'acp_telegram_body';
        $this->page_title = $user->lang('ACP_TELEGRAM_TITLE');

        add_form_key('acp_telegram');

        // Form is submitted
        if ($request->is_set_post('submit'))
        {
            if (!check_form_key('acp_telegram'))
            {
                trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
            }

            $config->set('gsere_telegram_bot', $request->variable('sender', ''));
            trigger_error($user->lang('ACP_SAVED') . adm_back_link($this->u_action));
        }

        $template->assign_vars(array(
            'U_ACTION'        => $this->u_action,
            'SENDER'            => isset($config['gsere_telegram_bot']) ? $config['gsere_telegram_bot'] : '',
        ));
    }
}
