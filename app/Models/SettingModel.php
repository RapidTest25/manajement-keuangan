<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'setting_key';
    protected $allowedFields = ['setting_key', 'setting_value', 'created_at', 'updated_at'];

    public function getSetting($key)
    {
        $setting = $this->find($key);
        return $setting ? $setting['setting_value'] : null;
    }

    public function getAllSettings()
    {
        $settings = $this->findAll();
        $formatted = [];
        foreach ($settings as $setting) {
            $formatted[$setting['setting_key']] = $setting['setting_value'];
        }
        return $formatted;
    }
}
