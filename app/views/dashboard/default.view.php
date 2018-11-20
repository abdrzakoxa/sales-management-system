<!--<div class="f-row">-->
<!--    <div class="col-2">-->
<!--        <canvas id="counts_chart" width="400" height="400"></canvas>-->
<!--    </div>-->
<!---->
<!---->
<!--    <div class="col-2">-->
<!--        <canvas id="myChart" width="400" height="400"></canvas>-->
<!--    </div>-->
<!--</div>-->

<div class="f-row chart-parent">
    <div class="col-sm-up-2 col-sm-down-1 col-md-up-4">
        <div class="chart-counts total-sales">
            <i class="fas fa-shopping-cart"></i>
            <span class="line-number d-flex">
                <span class="chart-number" @notshort (#Info->Sales_count) data-bottom-title="{ Info->Sales_count } { text_sold }" @end >@format_num_dash (#Info->Sales_count) </span> <span class="chart-text">{ text_sold }</span>
            </span>
        </div>
    </div>

    <div class="col-sm-up-2 col-sm-down-1 col-md-up-4">
        <div class="chart-counts total-purchases">
            <i class="fas fa-cart-arrow-down"></i>
            <span class="line-number d-flex">
                <span class="chart-number" @notshort (#Info->Purchases_count) data-bottom-title="{ Info->Purchases_count } { text_buyer }" @end >@format_num_dash (#Info->Purchases_count)</span> <span class="chart-text">{ text_buyer }</span>
            </span>
        </div>
    </div>

    <div class="col-sm-up-2 col-sm-down-1 col-md-up-4">
        <div class="chart-counts total-clients">
            <i class="fas fa-user"></i>
            <span class="line-number d-flex">
                <span class="chart-number" @notshort (#Info->Clients_count) data-bottom-title="{ Info->Clients_count } { text_client }" @end >@format_num_dash (#Info->Clients_count)</span> <span class="chart-text">{ text_client }</span>
            </span>
        </div>
    </div>

    <div class="col-sm-up-2 col-sm-down-1 col-md-up-4">
        <div class="chart-counts total-suppliers">
            <i class="fas fa-user-tie"></i>
            <span class="line-number d-flex">
                <span class="chart-number" @notshort (#Info->Suppliers_count) data-bottom-title="{ Info->Suppliers_count } { text_supplier }" @end >@format_num_dash (#Info->Suppliers_count)</span> <span class="chart-text">{ text_supplier }</span>
            </span>
        </div>
    </div>
</div>


<div class="charts-js d-grid">

    <div class="chart-box quick-links">
        <div class="chart-title">
            { text_quick_link }
        </div>
        <div class="chart-content padding-no">
            <div class="dash-quick-links d-flex f-wrap">
                <a href="/Users" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-users"></i><span>{ text_users }</span></a>
                <a href="/Groups" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-user-tag"></i><span>{ text_group_users }</span></a>
                <a href="/Profile" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-user"></i><span>{ text_profile }</span></a>
                <a href="/Products" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-shopping-cart"></i><span>{ text_products }</span></a>
                <a href="/Permissions" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-key"></i><span>{ text_permissions }</span></a>
                <a href="/Settings/System" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-cogs"></i><span>{ text_settings }</span></a>
                <a href="/Purchases" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-cart-plus"></i><span>{ text_purchases }</span></a>
                <a href="/Sales" class="quick-link col-md-up-8 col-md-down-4"><i class="fas fa-cart-arrow-down"></i><span>{ text_sales }</span></a>

            </div>
        </div>

    </div>

    <div class="chart-box info-sales">
        <div class="chart-title">
            { text_sold_sell }
        </div>
        <div class="chart-content">
            <canvas id="pur-sell" width="500" height="200"></canvas>
        </div>
    </div>

    <div class="chart-box last-sales">
        <div class="chart-title">
            @sprintf (#text_last_sales,#Info->LastSales[1])
        </div>
        <div class="chart-content last-sales-content">
            <table class="table-custom">
                <thead>
                <tr>
                    <th>{ text_title }</th>
                    <th>{ text_quantity }</th>
                    <th>{ text_price }</th>
                    <th>{ text_client_name }</th>
                </tr>
                </thead>
                <tbody>
                @foreach (#Info->LastSales[0] as $LastSale)
                <tr>
                    <td data-cut-title="25">{! $LastSale->Title !}</td>
                    <td data-bottom-title="{! self::format_quantity($LastSale->QuantitySales) _ $LastSale->UnitName !}" ><bdi>@number_zero ($LastSale->QuantitySales) {! $LastSale->UnitCode !}</bdi></td>
                    <td>@Currency ($LastSale->SellPrice)</td>
                    <td>{! $LastSale->FirstName _ $LastSale->LastName !}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="chart-left">
        <div class="calendar chart-box chart-calender"></div>
        <div class="chart-box invoice-sales-status">
            <div class="chart-title">
                { text_invoice_sales_status }
            </div>
            <div class="chart-content">
                <canvas id="invoice-sales-status" width="400" height="500"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-box best-sales">
        <div class="chart-title">
            { text_best_sales }
        </div>
        <div class="chart-content">
            <canvas id="best-sales" width="400" height="200"></canvas>
        </div>
    </div>

    <div class="chart-box expenses-profits">
        <div class="chart-title">
            { text_profit_vs_expenses }
        </div>
        <div class="chart-content">
            <canvas id="expenses-profits" width="400" height="400"></canvas>
        </div>
    </div>

<!--    <div class="chart-box invoice-sales-status">-->
<!--        <div class="chart-title">-->
<!--            { text_invoice_sales_status }-->
<!--        </div>-->
<!--        <div class="chart-content">-->
<!--            <canvas id="invoice-sales-status" width="400" height="500"></canvas>-->
<!--        </div>-->
<!--    </div>-->


</div>

