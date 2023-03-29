<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(ImageUploadRequest $request) {
        $file = $request->file('image');

        // gera uma string com 10 caracteres randômicos para o nome
        $name = Str::random(10);

        // salva o arquivo na pasta images do storage, com o nome gerado e a extensão do arquivo
        $url = Storage::putFileAs('images', $file, $name . '.'. $file->extension());

        return ['url' => env('APP_URL') .'/' . $url];
    }
}
