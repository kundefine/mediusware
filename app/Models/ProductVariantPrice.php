<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $guarded = ["id"];
    public function pv1() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one', 'id');
    }

    public function pv2() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two', 'id');
    }

    public function pv3() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
