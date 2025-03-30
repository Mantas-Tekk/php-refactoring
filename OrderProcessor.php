<?php

class OrderProcessor {

    // Not to have hardcoded values in functions. And for easier readabilty if change is needed
    private const DISCOUNT_THRESHOLD_TOTAL = 100;
    private const DISCOUNT_RATE = 0.1;
    private const VIP_DISCOUNT_RATE = 0.9;


    // Breaking down responsibilities
    public function processOrders($orders) {

        foreach ($orders as $order) {

            if($this->isOrderStatusPending($order)) {
                $total = $this->calculateTotal($order['items']);
                $finalTotal = $this->applyDiscount($total, $order['customer_type']);
                $this->notifyCustomer($order['customer_email'], $finalTotal);  
            }
        }
    }

    // Makesit more reausable and easier to test
    private function isOrderStatusPending($order) {

        return $order['status'] === 'pending'; 
    }

    // Makesit more reausable and easier to test
    private function calculateTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    // Expading function is much easier 
    private function applyDiscount($total, $customerType) {
        // Applys discount only if thresgold is passed.
        if($total > self::DISCOUNT_THRESHOLD_TOTAL) {
            $total -= $total * self::DISCOUNT_RATE;
        }
        
        if($customerType === 'vip') {
            $total *= self::VIP_DISCOUNT_RATE;
        }

        return $total;
    }

    // doing changes to  
    private function notifyCustomer($email, $amount) {
        $formattedAmount = number_format($amount, 2);
        $orderMessage = "Your order total: $formattedAmount";
        $this->sendEmail($email, $orderMessage);
    }

    private function sendEmail($email, $message) {
        // Simulating email sending
        echo "Sending email to $email: $message\n";
    }
}

$orders = [
    [
        'status' => 'pending',
        'customer_email' => 'customer1@example.com',
        'customer_type' => 'vip',
        'items' => [
            ['price' => 50, 'quantity' => 2],
            ['price' => 30, 'quantity' => 1]
        ]
    ],
    [
        'status' => 'completed',
        'customer_email' => 'customer2@example.com',
        'customer_type' => 'regular',
        'items' => [
            ['price' => 20, 'quantity' => 3]
        ]
    ]
];

$processor = new OrderProcessor();
$processor->processOrders($orders);
