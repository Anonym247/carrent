<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ManagementController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cars(Request $request): JsonResponse
    {
        $cars = Car::query()
            ->with('clients')
            ->get();

        return response()->json($cars);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function clients(Request $request): JsonResponse
    {
        $clients = Client::query()
            ->with('cars')
            ->get();

        return response()->json($clients);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function attachCarToClient(Request $request): JsonResponse
    {
        $this->validate($request, [
            'car_id' => 'required',
            'client_id' => 'required'
        ]);

        $car = Car::with('clients')->findOrFail($request->get('car_id'));
        $client = Client::with('cars')->findOrFail($request->get('client_id'));

        if ($car->clients->count()) {
            return response()->json([
                'message' => __('messages.logic.car_is_not_empty'),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        if ($client->cars->count()) {
            return response()->json([
                'message' => __('messages.logic.client_has_car'),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $car->clients()->attach($client->getKey());

        return response()->json([
            'message' => __('messages.saved')
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function detachCarFromClient(Request $request): JsonResponse
    {
        $this->validate($request, [
            'car_id' => 'required|exists:cars,id',
            'client_id' => 'required|exists:clients,id'
        ]);

        $car = Car::query()
            ->with('clients')
            ->whereHas('clients', function ($query) use ($request) {
                return $query->where('client_id', $request->get('client_id'));
            })
            ->find($request->get('car_id'));

        if (!$car) {
            return response()->json([
                'message' => __('messages.logic.not_belongs_to_client')
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $car->clients()->detach($request->get('client_id'));

        return response()->json([
            'message' => __('messages.saved')
        ]);
    }
}
