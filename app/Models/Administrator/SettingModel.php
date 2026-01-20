<?php

namespace App\Models\Administrator;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getSetting($key)
    {
        $setting = $this->where('setting_key', $key)->first();
        return $setting ? $setting['setting_value'] : null;
    }

    public function setSetting($key, $value)
    {
        $existing = $this->where('setting_key', $key)->first();

        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $value
            ]);
        }

        return $this->insert([
            'setting_key' => $key,
            'setting_value' => $value
        ]);
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
