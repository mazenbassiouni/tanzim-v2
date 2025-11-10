<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'رئيس التنظيم';
    case EDARA = 'إدارة عسكرية';
    case OFFICER_AFFAIRES = 'شئون ضباط';
    case SEGELAT = 'افراد وسجلات';
    case PERSONAL_AFFAIRES = 'شئون شخصية';
} 
