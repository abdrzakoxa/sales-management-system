<div class="actions">
    <a class="b-primary-upt bn" href="/Expenses/Create/">{ text_new_expense }</a>
</div>
<table class="display dataTableEnable">
    <thead>
    <tr>
        <th width="100px">{ text_expense_id }</th>
        <th>{ text_category_name }</th>
        <th>{ text_created_by }</th>
        <th>{ text_payment }</th>
        <th>{ text_created_date }</th>
        <th width="100px">{ text_control }</th>
    </tr>
    </thead>
    <tbody>
@foreach (#Expenses as $Expense)

    <tr>
        <td>{! $Expense->ExpenseId !}</td>
        <td>{! $Expense->CategoryName !}</td>
        <td>{! $Expense->Username !}</td>
        <td>@Currency ($Expense->Payment)</td>
        <td data-bottom-title="{ on_time } @time_format ($Expense->CreatedDate)">@date_format ($Expense->CreatedDate)</td>
        <td><a href="/Expenses/Edit/?id={! $Expense->ExpenseId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Expenses/Delete/?id={! $Expense->ExpenseId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this user')"><i class="far fa-trash-alt"></i></a></td>
    </tr>

@endforeach
    </tbody>
</table>
