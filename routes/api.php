<?php

use Illuminate\Http\Request;
use App\User;
use App\Partida;
header("Access-Control-Allow-Origin: *");
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

    return response()->json(['email'=>$email,'token'=>$token]);
  }else{
    echo "No existe ese user";
  }
});

Route::get('/partidas/', function(Request $request){
    $partidas = Partida::all();
    return response()->json(['partida1'=>$partidas[0], 'partida2'=>$partidas[1], 'partida3'=>$partidas[2], 'partida4'=>$partidas[3]]);

});

Route::get('/addPartida/{token}/{email}/{idPartida}', function(Request $request, $token, $email, $idPartida){
  $usuario = User::where('token', $token)->first();
  if($usuario->email == $email){
    //usuario conectado
    $partida = Partida::where('id', $idPartida)->first();
    if($partida->estado == 0){
      if($partida->jugador0_id == "empty") {
        $partida->jugador0_id = $email;
        $partida->estado = 1;
        $partida->save();
        $msj="1";
        return response()->json(['mensaje'=>$msj]);
      }else if($partida->jugador1_id == "empty"){
        $partida->jugador1_id = $email;
        $partida->estado = 1;
        $partida->save();
        $msj="2";
        return response()->json(['mensaje'=>$msj]);
      }
    }else if($partida->estado == 1){
      if($partida->jugador0_id == "empty") {
        $partida->jugador0_id = $email;
        $partida->estado = 2;
        $partida->save();
        $msj="3";
        return response()->json(['mensaje'=>$msj]);
      }else if($partida->jugador1_id == "empty"){
        $partida->jugador1_id = $email;
        $partida->estado = 2;
        $partida->save();
        $msj="4";
        return response()->json(['mensaje'=>$msj]);
      }
    }else{
      $msj="5";
      return response()->json(['mensaje'=>$msj]);
    }
  }else{
    $msj="6";
    return response()->json(['mensaje'=>$msj]);
  }

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
