<?php

namespace App\Enums;

enum PageEnum: string
{
    const AUTH  = 'login';
    case HOME   = 'home';
    case NVPlatformOverview ='NVPlatformOverview';
    case ConversationalAI='conversationalai';
    case EmailAndTextAI='emailandtextai';
    case Drive_ThruAIController='drive_thruai';
    case Aboutus='aboutus';
    case Infrastructure='infrastructure';
    case Partner='partner';
    case CarrerPage='carrerpage';
    case Trust = 'trust';
    case COMMON = 'common';
    case ANNOUNCEMENT = 'announcement';
}
