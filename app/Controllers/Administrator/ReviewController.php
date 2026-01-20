<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class ReviewController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Reviews',
            'reviews' => $this->reviewModel->getAllReviews()
        ];

        return view('administrator/reviews', $data);
    }

    public function toggle($id)
    {
        $review = $this->reviewModel->find($id);

        if (!$review) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Review not found'
            ]);
        }

        $newStatus = $review['status'] === 'active' ? 'inactive' : 'active';
        if ($this->reviewModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update review status'
        ]);
    }

    public function delete($id)
    {
        if ($this->reviewModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to delete review'
        ]);
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|max_length[50]',
            'content' => 'required|min_length[10]',
            'rating' => 'required|numeric|greater_than[0]|less_than[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'content' => $this->request->getPost('content'),
            'rating' => $this->request->getPost('rating'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->reviewModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review added successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to add review'
        ]);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|max_length[50]',
            'content' => 'required|min_length[10]',
            'rating' => 'required|numeric|greater_than[0]|less_than[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'content' => $this->request->getPost('content'),
            'rating' => $this->request->getPost('rating'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->reviewModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update review'
        ]);
    }
}
