<?php

namespace App\Controllers\App;

use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\BudgetModel;
use App\Models\CicilanModel;
use App\Models\BiayaEfektifModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class Ajax extends BaseController
{
    protected $transactionModel;
    protected $budgetModel;
    protected $cicilanModel;
    protected $biayaEfektifModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->budgetModel = new BudgetModel();
        $this->cicilanModel = new CicilanModel();
        $this->biayaEfektifModel = new BiayaEfektifModel();
    }
    public function addTransaction()
    {
        try {
            $file = $this->request->getFile('image_receipt');
            $newName = null;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/', $newName);
            }

            // Get the transaction date from form
            $transactionDate = $this->request->getPost('transaction_date');
            if (empty($transactionDate)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Tanggal transaksi harus diisi',
                    'errors' => ['transaction_date' => 'Tanggal transaksi harus diisi']
                ])->setStatusCode(400);
            }

            // Set timezone to Asia/Jakarta (WIB)
            date_default_timezone_set('Asia/Jakarta');

            $data = [
                'transaction_id' => uniqid() . 'TRx',
                'users' => session()->get('username'),
                'category' => $this->request->getPost('category'),
                'description' => $this->request->getPost('description'),
                'amount' => $this->request->getPost('amount'),
                'image_receipt' => $newName,
                'status' => $this->request->getPost('status'),
                'transaction_date' => date('Y-m-d', strtotime($transactionDate)),
                'created_at' => date('Y-m-d H:i:s') // Current time in WIB
            ];

            // Log the data being saved for debugging
            log_message('debug', 'Trying to save transaction with data: ' . json_encode($data));

            if ($this->transactionModel->save($data) === false) {
                $errors = $this->transactionModel->errors();
                log_message('error', 'Transaction save failed. Validation errors: ' . json_encode($errors));
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menyimpan transaksi.',
                    'errors' => $errors
                ])->setStatusCode(400);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Transaksi berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in addTransaction: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function history()
    {
        $data = $this->transactionModel->orderBy('created_at', 'DESC')->findAll();

        return json_encode($data);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $this->transactionModel->update($id, $data);

        return json_encode($data);
    }

    public function delete($id = null)
    {
        $this->transactionModel->delete($id);
        return json_encode(array('title', 'msg' => 'Server pengaturan harga berhasil dihapus.', 'label' => 'success'));
    }

    public function getTransactions()
    {
        $username = session()->get('username');
        $month = $this->request->getGet('month');
        $category = $this->request->getGet('category');
        $type = $this->request->getGet('type');

        $query = $this->transactionModel->where('users', $username);

        if ($month) {
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $query->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('status', $type);
        }

        $transactions = $query->orderBy('created_at', 'DESC')->findAll();
        return $this->response->setJSON($transactions);
    }

    public function getFilteredTransactions()
    {
        $username = session()->get('username');
        $month = $this->request->getGet('month');
        $category = $this->request->getGet('category');
        $type = $this->request->getGet('type');

        $builder = $this->transactionModel->where('users', $username);

        if ($month) {
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $builder->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate);
        }

        if ($category) {
            $builder->where('category', $category);
        }

        if ($type) {
            $builder->where('status', $type);
        }

        $transactions = $builder->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'status' => true,
            'data' => $transactions
        ]);
    }

    public function testExport()
    {
        try {
            $username = session()->get('username');
            
            if (!$username) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Username tidak ditemukan'
                ]);
            }
            
            $transactionModel = new TransactionModel();
            $transactions = $transactionModel->where('users', $username)->findAll();
            
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Test berhasil - Ditemukan ' . count($transactions) . ' transaksi',
                'username' => $username,
                'count' => count($transactions)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function exportTransactions()
    {
        try {
            // Get username from session
            $username = session()->get('username');
            
            if (!$username) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Username tidak ditemukan dalam session'
                ])->setStatusCode(401);
            }

            // Premium feature check
            if (!is_premium(session()->get('user_id'))) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Fitur Export/Backup Data hanya tersedia untuk pengguna Premium',
                    'requirePremium' => true
                ]);
            }
            
            // Get filter parameters from URL
            $month = $this->request->getGet('month');
            $category = $this->request->getGet('category');
            $type = $this->request->getGet('type');
            
            // Get transactions with filters applied
            $transactionModel = new TransactionModel();
            $query = $transactionModel->where('users', $username);
            
            // Apply month filter
            if ($month) {
                $startDate = $month . '-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                $query->where('DATE(transaction_date) >=', $startDate)
                      ->where('DATE(transaction_date) <=', $endDate);
            }
            
            // Apply category filter
            if ($category) {
                $query->where('category', $category);
            }
            
            // Apply type filter
            if ($type) {
                $query->where('status', $type);
            }
            
            $transactions = $query->orderBy('created_at', 'ASC')->findAll();
            
            // Create new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('FinanceFlow')
                ->setLastModifiedBy('FinanceFlow')
                ->setTitle('Data Transaksi Keuangan')
                ->setSubject('Export Data Transaksi')
                ->setDescription('Data transaksi keuangan yang diekspor dari aplikasi FinanceFlow')
                ->setKeywords('finance transaction export')
                ->setCategory('Financial Data');

            // Set headers with styling
            $headers = [
                'No',
                'ID Transaksi', 
                'Tanggal Transaksi',
                'Tanggal Input',
                'Tipe',
                'Kategori', 
                'Deskripsi', 
                'Jumlah (Rp)',
                'Status'
            ];

            // Set header row
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                
                // Style header
                $sheet->getStyle($col . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Auto-size columns
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $col++;
            }

            // Add data rows
            $rowNum = 2;
            $no = 1;
            $totalIncome = 0;
            $totalExpense = 0;
            
            foreach ($transactions as $transaction) {
                $sheet->setCellValue('A' . $rowNum, $no);
                $sheet->setCellValue('B' . $rowNum, $transaction['transaction_id']);
                $sheet->setCellValue('C' . $rowNum, date('d/m/Y', strtotime($transaction['transaction_date'])));
                $sheet->setCellValue('D' . $rowNum, date('d/m/Y H:i', strtotime($transaction['created_at'])));
                $sheet->setCellValue('E' . $rowNum, ($transaction['status'] == 'INCOME') ? 'Pemasukan' : 'Pengeluaran');
                $sheet->setCellValue('F' . $rowNum, $transaction['category']);
                $sheet->setCellValue('G' . $rowNum, $transaction['description']);
                
                // Format amount as currency
                $sheet->setCellValue('H' . $rowNum, $transaction['amount']);
                $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
                
                $sheet->setCellValue('I' . $rowNum, ucfirst(strtolower($transaction['status'])));

                // Calculate totals
                if ($transaction['status'] == 'INCOME') {
                    $totalIncome += $transaction['amount'];
                } else {
                    $totalExpense += $transaction['amount'];
                }

                // Style data rows
                $sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Color coding for transaction types
                if ($transaction['status'] == 'INCOME') {
                    $sheet->getStyle('E' . $rowNum)->applyFromArray([
                        'font' => ['color' => ['rgb' => '008000']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F5E8']
                        ]
                    ]);
                } else {
                    $sheet->getStyle('E' . $rowNum)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'CC0000']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFF0F0']
                        ]
                    ]);
                }

                $rowNum++;
                $no++;
            }

            // Add summary section if there are transactions
            if (!empty($transactions)) {
                $rowNum += 2;
                
                // Add filter info
                $filterInfo = 'FILTER YANG DITERAPKAN:';
                if ($month) {
                    $filterInfo .= ' Periode: ' . date('F Y', strtotime($month . '-01'));
                }
                if ($category) {
                    $filterInfo .= ' | Kategori: ' . $category;
                }
                if ($type) {
                    $typeText = ($type == 'INCOME') ? 'Pemasukan' : 'Pengeluaran';
                    $filterInfo .= ' | Tipe: ' . $typeText;
                }
                
                $sheet->setCellValue('A' . $rowNum, $filterInfo);
                $sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
                $sheet->getStyle('A' . $rowNum)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '666666']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);
                
                $rowNum += 2;
                $sheet->setCellValue('F' . $rowNum, 'RINGKASAN:');
                $sheet->getStyle('F' . $rowNum)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12]
                ]);

                $rowNum++;
                $sheet->setCellValue('F' . $rowNum, 'Total Pemasukan:');
                $sheet->setCellValue('H' . $rowNum, $totalIncome);
                $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('F' . $rowNum . ':H' . $rowNum)->applyFromArray([
                    'font' => ['color' => ['rgb' => '008000']]
                ]);

                $rowNum++;
                $sheet->setCellValue('F' . $rowNum, 'Total Pengeluaran:');
                $sheet->setCellValue('H' . $rowNum, $totalExpense);
                $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('F' . $rowNum . ':H' . $rowNum)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'CC0000']]
                ]);

                $rowNum++;
                $balance = $totalIncome - $totalExpense;
                $sheet->setCellValue('F' . $rowNum, 'Saldo:');
                $sheet->setCellValue('H' . $rowNum, $balance);
                $sheet->getStyle('H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('F' . $rowNum . ':H' . $rowNum)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => $balance >= 0 ? '008000' : 'CC0000']
                    ]
                ]);
            } else {
                // Add "No data" message
                $rowNum++;
                $sheet->setCellValue('D' . $rowNum, 'Belum ada data transaksi untuk filter yang dipilih');
                $sheet->getStyle('D' . $rowNum)->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '666666']]
                ]);
            }

            // Set sheet title
            $sheet->setTitle('Data Transaksi');

            // Generate filename with filter info
            $filename = 'transaksi_keuangan';
            if ($month) {
                $filename .= '_' . date('M_Y', strtotime($month . '-01'));
            }
            if ($category) {
                $filename .= '_' . str_replace(' ', '_', $category);
            }
            if ($type) {
                $filename .= '_' . strtolower($type);
            }
            $filename .= '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Clean all output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set headers for file download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer = new WriterXlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            log_message('error', 'Export error: ' . $e->getMessage());
            
            // Return JSON error response instead of trying to output Excel
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error saat export: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function importTransactions()
    {
        // Premium feature check
        if (!is_premium(session()->get('user_id'))) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Fitur Import Transaksi hanya tersedia untuk pengguna Premium',
                'requirePremium' => true
            ]);
        }

        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'File tidak valid'
            ]);
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'File harus berformat Excel (.xlsx atau .xls)'
            ]);
        }

        try {
            $username = session()->get('username');
            $imported = 0;
            $errors = [];
            $skipped = 0;

            if (!$username) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Username tidak ditemukan dalam session'
                ]);
            }

            // Read Excel file
            if ($ext === 'xlsx') {
                $reader = new Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            // Remove header row
            $header = array_shift($rows);
            
            // Validate header format
            $expectedHeaders = ['No', 'ID Transaksi', 'Tanggal Transaksi', 'Tanggal Input', 'Tipe', 'Kategori', 'Deskripsi', 'Jumlah (Rp)', 'Status'];
            $headerCheck = true;
            
            // Process each row
            foreach ($rows as $rowIndex => $row) {
                $actualRowNumber = $rowIndex + 2; // +2 because we removed header and Excel starts from 1
                
                // Skip empty rows
                if (empty($row['A']) && empty($row['B']) && empty($row['C']) && empty($row['D']) && 
                    empty($row['E']) && empty($row['F']) && empty($row['G']) && empty($row['H']) && empty($row['I'])) {
                    $skipped++;
                    continue;
                }

                // Skip summary rows (detect by "RINGKASAN" or "Total")
                if (stripos($row['F'], 'RINGKASAN') !== false || 
                    stripos($row['F'], 'Total') !== false || 
                    stripos($row['F'], 'Saldo') !== false) {
                    $skipped++;
                    continue;
                }

                // Validate required fields
                if (empty($row['B']) || empty($row['C']) || empty($row['E']) || 
                    empty($row['F']) || empty($row['H'])) {
                    $errors[] = "Data tidak lengkap pada baris $actualRowNumber";
                    continue;
                }

                try {
                    // Parse transaction date
                    $transactionDateStr = trim($row['C']);
                    if (strpos($transactionDateStr, '/') !== false) {
                        $transactionDate = date('Y-m-d', strtotime(str_replace('/', '-', $transactionDateStr)));
                    } else {
                        $transactionDate = date('Y-m-d', strtotime($transactionDateStr));
                    }

                    // Parse created date (optional)
                    $createdAt = date('Y-m-d H:i:s');
                    if (!empty($row['D'])) {
                        $createdDateStr = trim($row['D']);
                        if (strpos($createdDateStr, '/') !== false) {
                            $createdAt = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $createdDateStr)));
                        } else {
                            $createdAt = date('Y-m-d H:i:s', strtotime($createdDateStr));
                        }
                    }

                    // Parse transaction type
                    $tipeStr = trim(strtolower($row['E']));
                    if (strpos($tipeStr, 'pemasukan') !== false || strpos($tipeStr, 'income') !== false) {
                        $status = 'INCOME';
                    } elseif (strpos($tipeStr, 'pengeluaran') !== false || strpos($tipeStr, 'expense') !== false) {
                        $status = 'EXPENSE';
                    } else {
                        $errors[] = "Tipe transaksi tidak valid pada baris $actualRowNumber (harus 'Pemasukan' atau 'Pengeluaran')";
                        continue;
                    }

                    // Parse amount
                    $amountStr = trim($row['H']);
                    $amount = (float) preg_replace('/[^0-9.-]/', '', str_replace(',', '.', $amountStr));
                    
                    if ($amount <= 0) {
                        $errors[] = "Jumlah tidak valid pada baris $actualRowNumber";
                        continue;
                    }

                    // Generate unique transaction ID
                    $transactionId = $row['B'] ?: 'IMP_' . time() . '_' . $imported;

                    // Prepare data for insertion
                    $data = [
                        'transaction_id' => $transactionId,
                        'users' => $username,  // Changed from user_id to users
                        'category' => trim($row['F']),
                        'description' => trim($row['G']) ?: 'Import dari Excel',
                        'amount' => $amount,
                        'status' => $status,
                        'transaction_date' => $transactionDate,
                        'created_at' => $createdAt,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Insert data
                    if ($this->transactionModel->insert($data)) {
                        $imported++;
                    } else {
                        $errors[] = "Gagal menyimpan data pada baris $actualRowNumber";
                    }

                } catch (\Exception $e) {
                    $errors[] = "Error pada baris $actualRowNumber: " . $e->getMessage();
                }
            }

            // Prepare response message
            $message = "Import selesai!";
            if ($imported > 0) {
                $message .= " Berhasil import $imported transaksi.";
            }
            if ($skipped > 0) {
                $message .= " $skipped baris dilewati.";
            }
            if (!empty($errors)) {
                $message .= " " . count($errors) . " error ditemukan.";
            }

            return $this->response->setJSON([
                'status' => $imported > 0,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => array_slice($errors, 0, 10) // Limit errors to first 10
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Import error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error saat membaca file: ' . $e->getMessage()
            ]);
        }
    }

    public function resetData()
    {
        $username = session()->get('username');

        $this->transactionModel->where('users', $username)->delete();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Semua data berhasil direset'
        ]);
    }

    public function get_all()
    {
        $data = $this->transactionModel->findAll();
        return $this->response->setJSON($data);
    }

    public function updateProfile()
    {
        $userModel = new UserModel();
        $userId = user_id(); // Get logged in user ID using Auth helper
        $data = $this->request->getPost();

        // Validate and process profile picture URL if provided
        $profilePicture = $data['profile_picture'] ?? null;
        if (!empty($profilePicture)) {
            // Convert Imgur gallery URLs to direct image URLs
            $profilePicture = $this->convertToDirectImageUrl($profilePicture);
            
            // Check if it's a valid URL
            if (!filter_var($profilePicture, FILTER_VALIDATE_URL)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'URL foto profil tidak valid. Pastikan menggunakan link langsung ke gambar.'
                ]);
            }
            
            // Check if URL points to an image
            if (!$this->isImageUrl($profilePicture)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'URL harus mengarah ke file gambar (JPG, PNG, GIF, WebP)'
                ]);
            }
        }

        // Filter only allowed fields
        $updateData = [
            'fullname' => $data['fullname'] ?? null,
            'email' => $data['email'] ?? null,
            'user_image' => $profilePicture ?: 'default.jpg' // Map profile_picture to user_image
        ];

        // Handle password update if provided
        if (!empty($data['password'])) {
            // Verify old password if provided
            if (!empty($data['old_password'])) {
                $currentUser = $userModel->find($userId);
                if (!password_verify($data['old_password'], $currentUser['password_hash'])) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Password lama tidak sesuai'
                    ]);
                }
            }
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Update user data
        if ($userModel->update($userId, $updateData)) {
            // Update session data
            $user = $userModel->find($userId);
            session()->set([
                'fullname' => $user['fullname'],
                'email' => $user['email'],
                'profile_picture' => $user['user_image'] ?? 'default.jpg' // Map user_image to profile_picture in session
            ]);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Profil berhasil diperbarui'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal memperbarui profil'
        ]);
    }

    private function convertToDirectImageUrl($url)
    {
        // Convert Imgur gallery URLs to direct image URLs
        if (strpos($url, 'imgur.com/gallery/') !== false) {
            // Extract image ID from gallery URL
            preg_match('/imgur\.com\/gallery\/[^\/]*-([a-zA-Z0-9]+)/', $url, $matches);
            if (isset($matches[1])) {
                return 'https://i.imgur.com/' . $matches[1] . '.jpg';
            }
        }
        
        // Convert regular Imgur URLs to direct URLs
        if (strpos($url, 'imgur.com/') !== false && strpos($url, 'i.imgur.com') === false) {
            preg_match('/imgur\.com\/([a-zA-Z0-9]+)/', $url, $matches);
            if (isset($matches[1])) {
                return 'https://i.imgur.com/' . $matches[1] . '.jpg';
            }
        }
        
        return $url;
    }

    private function isImageUrl($url)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $parsedUrl = parse_url($url);
        
        if (!isset($parsedUrl['path'])) {
            return false;
        }
        
        $extension = strtolower(pathinfo($parsedUrl['path'], PATHINFO_EXTENSION));
        return in_array($extension, $imageExtensions) || strpos($url, 'i.imgur.com') !== false;
    }

    public function getCategories()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $userId = user_id();
        $categories = $categoryModel->getUserCategories($userId);

        // Debugging
        log_message('debug', 'Categories loaded for user ' . $userId . ': ' . json_encode($categories));

        return $this->response->setJSON($categories);
    }

    public function addCategory()
    {
        $categoryModel = new \App\Models\CategoryModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'user_id' => user_id()
        ];

        // Check category limit
        $userId = user_id();
        $limit = get_category_limit($userId);
        $currentCount = $categoryModel->where('user_id', $userId)->countAllResults();
        
        log_message('error', '[AddCategory] User: ' . $userId . ', Limit: ' . $limit . ', Count: ' . $currentCount . ', Premium: ' . (is_premium($userId) ? 'Yes' : 'No'));

        if ($currentCount >= $limit) {
            $message = is_premium($userId) 
                ? "Anda telah mencapai batas maksimal $limit kategori." 
                : "Limit kategori Free user adalah $limit. Upgrade ke Premium untuk 20 kategori!";
            
            return $this->response->setJSON([
                'status' => false,
                'message' => $message,
                'requirePremium' => !is_premium($userId)
            ]);
        }

        if ($categoryModel->save($data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Kategori berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal menambahkan kategori'
        ]);
    }

    public function deleteCategory($id)
    {
        $categoryModel = new \App\Models\CategoryModel();

        // Check if category exists and belongs to user
        $category = $categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kategori tidak ditemukan'
            ]);
        }

        // Don't allow deletion of default categories
        if ($category['user_id'] === '0') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kategori default tidak dapat dihapus'
            ]);
        }

        // Delete the category
        if ($categoryModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal menghapus kategori'
        ]);
    }
    public function saveSavingTarget()
    {
        try {
            $json = $this->request->getJSON(true);
            $userId = user_id();

            if (!$userId) {
                throw new \Exception('User tidak ditemukan');
            }

            // Determine if this is a new target or resetting an existing one
            $isReset = isset($json['is_new']) && $json['is_new'] === true;

            $savingsModel = new \App\Models\SavingsModel();
            $targetData = [
                'target_amount' => floatval($json['target_amount']),
                'daily_amount' => floatval($json['daily_amount']),
                'wish_target' => $json['wish_target'],
                'description' => $json['description'] ?? ''
            ];

            // Log the data being saved
            log_message('debug', 'Saving target data: ' . json_encode($targetData) . ', reset: ' . ($isReset ? 'true' : 'false'));

            $result = $savingsModel->saveUserTarget($userId, $targetData, $isReset);

            if (!$result) {
                throw new \Exception('Gagal menyimpan data');
            }

            // Get the updated target info
            $target = $savingsModel->getUserTarget($userId);

            return $this->response->setJSON([
                'status' => true,
                'message' => $isReset ? 'Target baru berhasil dibuat' : 'Target berhasil disimpan',
                'is_reset' => $isReset,
                'target' => $target
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saving target: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getSavingTarget()
    {
        $userId = user_id();
        $savingsModel = new \App\Models\SavingsModel();
        $target = $savingsModel->getUserTarget($userId);

        return $this->response->setJSON([
            'status' => true,
            'target' => $target
        ]);
    }
    public function updateSaving()
    {
        try {
            $data = $this->request->getJSON(true);
            $userId = user_id();

            if (!isset($data['savings_id']) || !$data['savings_id']) {
                // Get the active savings ID if not provided
                $savingsModel = new \App\Models\SavingsModel();
                $currentSavings = $savingsModel->where('user_id', $userId)
                    ->orderBy('id', 'DESC')
                    ->first();

                if (!$currentSavings) {
                    throw new \Exception('No active savings target found');
                }

                $savingsId = $currentSavings['id'];
            } else {
                $savingsId = $data['savings_id'];
            }

            // Update saving status for this date
            $savingsModel = new \App\Models\SavingsModel();
            $date = $data['date'];
            $status = $data['status'];

            $success = $savingsModel->updateProgress($savingsId, $status, $date);

            if ($success) {
                // Get updated target information with new progress
                $updatedTarget = $savingsModel->getUserTarget($userId);

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Status tabungan berhasil diperbarui',
                    'target' => $updatedTarget
                ]);
            }

            throw new \Exception('Gagal memperbarui status tabungan');
        } catch (\Exception $e) {
            log_message('error', 'Error updating saving: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getMonthlySavings()
    {
        try {
            $month = $this->request->getGet('month') ?: date('m');
            $year = $this->request->getGet('year') ?: date('Y');
            $userId = user_id();

            // Get the active savings target
            $savingsModel = new \App\Models\SavingsModel();
            $target = $savingsModel->getUserTarget($userId);

            if (!$target || !isset($target['id'])) {
                return $this->response->setJSON([
                    'status' => true,
                    'savings' => [],
                    'dailyTarget' => 0,
                    'totalSaved' => 0,
                    'targetAmount' => 0,
                    'progressPercentage' => 0
                ]);
            }

            // Get monthly calendar data
            $monthlyData = $savingsModel->getMonthlySavings($userId, $month, $year);

            return $this->response->setJSON([
                'status' => true,
                'savings' => $monthlyData['savings'],
                'dailyTarget' => floatval($target['daily_amount']),
                'totalSaved' => floatval($target['saved_amount']),
                'targetAmount' => floatval($target['target_amount']),
                'progressPercentage' => $target['progress_percentage'],
                'payment_count' => $target['payment_count'],
                'successful_days' => $target['successful_days'],
                'days_left' => $target['days_left']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getMonthlySavings: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal memuat data tabungan',
                'dailyTarget' => 0
            ]);
        }
    }

    public function setDebtNoteCookie()
    {
        set_cookie('debt_note_started', 'true', 3600 * 24 * 30); // 30 days
        return $this->response->setJSON(['status' => true]);
    }

    public function getDebtNotes()
    {
        $debtNoteModel = new \App\Models\DebtNoteModel();
        $debts = $debtNoteModel->where('user_id', user_id())->findAll();

        return $this->response->setJSON([
            'status' => true,
            'debts' => $debts
        ]);
    }

    public function saveBudget()
    {
        try {
            $username = session()->get('username');
            if (!$username) {
                // Fallback: cari username dari email atau user_id
                $email = session()->get('email');
                $userId = session()->get('user_id');
                if ($email) {
                    $user = (new \App\Models\UserModel())->where('email', $email)->first();
                    $username = $user['username'] ?? null;
                } elseif ($userId) {
                    $user = (new \App\Models\UserModel())->find($userId);
                    $username = $user['username'] ?? null;
                }
                log_message('debug', '[saveBudget] Fallback username: ' . ($username ?? 'NULL'));
            }
            
            // Check if user is premium (for multiple budget limit)
            $subscriptionModel = new \App\Models\UserSubscriptionModel();
            $isPremium = $subscriptionModel->hasPremiumSubscription(session()->get('user_id'));
            
            // Check existing budget count for free users
            if (!$isPremium) {
                $budgetCount = $this->budgetModel->where('users', $username)->countAllResults();
                if ($budgetCount >= 3) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => '❌ User gratis hanya bisa membuat maksimal 3 budget. Upgrade ke Premium untuk unlimited budget!',
                        'requirePremium' => true
                    ]);
                }
            }
            
            $dailyBudget = $this->request->getPost('daily_budget');
            log_message('debug', '[saveBudget] Called. Username: ' . ($username ?? 'NULL') . ', dailyBudget: ' . ($dailyBudget ?? 'NULL'));
            if (!$username) {
                log_message('error', '[saveBudget] No username in session or fallback');
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Sesi login tidak valid (username tidak ditemukan)'
                ]);
            }

            if (!$dailyBudget) {
                log_message('error', '[saveBudget] No budget amount provided');
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Budget harian harus diisi'
                ]);
            }

            $dailyBudget = (float)$dailyBudget;
            if ($dailyBudget < 1000) {
                log_message('error', '[saveBudget] Budget kurang dari 1000: ' . $dailyBudget);
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Budget harian minimal Rp 1.000'
                ]);
            }

            $result = $this->budgetModel->saveUserBudget($username, $dailyBudget);
            log_message('debug', '[saveBudget] saveUserBudget result: ' . var_export($result, true));
            if ($result) {
                // Get actual spent today to calculate real remaining
                $today = date('Y-m-d');
                $spentToday = $this->transactionModel
                    ->where('users', $username)
                    ->where('DATE(created_at)', $today)
                    ->where('status', 'expense')
                    ->selectSum('amount')
                    ->get()
                    ->getRow()
                    ->amount ?? 0;

                $remaining = $dailyBudget - $spentToday;
                $percentageUsed = $dailyBudget > 0 ? ($spentToday / $dailyBudget) * 100 : 0;

                $budgetStatus = [
                    'has_budget' => true,
                    'daily_budget' => $dailyBudget,
                    'spent_today' => (float)$spentToday,
                    'remaining' => (float)$remaining,
                    'percentage_used' => (float)$percentageUsed
                ];

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Budget berhasil disimpan',
                    'budgetStatus' => $budgetStatus
                ]);
            }

            log_message('error', '[saveBudget] Gagal menyimpan budget untuk user: ' . $username);
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menyimpan budget'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[saveBudget] Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan budget: ' . $e->getMessage()
                ]);
        }
    }

    public function manageBudget()
    {
        try {
            $username = session()->get('username');
            $budgetData = $this->budgetModel->getUserBudget($username);

            // Calculate daily stats
            $today = date('Y-m-d');
            $spentToday = $this->transactionModel
                ->where('users', $username)
                ->where('DATE(created_at)', $today)
                ->where('status', 'expense')
                ->selectSum('amount')
                ->get()
                ->getRow()
                ->amount ?? 0;

            $response = [
                'status' => true,
                'budgetStatus' => [
                    'has_budget' => !empty($budgetData),
                    'daily_budget' => $budgetData['daily_budget'] ?? 0,
                    'spent_today' => (float)$spentToday,
                    'remaining' => ($budgetData['daily_budget'] ?? 0) - (float)$spentToday,
                    'percentage_used' => $budgetData['daily_budget'] ? ((float)$spentToday / $budgetData['daily_budget']) * 100 : 0
                ]
            ];

            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            log_message('error', 'Error in manageBudget: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat mengambil data budget'
                ]);
        }
    }

    public function saveCicilan()
    {
        try {
            $json = $this->request->getJSON(true);
            $userId = user_id();

            if (!$userId) {
                throw new \Exception('User tidak ditemukan');
            }

            $data = [
                'user_id' => $userId,
                'name' => $json['name'],
                'type' => $json['type'],
                'total_amount' => $json['totalAmount'],
                'monthly_amount' => $json['monthlyAmount'],
                'tenor' => $json['tenor'],
                'start_date' => $json['startDate'],
                'notes' => $json['notes'] ?? null
            ];

            if ($this->cicilanModel->saveCicilan($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Cicilan berhasil disimpan'
                ]);
            } else {
                throw new \Exception('Gagal menyimpan cicilan');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getCicilan()
    {
        try {
            $userId = user_id();
            $filter = $this->request->getGet('filter') ?? 'all';

            if (!$userId) {
                throw new \Exception('User tidak ditemukan');
            }

            $cicilan = $this->cicilanModel->getUserCicilan($userId, $filter);

            return $this->response->setJSON([
                'status' => true,
                'cicilan' => $cicilan
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function payCicilan($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('ID cicilan tidak ditemukan');
            }

            $userId = user_id();
            if (!$userId) {
                throw new \Exception('User tidak ditemukan');
            }

            if ($this->cicilanModel->markPaid($id, $userId)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Pembayaran cicilan berhasil dicatat'
                ]);
            } else {
                throw new \Exception('Gagal mencatat pembayaran');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function saveBiayaEfektif()
    {
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
        $session = session();

        $data = [
            'user_id' => $session->get('user_id'), // Make sure this matches your session key
            'kategori' => $this->request->getPost('kategori'),
            'nama_biaya' => $this->request->getPost('nama_biaya'),
            'jumlah' => $this->request->getPost('jumlah'),
            'frekuensi' => $this->request->getPost('frekuensi'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'is_active' => 1
        ];

        try {
            // Debug log the user_id
            log_message('debug', 'User ID from session: ' . $session->get('user_id'));

            // Add validation for user_id
            if (empty($data['user_id'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID tidak ditemukan dalam session'
                ]);
            }

            if ($biayaEfektifModel->save($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Biaya efektif berhasil ditambahkan'
                ]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan biaya efektif'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saving biaya efektif: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function updateBiayaEfektif($id = null)
    {
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
        $session = session();

        // Verify the biaya efektif belongs to the current user
        $existing = $biayaEfektifModel->find($id);
        if (!$existing || $existing['user_id'] != $session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses'
            ]);
        }

        $data = [
            'id' => $id,
            'user_id' => $session->get('user_id'),
            'kategori' => $this->request->getPost('kategori'),
            'nama_biaya' => $this->request->getPost('nama_biaya'),
            'jumlah' => $this->request->getPost('jumlah'),
            'frekuensi' => $this->request->getPost('frekuensi'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'is_active' => $this->request->getPost('is_active')
        ];

        try {
            if ($biayaEfektifModel->save($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Biaya efektif berhasil diupdate'
                ]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate biaya efektif'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating biaya efektif: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteBiayaEfektif($id = null)
    {
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();

        try {
            if ($biayaEfektifModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Biaya efektif berhasil dihapus'
                ]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus biaya efektif'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    public function getBiayaEfektifData()
    {
        $userId = session()->get('user_id'); // Using consistent session key
        log_message('debug', 'getBiayaEfektifData called. User ID: ' . ($userId ?? 'null'));

        if (!$userId) {
            log_message('error', 'Session user ID not found');
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Session tidak ditemukan'
            ]);
        }
        try {
            $data = $this->biayaEfektifModel->getBiayaEfektif($userId);
            log_message('debug', 'Data retrieved: ' . json_encode($data));

            if (!$data) {
                log_message('debug', 'No data found for user');
                $data = [];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error retrieving data: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error retrieving data'
            ]);
        }

        // Calculate total monthly cost
        $totalMonthlyCost = 0;
        foreach ($data as &$item) {
            $monthlyCost = (float)$item['jumlah'];
            switch ($item['frekuensi']) {
                case 'Harian':
                    $monthlyCost *= 30;
                    break;
                case 'Mingguan':
                    $monthlyCost *= 4;
                    break;
                case 'Tahunan':
                    $monthlyCost /= 12;
                    break;
                    // case 'Bulanan' is default, no calculation needed
            }
            $item['monthly_cost'] = $monthlyCost;
            $totalMonthlyCost += $monthlyCost;
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $data,
            'totalMonthlyCost' => $totalMonthlyCost
        ]);
    }

    public function getTotalBiayaEfektif()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Session tidak ditemukan'
            ]);
        }

        try {
            $totalBulanan = $this->biayaEfektifModel->getTotalBiayaBulanan($userId);
            
            return $this->response->setJSON([
                'status' => true,
                'total_bulanan' => $totalBulanan,
                'formatted_total' => 'Rp ' . number_format($totalBulanan, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting total biaya efektif: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data'
            ]);
        }
    }

    public function getBiayaEfektifStatistik()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Session tidak ditemukan'
            ]);
        }

        try {
            $statistik = $this->biayaEfektifModel->getStatistikBiaya($userId);
            
            return $this->response->setJSON([
                'status' => true,
                'data' => $statistik
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting biaya efektif statistik: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik'
            ]);
        }
    }

    public function getSaldoBulan()
    {
        $username = session()->get('username');
        if (!$username) {
            $email = session()->get('email');
            $userId = session()->get('user_id');
            if ($email) {
                $user = (new \App\Models\UserModel())->where('email', $email)->first();
                $username = $user['username'] ?? null;
            } elseif ($userId) {
                $user = (new \App\Models\UserModel())->find($userId);
                $username = $user['username'] ?? null;
            }
        }
        $month = $this->request->getGet('month');
        if (!$username || !$month) {
            return $this->response->setJSON(['saldo' => 0]);
        }
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $income = $this->transactionModel
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0;
        $expense = $this->transactionModel
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0;
        $saldo = (float)$income - (float)$expense;
        return $this->response->setJSON(['saldo' => $saldo]);
    }

    public function getSummaryBulan()
    {
        $username = session()->get('username');
        if (!$username) {
            $email = session()->get('email');
            $userId = session()->get('user_id');
            if ($email) {
                $user = (new \App\Models\UserModel())->where('email', $email)->first();
                $username = $user['username'] ?? null;
            } elseif ($userId) {
                $user = (new \App\Models\UserModel())->find($userId);
                $username = $user['username'] ?? null;
            }
        }
        $month = $this->request->getGet('month');
        if (!$username || !$month) {
            return $this->response->setJSON([
                'saldo' => 0,
                'income' => 0,
                'expense' => 0,
                'avg_expense' => 0
            ]);
        }
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $income = $this->transactionModel
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0;
        $expense = $this->transactionModel
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0;
        $saldo = (float)$income - (float)$expense;
        $daysInMonth = date('t', strtotime($startDate));
        $avg_expense = $daysInMonth > 0 ? ((float)$expense / $daysInMonth) : 0;
        return $this->response->setJSON([
            'saldo' => $saldo,
            'income' => (float)$income,
            'expense' => (float)$expense,
            'avg_expense' => $avg_expense
        ]);
    }
}
