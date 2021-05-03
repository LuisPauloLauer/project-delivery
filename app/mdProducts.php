<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class mdProducts extends Model
{
    protected $table = 'products';
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

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = preg_replace('/\D+/', '', $value);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if($this->alterStatusManual){
            if($this->attributes['status'] == 'N'){

                $productsByCategoryProduct = mdCategoriesProduct::find($this->attributes['category_product'])->pesqProductsByCategoryProduct('S', $this->id, '<>')->get();

                if(count($productsByCategoryProduct) == 0){
                    $categoriesProduct = $this->pesqCategoryProduct()->first();

                    if($categoriesProduct->status == 'S'){
                        $categoriesProduct->status = $this->attributes['status'];

                        try {
                            if(!$categoriesProduct->save()){
                                throw new \ErrorException('Erro ao alterar o status da Categoria ID ('.$categoriesProduct->id.').');
                            }
                        } catch (\Exception $exception) {
                            throw new \ErrorException('Erro ao alterar o status da Categoria ID ('.$categoriesProduct->id.').');
                        } finally {
                            unset($categoriesProduct);
                        }
                    }
                }

            } else {
                $categoriesProduct = $this->pesqCategoryProduct()->first();

                if($categoriesProduct->status == 'N'){
                    $categoriesProduct->status = $this->attributes['status'];
                    try {
                        if(!$categoriesProduct->save()){
                            throw new \ErrorException('Erro ao alterar o status da Categoria ID ('.$categoriesProduct->id.').');
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao alterar o status da Categoria ID ('.$categoriesProduct->id.').');
                    } finally {
                        unset($categoriesProduct);
                    }
                }

            }
        }

    }

    public function getAmountAttribute()
    {
        return round($this->attributes['amount'], 4);
    }

    public function getUnitPriceAttribute()
    {
      //  return round($this->attributes['unit_price'], 2);
        return number_format($this->attributes['unit_price'],2);
    }

    public function getUnitPromotionPriceAttribute()
    {
      //  return round($this->attributes['unit_promotion_price'], 2);
        return number_format($this->attributes['unit_promotion_price'],2);
    }

    public static function pesqNameProduct($id) : string
    {
        //$product = mdProducts::where('id', $id)->get();
        $product = mdProducts::findOrFail($id);

        return $product->name;
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

    public function allkitsByProduct()
    {
        return $this->belongsToMany( mdKits::class, 'rel_kits_products', 'product', 'kit');
    }

    public function pesqFirstImageProduct()
    {
        return $this->hasOne(mdImagensProducts::class, 'product', 'id');
    }

    public static function pesqSubItensOfKit($pSubItens)
    {
        $arraySubItens = [];
        $countArraySubItens = 0;
        for ($i = 0; $i < strlen($pSubItens); $i++) {
            $subItensIdProduct = '';
            for ($j = $i; $j < strlen($pSubItens); $j++) {
                if ($pSubItens[$j] <> ',') {
                    $i++;
                    $subItensIdProduct = $subItensIdProduct . $pSubItens[$j];
                } else {
                    $i = $j;
                    break;
                }
            }
            $countArraySubItens++;

            $product = mdProducts::findOrFail($subItensIdProduct);

            $arraySubItens[$countArraySubItens] = array(
                "product_id" => $product->id,
                "product_name" => $product->name,
                "product_unit_price" => $product->unit_price,
                "product_unit_promotion_price" => $product->unit_promotion_price,

            );
        }

        return $arraySubItens;
    }
}
