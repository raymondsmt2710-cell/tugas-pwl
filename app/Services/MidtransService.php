class midtransService
{
    public function createTransaction(Donation $donation)
    {
        $snap = new Snap();
        
        $params = [
            'transaction_details' => [
                'order_id' => 'DONATION-' . $donation->id,
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email,
            ],
            'item_details' => [
                [
                    'id' => $donation->campaign_id,
                    'price' => $donation->amount,
                    'quantity' => 1,
                    'name' => $donation->campaign->title,
                ],
            ],
        ];
        
        return $snap->createTransaction($params);
    }
}