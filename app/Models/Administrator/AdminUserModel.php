<?php

namespace App\Models\Administrator;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password_hash', 'role', 'status', 'active', 'fullname'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required|min_length[6]'
    ];

    public function getUserStats()
    {
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        $currentCount = $this->countAll();
        $lastMonthCount = $this->where('created_at <', $currentMonth . '-01')->countAllResults();

        $growth = $lastMonthCount > 0 ? (($currentCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;

        return [
            'total' => $currentCount,
            'growth' => round($growth, 1),
            'active' => $this->where('status', 'active')->countAllResults(),
            'new' => $this->where('SUBSTRING(created_at, 1, 7)', $currentMonth)->countAllResults()
        ];
    }

    public function getAllUsers()
    {
        return $this->select('id, username, email, role, status, active, created_at')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function createUser($data)
    {
        // Hash password jika ada field 'password'
        if (isset($data['password']) && !empty($data['password'])) {
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['password_hash'] = $hashed;
            unset($data['password']);
        } elseif (isset($data['password_hash']) && strlen($data['password_hash']) < 60) {
            $hashed = password_hash($data['password_hash'], PASSWORD_DEFAULT);
            $data['password_hash'] = $hashed;
        }
        // Set default status aktif
        $data['status'] = $data['status'] ?? 'active';
        $data['active'] = $data['active'] ?? 1;
        // Hanya field yang diizinkan
        $allowed = ['username', 'email', 'password_hash', 'status', 'active', 'role', 'fullname'];
        $insertData = array_intersect_key($data, array_flip($allowed));
        try {
            $userId = $this->insert($insertData, true); // return insert id
            if (!$userId) {
                log_message('error', 'Gagal insert user: ' . json_encode($insertData) . ' ERR: ' . json_encode($this->errors()));
                throw new \Exception('Gagal menambahkan user.');
            }
            // Masukkan user ke grup 'user' di auth_groups_users
            $db = \Config\Database::connect();
            $group = $db->table('auth_groups')->where('name', 'user')->get()->getRow();
            if ($group && $userId) {
                $db->table('auth_groups_users')->insert([
                    'group_id' => $group->id,
                    'user_id' => $userId
                ]);
            }
            return $userId;
        } catch (\Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
            throw new \Exception('Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function updateUser($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')
            ->findAll();
    }

    public function getUsersWithTransactionCount()
    {
        $db = \Config\Database::connect();

        return $db->table('users u')
            ->select('u.*, COUNT(t.id) as transaction_count')
            ->join('transactions t', 't.user_id = u.id', 'left')
            ->groupBy('u.id')
            ->get()
            ->getResultArray();
    }

    public function deleteUser($id)
    {
        $db = \Config\Database::connect();
        // Hapus relasi grup
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        // Hapus token login
        $db->table('auth_tokens')->where('user_id', $id)->delete();
        // Hapus log login
        $db->table('auth_logins')->where('user_id', $id)->delete();
        // Hapus user permissions
        $db->table('auth_users_permissions')->where('user_id', $id)->delete();
        // Hard delete user
        return $this->where('id', $id)->delete(); // force delete
    }
}
