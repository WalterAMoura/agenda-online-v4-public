<?php

namespace App\Controller\Application;

use App\Model\Entity\AgendaByDepartaments as EntityAgendaByDepartments;
use App\Model\Entity\AgendaByEventStatus as EntityAgendaByEventStatus;
use App\Model\Entity\AgendaByPrograms as EntityAgendaByPrograms;
use App\Utils\Debug;
use App\Utils\UUID;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;

class Charts
{
    /**
     * Método responsável por retornar os scripts js para gráficos
     * @param string $script
     * @param string $id
     * @return string
     * @throws Exception
     */
    private static function getChartStatusEvent(string $script, string $id): string
    {
        //variáveis
        $scripts = '';
        $labels = array();
        $data = array();
        $colors = array();
        $ids = array();

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByEventStatus::getAgendaByEventStatus('year = "'. $year. '"',$order);
        while ($obEventStatus = $results->fetchObject(EntityAgendaByEventStatus::class)){
            $st[] = array_push($labels, $obEventStatus->status);
            $dt[] = array_push($data,$obEventStatus->total);
            $cs[] = array_push($colors,$obEventStatus->color);
            $is[] = array_push($ids,$obEventStatus->id);
        }

        $scripts .= ViewJS::render($script,[
            'id' => $id,
            'url' => URL,
            'labels' => json_encode($labels),
            'data' =>json_encode($data),
            'backgroundColor' =>json_encode($colors),
            'ids' => json_encode($ids),
            'year' => $year,
            'typeView' => 'event-status'
        ]);

        return $scripts;
    }

    /**
     * Método responsável por retornar os scripts js para gráficos
     * @param string $script
     * @param string $id
     * @return string
     * @throws Exception
     */
    private static function getChartProgram(string $script, string $id): string
    {
        //variáveis
        $scripts = '';
        $labels = array();
        $data = array();
        $colors = array();
        $ids = array();

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByPrograms::getAgendaByPrograms('year = "'. $year. '"',$order);
        while ($obProgram = $results->fetchObject(EntityAgendaByPrograms::class)){
            $st[] = array_push($labels, $obProgram->description);
            $dt[] = array_push($data,$obProgram->total);
            $is[] = array_push($ids,$obProgram->id);
        }

        $scripts .= ViewJS::render($script,[
//            'uuid' => (new UUID())->getUUID(),
//            'randomBarChartCanvas' => (new UUID())->getUUID(),
//            'randomBarChartData' => (new UUID())->getUUID(),
//            'randomBarChartOptions' => (new UUID())->getUUID(),
//            'randomBarChart' => (new UUID())->getUUID(),
            'id' => $id,
            'url' => URL,
            'labels' => json_encode($labels),
            'data' =>json_encode($data),
            'backgroundColor' =>json_encode($colors),
            'ids' => json_encode($ids),
            'year' => $year,
            'typeView' => 'program'
        ]);

        return $scripts;
    }

    /**
     * Método responsável por retornar os scripts js para gráficos
     * @param string $script
     * @param string $id
     * @return string
     * @throws Exception
     */
    private static function getChartDepartment(string $script, string $id): string
    {
        //variáveis
        $scripts = '';
        $labels = array();
        $data = array();
        $colors = array();
        $ids = array();

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByDepartments::getAgendaByDepartments('year = "'. $year. '"',$order);

        while ($obAgendaByDepartment = $results->fetchObject(EntityAgendaByDepartments::class)){
                $st[] = array_push($labels, $obAgendaByDepartment->department);
                $dt[] = array_push($data,$obAgendaByDepartment->total);
                $is[] = array_push($ids,$obAgendaByDepartment->id);
        }

        $scripts .= ViewJS::render($script,[
            'id' => $id,
            'url' => URL,
            'labels' => json_encode($labels),
            'data' =>json_encode($data),
            'backgroundColor' =>json_encode($colors),
            'ids' => json_encode($ids),
            'year' => $year,
            'typeView' => 'department'
        ]);

        return $scripts;
    }


    /**
     * @param int $length
     * @return string
     */
    private static function generateRandomColor(int $length = 10) {
        if (empty($values)) {
            $values = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de','#d2d6ff', '#3c8cbd','#6d4fb7','#275814','#d2e7db','#1832c4','#86ae81','#4bc827','#a272d0','#6503df','#1c9dbc','#c837c5','#e2ad3e','#bb335f','#8fb3bd','#98dab8','#28a81a','#bb9904','#a688f8','#b35b48','#0ce6d9','#3a0593','#55f115','#a172de','#286a9a','#1ba85e','#82e84a','#6f9d28','#5cc346','#493085','#3d5ed5','#0f32c7','#293d0d','#aaae8c','#368adc','#fc24e9','#c2e150','#bd395d','#47ed10','#655420','#223d3d','#648acd','#2d8be6','#04ea7b','#1fbe2c','#bd3fa3','#9dc534','#486647','#78d8a8','#444e2d','#fbc18f','#85bf81','#7e53c1','#51b2a4','#273260','#a6be5c'];
        }
        $randomString = '';
        $maxIndex = count($values) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $values[rand(0, $maxIndex)];
        }
        return $randomString;
    }

    /**
     * Método responsável por renderizar o script js para o gráfico
     * @param string $type
     * @param string $script
     * @param string $id
     * @return string
     * @throws Exception
     */
    public static function getCharts(string $type, string $script, string $id): string
    {
        // retornar os script
        if($type == 'event-status') {
            return self::getChartStatusEvent($script, $id);
        }elseif ($type == 'program'){
            return self::getChartProgram($script, $id);
        }elseif ($type == 'department'){
            return self::getChartDepartment($script, $id);
        }

        return '';
    }
}