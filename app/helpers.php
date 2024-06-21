<?php

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function pinChecker($pin) {
    $userId = auth()->user()->id;
    $wallet = Wallet::where('user_id', $userId)->first();

    if ($wallet == null) return false;

    if ($wallet->pin == $pin) return true;
    return false;
}

function uploadBase64Image($base64Image) {
    $decoder = new Base64ImageDecoder($base64Image, $allowedFormats = ['jpeg', 'png', 'jpg']);
    $decodedContent = $decoder->getDecodedContent();
    $format = $decoder->getFormat();
    $image = Str::random(10).'.'.$format;
    Storage::disk('public')->put($image, $decodedContent);

    return $image;
}

function getUser($param) {
    // Determina se o parâmetro é um número (ID)
    if (is_numeric($param)) {
        $user = User::where('id', $param)->first();
    } else {
        // Caso contrário, considera como e-mail ou nome de usuário
        $user = User::where('email', $param)
                    ->orWhere('username', $param)
                    ->first();
    }

    // Verifica se o usuário foi encontrado
    if ($user) {
        $wallet = Wallet::where('user_id', $user->id)->first();

        // Adiciona os detalhes do perfil do usuário
        $user->profile_picture = $user->profile_picture ? url('storage/' . $user->profile_picture) : "";
        $user->ktp = $user->ktp ? url('storage/' . $user->ktp) : "";
        $user->balance = $wallet ? $wallet->balance : 0;
        $user->card_number = $wallet ? $wallet->card_number : '';
        $user->pin = $wallet ? $wallet->pin : '';
    }

    return $user;
}
