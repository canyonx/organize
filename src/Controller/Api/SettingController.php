<?php

namespace App\Controller\Api;

use App\Entity\Setting;
use Symfony\Component\Routing\Attribute\Route;

class SettingController
{
    /**
     * Toggle a setting
     * @param Setting $setting
     */
    #[Route('/api/setting/toggle/{id}', name: 'app_api_setting_toggle')]
    public function toggle(Setting $id)
    {
    }
}
