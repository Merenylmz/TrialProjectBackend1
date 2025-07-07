<?php

namespace App\Repository;

use App\Interfaces\IBlogRepository;
use App\Models\Blogs;

class BlogRepository implements IBlogRepository
{
    use Common\CommonRepositoryTrait;

    protected $blog;
    public function __construct(Blogs $blog)
    {
        $this->blog = $blog;
        $this->model = $this->blog;
    }

    public function getBlogByUserId($id){
        $blogs = $this->blog->where("userId", $id)->get();

        return $blogs;
    }
}
