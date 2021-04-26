<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Library\FilesControl;
use App\mdCategoriesProduct;
use App\mdKits;
use App\mdSegments;
use App\mdStores;

class pageStoresController extends Controller
{
    public function showStore($segment = null, $store = null)
    {
        $pathImagens = FilesControl::getPathImages();

        if( !is_null($segment) && !is_null($store) ){

            if(mdSegments::where('slug', $segment)->exists()) {

                if (mdStores::where('slug', $store)->exists()) {

                    $Segment = mdSegments::where('slug', $segment)->first();

                    $Store = mdStores::where('slug', $store)->first();

                    /*$Kits = DB::select('select
                                            k.id,
                                            k.status,
                                            k.store,
                                            k.category_product,
                                            k.name,
                                            k.slug,
                                            k.amount,
                                            k.unit_price,
                                            k.unit_promotion_price,
                                            k.unit_discount,
                                            k.description,
                                            k.views,
                                            k.sold,
                                            k.created_at,
                                            k.updated_at
                                        from
                                            kits as k,
                                            products as p,
                                            rel_kits_products rel,
                                            stores s
                                         where
                                            k.id = rel.kit
                                            and p.id = rel.product
                                            and s.id = k.store
                                            and s.id = p.store
                                            and s.status = "S"
                                            and k.status = "S"
                                            and p.status = "S"
                                            and k.store = ' . $Store->id . '
                                            and p.store = ' . $Store->id . '
                                        group by
                                            k.id,
                                            k.status,
                                            k.store,
                                            k.category_product,
                                            k.name,
                                            k.slug,
                                            k.amount,
                                            k.unit_price,
                                            k.unit_promotion_price,
                                            k.unit_discount,
                                            k.description,
                                            k.views,
                                            k.sold,
                                            k.created_at,
                                            k.updated_at'); */

                    $Kits = mdStores::find($Store->id)->pesqKitsByStore('S', null, true)->get();

                    $Products = mdStores::find($Store->id)->pesqProductsByStore('S', null, true)->get();

                    $CategoriesProducts = mdStores::find($Store->id)->pesqCategoriesProductByStore('S', true)->get();

                  //  dd($CategoriesProducts);

                    $totalObjects = count($Kits) + count($Products);

                    return view('site.store.pageStore', [
                        'Segment'                   => $Segment,
                        'Store'                     => $Store,
                        'listKit'                   => $Kits,
                        'listProduct'               => $Products,
                        'listCategoriesProducts'    => $CategoriesProducts,
                        'totalObjects'              => $totalObjects,
                        'pathImagens'               => $pathImagens
                    ]);
                }
            }
        }
    }

    public function showStoreByCategory($segment = null, $store = null, $category = null)
    {

        $pathImagens = FilesControl::getPathImages();

        if(!is_null($store)){
            if(mdStores::where('slug', $store)->exists()){

                $Segment = mdSegments::where('slug', $segment)->first();
                $Store = mdStores::where('slug', $store)->first();
                $categoriesProduct = mdCategoriesProduct::where('id', $category)->first();

                /*$Kits = DB::select('select
                                            k.id,
                                            k.status,
                                            k.store,
                                            k.category_product,
                                            k.name,
                                            k.slug,
                                            k.amount,
                                            k.unit_price,
                                            k.unit_promotion_price,
                                            k.unit_discount,
                                            k.description,
                                            k.views,
                                            k.sold,
                                            k.created_at,
                                            k.updated_at
                                        from
                                            kits as k,
                                            products as p,
                                            rel_kits_products rel,
                                            stores s
                                         where
                                            k.id = rel.kit
                                            and p.id = rel.product
                                            and s.id = k.store
                                            and s.id = p.store
                                            and s.status = "S"
                                            and k.status = "S"
                                            and p.status = "S"
                                            and k.store = '.$Store->id.'
                                            and p.store = '.$Store->id.'
                                            and k.category_product = '.$category.'
                                        group by
                                            k.id,
                                            k.status,
                                            k.store,
                                            k.category_product,
                                            k.name,
                                            k.slug,
                                            k.amount,
                                            k.unit_price,
                                            k.unit_promotion_price,
                                            k.unit_discount,
                                            k.description,
                                            k.views,
                                            k.sold,
                                            k.created_at,
                                            k.updated_at'); */

               // $Kits = mdKits::pesqKits($Store->id, $categoriesProduct->id);

                $Kits = mdStores::find($Store->id)->pesqKitsByStore('S', $categoriesProduct->id, true)->get();

                $Products = mdStores::find($Store->id)->pesqProductsByStore('S', $categoriesProduct->id)->get();

                $CategoriesProducts = mdStores::find($Store->id)->pesqCategoriesProductByStore('S', true)->get();

                $totalObjects = count(mdKits::pesqKits($Store->id)) + count(mdStores::find($Store->id)->pesqProductsByStore('S', null, true)->get());

                return view('site.store.pageStore',[
                    'Segment'                   =>  $Segment,
                    'Store'                     =>  $Store,
                    'listKit'                   =>  $Kits,
                    'listProduct'               =>  $Products,
                    'listCategoriesProducts'    =>  $CategoriesProducts,
                    'totalObjects'              =>  $totalObjects,
                    'pathImagens'               =>  $pathImagens
                ]);
            }
        }
    }
}
