<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        return view('administrator/categories');
    }

    public function list()
    {
        try {
            $categories = $this->categoryModel->select('categories.*, COUNT(transaction.id) as transaction_count')
                ->join('transaction', 'transaction.category = categories.id', 'left')
                ->groupBy('categories.id')
                ->findAll();

            return $this->response->setJSON($categories);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error fetching categories',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function get($id)
    {
        try {
            $category = $this->categoryModel->find($id);
            if (!$category) {
                return $this->response->setJSON([
                    'error' => 'Category not found'
                ])->setStatusCode(404);
            }
            return $this->response->setJSON($category);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error fetching category',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function add()
    {
        try {
            $data = $this->request->getJSON();
            $categoryData = [
                'name' => $data->name,
                'type' => $data->type
            ];

            if ($this->categoryModel->insert($categoryData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Category added successfully'
                ]);
            }

            return $this->response->setJSON([
                'error' => 'Failed to add category'
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error adding category',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function update()
    {
        try {
            $data = $this->request->getJSON();
            $categoryData = [
                'name' => $data->name,
                'type' => $data->type
            ];

            if ($this->categoryModel->update($data->id, $categoryData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Category updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'error' => 'Failed to update category'
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error updating category',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function delete($id)
    {
        try {
            if ($this->categoryModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Category deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'error' => 'Failed to delete category'
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error deleting category',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
