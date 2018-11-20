<div class="actions">
    <a class="b-primary-upt bn" href="/ExpensesCategories/Create/">{ text_new_category }</a>
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
        @if (#ExpensesCategories != [])
        @foreach (#ExpensesCategories as $ExpenseCategory)
            <div class="card-category">
                <h3>{! $ExpenseCategory->Type !}</h3>
                @notempty ($ExpenseCategory->FixedPayment) <p>@Currency ($ExpenseCategory->FixedPayment)</p> @endempty
                <span class="card-control">
                    <a href="/ExpensesCategories/Edit/?id={! $ExpenseCategory->ExpenseCategoryId !}">
                        <i class="far fa-edit"></i>
                    </a>
                    <a href="/ExpensesCategories/Delete/?id={! $ExpenseCategory->ExpenseCategoryId !}" onclick="return confirm('do you want delete this Expenses Category')">
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
