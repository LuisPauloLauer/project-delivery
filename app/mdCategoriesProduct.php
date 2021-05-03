<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class mdCategoriesProduct extends Model
{
    protected $table = 'categoriesproduct';
    public $alterStatusManual = false;

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

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if($this->alterStatusManual){

            $kits = $this->allKitsByCategoriesProduct()->get();

            for ($i=0; $i < count($kits); $i++ ){
                $kit =  $kits[$i];
                $kit->status = $this->attributes['status'];

                try {
                    if(!$kit->save()){
                        throw new \ErrorException('Erro ao alterar o status do Kit ID ('.$kits[$i]->id.').');
                    }
                } catch (\Exception $exception) {
                    throw new \ErrorException('Erro ao alterar o status do Kit ID ('.$kits[$i]->id.').');
                } finally {
                    unset($kit);
                }

            }

            $products = $this->allProductsByCategoriesProduct()->get();

            for ($i=0; $i < count($products); $i++ ){
                $product =  $products[$i];
                $product->status = $this->attributes['status'];

                try {
                    if(!$product->save()){
                        throw new \ErrorException('Erro ao alterar o status do Produto ID ('.$products[$i]->id.').');
                    }
                } catch (\Exception $exception) {
                    throw new \ErrorException('Erro ao alterar o status do Produto ID ('.$products[$i]->id.').');
                } finally {
                    unset($product);
                }

            }
        }

    }

    /*public function allStoresByCategoriesProduct()
    {
        return $this->belongsToMany( mdStores::class, 'rel_stores_categoriesproduct', 'category_product', 'store');
    }*/

    public function allKitsByCategoriesProduct()
    {
        return $this->hasMany(mdKits::class, 'category_product', 'id');
    }

    public function pesqKitsByCategoryProduct($pStatus = 'S', $pKit = null, $pOperator = '=')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pKit)){
            return $kits = $this->allKitsByCategoriesProduct()->where('kits.status', $pStatus);
        } else {
            return $kits = $this->allKitsByCategoriesProduct()->where('kits.status', $pStatus)->where('kits.id', $pOperator, $pKit);
        }
    }

    public function pesqKitsByCategoriesProduct(mdStores $store, $pStatus = 'S', $pExistsProduct = false)
    {
        $pStatus = strtoupper($pStatus);

        $kits = $this->allKitsByCategoriesProduct()->where('kits.status', $pStatus);

        if(!$pExistsProduct){
            return $kits;
        } else {
            return $kits->select(
                'kits.*'
            )->join('rel_kits_products', 'kits.id', '=', 'rel_kits_products.kit')
                ->join('categoriesproduct', 'kits.category_product', '=', 'categoriesproduct.id')
                ->where('categoriesproduct.status', $pStatus)
                ->where('kits.store', $store->id)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereColumn('products.id', 'rel_kits_products.product')
                        ->where('products.status', 'S')
                        ->whereColumn('products.store', 'kits.store');
                })
                ->groupBy('kits.id')
                ->orderBy('kits.category_product', 'asc')
                ->orderBy('kits.id', 'asc');
        }
    }

    public function allProductsByCategoriesProduct()
    {
        return $this->hasMany(mdProducts::class, 'category_product', 'id');
    }

    public function pesqProductsByCategoryProduct($pStatus = 'S', $pProduct = null, $pOperator = '=')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pProduct)){
            return $products = $this->allProductsByCategoriesProduct()->where('products.status', $pStatus);
        } else {
            return $products = $this->allProductsByCategoriesProduct()->where('products.status', $pStatus)->where('products.id', $pOperator, $pProduct);
        }
    }

    public function pesqProductsByCategoriesProduct(mdStores $store, $pStatus = 'S')
    {
        $pStatus = strtoupper($pStatus);

        $products = $this->allProductsByCategoriesProduct()->where('products.status', $pStatus);

        return $products->select(
            'products.*'
        )->join('categoriesproduct', 'products.category_product', '=', 'categoriesproduct.id')
        ->where('categoriesproduct.status', $pStatus)
        ->where('products.store', $store->id)
        ->orderBy('products.category_product', 'asc')
        ->orderBy('products.id', 'asc');
    }
}
