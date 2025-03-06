<?php

// carrega home recepção
include __DIR__ . '/reception/home.php';

// Inclui Rotas da Equipe de Recepção
include __DIR__ . '/reception/manager-reception-team.php';

// inclui rotas de agendamento do time
include __DIR__ . '/reception/team-lineup.php';

// inclui rotas de solicitação de troca
include __DIR__ . '/reception/ask-to-change.php';