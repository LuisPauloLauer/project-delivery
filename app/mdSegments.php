<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class mdSegments extends Model
{
    protected $table = 'segments';
    public $alterStatusManual = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;

        if($this->alterStatusManual){

            $storesBysegment = $this->allStoresBySegment()->get();

            if (count($storesBysegment) > 0) {
                for ($i = 0; $i < count($storesBysegment); $i++) {

                    $store = $storesBysegment[$i];
                    $store->status = $this->attributes['status'];

                    try {
                        if (!$store->save()) {
                            throw new \ErrorException('Erro ao alterar o status da loja ID (' . $storesBysegment[$i]->id . ').');
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao alterar o status da loja ID (' . $storesBysegment[$i]->id . ').');
                    } finally {
                        unset($store);
                    }

                    $affiliate = mdStores::find($storesBysegment[$i]->id)->pesqAffiliate;

                    $affiliate->status = $this->attributes['status'];

                    try {
                        if (!$affiliate->save()) {
                            throw new \ErrorException('Erro ao alterar o status do Affiliado ID (' . $affiliate->id . ').');
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao alterar o status do Affiliado ID (' . $affiliate->id . ').');
                    } finally {
                        unset($affiliate);
                    }
                }
            }
        }
    }

    public static function pesqSegments($pStatus = 'S')
    {
        return self::select('segments.*')
        ->join('stores', 'segments.id', '=', 'stores.segment')
        ->join('affiliates', 'stores.affiliate', '=', 'affiliates.id')
        ->where('segments.status', $pStatus)
        ->where('stores.status', $pStatus)
        ->where('stores.active_store_site', $pStatus)
        ->where('affiliates.status', $pStatus)
        ->groupBy('segments.id')
        ->orderBy('segments.id')->get();
    }

    public function allStoresBySegment()
    {
        return $this->hasMany(mdStores::class,'segment','id');
    }

    public function pesqStoresBySegment($pStatus = 'S', $pActiveStoreSite = null, $pStore = null, $pOperator = '=')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pActiveStoreSite)){
            //Result of Web Painel Admin/dashboard
            if(is_null($pStore)){
                return $this->allStoresBySegment()->where('stores.status', $pStatus);
            } else {
                return $this->allStoresBySegment()->where('stores.status', $pStatus)->where('stores.id', $pOperator, $pStore);
            }
        } else {
            //Result of WebSite
            return $this->allStoresBySegment()->
                select('stores.*')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('rel_categories_stores as cst')
                        ->join('categoriesstore', 'cst.category', '=', 'categoriesstore.id')
                        ->whereColumn('cst.store', 'stores.id')
                        ->where('categoriesstore.status', '=', 'S');
                })
                ->where('stores.status', $pOperator, $pStatus)
                ->where('stores.active_store_site', $pOperator, $pActiveStoreSite);
        }
    }
}
