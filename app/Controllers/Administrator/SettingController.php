<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseBuilder;
use App\Models\Administrator\SettingModel;

class SettingController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $settings = $this->getSettings();
        return view('administrator/setting', [
            'title' => 'Pengaturan Website',
            'settings' => $settings
        ]);
    }
    public function updateSettings()
    {
        try {
            $json = $this->request->getBody();
            log_message('error', '[updateSettings] RAW JSON: ' . $json);
            $data = json_decode($json, true);

            if (!$data) {
                log_message('error', '[updateSettings] Invalid JSON data received: ' . $json);
                throw new \Exception('Invalid JSON data received');
            }

            $settingModel = new SettingModel();
            foreach ($data as $key => $value) {
                // Skip empty keys
                if (empty($key)) continue;
                $settingModel->setSetting($key, $value);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pengaturan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[updateSettings] Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function backupDatabase()
    {
        try {
            // Cek apakah mysqldump tersedia
            $whichCmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'where mysqldump' : 'which mysqldump';
            $output = [];
            $returnVar = 0;
            exec($whichCmd, $output, $returnVar);
            $mysqldumpAvailable = ($returnVar === 0 && !empty($output));

            // Get database configuration
            $db = db_connect();
            $dbName = $db->database;
            $dbUser = $db->username;
            $dbPass = $db->password;
            $dbHost = $db->hostname;

            // Set backup filename
            $backupFile = WRITEPATH . 'backups/' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

            // Create backups directory if it doesn't exist
            if (!is_dir(WRITEPATH . 'backups')) {
                mkdir(WRITEPATH . 'backups', 0777, true);
            }

            if ($mysqldumpAvailable) {
                // Create backup command
                $command = "mysqldump --host={$dbHost} --user={$dbUser} --password={$dbPass} {$dbName} > {$backupFile}";
                exec($command, $output, $return);
                if ($return === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Database berhasil di-backup (mysqldump)',
                        'file' => basename($backupFile)
                    ]);
                } else {
                    log_message('error', '[backupDatabase] Backup mysqldump gagal. Output: ' . print_r($output, true));
                    // fallback ke manual
                }
            }
            // Fallback: export manual via PHP
            if ($this->exportDatabaseManual($db, $backupFile)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Database berhasil di-backup (PHP Export)',
                    'file' => basename($backupFile)
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Backup database gagal. Tidak bisa menggunakan mysqldump maupun export manual.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            log_message('error', '[backupDatabase] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal melakukan backup: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    // Export database manual via PHP (struktur + data)
    private function exportDatabaseManual($db, $backupFile)
    {
        try {
            $tables = [];
            $result = $db->query('SHOW TABLES');
            foreach ($result->getResultArray() as $row) {
                $tables[] = array_values($row)[0];
            }
            $sql = "-- Manual SQL Dump\n-- Database: `" . $db->database . "`\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            foreach ($tables as $table) {
                // Struktur tabel
                $row2 = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
                $sql .= $row2['Create Table'] . ";\n\n";
                // Data
                $data = $db->query("SELECT * FROM `{$table}`")->getResultArray();
                if (count($data) > 0) {
                    $columns = array_map(function ($col) {
                        return "`$col`";
                    }, array_keys($data[0]));
                    foreach ($data as $row) {
                        $values = array_map(function ($val) use ($db) {
                            if ($val === null) return 'NULL';
                            return "'" . addslashes($val) . "'";
                        }, array_values($row));
                        $sql .= "INSERT INTO `{$table}` (" . implode(",", $columns) . ") VALUES (" . implode(",", $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            file_put_contents($backupFile, $sql);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[exportDatabaseManual] Error: ' . $e->getMessage());
            return false;
        }
    }

    public function downloadBackup($filename)
    {
        $path = WRITEPATH . 'backups/' . $filename;

        if (!file_exists($path)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File backup tidak ditemukan'
            ])->setStatusCode(404);
        }

        return $this->response->download($path, null)
            ->setContentType('application/sql');
    }

    public function getLastBackup()
    {
        $backupDir = WRITEPATH . 'backups/';
        $files = glob($backupDir . '*.sql');
        if (!$files || count($files) === 0) {
            return $this->response->setJSON([
                'status' => 'empty',
                'message' => 'Belum ada backup'
            ]);
        }
        // Ambil file terbaru
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $latest = $files[0];
        return $this->response->setJSON([
            'status' => 'success',
            'file' => basename($latest),
            'date' => date('Y-m-d H:i:s', filemtime($latest))
        ]);
    }
    private function getSettings()
    {
        $results = $this->db->table('settings')
            ->get()
            ->getResult();

        $settings = [];
        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }

        return $settings;
    }

    public function getDatabaseSize()
    {
        try {
            $db = \Config\Database::connect();
            $database = $db->database;

            // Get all tables
            $tables = $db->query("SHOW TABLE STATUS FROM {$database}")->getResult();

            $size = 0;
            foreach ($tables as $table) {
                $size += $table->Data_length + $table->Index_length;
            }

            // Convert to MB with 2 decimal places
            $sizeInMB = number_format($size / (1024 * 1024), 2);

            return $this->response->setJSON([
                'status' => 'success',
                'size' => $sizeInMB . ' MB'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getDatabaseSize] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mendapatkan ukuran database: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
