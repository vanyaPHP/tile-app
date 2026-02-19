<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'number' => $this->number,
            'name' => $this->name,
            'status' => $this->status,
            'created_at' => $this->create_date?->toIso8601String(),
            'updated_at' => $this->update_date?->toIso8601String(),

            'client' => [
                'id' => $this->user_id,
                'name' => $this->client_name,
                'surname' => $this->client_surname,
                'email' => $this->email,
                'company' => $this->company_name,
                'sex' => $this->sex,
            ],

            'delivery' => [
                'type' => $this->delivery_type,
                'cost' => (float) $this->delivery,
                'cost_eur' => (float) $this->delivery_price_euro,
                'address' => [
                    'country' => $this->delivery_country,
                    'region' => $this->delivery_region,
                    'city' => $this->delivery_city,
                    'address' => $this->delivery_address,
                    'building' => $this->delivery_building,
                    'apartment_office' => $this->delivery_apartment_office,
                    'zip_code' => $this->delivery_index,
                ],
                'phone' => $this->delivery_phone_code . ' ' . $this->delivery_phone,
                'dates' => [
                    'min' => $this->delivery_time_min?->format('Y-m-d'),
                    'max' => $this->delivery_time_max?->format('Y-m-d'),
                    'confirmed_min' => $this->delivery_time_confirm_min?->format('Y-m-d'),
                    'confirmed_max' => $this->delivery_time_confirm_max?->format('Y-m-d'),
                ],
                'carrier' => [
                    'name' => $this->carrier_name,
                    'contact' => $this->carrier_contact_data,
                    'tracking_number' => $this->tracking_number,
                ]
            ],

            'payment' => [
                'type' => $this->pay_type,
                'currency' => $this->currency,
                'currency_rate' => (float) $this->cur_rate,
                'discount_percent' => $this->discount,
                'vat_type' => $this->vat_type,
                'vat_number' => $this->vat_number,
                'tax_number' => $this->tax_number,
                'is_paid_euro' => (bool) $this->payment_euro,
                'full_payment_date' => $this->full_payment_date?->format('Y-m-d'),
            ],

            'manager' => [
                'name' => $this->manager_name,
                'email' => $this->manager_email,
                'phone' => $this->manager_phone,
            ],
            'metadata' => [
                'locale' => $this->locale,
                'measure' => $this->measure,
                'description' => $this->description,
                'weight_gross' => (float) $this->weight_gross,
                'warehouse_info' => $this->warehouse_data,
            ],

            'flags' => [
                'is_special_price' => (bool) $this->spec_price,
                'show_message' => (bool) $this->show_msg,
                'address_equal' => (bool) $this->address_equal,
                'accepted' => (bool) $this->accept_pay,
                'processed' => (bool) $this->process,
            ],

            'articles' => OrderArticleResource::collection($this->whenLoaded('articles')),
        ];
    }
}
