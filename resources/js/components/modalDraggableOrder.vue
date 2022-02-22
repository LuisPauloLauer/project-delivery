<template>
    <div id="idModalAlterOrdem" class="modal sort-menu-items-modal" @click.self="onCloseModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-sortable">
                <div class="modal-header">
                    <h2 class="modal-title">Alterar ordem</h2>
                    <button type="button" class="close" @click="onCloseModal" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="sortable-title-info">
                        <h6>Clique na categoria para listar os kits e produtos. Para alterar a ordem das categorias, kits e produtos segure no ícone <i class="fa fa-arrows"></i> e arraste para cima ou para baixo, após clique em salvar.</h6>
                    </div>
                    <div class="sortable-container-itens">
                        <div class="sortable-content-itens">
                            <div class="sortable-content-menu-header-itens">
                                <div class="sortable-content-menu-column-itens sortable-content-menu-group sortable-content-menu-group-categories">
                                    <div class="sortable-content-menu-group-title">
                                        Categorias
                                    </div>
                                    <div class="sortable-content-menu-group-body">
                                        <draggable class="sortable-content-menu-group-body-list-itens" :list="categoriesProductNew" :options="{animation:200, handle:'.my-handle'}" :element="'div'" @change="updateOrderCategory(false)">
                                            <div v-for="(categoriesproduct, index) in categoriesProductNew">
                                                <div class="sortable-item sortable-item--clickable" v-bind:class="{ itemactive: categoriesproduct.active }" @click="listKitsAndProductsByCategory($event, categoriesproduct.id)">
                                                    <i style="width: 25px;" class="fa fa-arrows my-handle"></i>
                                                    <span style="width: 25px;">{{ categoriesproduct.n_order }}</span>
                                                    <span :title="categoriesproduct.name" class="sortable-item--name sortable-item--truncate" style="-webkit-line-clamp: 1; display: -webkit-box;">{{ categoriesproduct.name }}</span>
                                                </div>
                                            </div>
                                        </draggable>
                                    </div>
                                </div>
                                <div class="sortable-content-menu-column-itens sortable-content-menu-group sortable-content-menu-group-kits">
                                    <div class="sortable-content-menu-group-title">
                                        Kits
                                    </div>
                                    <div class="sortable-content-menu-group-body">
                                        <draggable class="sortable-content-menu-group-body-list-itens" :list="kitsNew" :options="{animation:200, handle:'.my-handle'}" :element="'div'" @change="updateOrderKit(false)">
                                            <div v-for="(kit, index) in kitsNew">
                                                <div class="sortable-item sortable-item--clickable">
                                                    <i style="width: 25px;" class="fa fa-arrows my-handle"></i>
                                                    <span style="width: 25px;">{{ kit.n_order }}</span>
                                                    <span :title="kit.name" class="sortable-item--name sortable-item--truncate" style="-webkit-line-clamp: 1; display: -webkit-box;">{{ kit.name }}</span>
                                                </div>
                                            </div>
                                        </draggable>
                                    </div>
                                </div>
                                <div class="sortable-content-menu-column-itens sortable-content-menu-group sortable-content-menu-group-products">
                                    <div class="sortable-content-menu-group-title">
                                        Produtos
                                    </div>
                                    <div class="sortable-content-menu-group-body">
                                        <draggable class="sortable-content-menu-group-body-list-itens" :list="productsNew" :options="{animation:200, handle:'.my-handle'}" :element="'div'" @change="updateOrderProduct(false)">
                                            <div v-for="(product, index) in productsNew">
                                                <div class="sortable-item sortable-item--clickable">
                                                    <i style="width: 25px;" class="fa fa-arrows my-handle"></i>
                                                    <span style="width: 25px;">{{ product.n_order }}</span>
                                                    <span :title="product.name" class="sortable-item--name sortable-item--truncate" style="-webkit-line-clamp: 1; display: -webkit-box;">{{ product.name }}</span>
                                                </div>
                                            </div>
                                        </draggable>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="idsaveOrder" class="btn btn-success" :disabled="isDisableSaveOrder" @click="saveOrder">Salvar</button>
                    <button type="button" class="btn btn-secondary" @click="onCloseModal" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';
    export default {
        components: {
            draggable
        },
        props: ['categoriesproduct'],
        data() {
            return {
                categoriesProductNew: this.categoriesproduct,
                kitsNew: [],
                productsNew: [],
                isCategoryChange: false,
                isKitChange: false,
                isProductChange: false,
                initOpenModal : false,
                isDisableSaveOrder: true,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content
            }
        },
        methods: {
            updateOrderCategory(pDisableSaveOrder) {
                if(!pDisableSaveOrder){
                    this.isCategoryChange = true;
                }
                this.categoriesProductNew.map((categoriesproduct, index) => {
                    if(this.isDisableSaveOrder){
                        categoriesproduct.n_order_old = categoriesproduct.n_order;
                    }
                    categoriesproduct.n_order = index + 1;
                });
                this.isDisableSaveOrder = pDisableSaveOrder;
            },
            updateOrderKit(pDisableSaveOrder) {
                this.isKitChange = true;
                this.kitsNew.map((kit, index) => {
                    kit.n_order = index + 1;
                });
                this.isDisableSaveOrder = pDisableSaveOrder;
            },
            updateOrderProduct(pDisableSaveOrder) {
                this.isProductChange = true;
                this.productsNew.map((product, index) => {
                    product.n_order = index + 1;
                });
                this.isDisableSaveOrder = pDisableSaveOrder;
            },
            listKitsAndProductsByCategory(e, idCategory){

                this.categoriesProductNew.map((categoriesproduct, index) => {

                    if(categoriesproduct.id === idCategory){
                        categoriesproduct.active = true;
                    } else {
                        categoriesproduct.active = false;
                    }

                });

                this.initOpenModal = true;

                axios.post( window.location.href+'/listitensbycategory', {
                    idCategory: idCategory
                }).then(response => {
                    if(response.data.success === true){
                        if(response.data.kits){
                            this.kitsNew = response.data.kits;
                        } else {
                            this.kitsNew = [];
                            this.isKitChange = false;
                        }
                        if(response.data.products){
                            this.productsNew = response.data.products;
                        } else {
                            this.productsNew = [];
                            this.isProductChange = false;
                        }
                    } else {
                        alert(response.data.message);
                    }
                }).catch(error => {
                        alert('Erro ao buscar por categoria!');
                        console.log(error.response);
                });

            },
            async saveOrder(){
                var msgCategory = null,
                    msgKit = null,
                    msgProduct = null,
                    bCategory = true,
                    bKit = true,
                    bProduct = true;

                if(this.isCategoryChange){
                    try {
                        const response = await axios.post(window.location.href+'/change/order', {
                            categoriesProduct: this.categoriesproduct
                        });
                        msgCategory = response.data.message;
                        bCategory = response.data.success;
                        this.isCategoryChange = false;
                    } catch (error) {
                        if (error.response) {
                            // Request made and server responded
                            //msgCategory = error.response.data.message;
                            msgCategory = 'Erro ao alterar ordem da categoria!';
                            bCategory = false;
                            //console.log(error.response.status);
                            //console.log(error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            //msgCategory = error.request;
                            msgCategory = 'Erro ao alterar ordem da categoria!';
                            bCategory = false;
                            //console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            //msgCategory = error.message;
                            msgCategory = 'Erro ao alterar ordem da categoria!';
                            bCategory = false;
                            //console.log('Error', error.message);
                        }
                    }
                } else {
                    bCategory = false;
                }
                if(this.isKitChange){
                    try {
                        const response = await axios.post(window.location.href+'/kits/change/order', {
                            kits: this.kitsNew
                        });
                        msgKit = response.data.message;
                        bKit = response.data.success;
                        this.isKitChange = false;
                    } catch (error) {
                        if (error.response) {
                            // Request made and server responded
                            //msgCategory = error.response.data.message;
                            msgKit = 'Erro ao alterar ordem do Kit!';
                            bKit = false;
                            //console.log(error.response.status);
                            //console.log(error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            //msgCategory = error.request;
                            msgKit = 'Erro ao alterar ordem do Kit!';
                            bKit = false;
                            //console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            //msgCategory = error.message;
                            msgKit = 'Erro ao alterar ordem do Kit!';
                            bKit = false;
                            //console.log('Error', error.message);
                        }
                    }
                } else {
                    bKit = false;
                }
                if(this.isProductChange){
                    try {
                        const response = await axios.post(window.location.href+'/products/change/order', {
                            products: this.productsNew
                        });
                        msgProduct = response.data.message;
                        bProduct = response.data.success;
                        this.isProductChange = false;
                    } catch (error) {
                        if (error.response) {
                            // Request made and server responded
                            //msgCategory = error.response.data.message;
                            msgProduct = 'Erro ao alterar ordem do Produto!';
                            bProduct = false;
                            //console.log(error.response.status);
                            //console.log(error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            //msgCategory = error.request;
                            msgProduct = 'Erro ao alterar ordem do Produto!';
                            bProduct = false;
                            //console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            //msgCategory = error.message;
                            msgProduct = 'Erro ao alterar ordem do Produto!';
                            bProduct = false;
                            //console.log('Error', error.message);
                        }
                    }
                } else {
                    bProduct = false;
                }

                if(bCategory || bKit || bProduct){
                    //toastr.success((msgCategory ? msgCategory+'\n' : '')+(msgKit ? msgKit+'\n' : "")+(msgProduct ? msgProduct : ""));
                    alert((msgCategory ? msgCategory+'\n' : '')+(msgKit ? msgKit+'\n' : "")+(msgProduct ? msgProduct : ""));
                    document.location.reload(true);
                } else {
                    //toastr.success((msgCategory ? msgCategory+'\n' : '')+(msgKit ? msgKit+'\n' : "")+(msgProduct ? msgProduct : ""));
                    alert((msgCategory ? msgCategory+'\n' : '')+(msgKit ? msgKit+'\n' : "")+(msgProduct ? msgProduct : ""));
                }

            },
            clearComponents(){
                this.categoriesProductNew.map((categoriesproduct, index) => {
                    categoriesproduct.active = false;
                });
            },
            onCloseModal(){
                if(this.initOpenModal){
                    this.kitsNew = [];
                    this.productsNew = [];
                    this.clearComponents();
                }
                if(!this.isDisableSaveOrder){
                    this.isCategoryChange = false;
                    this.isKitChange = false;
                    this.isProductChange = false;
                    this.categoriesproduct = this.categoriesproduct.sort((a, b) => a.n_order_old - b.n_order_old);
                    this.updateOrderCategory(true);
                }
            }
        },
        mounted() {
            //console.log(window.location.href);
        }
    }
