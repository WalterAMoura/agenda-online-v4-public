<?php

//Inclui rota default
include __DIR__ . '/api/v1/default.php';

// Inclui Rotas de Api Auth
include __DIR__ . '/api/v1/auth.php';

// Inclui Rotas de Api Check
include __DIR__ . '/api/v1/check.php';

// inclui Rotas de APi Email
include  __DIR__ . '/api/v1/email.php';

// Inclui Rotas de ask to change
include __DIR__ . '/api/v1/ask-to-change.php';

// Inclui rotas de eventos
include __DIR__ . '/api/v1/events.php';

// inclui rotas whatsapp
include __DIR__ . '/api/v1/whatsapp.php';

// inclui rotas whatsapp recepção
include __DIR__ . '/api/v1/whatsapp-reception.php';

// inclui rotas whatsapp louvor
include __DIR__ . '/api/v1/whatsapp-worship.php';