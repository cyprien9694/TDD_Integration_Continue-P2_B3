<?php

namespace App\Services;

class PricingEngine
{
    // Fonction 1 : calculateDeliveryFee
    public static function calculateDeliveryFee(float $distance, float $weight): ?float
    {
        if ($distance < 0 || $weight < 0) {
            throw new \InvalidArgumentException("Distance and weight cannot be negative.");
        }

        if ($distance > 10) {
            return null; // Hors zone
        }

        $fee = 2.00; // Base

        if ($distance > 3) {
            $fee += ($distance - 3) * 0.50;
        }

        if ($weight > 5) {
            $fee += 1.50;
        }

        return round($fee, 2);
    }

    // Fonction 2 : applyPromoCode
    public static function applyPromoCode(float $subtotal, ?string $promoCode, array $promoCodes): array
    {
        if ($subtotal < 0) {
            throw new \InvalidArgumentException("Subtotal cannot be negative.");
        }

        if (empty($promoCode)) {
            return ['subtotal' => $subtotal, 'discount' => 0.0];
        }

        $found = null;
        foreach ($promoCodes as $code) {
            if ($code['code'] === $promoCode) {
                $found = $code;
                break;
            }
        }

        if (!$found) {
            throw new \InvalidArgumentException("Promo code not found.");
        }

        // Vérification de l'expiration
        if (isset($found['expiresAt']) && strtotime($found['expiresAt']) < time()) {
            throw new \InvalidArgumentException("Promo code has expired.");
        }

        // Vérification du montant minimum
        if (isset($found['minOrder']) && $subtotal < $found['minOrder']) {
            throw new \InvalidArgumentException("Minimum order amount not reached.");
        }

        $discount = 0.0;
        if ($found['type'] === 'percentage') {
            $discount = round($subtotal * ($found['value'] / 100), 2);
        } elseif ($found['type'] === 'fixed') {
            $discount = min($subtotal, $found['value']);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'final' => max(0.0, round($subtotal - $discount, 2))
        ];
    }

    // Fonction 3 : calculateSurge
    public static function calculateSurge(int $hour, string $dayOfWeek): float
    {
        if ($hour < 0 || $hour > 23) {
            throw new \InvalidArgumentException("Invalid hour.");
        }

        // Hors horaires d'ouverture (avant 10h ou après 22h)
        if ($hour < 10 || $hour >= 22) {
            return 0.0; // Fermé
        }

        $dayOfWeek = strtolower($dayOfWeek);
        $isWeekend = in_array($dayOfWeek, ['friday', 'saturday']);

        if ($dayOfWeek === 'sunday') {
            return 1.2;
        }

        if ($isWeekend && $hour >= 19 && $hour <= 22) {
            return 1.8;
        }

        // Lundi - Jeudi (et Vendredi/Samedi hors soir)
        if (!$isWeekend && $dayOfWeek !== 'sunday') {
            if ($hour >= 12 && $hour < 14) {
                return 1.3; // Déjeuner
            }
            if ($hour >= 19 && $hour < 21) {
                return 1.5; // Dîner
            }
        }

        return 1.0; // Normal
    }

    // Fonction 4 : calculateOrderTotal
    public static function calculateOrderTotal(array $items, float $distance, float $weight, ?string $promoCode, int $hour, string $dayOfWeek, array $promoCodes = []): array
    {
        if (empty($items)) {
            throw new \InvalidArgumentException("Items cart cannot be empty.");
        }

        $subtotal = 0.0;
        foreach ($items as $item) {
            if ($item['price'] < 0 || $item['quantity'] <= 0) {
                throw new \InvalidArgumentException("Invalid item price or quantity.");
            }
            $subtotal += $item['price'] * $item['quantity'];
        }
        $subtotal = round($subtotal, 2);

        // Surge pricing check
        $surge = self::calculateSurge($hour, $dayOfWeek);
        if ($surge === 0.0) {
            throw new \InvalidArgumentException("Restaurant is closed.");
        }

        // Delivery fee check
        $deliveryFee = self::calculateDeliveryFee($distance, $weight);
        if ($deliveryFee === null) {
            throw new \InvalidArgumentException("Delivery address out of range.");
        }
        $deliveryFee = round($deliveryFee * $surge, 2);

        // Promo code check
        $discount = 0.0;
        if (!empty($promoCode)) {
            $promoResult = self::applyPromoCode($subtotal, $promoCode, $promoCodes);
            $discount = $promoResult['discount'];
            $subtotalAfterPromo = $promoResult['final'];
        } else {
            $subtotalAfterPromo = $subtotal;
        }

        $total = round(max(0.0, $subtotalAfterPromo) + $deliveryFee, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'deliveryFee' => $deliveryFee,
            'surge' => $surge,
            'total' => $total
        ];
    }
}