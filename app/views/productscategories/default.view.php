<div class="actions">
    <a class="b-primary-upt bn" href="/ProductsCategories/Create/">{ text_new_category }</a>
</div>

<div class="card">
    <div class="card-header">
        <span>{ text_card_title }</span>
        <div class="card-header-control">
            <div class="card-control-view">
                { text_view } : <span>{ text_classic }</span> | <span>{ text_full }</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (!empty(#ProductsCategories))
            @foreach (#ProductsCategories as $ProductCategory)
            <div class="card-category">
                <h3>{! $ProductCategory->Name !}</h3>
                @if ($ProductCategory->Description != '')
                <p>{!$ProductCategory->Description!}</p>
                @endif
                <span class="card-control">
                    <a href="/ProductsCategories/Edit/?id={! $ProductCategory->ProductCategoryId !}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="/ProductsCategories/Delete/?id={! $ProductCategory->ProductCategoryId !}" onclick="return confirm('do you want delete this product Category')">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </span>
            </div>
            @endforeach
        @else
            <div class="not-found-category">
                <span>ليس هناك أي قسم</span>
            </div>
        @endif
    </div>
</div>
