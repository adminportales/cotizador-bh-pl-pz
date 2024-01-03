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

    /**
     * Método para obtener los usuarios y actualizar su información de Odoo.
     *
     * @param Request $request La solicitud HTTP recibida.
     * @return JsonResponse La respuesta JSON con el resultado de la actualización.
     */
    public function getUsers(Request $request)
    {
        $time = time();
        Storage::put('/public/dataUsers' .  $time . '.txt', json_encode($request->all()));
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
                        // Storage::put('/public/dataUsers.txt', Storage::get('/public/dataUsers.txt') .  json_encode($requestData));
                        // Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataClients.txt'));
                        foreach ($requestData as $dataUser) {
                            if ($dataUser['active']) {
                                // Buscamos el Id de odoo y la empresa
                                $userOdooId = UserInformationOdoo::where('odoo_id', $dataUser['id'])->where('company_id', $dataUser['company_id'])->first();
                                // Si ya existe, actualizamos su informacion de usuario
                                if ($userOdooId) {
                                    $userOdooId->user()->update([
                                        'name' => $dataUser['display_name'],
                                        'email' => $dataUser['login'],
                                    ]);
                                } else {
                                    // Si no existe, podria existir ya un usuario con ese email pero tiene otra empresa,
                                    // Buscamos el usuario en la DB para saber si existe
                                    $user = User::where('email', $dataUser['login'])->first();
                                    $roleSeller = Role::find(2);
                                    if (!$user) {
                                        // Si el usuario no existe, lo creamos, le asignamos un rol y creamos su relacion en InfoOdoo
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
                                        $userCreated->attachRole($roleSeller);
                                    } else {
                                        // Si el usuario ya existe, agregamos un nuevo registro a InfoOdoo en caso de que no exista
                                        $registerOdooCompany = $user->info()->where('odoo_id', $dataUser['id'])->where('company_id', $dataUser['company_id'])->first();
                                        if (!$registerOdooCompany) {
                                            $user->info()->create([
                                                'odoo_id' => $dataUser['id'],
                                                'company_id' => $dataUser['company_id'],
                                            ]);
                                        }
                                    }
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
            Storage::put('/public/dataErrorUsers' .  $time . '.txt',   json_encode($th->getMessage()));
            Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataErrorClients.txt'));
            return  $th->getMessage();
        }
    }

    /**
     * Recupera los clientes de la solicitud y almacena los datos en un archivo.
     * Valida el token de la solicitud y procesa los datos del cliente.
     * Crea o actualiza los clientes en la base de datos en función de los datos recibidos.
     * Envía un correo electrónico con los datos de error si hay información incompleta del cliente.
     *
     * @param  Request  $request  El objeto de solicitud HTTP.
     * @return JsonResponse  La respuesta JSON que contiene el resultado de la operación.
     */
    public function getClients(Request $request)
    {
        Storage::put('/public/dataClients' . time() . '.txt', json_encode($request->all()));
        try {
            if ($request->header('token') == 'PL489FWVJJVMEWO') {
                $requestData = ($request->clients);
                $errors = [];
                if ($requestData) {
                    foreach ($requestData as $dataClient) {
                        if (!$dataClient['id'] || !$dataClient['name']) {
                            array_push($errors, [$dataClient, 'Falta informacion del usuario']);
                        }
                    }

                    if (count($errors) > 0) {
                        return response()->json(['errors' => 'Informacion Incompleta', 'data' => $errors]);
                    } else {
                        Storage::put('/public/dataClients.txt', json_encode($requestData));
                        foreach ($requestData as $dataClient) {
                            $client = Client::where('client_odoo_id', $dataClient['id'])->where('company_id', $dataClient['company_id'])->first();
                            if (!$client) {
                                $client = Client::create([
                                    'name' => $dataClient['name'],
                                    'email' => array_key_exists('email', $dataClient) ? $dataClient['email'] : "",
                                    'phone' => array_key_exists('phone', $dataClient) ? (is_int($dataClient['phone']) ? $dataClient['phone'] : 0) : 0,
                                    'contact' => array_key_exists('phone', $dataClient) ? "Sin Contacto" : $dataClient['contact'],
                                    'user_id' => array_key_exists('user_id', $dataClient) ? (is_int($dataClient['user_id']) ? $dataClient['user_id'] : null)  : null,
                                    'company_id' => array_key_exists('company_id', $dataClient) ?  $dataClient['company_id'] : null,
                                    'client_odoo_id' => $dataClient['id'],
                                ]);
                                if ($dataClient['tradename']) {
                                    if (count($dataClient['tradename']) > 0) {
                                        foreach ($dataClient['tradename'] as $value) {
                                            $client->tradenames()->create([
                                                'name' => $value['customer_label']
                                            ]);
                                        }
                                    }
                                }
                            } else {
                                $client->update([
                                    'name' => $dataClient['name'],
                                    'email' => array_key_exists('email', $dataClient) ? $dataClient['email'] : "",
                                    'phone' => array_key_exists('phone', $dataClient) ? (is_int($dataClient['phone']) ? $dataClient['phone'] : 0) : 0,
                                    'contact' => array_key_exists('phone', $dataClient) ? "Sin Contacto" : $dataClient['contact'],
                                    'user_id' => array_key_exists('user_id', $dataClient) ? (is_int($dataClient['user_id']) ? $dataClient['user_id'] : null)  : null,
                                ]);
                                if ($dataClient['tradename']) {
                                    if (count($dataClient['tradename']) > 0) {
                                        $client->tradenames()->delete();
                                        foreach ($dataClient['tradename'] as $value) {
                                            $client->tradenames()->create([
                                                'name' => $value['customer_label']
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                        // TODO: Enviar por mail este archivo a mi mismo
                        return response()->json(['message' => 'Actualizacion Completa', 'data' => ($request->clients)]);
                    }
                } else {
                    Storage::put('/public/dataErrorClients.txt',   json_encode($errors));
                    Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataErrorClients.txt'));
                    return response()->json(['errors' => 'Informacion Incompleta']);
                }
            } else {
                return response()->json(['message' => 'No Tienes autorizacion']);
            }
        } catch (Exception $th) {
            Storage::put('/public/dataErrorClients.txt',   json_encode($th->getMessage()));
            Mail::to('adminportales@promolife.com.mx')->send(new SendDataOdoo('adminportales@promolife.com.mx', '/storage/dataErrorClients.txt'));
            return  $th->getMessage();
        }
    }
}