</script>
<style>
    .sortable-title-info {
        display: -webkit-flex;
        display: flex;
        margin-top: 24px;
        padding: 0 16px;
    }
    .sortable-container-itens {
        display: -webkit-flex;
        display: flex;
        margin-top: 24px;
        padding: 0 16px;
        background: #fff;
        height: 385px;
    }
    .sortable-content-itens {
        display: contents;
    }
    .sortable-content-menu-header-itens {
        display: -webkit-flex;
        display: flex;
        width: 100%;
        background-color: #fbfbfb;
        border: 1px solid #f7f7f7;
        border-radius: 6px;
    }
    .sortable-content-menu-column-itens {
        width: calc(100% / 3);
        -webkit-flex: 0 0 calc(100% / 3);
        flex: 0 0 calc(100% / 3);
        border-right: 1px solid #eee;
        border-bottom: 1px solid #eee;
        overflow: hidden;
    }
    .sortable-content-menu-group {
        transition: all .2s ease-in-out;
    }
    .sortable-content-menu-group-title {
        padding: 8px 16px;
        border-bottom: 1px solid hsla(0,0%,58.4%,.4);
        font-size: 14px;
        font-weight: 500;
        background: #f8f8f8;
        color: #3e3e3e;
        height: 48px;
        display: -webkit-flex;
        display: flex;
        -webkit-align-items: center;
        align-items: center;
        -webkit-justify-content: space-between;
        justify-content: space-between;
    }
    .sort-menu-items-modal .sortable-content-menu-group-body {
        overflow-y: auto;
    }
    .sortable-content-menu-group-body {
        height: calc(100% - 48px);
    }
    .sortable-item {
        display: -webkit-flex;
        display: flex;
        -webkit-align-items: center;
        align-items: center;
        padding: 12px 8px 12px 16px;
        font-size: 12px;
        line-height: 1.57;
        background: #fff;
        border-bottom: 1px solid #eee;
    }
    .sortable-item:not(.sortable-chosen):hover {
        color: #ea1d2c;
    }
    .sortable-item--clickable {
        cursor: pointer;
    }
    .sortable-item--truncate {
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        -webkit-box-orient: vertical;
    }
    .my-handle:hover {
        cursor: move;
    }
    .itemactive {
        color: #3e3e3e;
        font-weight: 700;
        background: #f2f2f2;
    }
    .sort-menu-items-modal .sortable-chosen{
        padding: 2px 6px;
        margin: 12px 0;
        border: 1px solid #28a745;
        border-radius: 4px;
        box-shadow:0 2px 6px 0 rgba(0,0,0,0.1);
        background: #f2f2f2;
        color: #28a745;
    }
    @media (max-width: 991px) {
        .sortable-content-menu-header-itens{
            width: 900px;
        }
        .modal-content-sortable{
            width: 100%;
            margin: 0;
        }
        .sortable-content-itens {
            display: block;
            width: 100%;
            overflow-y: scroll;
        }
    }
</style>
