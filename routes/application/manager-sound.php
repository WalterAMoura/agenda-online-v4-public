<?php

// carrega home sonoplastia
include __DIR__ . '/manager-sound/home.php';

// Inclui Rotas da Equipe de Sonoplastia
include __DIR__ . '/manager-sound/manager-sound-team.php';

// Inclui Rotas Dispositivos de som
include __DIR__ . '/manager-sound/sound-device.php';

// Incluir rotas de horários de trabalho
include __DIR__ . '/manager-sound/suggested-time.php';

// inclui rotas de agendamento do time
include __DIR__ . '/manager-sound/team-lineup.php';

// inclui rotas de solicitação de troca
include __DIR__ . '/manager-sound/ask-to-change.php';