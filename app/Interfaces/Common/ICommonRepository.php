<?php

namespace App\Interfaces\Common;

interface ICommonRepository
{
    public function getAll($status, $modelName);
    public function getById($id);
    public function create(array $data, $modelName);
    public function delete($id, $modelName);
    public function edit(array $data, $id);

}
