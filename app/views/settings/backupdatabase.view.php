<div class="actions">
    <a class="b-primary-upt bn" href="/Settings/BackupDatabase/?action=NewBackup">{ text_new_backup }</a>
</div>

<table class="display dataTableEnable">
    <thead>
    <tr>
        <th>{ text_file_name }</th>
        <th>{ text_created_date }</th>
        <th>{ text_control }</th>
    </tr>
    </thead>
    <tbody>

    @foreach (#Backup as $Backup)
    <tr>
        <td>{! $Backup->fileName !}</td>
        <td data-bottom-title="{ on_time } @time_format ($Backup->CreatedDate)">@date_format ($Backup->CreatedDate)</td>
        <td>
            <a href="/Settings/BackupView/?name={! $Backup->fileName !}" data-top-title="{ title_preview }"><i class="far fa-eye"></i></a>
            <a href="/Settings/BackupDownload/?name={! $Backup->fileName !}" data-top-title="{ title_download }"><i class="fas fa-download"></i></a>
            <a href="/Settings/BackupDatabase/?action=Delete&&name={! $Backup->fileName !}" data-top-title="{ title_delete }" onclick="return confirm('do you want delete this Product')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

