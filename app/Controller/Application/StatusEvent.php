<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\View;
use App\Model\Entity\EventBackColor as EntityEventBackColor;
use App\Model\Entity\EventTextColor as EntityEventTextColor;
use App\Model\Entity\EventStatus as EntityEventStatus;
use App\Model\Entity\Event as EntityEvent;
use App\Utils\General;
use Exception;

class StatusEvent extends Page
{
    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Status event criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Status event atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Status event excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O status event ou descrição digitado já existe!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar status event!');
                break;
            case 'rejected':
                return Alert::getWarning('Este status event não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por retornar a lista cores de background
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getColorsItems(Request $request, $selected =null): string
    {
        $options = '';
        $order = isset($selected)? 'order_color' : null;
        $where = isset($selected)? 'id > 0' : null;
        $query = isset($selected)? 'id, color, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_color' : '*';
        $results = EntityEventBackColor::getColorBackEvents($where, $order,null,null,$query);

        while ($obBackColors = $results->fetchObject(EntityEventBackColor::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obBackColors->id,
                'optionName' => mb_strtoupper($obBackColors->color),
                'imgStatus' => 'circule-' . $obBackColors->color . '.png',
                'bgClass' => 'bg-'.$obBackColors->color
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por retornar a lista cores de background
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getTextColorItems(Request $request, $selected = null): string
    {
        $options = '';
        $order = isset($selected)? 'order_color' : null;
        $where = isset($selected)? 'id > 0' : null;
        $query = isset($selected)? 'id, color, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_color' : '*';
        $results = EntityEventTextColor::getColorTextEvents($where, $order,null,null,$query);

        while ($obTextColors = $results->fetchObject(EntityEventTextColor::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obTextColors->id,
                'optionName' => mb_strtoupper($obTextColors->color),
                'imgStatus' => 'circule-' . $obTextColors->color . '.png',
                'bgClass' => 'bg-danger'
            ]);
        }

        return $options;
    }


    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewStatusEvent($request)
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/status-event',[
            'title' => 'Cadastrar Status Evento',
            'breadcrumbItem' => 'Cadastrar Status Evento',
            'description' => null,
            'statusEvent' => null,
            'colors' => self::getColorsItems($request),
            'textColors' => self::getTextColorItems($request),
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de status eventos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewEvent(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

//        echo "<pre>";
//        print_r($postVars);
//        echo "</pre>";exit;

        $description = $postVars['description'] ?? '';
        $statusEvent = $postVars['statusEvent'] ?? '';
        $backColorId = $postVars['color'] ?? '';
        $textColorId = $postVars['textColor'] ?? '';

        if(General::isNullOrEmpty($postVars['description']) or General::isNullOrEmpty($postVars['statusEvent'])){
            $request->getRouter()->redirect('/application/config-event/status-event/new?status=failed');
        }

        // valida status evento já existe
        $obStatusEvent = EntityEventStatus::getStatusEventByName($statusEvent);
        if($obStatusEvent instanceof EntityEventStatus){
            $request->getRouter()->redirect('/application/config-event/status-event/new?status=duplicated');
        }

        // Nova instancia de level
        $obStatusEvent = new EntityEventStatus();
        $obStatusEvent->status = $statusEvent;
        $obStatusEvent->description = $description;
        $obStatusEvent->color_id = $backColorId;
        $obStatusEvent->text_color_id = $textColorId;
        $obStatusEvent->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/status-event/'.$obStatusEvent->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditStatusEvent($request,$id)
    {
        // Obtém o level do banco de dados
        $obStatusEvent = EntityEventStatus::getStatusEventById($id);

        // Valida instância
        if(!$obStatusEvent instanceof EntityEventStatus){
            $request->getRouter()->redirect('/application/config-event/status-event?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/status-event',[
            'title' => 'Cadastrar Status Evento',
            'breadcrumbItem' => 'Cadastrar Status Evento',
            'description' => $obStatusEvent->description,
            'statusEvent' => $obStatusEvent->status,
            'colors' => self::getColorsItems($request,$obStatusEvent->color_id),
            'textColors' => self::getTextColorItems($request,$obStatusEvent->text_color_id),
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de status eventos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setEditStatusEvent($request,$id)
    {
        // Obtém o level do banco de dados
        $obStatusEvent = EntityEventStatus::getStatusEventById($id);

        // Valida instância
        if(!$obStatusEvent instanceof EntityEventStatus){
            $request->getRouter()->redirect('/application/config-event/status-event?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $description = $postVars['description'] ?? '';
        $statusEvent = $postVars['statusEvent'] ?? '';
        $backColorId = $postVars['color'] ?? '';
        $textColorId = $postVars['textColor'] ?? '';

        if(General::isNullOrEmpty($postVars['description']) or General::isNullOrEmpty($postVars['statusEvent'])){
            $request->getRouter()->redirect('/application/config-event/status-event/new?status=failed');
        }

        // valida status evento já existe
        $obStatusEvent = EntityEventStatus::getStatusEventByName($statusEvent);
        if($obStatusEvent instanceof EntityEventStatus && $obStatusEvent->id != $id){
            $request->getRouter()->redirect('/application/config-event/status-event/new?status=duplicated');
        }

        // Nova instancia de level
        $obStatusEvent = new EntityEventStatus();
        $obStatusEvent->id = $id;
        $obStatusEvent->status = $statusEvent;
        $obStatusEvent->description = $description;
        $obStatusEvent->color_id = $backColorId;
        $obStatusEvent->text_color_id = $textColorId;
        $obStatusEvent->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/status-event/'.$obStatusEvent->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteStatusEvent($request,$id)
    {
        // Obtém o level do banco de dados
        $obStatusEvent = EntityEventStatus::getStatusEventById($id);

        // Valida instância
        if(!$obStatusEvent instanceof EntityEventStatus){
            $request->getRouter()->redirect('/application/config-event/status-event?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/status-event',[
            'title' => 'Excluir Status Event',
            'breadcrumbItem' => 'Excluir Status Event',
            'description' => $obStatusEvent->description,
            'statusEvent' => $obStatusEvent->status,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de status eventos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public static function setDeleteStatusEvent($request,$id)
    {
        // Obtém o level do banco de dados
        $obStatusEvent = EntityEventStatus::getStatusEventById($id);

        // Valida instância
        if(!$obStatusEvent instanceof EntityEventStatus){
            $request->getRouter()->redirect('/application/config-event/status-event?status=failed');
        }

        // Obtém o depoimento do banco de dados
        $obEvent = EntityEvent::getEventStatusById($id);

        // Valida instância
        if($obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/config-event/status-event/'.$id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obStatusEvent->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/status-event?status=deleted');

    }
}