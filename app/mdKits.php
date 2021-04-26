<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class mdKits extends Model
{
    protected $table = 'kits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'n_order',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = preg_replace('/\D+/', '', $value);
    }

    public function getAmountAttribute()
    {
        return round($this->attributes['amount'], 4);
    }

    public function getUnitPriceAttribute()
    {
       // return round($this->attributes['unit_price'], 2);
        return number_format($this->attributes['unit_price'],2);
    }

    public function getUnitPromotionPriceAttribute()
    {
      //  return round($this->attributes['unit_promotion_price'], 2);
        return number_format($this->attributes['unit_promotion_price'],2);
    }

    public function pesqPrice()
    {
        if($this->attributes['unit_promotion_price'] > 0){
            return $this->getUnitPromotionPriceAttribute();
        } else {
            return $this->getUnitPriceAttribute();
        }

    }

    public function pesqStore()
    {
        return $this->belongsTo(mdStores::class, 'store', 'id');
    }

    public function pesqCategoryProduct()
    {
        return $this->belongsTo(mdCategoriesProduct::class, 'category_product', 'id');
    }

    public function pesqFirstImageKit()
    {
        return $this->hasOne(mdImagensKits::class, 'kit', 'id');
    }

    public static function pesqKits($pStore = null, $pCategoriesProduct = null )
    {
       if(is_null($pCategoriesProduct)){
           return self::select('kits.*')
               ->join('rel_kits_products', 'kits.id', '=', 'rel_kits_products.kit')
               ->where('kits.store', $pStore)
               ->where('kits.status', 'S')
               ->whereExists(function ($query) {
                   $query->select(DB::raw(1))
                       ->from('products')
                       ->whereColumn('products.id', 'rel_kits_products.product')
                       ->where('products.status', 'S')
                       ->whereColumn('products.store', 'kits.store');
               })
               ->groupBy('kits.id')
               ->orderBy('kits.id', 'asc')->get();
       } else {
           return self::select('kits.*')
               ->join('rel_kits_products', 'kits.id', '=', 'rel_kits_products.kit')
               ->where('kits.category_product', $pCategoriesProduct)
               ->where('kits.store', $pStore)
               ->where('kits.status', 'S')
               ->whereExists(function ($query) {
                   $query->select(DB::raw(1))
                       ->from('products')
                       ->whereColumn('products.id', 'rel_kits_products.product')
                       ->where('products.status', 'S')
                       ->where('products.store', 'kits.store');
               })
               ->groupBy('kits.id')
               ->orderBy('kits.id', 'asc')->get();
       }
    }
}
