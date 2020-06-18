<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers;

use App\Car;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\exception_for;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cars = Car::all()->load('user');

        return response()->json(['cars' => $cars]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $authorization = $request->header('authorization');
        //Clausula de Guarda
        if (!$this->isAuthorized($authorization)) {
            return response([
                'status' => 'error',
                'code' => '400',
                'message' => 'No estas Autenticado'
            ]);
        }
        //Validar datos
        $json = $request->input('json');
        $arrayJson = json_decode($json, true);

        $validator = Validator::make($arrayJson, [
            'title' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $requestCar = json_decode($request->json);
        $car = new Car();
        $car->user_id = $this->getIdentity($authorization);
        $car->title = $requestCar->title;
        $car->description = (isset($requestCar->description)) ? $requestCar->description : null;
        $car->price = $requestCar->price;
        $car->status = (isset($requestCar->status)) ? $requestCar->status : null;

        $car->save();


        return response([
            'status' => 'success',
            'code' => '200',
            'message' => 'creado correctamente'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public
    function show($id)
    {
        $car = Car::query()->find($id);
        return response()->json(['car' => $car]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function edit(Request $request, $id)
    {
        $authorization = $request->header('authorization');

        //Clausula de Guarda
        if (!$this->isAuthorized($authorization)) {
            return response([
                'status' => 'error',
                'code' => '400',
                'message' => 'No estas Autenticado'
            ]);
        }

        //Validar datos
        $json = $request->input('json');
        $arrayJson = json_decode($json, true);

        $validator = Validator::make($arrayJson, [
            'title' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $requestCar = json_decode($request->json);
        $car = Car::query()->find($id);
        $car->user_id = $this->getIdentity($authorization);
        $car->title = $requestCar->title;
        $car->description = (isset($requestCar->description)) ? $requestCar->description : null;
        $car->price = $requestCar->price;
        $car->status = (isset($requestCar->status)) ? $requestCar->status : null;

        $car->save();


        return response([
            'status' => 'success',
            'code' => '200',
            'message' => 'editado correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return void
     */
    public
    function destroy(Request $request ,$id)
    {
        $authorization = $request->header('authorization');
        //Clausula de Guarda
        if (!$this->isAuthorized($authorization)) {
            return response([
                'status' => 'error',
                'code' => '400',
                'message' => 'No estas Autenticado'
            ]);
        }
        //Buscamos coche
        $car = Car::query()->find($id);

        try{
            $car->delete();
        }catch (\exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Fallo al borrar el car'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'El car: '.$id.' ha sido borrado'
        ]);
    }

    private function isAuthorized($authorization)
    {
        $jwtAuth = new JwtAuth();
        return $jwtAuth->checkToken($authorization);
    }

    private function getIdentity($authorization)
    {
        $jwtAuth = new JwtAuth();
        $decoded = $jwtAuth->checkToken($authorization, true);
        return $decoded->sub;
    }
}
