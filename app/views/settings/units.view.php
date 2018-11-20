<div id="tab-control" class="tab-control" data-cookies="units-settings" data-default-cos="setting-base-units" >
    <a class="bn b-primary-icon" href="~setting-base-units" data-bottom-title="{ text_settings_base_units }" ><i class="fas fa-wrench"></i></a>
    <a class="bn b-primary-icon" href="~setting-units" data-bottom-title="{ text_settings_units }" ><i class="fas fa-balance-scale"></i></a>
</div>
<div class="tab-content">
    <div id="setting-base-units" class="tab-parent">
        <div class="actions">
            <a class="b-primary-upt bn" href="/Settings/CreateBaseUnit/">{ text_new_base_unit }</a>
        </div>
        <table class="display dataTableEnable">
            <thead>
            <tr>
                <th>{ text_unit_id }</th>
                <th>{ text_code }</th>
                <th>{ text_name }</th>
                <th>{ text_control }</th>
            </tr>
            </thead>
            <tbody>
            @foreach (#UnitsBase as $UnitBase)
                <tr>
                    <td>{! $UnitBase->BaseUnitId !}</td>
                    <td>{! $UnitBase->Code !}</td>
                    <td>{! $UnitBase->Name !}</td>
                    <td><a href="/Settings/EditBaseUnit/?id={! $UnitBase->BaseUnitId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Settings/DeleteBaseUnit/?id={! $UnitBase->BaseUnitId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this Unit')"><i class="far fa-trash-alt"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <div id="setting-units" class="tab-parent">
        <div class="actions">
            <a class="b-primary-upt bn" href="/Settings/CreateUnit/">{ text_new_unit }</a>
        </div>
        <table class="display dataTableEnable">
            <thead>
            <tr>
                <th>{ text_unit_id }</th>
                <th>{ text_code }</th>
                <th>{ text_name }</th>
                <th>{ text_base_unit }</th>
                <th>{ text_operator }</th>
                <th>{ text_operation_value }</th>
                <th>{ text_control }</th>
            </tr>
            </thead>
            <tbody>
            @foreach (#Units as $Unit)
            @if ($Unit->Name == $Unit->BaseUnitName && $Unit->Operator == '*' && $Unit->OperationValue == 1)
            @else
            <tr>
                <td>{! $Unit->UnitId !}</td>
                <td>{! $Unit->Code !}</td>
                <td>{! $Unit->Name !}</td>
                <td>{! $Unit->BaseUnitName !}</td>
                <td>{ main_operator[$Unit->Operator] }</td>
                <td>{! $Unit->OperationValue !}</td>
                <td><a href="/Settings/EditUnit/?id={! $Unit->UnitId !}" data-top-title="{ title_edit }"><i class="far fa-edit"></i></a><a href="/Settings/DeleteUnit/?id={! $Unit->UnitId !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this Unit')"><i class="far fa-trash-alt"></i></a></td>
            </tr>
            @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>



