<?php

namespace App\Enums;

enum MidtransStatus: string
{
    case CAPTURE = 'capture';
    case SETTLEMENT = 'settlement';
    case CANCEL = 'cancel';
    case DENY = 'deny';
    case EXPIRE = 'expire';
    case PENDING = 'pending';
}
