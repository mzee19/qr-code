<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subscription = $this->subscription;
        $payload = json_decode($this->payload,true);
        $payment_method = '';

        switch ($this->payment_method) {
            case config('constants.payment_methods')['PAYPAL']:
                $payment_method = 'Paypal';
                break;
            case config('constants.payment_methods')['MOLLIE']:
                $payment_method = 'Mollie';
                break;
            case config('constants.payment_methods')['ADMIN']:
                $payment_method = 'Admin';
                break;
        }

        return [
            'id' => $this->id,
            'hash_id' => \Hashids::encode($this->id),
            'item' => $this->item,
            'amount' => $this->amount,
            'vat_percentage' => $this->vat_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'payment_method' => $payment_method,
            'status' => $this->status,
            'payment_date' => \Carbon\Carbon::createFromTimeStamp($this->timestamp, "UTC")->tz($this->user->timezone)->format('d M, Y'),
            'end_date' => empty($subscription->end_date) ? 'Lifetime' : \Carbon\Carbon::createFromTimeStamp($subscription->end_date, "UTC")->format('d M, Y'),
            'created_at' => (string) $this->created_at,
        ];
    }
}
