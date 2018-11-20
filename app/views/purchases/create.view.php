<form class="f-row form-style purchases-invoice-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>

    <div class="input-group-s radio-g col-md-down-1 col-md-up-2">
        <label >{ label_payment_type } :</label>
        <label class="checkmark-p" for="1">
            <input type="radio" id="1" value="1" name="payment_type" @if ($this->getPost('payment_type') == '1') checked @endif  >
            { array_payment_type[1] }
        </label>
        <label class="checkmark-p" for="2">
            <input type="radio" id="2" value="2" name="payment_type" @if ($this->getPost('payment_type') == '2') checked @endif >
            { array_payment_type[2] }
        </label>
        <label class="checkmark-p" for="3">
            <input type="radio" id="3" value="3" name="payment_type" @if ($this->getPost('payment_type') == '3') checked @endif >
            { array_payment_type[3] }
        </label>
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_discount }</label>
        <input type="text" name="discount" value="@post (discount)" max="10"  data-pattern="^[0-9]{1,18}(\.[0-9]{1,8})?$|^[0-9]{1,3}(\.[0-9]{1,2})?%$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_supplier_name }</label>
        <select name="supplier_name">
            <option value="0" disabled selected>{ label_supplier_name }</option>
            @foreach (#Suppliers as $supplier)
                <option value="{! $supplier->SupplierId; !}" @if ($this->getPost('supplier_name') == $supplier->SupplierId): selected @endif >{! $supplier->FirstName _ $supplier->LastName; !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_product_name }</label>
        <select name="product_name">
            <option value="0" disabled selected>{ label_product_name }</option>
            @foreach (#Products as $Product)
                <option value="{! $Product->ProductId !}" data-quantity="@number_zero ($Product->Quantity)" data-quantity-name="{! $Product->UnitName !}" data-price="@currency_input ($Product->BuyPrice)" @if ($this->getPost('product_name') == $Product->ProductId): selected @endif >{! $Product->Title; !}</option>
            @endforeach
        </select>
    </div>


    @if (self::has_post('product_id') && !empty(self::getPost('product_id')[0]))
    @foreach ($_POST['product_id'] as $key => $products)

    <div class="action-product f-row w-100" id="action-product">
        <div class="input-group-s col-3 name">
            <label class="is_focus">{ label_product_name }</label>
            <label data-cut-title="35" class="name-label">@if (\Store\Models\ProductsModel::getColByKey('Title',$products)): {! \Store\Models\ProductsModel::getColByKey('Title',$products) !} @else { text_product_notfound } @endif </label>
            <input type="hidden" name="product_id[]" value="{! $products !}" >
        </div>

        <div class="input-group-s col-3 b-input-full quantity">
            <label >{ label_quantity_in }</label>
            <input type="text" name="quantity[]" value="{! $_POST['quantity'][$key] !}" max="10"  data-pattern="^[0-9]{1,16}(\.[0-9]{1,6})?$" >
        </div>

        <div class="input-group-s col-3 price">
            <label >{ label_price }</label>
            <input type="text" name="price[]" value="{! $_POST['price'][$key] !}" max="10"  data-pattern="^(?=^.{1,10}$)[0-9]+(\.[0-9]{1,8})?$" >
        </div>
        <span class="action-close"><i class="far fa-trash-alt"></i></span>
    </div>

    @endforeach
    @else

    <div class="action-product f-row w-100 d-none" id="action-product">
        <div class="input-group-s col-3 name">
            <label class="is_focus">{ label_product_name }</label>
            <label data-cut-title="35" class="name-label">{ text_product_notfound }</label>
            <input type="hidden" name="product_id[]" >
        </div>

        <div class="input-group-s col-3 b-input-full quantity">
            <label >{ label_quantity_in }</label>
            <input type="text" name="quantity[]"  max="10"  data-pattern="^[0-9]{1,16}(\.[0-9]{1,6})?$" >
        </div>

        <div class="input-group-s col-3 price">
            <label >{ label_price }</label>
            <input type="text" name="price[]"  max="10"  data-pattern="^(?=^.{1,10}$)[0-9]+(\.[0-9]{1,8})?$" >
        </div>
        <span class="action-close"><i class="far fa-trash-alt"></i></span>
    </div>


    @endif

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>


</form>

