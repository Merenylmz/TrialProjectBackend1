<?php

namespace App\Http\Controllers;

use App\Interfaces\IBlogRepository;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    protected $blogRepository;

    public function __construct(IBlogRepository $blogRepository) {
        $this->blogRepository = $blogRepository;
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

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteBlog($id){
        try {
            $status = $this->blogRepository->delete($id, "blogs");

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editBlog(Request $req, $id){
        try {
            $status = $this->blogRepository->edit($req->all(), $id);

            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
