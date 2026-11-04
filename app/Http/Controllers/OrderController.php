<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PricingEngine;

class OrderController extends Controller
{
    // Stockage en mémoire partagé pour l'exercice
    private static array $orders = [];

    private function getMockPromoCodes(): array
    {
        return [
            [
                "code" => "BIENVENUE20",
                "type" => "percentage",
                "value" => 20,
                "minOrder" => 15.00,
                "expiresAt" => "2030-12-31"
            ],
            [
                "code" => "SOLDE5",
                "type" => "fixed",
                "value" => 5.00,
                "minOrder" => 10.00,
                "expiresAt" => "2030-12-31"
            ]
        ];
    }

    public function simulate(Request $request)
    {
        try {
            $data = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'distance' => 'required|numeric|min:0',
                'weight' => 'required|numeric|min:0',
                'promoCode' => 'nullable|string',
                'hour' => 'required|integer|between:0,23',
                'dayOfWeek' => 'required|string'
            ]);

            $result = PricingEngine::calculateOrderTotal(
                $data['items'],
                $data['distance'],
                $data['weight'],
                $data['promoCode'] ?? null,
                $data['hour'],
                $data['dayOfWeek'],
                $this->getMockPromoCodes()
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'distance' => 'required|numeric|min:0',
                'weight' => 'required|numeric|min:0',
                'promoCode' => 'nullable|string',
                'hour' => 'required|integer|between:0,23',
                'dayOfWeek' => 'required|string'
            ]);

            $result = PricingEngine::calculateOrderTotal(
                $data['items'],
                $data['distance'],
                $data['weight'],
                $data['promoCode'] ?? null,
                $data['hour'],
                $data['dayOfWeek'],
                $this->getMockPromoCodes()
            );

            $id = uniqid('ord_');
            $order = array_merge(['id' => $id], $data, $result);
            self::$orders[$id] = $order;

            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        if (!isset(self::$orders[$id])) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(self::$orders[$id], 200);
    }

    public function validatePromo(Request $request)
    {
        try {
            $data = $request->validate([
                'promoCode' => 'required|string',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $result = PricingEngine::applyPromoCode(
                $data['subtotal'],
                $data['promoCode'],
                $this->getMockPromoCodes()
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}