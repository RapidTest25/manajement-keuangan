<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionsTables extends Migration
{
    public function up()
    {
        // Table for subscription plans
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'duration_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Duration in days (30 for monthly, 365 for yearly)',
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of features',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('subscription_plans');

        // Table for user subscriptions
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'plan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'start_date' => [
                'type' => 'DATETIME',
            ],
            'end_date' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'expired', 'cancelled'],
                'default' => 'active',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed'],
                'default' => 'pending',
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('plan_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('plan_id', 'subscription_plans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_subscriptions');

        // Insert default subscription plans
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Paket gratis dengan fitur dasar',
                'price' => 0,
                'duration_days' => 0,
                'features' => json_encode([
                    'Pencatatan transaksi dasar',
                    'Budget sederhana',
                    'Maksimal 3 kategori',
                    'Laporan bulanan basic'
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Premium Bulanan',
                'slug' => 'premium-monthly',
                'description' => 'Paket premium dengan semua fitur lengkap',
                'price' => 49000,
                'duration_days' => 30,
                'features' => json_encode([
                    '✅ Semua fitur Free',
                    '✅ Laporan keuangan lanjutan dengan grafik',
                    '✅ Export ke PDF & Excel',
                    '✅ Multiple budget planning (unlimited)',
                    '✅ Recurring transactions otomatis',
                    '✅ Financial goal tracker',
                    '✅ Analisis pengeluaran AI',
                    '✅ Tanpa iklan',
                    '✅ Support prioritas'
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Premium Tahunan',
                'slug' => 'premium-yearly',
                'description' => 'Paket premium tahunan - hemat 30%!',
                'price' => 399000,
                'duration_days' => 365,
                'features' => json_encode([
                    '✅ Semua fitur Premium Bulanan',
                    '✅ Hemat 30% (Normal: Rp 588.000/tahun)',
                    '✅ Prioritas fitur baru',
                    '✅ Konsultasi keuangan gratis (1x/bulan)'
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('subscription_plans')->insertBatch($plans);
    }

    public function down()
    {
        $this->forge->dropTable('user_subscriptions', true);
        $this->forge->dropTable('subscription_plans', true);
    }
}
