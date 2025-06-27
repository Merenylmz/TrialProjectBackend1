<?php

namespace App\Http\Controllers;

use App\Interfaces\ICategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(ICategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(){
        try {
            $categories = $this->categoryRepository->getAll(true, "categories");

            return response()->json($categories);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCategoryById($id){
        try {
            $category = $this->categoryRepository->getById($id);
            
            return response()->json($category);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addCategory(Request $req){
        try {
            $status = $this->categoryRepository->create($req->all(), "categories");

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteCategory($id){
        try {
            $status = $this->categoryRepository->delete($id, "categories");

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
