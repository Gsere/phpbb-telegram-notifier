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
	'ACP_SETTINGS'			=> 'Configuración',
	'ACP_SENDER'			=> 'Identificador del Bot de Telegram',
	'ACP_SENDER_EXPLAIN'		=> 'Ingrese el ID del Bot desde donde serán enviadas las notificaciones',
	'ACP_SUBMIT'			=> 'Actualizar',
	'ACP_SAVED'			=> 'Datos del Bot actualizados.',
	'TELEGRAM_ID'			=> 'Su ID de Telegram',
	'TELEGRAM_ID_EXPLAIN'		=> '<b>Notificaciones por Telegram</b><br>Agrege el contacto "BOTNAME" a sus contactos de Telegram en su dispositivo. Luego ingrese aquí su ID de Telegram. Luego de esto podrá ir a "Preferencias de Foros->Editar opcines de notificación" y seleccionar qué alertas recibir en su dispositivo',
	'TELEGRAM_GROUP_REQUEST' => "Mensaje de %s:\nHola %s, el usuario %s solicita unirse al grupo <b>\"%s\"</b>.", 
	'TELEGRAM_NEW_MESSAGE'   => "Mensaje de %s:\nHola %s, el usuario %s envió un mensaje en %s con el título <b>\"%s\"</b>.", 
	'TELEGRAM_OTHER'         => "Mensaje de %s:\nHola %s, %s.",
	'TELEGRAM_PRIVATE_MSG'   => "Mensaje de %s:\nHola %s, el usuario %s te envió un mensaje privado de título <b>\"%s\"</b>.",
));

