<div class="col-md-12">
<div class="table-responsive">
<table id='clients' class="display table table-condensed table-striped table-hover table-bordered clients responsive pull-left">
<thead><tr><th>ID</th><th>Campaign Name</th><th>Email</th><th>Full Name</th><th>Action</th></tr></thead>

<?php
//var_dump ($data);

foreach ($data as $item)
{
    print '<tr>';
    print '<td>'.$item["id"].'</td>';
    print '<td>'.$item["name"].'</td>';
    print '<td>'.$item["email"].'</td>';
    print '<td>'.$item["full_name"].'</td>';
    if($item['u.id'])
    {
        print '<td><button class="btn btn-success modify btn-sm" onclick="modify(\''.$item["id"].'\',\''.$item["name"].'\')">Modify</button></td>';
    }
    else
    {
        print '<td><button class="btn btn-primary create btn-sm">Create</button></td>';
    }
    print '</tr>';
}
?>
</table>
</div>
</div>
<div class="modal fade" id="modalka"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="modify">
                <input type="text" name="campaign">
            <div class="modal-header">
            qrvqergqergq
            </div>
                       <div class="modal-body">
                       </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function modify(id,name)
    {
        var inner='<P>'+name+'</p>';
        var form=document.forms.modify;
        console.log(form);
        //$("#modalka").html(inner);
        $("#modalka").modal('show');
    }
</script>
