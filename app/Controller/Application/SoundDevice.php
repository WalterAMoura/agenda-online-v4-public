<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SoundDevice as EntitySoundDevice;
use App\Utils\General;
use App\Utils\View;
use Exception;

class SoundDevice extends Page
{
    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewSoundDevice(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/sound-device',[
            'title' => 'Cadastrar Dispositivo',
            'breadcrumbItem' => 'Cadastrar Dispositivo',
            'deviceName' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de equipamento de som.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Dispositivos de Som',$content,'manager-sound-team');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setSoundDevice(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $deviceName = $postVars['deviceName'] ?? '';

        if(General::isNullOrEmpty($postVars['deviceName']) ){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obDeviceSound = EntitySoundDevice::getSoundDeviceByName($deviceName);
        if($obDeviceSound instanceof EntitySoundDevice){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obDeviceSound = new EntitySoundDevice();
        $obDeviceSound->device = $deviceName;
        $obDeviceSound->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/sound-device/'.$obDeviceSound->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditSoundTeam(Request $request, int $id): string
    {
        // Obtém o sonoplasta do banco de dados
        $obDeviceSound = EntitySoundDevice::getSoundDeviceById($id);

        // Valida instância
        if(!$obDeviceSound instanceof EntitySoundDevice){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/sound-device',[
            'title' => 'Editar Sonoplasta',
            'breadcrumbItem' => 'Editar Sonoplasta',
            'deviceName' =>$obDeviceSound->device,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de equipamento de som.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Dispositivos de Som',$content,'manager-sound-team');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditSoundTeam(Request $request, int $id): array
    {
        // Obtém o sonoplasta do banco de dados
        $obDeviceSound = EntitySoundDevice::getSoundDeviceById($id);

        // Valida instância
        if(!$obDeviceSound instanceof EntitySoundDevice){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $deviceName = $postVars['deviceName'] ?? '';

        if(General::isNullOrEmpty($postVars['deviceName']) ){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obDeviceSound = EntitySoundDevice::getSoundDeviceByName($deviceName);
        if($obDeviceSound instanceof EntitySoundDevice && $obDeviceSound->id != $id){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obDeviceSound = new EntitySoundDevice();
        $obDeviceSound->id = $id;
        $obDeviceSound->device = $deviceName;
        $obDeviceSound->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/sound-device/'.$obDeviceSound->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteSoundDevice(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obDeviceSound = EntitySoundDevice::getSoundDeviceById($id);

        // Valida instância
        if(!$obDeviceSound instanceof EntitySoundDevice){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device?status=failed');
        }

        $content = View::render('application/modules/manager-sound-team/delete/sound-device', [
            'title' => 'Excluir Dispositivo',
            'breadcrumbItem' => 'Excluir Dispositivo',
            'deviceName' => $obDeviceSound->device,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de equipamento de som.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Dispositivos de Som',$content,'manager-sound-team');

    }

    public static function setDeleteSoundDevice(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obDeviceSound = EntitySoundDevice::getSoundDeviceById($id);

        // Valida instância
        if(!$obDeviceSound instanceof EntitySoundDevice){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-device?status=failed');
        }

        $obDeviceSound->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/sound-device?status=deleted');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string|void
     */
    private static function getStatus(Request $request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Registro criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Registro atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Registro excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O registro ou descrição digitado já está sendo usado!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar registro!');
                break;
            case 'rejected':
                return Alert::getWarning('Este registro não pode ser apagado, porque já está em uso!');
                break;
        }
    }
}