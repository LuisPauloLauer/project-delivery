<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdAffiliates extends Model
{
    protected $table = 'affiliates';
    public $alterStatusManual = false;

    public function setZipCodeAttribute($value)
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

            $allStores = $this->allStoresByAffiliate()->get();

            for ($i=0; $i < count($allStores); $i++ ){
                $store =  $allStores[$i];
                $store->alterStatusManual   = true;
                $store->status              = $this->attributes['status'];

                try {
                    if(!$store->save()){
                        throw new \ErrorException('Erro ao alterar o status da loja ID ('.$allStores[$i]->id.').');
                    }
                } catch (\Exception $exception) {
                    throw new \ErrorException('Erro ao alterar o status da loja ID ('.$allStores[$i]->id.').');
                } finally {
                    unset($store);
                }

            }
        }

    }

    public function pesqTpAffiliate()
    {
        return $this->belongsTo(mdTpAffiliates::class, 'tpaffiliate', 'id');
    }

    public function allStoresByAffiliate()
    {
        return $this->hasMany(mdStores::class, 'affiliate', 'id');
    }

    public function pesqStoresByAffiliate($pStatus = 'S', $pStore = null, $pOperator = '=')
    {
        $pStatus = strtoupper($pStatus);

        if(is_null($pStore)){
            return $stores = $this->allStoresByAffiliate()->where('stores.status', $pStatus);
        } else {
            return $stores = $this->allStoresByAffiliate()->where('stores.status', $pStatus)->where('stores.id', $pOperator, $pStore);
        }

    }
}
