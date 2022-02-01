<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class mdStores extends Model
{
    protected $table = 'stores';
    public $alterStatusManual = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setZip_codeAttribute($value)
    {
        $this->attributes['zip_code'] = preg_replace('/\D+/', '', $value);
    }

    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = preg_replace('/\D+/', '', $value);
    }

    public function setFoneAttribute($value)
    {
        $this->attributes['fone1'] = preg_replace('/\D+/', '', $value);
        $this->attributes['fone2'] = preg_replace('/\D+/', '', $value);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if($this->alterStatusManual){
            if($this->attributes['status'] == 'N'){

                $storesByAffiliate = mdAffiliates::find($this->attributes['affiliate'])->pesqStoresByAffiliate('S', $this->id, '<>')->get();
                $storesBySegment = mdSegments::find($this->attributes['segment'])->pesqStoresBySegment('S', null, $this->id, '<>')->get();

                if(count($storesByAffiliate) == 0){
                    $affiliate = $this->pesqAffiliate()->first();

                    if($affiliate->status == 'S'){
                        $affiliate->status = $this->attributes['status'];

                        try {
                            if(!$affiliate->save()){
                                throw new \ErrorException('Erro ao alterar o status do Affiliado ID ('.$affiliate->id.').');
                            }
                        } catch (\Exception $exception) {
                            throw new \ErrorException('Erro ao alterar o status do Affiliado ID ('.$affiliate->id.').');
                        } finally {
                            unset($affiliate);
                        }
                    }
                }

                if(count($storesBySegment) == 0){
                    $segment = $this->pesqSegment()->first();

                    if($segment->status == 'S'){
                        $segment->status = $this->attributes['status'];

                        try {
                            if(!$segment->save()){
                                throw new \ErrorException('Erro ao alterar o status do Segmento ID ('.$segment->id.').');
                            }
                        } catch (\Exception $exception) {
                            throw new \ErrorException('Erro ao alterar o status do Segmento ID ('.$segment->id.').');
                        } finally {
                            unset($segment);
                        }
                    }
                }

            } else {
                $affiliate = $this->pesqAffiliate()->first();
                $segment = $this->pesqSegment()->first();

                if($affiliate->status == 'N'){
                    $affiliate->status = $this->attributes['status'];
                    try {
                        if(!$affiliate->save()){
                            throw new \ErrorException('Erro ao alterar o status do Affiliado ID ('.$affiliate->id.').');
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao alterar o status do Affiliado ID ('.$affiliate->id.').');
                    } finally {
                        unset($affiliate);
                    }
                }
                if($segment->status == 'N'){
                    $segment->status = $this->attributes['status'];
                    try {
                        if(!$segment->save()){
                            throw new \ErrorException('Erro ao alterar o status do Segmento ID ('.$segment->id.').');
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao alterar o status do Segmento ID ('.$segment->id.').');
                    } finally {
                        unset($segment);
                    }
                }
            }
        }

    }

    public function getMinimumOrderAttribute()
    {
        return number_format($this->attributes['minimum_order'],2);
    }

    public function getMinimumShippingAttribute()
    {
        return number_format($this->attributes['minimum_shipping'],2);
    }

    public function pesqCity()
    {
        return $this->belongsTo(mdCities::class, 'city', 'id');
    }

    public static function pesqStoreOrderByAffiliate()
    {
        return self::where('status', 'S')->orderBy('affiliate','asc')->orderBy('id','asc')->get();
    }

    public function pesqAffiliate()
    {
        return $this->belongsTo(mdAffiliates::class, 'affiliate', 'id');
    }

    public function pesqSegment()
    {
        return $this->belongsTo(mdSegments::class, 'segment', 'id');
    }

    public function allCategoriesByStore()
    {
        return $this->belongsToMany( mdCategoriesStore::class, 'rel_categories_stores', 'store', 'category');
    }

    public function pesqCategoriesByStore($pStatus = 'S')
    {
        $pStatus = strtoupper($pStatus);

        return $this->allCategoriesByStore()->where('status', $pStatus);
    }

    /*public function allCategoriesProductByStore()
    {
        return $this->belongsToMany( mdCategoriesProduct::class, 'rel_stores_categoriesproduct', 'store', 'category_product');
    }*/

    public function allCategoriesProductByStore()
    {
        return $this->hasMany(mdCategoriesProduct::class, 'store', 'id');
    }

    public function pesqCategoriesProductByStore($pStatus = 'S', $pJoinProduct = false)
    {

        $pStatus = strtoupper($pStatus);

        $categoriesProduct = $this->allCategoriesProductByStore()->where('status', $pStatus);

        if(!$pJoinProduct){
            return $categoriesProduct;
        } else {
            return $categoriesProduct->select(
                'categoriesproduct.*'
            )->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('products')
                    ->whereColumn('products.category_product', 'categoriesproduct.id')
                    ->where('products.status', 'S')
                    ->where('products.store', $this->id)
                    ->orWhereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('kits')
                            ->join('rel_kits_products', 'kits.id', '=', 'rel_kits_products.kit')
                            ->join('products', 'products.id', '=', 'rel_kits_products.product')
                            ->whereColumn('kits.category_product', 'categoriesproduct.id')
                            ->where('kits.status', 'S')
                            ->where('kits.store', $this->id)
                            ->where('products.status', 'S')
                            ->where('products.store', $this->id);
                    });
            })
            ->orderBy('categoriesproduct.n_order', 'asc');
        }
    }

    public function allKitsByStore()
    {
        return $this->hasMany(mdKits::class,'store','id');
    }

    public function pesqKitsByStore($pStatus = 'S', $pCategoryProduct = null, $pExistsProduct = false, $pOrderByField = null, $pOrderBy = 'asc')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pCategoryProduct)){
            $kits = $this->allKitsByStore()->where('kits.status', $pStatus);
        } else {
            $kits = $this->allKitsByStore()->where('kits.status', $pStatus)->where('kits.category_product', $pCategoryProduct);
        }

        if(!$pExistsProduct){
            $kits;
        } else {
            $kits->select(
                'kits.*'
                )->join('rel_kits_products', 'kits.id', '=', 'rel_kits_products.kit')
                ->join('categoriesproduct', 'kits.category_product', '=', 'categoriesproduct.id')
                ->where('categoriesproduct.status', $pStatus)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereColumn('products.id', 'rel_kits_products.product')
                        ->where('products.status', 'S')
                        ->where('products.store', $this->id);
                })
                ->groupBy('kits.id');
        }

        if(is_null($pOrderByField)){
            $kits->orderBy('categoriesproduct.n_order', $pOrderBy);
        }else{
            $kits->orderBy('kits.'.$pOrderByField, $pOrderBy);
        }

        return $kits;

    }

    public function allProductsByStore()
    {
        return $this->hasMany(mdProducts::class,'store','id');
    }

    public function pesqProductsByStore($pStatus = 'S', $pCategoryProduct = null, $pJoinCategoryProduct = false, $pOrderByField = null, $pOrderBy = 'asc')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pCategoryProduct)){
            $products = $this->allProductsByStore()->where('products.status', $pStatus);
        } else {
            $products = $this->allProductsByStore()->where('products.status', $pStatus)->where('products.category_product', $pCategoryProduct);
        }

        if(!$pJoinCategoryProduct){
             $products;
        } else {
             $products->select(
                    'products.*'
            )->join('categoriesproduct', 'products.category_product', '=', 'categoriesproduct.id')
            ->where('categoriesproduct.status', $pStatus);
        }

        if(is_null($pOrderByField)){
            $products->orderBy('categoriesproduct.n_order', $pOrderBy);
        }else{
            $products->orderBy('products.'.$pOrderByField, $pOrderBy);
        }

        return $products;

    }
}
