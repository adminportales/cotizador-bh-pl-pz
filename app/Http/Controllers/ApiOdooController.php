<?php

namespace App\Http\Controllers;

use App\Mail\SendDataOdoo;
use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInformationOdoo;
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
            if ($request->header('token') == 'PL489FWVJJVMEWO') {
                $requestData = ($request->users);
                if ($requestData) {
                    $errors = [];
                    foreach ($requestData as $dataUser) {
                        if (!$dataUser['display_name'] || !$dataUser['active'] || !$dataUser['login'] || !$dataUser['id'] || !$dataUser['company_id']) {
                            array_push($errors, [$dataUser, 'Falta informacion del usuario']);
                        }
                    }
                    if (count($errors) > 0) {
                        return response()->json(['errors' => 'Informacion Incompleta', 'data' => $errors]);
                    } else {
                        // dd(1);
                        Storage::put('/public/dataUsers.txt',   json_encode($request->users));
                        foreach ($requestData as $dataUser) {
                            if (strtolower($dataUser['active']) == "true") {
                                $userOdooId = UserInformationOdoo::where('odoo_id', $dataUser['id'])->first();
                                if ($userOdooId) {
                                    $userOdooId->user()->update([
                                        'name' => $dataUser['display_name'],
                                        'email' => $dataUser['login'],
                                        'company_id' => null,
                                    ]);
                                } else {
                                    $userCreated =  User::create([
                                        'name' => $dataUser['display_name'],
                                        'email' => $dataUser['login'],
                                        'password' => Hash::make(12345678),
                                        'company_id' => null,
                                    ]);
                                    $userCreated->info()->create([
                                        'odoo_id' => $dataUser['id'],
                                        'company_id' => $dataUser['company_id'],
                                    ]);

                                    $roleSeller = Role::find(2);

                                    $userCreated->attachRole($roleSeller);
                                }
                            }
                        }
                        // TODO: Enviar por mail este archivo a mi mismo
                        return response()->json(['message' => 'Actualizacion Completa', 'data' => ($request->users)]);
                    }
                } else {
                    return response()->json(['errors' => 'Informacion Incompleta']);
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
            if ($request->header('token') == 'PL489FWVJJVMEWO') {
                $requestData = ($request->clients);
                if ($requestData) {
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
                        //Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataClients.txt'));
                        foreach ($requestData as $dataClient) {
                            $client = Client::where('client_odoo_id', $dataClient['id'])->first();
                            if (!$client) {
                                Client::create([
                                    'name' => $dataClient['name'],
                                    'email' => $dataClient['email'],
                                    'phone' => $dataClient['phone'],
                                    'contact' => $dataClient['contact'],
                                    'user_id' => $dataClient['user_id'],
                                    'client_odoo_id' => $dataClient['id'],
                                ]);
                            } else {
                                $client->update([
                                    'name' => $dataClient['name'],
                                    'email' => $dataClient['email'],
                                    'phone' => $dataClient['phone'],
                                    'contact' => $dataClient['contact'],
                                    'user_id' => $dataClient['user_id'],
                                ]);
                            }
                        }
                        // TODO: Enviar por mail este archivo a mi mismo
                        return response()->json(['message' => 'Actualizacion Completa', 'data' => ($request->clients)]);
                    }
                } else {
                    return response()->json(['errors' => 'Informacion Incompleta']);
                }
            } else {
                return response()->json(['message' => 'No Tienes autorizacion']);
            }
        } catch (Exception $th) {
            Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataUsers.txt'));
            return  $th->getMessage();
        }
    }
}
