<form class="f-row form-style products-create" method="post" autocomplete="off">
    <span class="form-title bn">{ text_title_form }</span>
    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label >{ label_payment }</label>
        <input type="text" name="payment" value="@currency_input (#Expenses->Payment)" min="3" max="15"  data-pattern="^[0-9]+(\.[0-9]{1,8})?$" >
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_created_by }</label>
        <select name="UserId">
            <option value="0" disabled selected>{ label_created_by }</option>
            @foreach (#Users as $User)
                <option value="{! $User->UserId; !}" @if (#Expenses->UserId == $User->UserId) selected @endif >{! $User->Username; !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group-s col-md-down-1 col-md-up-2">
        <label>{ label_category_expense }</label>
        <select name="categoryId">
            <option value="0" disabled selected>{ label_category_expense }</option>
            @foreach (#Categories as $Category)
                <option value="{! $Category->ExpenseCategoryId; !}" @if (#Expenses->CategoryId == $Category->ExpenseCategoryId) selected @endif >{! $Category->Type; !}</option>
            @endforeach
        </select>
    </div>

    <div class="input-submit-p">
        <input type="submit" class="bn b-primary-submit" name="submit" value="{ text_save }" >
    </div>
</form>