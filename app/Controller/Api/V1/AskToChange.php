<?php

namespace App\Controller\Api\V1;

use App\Http\Request;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\AskToChange as EntityAskToChange;
use App\Utils\Debug;
use Exception;
use PDOStatement;

class AskToChange extends Api
{


    /**
     * Método responsável por atualizar as solicitações trocas expiradas
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setUpdateOverdue(Request $request)
    {
        // recupera dados de troca
        //Debug::debug($request);
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $proc = 'CALL updateAskToChangeStatusEvent()';
        $obAskToChange = new EntityAskToChange;
        $obAskToChange->procedure = $proc;
        $updateCount = $obAskToChange->call();

        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Registros expirados e atualizados ['. (integer)$updateCount . ']', null);

        return [
            'data' => null,
            'updateCount' => $updateCount
        ];
    }
}