<?php

use Illuminate\Http\Request;
use App\User;
use App\Partida;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/registro/{name}/{email}/{pass}', function (Request $request, $name, $user, $pass ){
  $nombre = $name;
  $correo = $user;
  $password = $pass;

  $crearUsuario = new User();
  $crearUsuario->name=$nombre;
  $crearUsuario->email=$correo;
  $crearUsuario->password=bcrypt($password);
  $crearUsuario->token=$token = md5(uniqid(rand(), TRUE));
  $crearUsuario->save();
});

Route::get('/login/{email}/{pass}', function(Request $request, $email, $pass){
  if (Auth::attempt(['email' => $email, 'password' => $pass])){
    $usuario = User::select()->where('email',$email)->first();
    $token = md5(uniqid(rand(), TRUE));
    $usuario->token=$token;
    $usuario->save();
    header("Access-Control-Allow-Origin: *");
    return response()->json(['email'=>$email,'token'=>$token]);
  }else{
    echo "No existe ese user";
  }
});

Route::get('/addPartida/{token}/{idPartida}', function(Request $request, $token, $idPartida){
  $usuario = User::select()->where('token', $token)->first();
  $partida = Partida::select()->where('estado<2')->first();

});

/*Route::get('/move', function(Request $request){

});*/
Route::get('/logout', function(Request $request){
  if (Auth::logout()){
    echo 'Sesion cerrada correctamente';
  }else{
    echo 'ERROR! No se ha podido cerrar sesion';
  }
});
