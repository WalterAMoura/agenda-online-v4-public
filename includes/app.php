<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 0);

require __DIR__ . '/../vendor/autoload.php';


use App\Utils\View;
use App\Utils\Environment;
use App\Model\Database\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

//Carrega variáveis de ambiente
Environment::load(__DIR__ . '/../../config/env/app-agenda-v4/');

// Define as configurações de banco de dados
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

// Dados encryption
const OPTIONS_BCRYPT = [
    'cost' => 12
];

// Define o tempo de expiração da sessão do usuário
define("SESSION_EXPIRATION", intval(getenv('SESSION_EXPIRATION')));
define("SESSION_NAME", getenv('SESSION_NAME'));

//define("SESSION_EXPIRATION", 12);

// Define a constante de URL do projeto
define("URL", getenv('URL'));
define("PATH_MAIN", getenv('PATH_MAIN'));
define("PATH_LOGO_PNG", getenv('PATH_LOGO_PNG'));
define("PATH_LOGO_ICO", getenv('PATH_LOGO_ICO'));
define("PATH_LOGO_PNG_IASD", getenv('PATH_LOGO_PNG_IASD'));

// defina a constantes do whatsapp
define("TEMPLATE_SEND_AGENDA_WHATSAPP", getenv('TEMPLATE_SEND_AGENDA_WHATSAPP'));
define("TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP", getenv('TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP'));
define("TEMPLATE_REMINDER_AGENDA_WHATSAPP", getenv('TEMPLATE_REMINDER_AGENDA_WHATSAPP'));
define("TEMPLATE_SEND_RECEPTION_WHATSAPP", getenv('TEMPLATE_SEND_RECEPTION_WHATSAPP'));
define("TEMPLATE_SEND_WORSHIP_WHATSAPP", getenv('TEMPLATE_SEND_WORSHIP_WHATSAPP'));
define("URL_BASE_API_WHATSAPP",getenv('URL_BASE_API_WHATSAPP'));
define("VERSION_API_WHATSAPP", getenv('VERSION_API_WHATSAPP'));

//Define valor padrão das variáveis
View::init([
    'URL' => URL,
    'MAIN_PATH_APP' => PATH_MAIN,
    'PATH_LOGO_PNG' => PATH_LOGO_PNG,
    'PATH_LOGO_ICO' => PATH_LOGO_ICO,
    'PATH_LOGO_PNG_IASD' => PATH_LOGO_PNG_IASD
]);

//Define o mapeamento de middlewares

MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Maintenance::class,
    'required-logout' => \App\Http\Middleware\RequireLogout::class,
    'required-alert' => \App\Http\Middleware\RequireLogin::class,
    'required-login' => \App\Http\Middleware\RequireLogin::class,
    'api' => \App\Http\Middleware\Api::class,
    'jwe-auth' => \App\Http\Middleware\JWEAuth::class,
    'session-is-open' => \App\Http\Middleware\SessionOpen::class,
    'allow-update-passwd' => \App\Http\Middleware\AllowUpdatePasswd::class,
    'allow-page-advanced-settings' => \App\Http\Middleware\AllowPageAdvancedSettings::class,
    'allow-page-home' => \App\Http\Middleware\AllowPageHome::class,
    'allow-page-level' => \App\Http\Middleware\AllowPageManagerLevel::class,
    'allow-page-type-module' => \App\Http\Middleware\AllowPageManagerTypeModule::class,
    'allow-page-modules' => \App\Http\Middleware\AllowPageManagerModule::class,
    'allow-page-access-modules' => \App\Http\Middleware\AllowPageManagerAccessModule::class,
    'allow-page-manager-user' => \App\Http\Middleware\AllowPageManagerUser::class,
    'allow-page-manager-settings-smtp' => \App\Http\Middleware\AllowPageManagerSettingsSmtp::class,
    'allow-page-manager-email-alarmes' => \App\Http\Middleware\AllowPageManagerEmailAlarmes::class,
    'allow-page-manager-apikey' => \App\Http\Middleware\AllowPageManagerApikey::class,
    'required-apikey' => \App\Http\Middleware\RequireApikey::class,
    'allow-page-manager-event' => \App\Http\Middleware\AllowPageManagerEvent::class,
    'allow-page-config-event' => \App\Http\Middleware\AllowPageConfigEvent::class,
    'allow-page-status-event' => \App\Http\Middleware\AllowPageStatusEvent::class,
    'allow-page-departments' => \App\Http\Middleware\AllowPageDepartments::class,
    'allow-page-programs' => \App\Http\Middleware\AllowPagePrograms::class,
    'allow-page-elder' => \App\Http\Middleware\AllowPageElder::class,
    'allow-page-elder-month' => \App\Http\Middleware\AllowPageElderMonth::class,
    'allow-page-manager-sound-team' => \App\Http\Middleware\AllowPageManagerSoundTeam::class,
    'allow-page-manager-team' => \App\Http\Middleware\AllowPageManagerTeam::class,
    'allow-page-device-sound' => \App\Http\Middleware\AllowPageDeviceSound::class,
    'allow-page-suggested-time' => \App\Http\Middleware\AllowPageSuggestedTime::class,
    'allow-page-team-lineup' => \App\Http\Middleware\AllowPageTeamLineup::class,
    'allow-page-ask-to-change' => \App\Http\Middleware\AllowPageAskToChange::class,
    'allow-page-monitoring' => \App\Http\Middleware\AllowPageMonitoring::class,
    'allow-page-manager-events-church' => \App\Http\Middleware\AllowPageManagerEventsChurch::class,
    'allow-page-elder-for-department' => \App\Http\Middleware\AllowPageElderForDepartment::class,
    'allow-page-manager-temp-users' => \App\Http\Middleware\AllowPageManagerTempUsers::class,
    'allow-page-approve-temp-user' => \App\Http\Middleware\AllowPageApproveTempUsers::class,
    'allow-page-reprove-temp-user' => \App\Http\Middleware\AllowPageReproveTempUsers::class,
    'allow-page-manager-organization' => \App\Http\Middleware\AllowPageManagerOrganization::class,
    'webhook-whatsapp' => \App\Http\Middleware\WhatsAppHook::class,
    'allow-page-manager-access-token-whatsapp' => \App\Http\Middleware\AllowPageManagerAccessTokenWhatsApp::class,
    'allow-page-monitoring-whatsapp' => \App\Http\Middleware\AllowPageMonitoringWhatsApp::class,
    'allow-page-manager-reception-team' => \App\Http\Middleware\AllowPageReception::class,
    'allow-page-team-lineup-reception' => \App\Http\Middleware\AllowPageTeamLineupReception::class,
    'allow-page-manager-worship-team' => \App\Http\Middleware\AllowPageWorship::class,
    'allow-page-team-lineup-worship' => \App\Http\Middleware\AllowPageTeamLineupWorship::class,
]);

MiddlewareQueue::setDefault([
    'maintenance'
]);