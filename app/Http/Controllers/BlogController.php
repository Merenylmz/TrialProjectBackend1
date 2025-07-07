<?php

namespace App\Http\Controllers;

use App\Interfaces\IBlogRepository;
use App\Interfaces\ICategoryRepository;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    protected $blogRepository;
    protected $categoryRepository;

    public function __construct(IBlogRepository $blogRepository, ICategoryRepository $categoryRepository) {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllBlogs(){
        try {
            $blogs = $this->blogRepository->getAll(true, "blogs");

            return response()->json($blogs);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getBlogById($id){
        try {
            $blog = $this->blogRepository->getById($id);
            
            return response()->json($blog);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addBlog(Request $req){
        try {
            $status = $this->blogRepository->create($req->all(), "blogs");
            $category = $this->categoryRepository->getById($status->categoryId);

            $categoryBlogs = $category->blogs;
            $categoryBlogs[] = $status->id;
            $category->blogs = $categoryBlogs;
            $category->save();

            $user = User::find($status->userId);
            $blogs = $user->blogs;
            $blogs[] = $status->id;
            $user->blogs = $blogs;
            $user->save();

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteBlog($id){
        try {
            $status = $this->blogRepository->delete($id, "blogs");

            return response()->json(["status"=>true, "id"=>$status]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editBlog(Request $req, $id){
        try {
            $status = $this->blogRepository->edit($req->all(), $id);

            return response()->json(["status"=>true, $status]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getBlogByUserId($id){//User ID
        try {
            $blogs = $this->blogRepository->getBlogByUserId($id);
            
            return response()->json($blogs);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
