<div class="row mb-1">
    <div class="col-lg-auto">
        <div class="icheck-primary d-inline">
            <input type="checkbox" id="idckbFilterObject" name="ckbFilterObject" value="filterobject">
            <label for="idckbFilterObject">Habilitar pesquisa por filtro</label>
            </label>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-lg-auto">
        <label for="selectFilterObject">Filtrar por</label>
        <select id="idSelectFilterObject" name="opFilterObject" class="custom-select d-block w-100">
            @if(isset($optionFilterObject))
                <option value="1" {{(($optionFilterObject == '1') ? "selected" : "")}}>ID</option>
                <option value="2" {{(($optionFilterObject == '2') ? "selected" : "")}}>ID PDV</option>
                <option value="3" {{(($optionFilterObject == '3') ? "selected" : "")}}>Código PDV</option>
                <option value="4" {{(($optionFilterObject == '4') ? "selected" : "")}}>Código de barras PDV</option>
                <option value="5" {{(($optionFilterObject == '5') ? "selected" : "")}}>Descrição</option>
                <option value="6" {{(($optionFilterObject == '6') ? "selected" : "")}}>Categoria apenas</option>
            @else
                <option value="1">ID</option>
                <option value="2">ID PDV</option>
                <option value="3">Código PDV</option>
                <option value="4">Código de barras PDV</option>
                <option value="5">Descrição</option>
                <option value="6">Categoria apenas</option>
            @endif
        </select>
    </div>
    <div class="col-lg-4">
        <label id="idLblFilterObject" for="inputFilterObject">ID</label>
        <input value="{{((isset($txtFilterObject)) ? $txtFilterObject : "")}}" type="text" class="form-control" id="idtxtFilterObject"  name="txtFilterObject" >
    </div>
    <div class="col-lg-3">
        <label id="idLblFilterCategoria" for="selectFilterCategoria">Categoria</label>
        <select id="idSelectFilterCategoria" name="opFilterCategoria" autofocus class="custom-select d-block w-100">
            <option value="T">Todos</option>
            @foreach($listCategoriesProduct as $CategorieProduct)
                @if(isset($optionFilterCategoria))
                    <option value="{{ $CategorieProduct->id }}"
                    <?php echo(($optionFilterCategoria == $CategorieProduct->id) ? "selected" : "")?>
                    >{{ $CategorieProduct->name }}</option>
                @else
                    <option value="{{ $CategorieProduct->id }}">{{ $CategorieProduct->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
