<?php

namespace App\Repository;

use App\Interfaces\ICategoryRepository;
use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    use Common\CommonRepositoryTrait;

    private $category;
    public function __construct(Category $category)
    {   
        $this->category = $category;
        $this->model = $this->category;
    }

    public function categoryWithBlogs($id){
        return $this->model->with("blogs")->find($id);
    }
}
