<?php

namespace App\Repository\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait CommonRepositoryTrait
{
    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function getAll($status = false, $modelName){
        try {
            //status ? use cache : use model
            if (!$status || !Cache::has($modelName)) {
                $datas = $this->model->all();
                Cache::put($modelName, $datas, 3600/2);
                return $datas;
            } 

            $data = Cache::get($modelName);
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function getById($id){
        try {
            $data = $this->model->find($id);
            return $data;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(array $data, $modelName) {
        try {
            Cache::has($modelName) && Cache::forget($modelName);

            return $this->model->create($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id, $modelName){
        try {
            Cache::has($modelName) && Cache::forget($modelName);
            
            return $this->model->destroy($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit(array $data, $id){
        try {
            $modelData = $this->model->findOrFail($id);
            return $modelData->update($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
