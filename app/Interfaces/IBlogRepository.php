<?php

namespace App\Interfaces;

use App\Interfaces\Common\ICommonRepository;

interface IBlogRepository extends ICommonRepository
{
    public function getBlogByUserId($id);
}
