<?php

namespace App\Http\Controllers;

use App\Mail\SendDataOdoo;
use App\Models\Client;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiOdooController extends Controller
{

    public function getUsers(Request $request)
    {
        try {
            if ($request->header('token_vde') == 'PL489FWVJJVMEWO') {
                $requestData = ($request->users);
                $errors = [];
                foreach ($requestData as $dataUser) {
                    if (!$dataUser['display_name'] || !$dataUser['active'] || !$dataUser['login'] || !$dataUser['id']) {
                        array_push($errors, [$dataUser, 'Falta informacion del usuario']);
                    }
                }
                if (count($errors) > 0) {
                    return response()->json(['errors' => 'Informacion Incompleta', 'data' => $errors]);
                } else {
                    Storage::put('/public/dataUsers.txt',   json_encode($request->users));
                    Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataUsers.txt'));
                    foreach ($requestData as $dataUser) {
                        if (strtolower($dataUser['active']) == "true") {
                            $user = User::where('email', $dataUser['login'])->first();
                            if (!$user) {
                                User::create([
                                    'name' => $dataUser['display_name'],
                                    'email' => $dataUser['login'],
                                    'password' => Hash::make(Str::random(8)),
                                    'company_id' => null,
                                ]);
                            }
                        }
                    }
                    // TODO: Enviar por mail este archivo a mi mismo
                    return response()->json(['message' => 'Actualizacion Completa', 'data' => ($request->users)]);
                }
            } else {
                return response()->json(['message' => 'No Tienes autorizacion']);
            }
        } catch (Exception $th) {
            return  $th->getMessage();
        }
    }

    public function getClients(Request $request)
    {
        try {
            if ($request->header('token_vde') == 'PL489FWVJJVMEWO') {
                $requestData = ($request->clients);
                $errors = [];
                foreach ($requestData as $dataClient) {
                    if (!$dataClient['user_id'] || !$dataClient['id'] || !$dataClient['name'] || !$dataClient['email'] || !$dataClient['phone'] || !$dataClient['contact']) {
                        array_push($errors, [$dataClient, 'Falta informacion del usuario']);
                    }
                }
                if (count($errors) > 0) {
                    return response()->json(['errors' => 'Informacion Incompleta', 'data' => $errors]);
                } else {
                    Storage::put('/public/dataClients.txt',   json_encode($request->clients));
                    Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataClients.txt'));
                    foreach ($requestData as $dataClient) {
                        $client = Client::where('email', $dataClient['email'])->first();
                        if (!$client) {
                            Client::create([
                                'name' => $dataClient['name'],
                                'email' => $dataClient['email'],
                                'phone' => $dataClient['phone'],
                                'contact' => $dataClient['contact'],
                                'user_id' => $dataClient['user_id'],
                                'client_odoo_id' => $dataClient['id'],
                            ]);
                        }
                    }
                    // TODO: Enviar por mail este archivo a mi mismo
                    return response()->json(['message' => 'Actualizacion Completa', 'data' => ($request->clients)]);
                }
            } else {
                return response()->json(['message' => 'No Tienes autorizacion']);
            }
        } catch (Exception $th) {
            return  $th->getMessage();
        }
    }
}
