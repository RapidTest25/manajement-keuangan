<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;

class DebtNote extends BaseController
{
    protected $debtNoteModel;

    public function __construct()
    {
        $this->debtNoteModel = new \App\Models\DebtNoteModel();
    }

    public function index()
    {
        // Premium check
        if (!is_premium(user_id())) {
            return redirect()->to('app/subscription/plans')->with('error', 'Fitur Catat Utang hanya untuk pengguna Premium');
        }

        $data['title'] = 'Note Utang';
        return view('app/noteUtang', $data);
    }

    public function save()
    {
        try {
            $data = $this->request->getJSON(true);
            $data['user_id'] = user_id();

            // Debug log
            log_message('debug', 'Saving debt note: ' . json_encode($data));

            if ($this->debtNoteModel->save($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Catatan utang berhasil disimpan'
                ]);
            }

            throw new \Exception('Gagal menyimpan data');
        } catch (\Exception $e) {
            log_message('error', 'Error saving debt note: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    public function list()
    {
        try {
            $userId = user_id();
            log_message('debug', 'Fetching debts for user ID: ' . $userId);

            $debts = $this->debtNoteModel->getDebts($userId);

            // Debug log
            log_message('debug', 'Fetched debts for user ' . $userId . ': ' . json_encode($debts));

            return $this->response->setJSON([
                'status' => true,
                'debts' => $debts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching debt notes: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    public function markPaid($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $debt = $this->debtNoteModel->find($id);
            if (!$debt) {
                throw new \Exception('Data utang tidak ditemukan');
            }

            $requestData = $this->request->getJSON(true);
            $data = [];

            // For lending type, update the amount paid
            if ($debt['type'] === 'lending') {

                // Validate amount exists and is valid 
                if (!isset($requestData['amount']) || !is_numeric($requestData['amount']) || $requestData['amount'] <= 0) {
                    throw new \Exception('Jumlah pembayaran tidak valid');
                }

                // Convert to integers for safe calculation
                $currentAmountPaid = (int)$debt['amount_paid'] ?? 0;
                $additionalAmount = (int)$requestData['amount'];
                $loanAmount = (int)$debt['loan_amount'];

                $newAmountPaid = $currentAmountPaid + $additionalAmount;

                log_message('debug', "Current: {$currentAmountPaid}, Additional: {$additionalAmount}, Total: {$newAmountPaid}, Loan: {$loanAmount}");

                // Validate payment amount
                if ($newAmountPaid > $loanAmount) {
                    throw new \Exception('Jumlah pembayaran melebihi total pinjaman');
                }

                $data['amount_paid'] = $newAmountPaid;

                // Update payment history
                $payments = [];
                if (!empty($debt['payments'])) {
                    $payments = json_decode($debt['payments'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $payments = [];
                    }
                }

                $payments[] = [
                    'date' => date('Y-m-d'),
                    'amount' => $additionalAmount
                ];

                $data['payments'] = json_encode($payments);

                // Only mark as paid if the full amount is paid
                $data['status'] = ($newAmountPaid >= $loanAmount) ? 'paid' : 'active';
            } else {
                // For borrowing type, simply mark as paid
                $data['status'] = 'paid';
            }

            if (!$this->debtNoteModel->update($id, $data)) {
                throw new \Exception('Gagal memperbarui status pembayaran');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $debt['type'] === 'lending' ?
                    'Pembayaran berhasil diperbarui' :
                    'Berhasil menandai sebagai lunas',
                'data' => [
                    'amount_paid' => $data['amount_paid'] ?? 0,
                    'status' => $data['status'],
                    'payments' => $data['payments'] ?? '[]'
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in markPaid: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $debt = $this->debtNoteModel->find($id);
            if (!$debt) {
                throw new \Exception('Data utang tidak ditemukan');
            }

            if ($this->debtNoteModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Catatan utang berhasil dihapus'
                ]);
            }

            throw new \Exception('Gagal menghapus catatan utang');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting debt note: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    public function updatePayment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $data = $this->request->getJSON(true);

            // Validate required fields
            if (!isset($data['debt_id']) || !isset($data['payment_amount'])) {
                throw new \Exception('Incomplete data: debt_id and payment_amount are required');
            }

            // Get the debt record
            $debt = $this->debtNoteModel->find($data['debt_id']);
            if (!$debt) {
                throw new \Exception('Debt record not found');
            }

            // Ensure payment amount is valid
            $paymentAmount = (float)$data['payment_amount'];
            if ($paymentAmount <= 0) {
                throw new \Exception('Payment amount must be greater than 0');
            }

            // Calculate new amount paid
            $currentAmountPaid = (float)($debt['amount_paid'] ?? 0);
            $totalAmount = (float)$debt['loan_amount'];
            $newAmountPaid = $currentAmountPaid + $paymentAmount;

            // Validate total amount
            if ($newAmountPaid > $totalAmount) {
                throw new \Exception('Total payment exceeds loan amount');
            }

            // Update payment records
            $payments = json_decode($debt['payments'] ?? '[]', true);
            $payments[] = [
                'date' => date('Y-m-d'),
                'amount' => $paymentAmount
            ];

            // Update debt record
            $updateData = [
                'amount_paid' => $newAmountPaid,
                'payments' => json_encode($payments),
                'status' => $newAmountPaid >= $totalAmount ? 'paid' : 'active'
            ];

            if (!$this->debtNoteModel->update($data['debt_id'], $updateData)) {
                throw new \Exception('Failed to update payment');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diperbarui',
                'data' => [
                    'amount_paid' => $newAmountPaid,
                    'remaining' => $totalAmount - $newAmountPaid,
                    'is_completed' => $newAmountPaid >= $totalAmount
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating payment: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function updateMonthlyPayment()
    {
        try {
            $data = $this->request->getJSON(true);
            log_message('debug', '[DebtNote::updateMonthlyPayment] Received data: ' . json_encode($data));

            if (!isset($data['debt_id']) || !isset($data['current_payments'])) {
                throw new \Exception('Data tidak lengkap');
            }

            $debt = $this->debtNoteModel->find($data['debt_id']);
            if (!$debt) {
                throw new \Exception('Data utang tidak ditemukan');
            }
            log_message('debug', '[DebtNote::updateMonthlyPayment] Found debt: ' . json_encode($debt));

            $newPaymentsCount = (int)$data['current_payments'] + 1;
            $loanDuration = (int)$debt['loan_duration'];

            // Calculate the new amount paid based on payment_amount * payments_count
            $paymentAmount = (float)$debt['payment_amount'];
            $newAmountPaid = $paymentAmount * $newPaymentsCount;

            // Update payment history
            $payments = [];
            if (!empty($debt['payments'])) {
                $payments = json_decode($debt['payments'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $payments = [];
                }
            }

            $payments[] = [
                'date' => date('Y-m-d'),
                'amount' => $paymentAmount
            ];

            $updateData = [
                'payments_count' => $newPaymentsCount,
                'amount_paid' => $newAmountPaid,
                'payments' => json_encode($payments),
                'status' => $newPaymentsCount >= $loanDuration ? 'paid' : 'active'
            ];
            log_message('debug', '[DebtNote::updateMonthlyPayment] Updating with data: ' . json_encode($updateData));

            if (!$this->debtNoteModel->update($data['debt_id'], $updateData)) {
                $error = $this->debtNoteModel->errors();
                log_message('error', '[DebtNote::updateMonthlyPayment] Update failed: ' . json_encode($error));
                throw new \Exception('Gagal memperbarui status pembayaran');
            }
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diperbarui',
                'data' => [
                    'payments_count' => $newPaymentsCount,
                    'amount_paid' => $newAmountPaid,
                    'total_duration' => $loanDuration,
                    'is_completed' => $newPaymentsCount >= $loanDuration,
                    'remaining_payments' => max(0, $loanDuration - $newPaymentsCount)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', '[DebtNote::updateMonthlyPayment] ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ])->setStatusCode(500); // Set proper error status code
        }
    }
}
