<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderApiTest extends TestCase
{
    public function test_can_simulate_order()
    {
        $response = $this->postJson('/api/orders/simulate', [
            'items' => [['name' => 'Pizza', 'price' => 12.50, 'quantity' => 2]],
            'distance' => 5,
            'weight' => 2,
            'hour' => 15,
            'dayOfWeek' => 'tuesday'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['subtotal', 'discount', 'deliveryFee', 'surge', 'total']);
    }

    public function test_returns_404_for_unknown_order()
    {
        $response = $this->getJson('/api/orders/unknown_id_999');
        $response->assertStatus(404);
    }

    public function test_can_create_and_retrieve_order()
    {
        $createResponse = $this->postJson('/api/orders', [
            'items' => [['name' => 'Burger', 'price' => 10.00, 'quantity' => 1]],
            'distance' => 2,
            'weight' => 1,
            'hour' => 12,
            'dayOfWeek' => 'monday'
        ]);

        $createResponse->assertStatus(201);
        $orderId = $createResponse->json('id');

        $getResponse = $this->getJson("/api/orders/{$orderId}");
        $getResponse->assertStatus(200);
        $getResponse->assertJson(['id' => $orderId]);
    }

    public function test_can_validate_promo_code()
    {
        $response = $this->postJson('/api/promo/validate', [
            'promoCode' => 'BIENVENUE20',
            'subtotal' => 20.00
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['subtotal', 'discount', 'final']);
    }

    public function test_fails_on_closed_restaurant()
    {
        $response = $this->postJson('/api/orders/simulate', [
            'items' => [['name' => 'Pizza', 'price' => 12.50, 'quantity' => 1]],
            'distance' => 2,
            'weight' => 1,
            'hour' => 23, // Fermé
            'dayOfWeek' => 'tuesday'
        ]);

        $response->assertStatus(400);
    }
}