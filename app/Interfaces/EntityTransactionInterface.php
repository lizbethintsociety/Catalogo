<?php

namespace App\Interfaces;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface EntityTransactionInterface {
  public function store(Request $request) : array;
  public function update(Request $request) : array;
  public function delete(Request $request) : array;
  public function show(Request $request) : array;
  public function get(Request $request) : array;
  public function restore(Request $request) : array;
  public function makeModel(Request  $request, Model $model = null) : Model;
  public function rulesValidateEntity(array $data, $update = false, Model $model = null) : \Illuminate\Contracts\Validation\Validator;
}
