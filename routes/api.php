<?php

use Illuminate\Http\Request;
use App\User;
use App\Partida;
use App\Ficha;
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
        $msj="Juegas con blancas";
        return response()->json(['mensaje'=>$msj]);
      }else if($partida->jugador1_id == "empty"){
        $partida->jugador1_id = $email;
        $partida->estado = 1;
        $partida->save();
        $msj="Juegas con blancas";
        return response()->json(['mensaje'=>$msj]);
      }
    }else if($partida->estado == 1){
      if($partida->jugador0_id == "empty") {
        $partida->jugador0_id = $email;
        $partida->estado = 2;
        $partida->save();
        $msj="Juegas con Negras";
        return response()->json(['mensaje'=>$msj]);
      }else if($partida->jugador1_id == "empty"){
        $partida->jugador1_id = $email;
        $partida->estado = 2;
        $partida->save();
        $msj="Juegas con negras";
        return response()->json(['mensaje'=>$msj]);
      }
    }else{
      $msj="Partida llena";
      return response()->json(['mensaje'=>$msj]);
    }
  }else{
    $msj="Usuario incorrecto";
    return response()->json(['mensaje'=>$msj]);
  }

});

Route::get('/movRey/{idPartida}/{fila}/{col}', function(Request $request, $partida, $fila, $columna){
  $movimiento = new Ficha();
  $defFil = 1;
  $defCol= 5;
  if($defFil-1==$fila && $defCol==$columna || $defFil+1==$fila && $defCol==$columna && $fila < 9 && $fila > 0 && $columna < 9 && $columna > 0){
    $msj="Movimiento correcto y realizado";
    $movimiento->partida_id=$partida;
    $movimiento->fila=$fila;
    $movimiento->col=$columna;
    $movimiento->save();
    return response()->json(['mensaje'=>$msj]);
  }else if($defFil==$fila && $defCol-1==$columna || $defFil==$fila && $defCol+1==$columna && $fila < 9 && $fila > 0 && $columna < 9 && $columna > 0){
    $msj="Movimiento correcto y realizado";
    $movimiento->partida_id=$partida;
    $movimiento->fila=$fila;
    $movimiento->col=$columna;
    $movimiento->save();
    return response()->json(['mensaje'=>$msj]);
  }else if($defFil-1==$fila && $defCol-1==$columna || $defFil+1==$fila && $defCol+1==$columna && $fila < 9 && $fila > 0 && $columna < 9 && $columna > 0){
    $msj="Diagonal correcta y realizada";
    $movimiento->partida_id=$partida;
    $movimiento->fila=$fila;
    $movimiento->col=$columna;
    $movimiento->save();
    return response()->json(['mensaje'=>$msj]);
  }else{
    $msj="Movimiento incorrecto";
    return response()->json(['mensaje'=>$msj]);
  }

});
Route::get('/logout', function(Request $request){
  if (Auth::logout()){
    echo 'Sesion cerrada correctamente';
  }else{
    echo 'ERROR! No se ha podido cerrar sesion';
  }
});
