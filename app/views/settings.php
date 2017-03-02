<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.02.2017
 * Time: 16:47
 */
?>
<h1 class="text-center">Lead Settings</h1>
<p>System will automatic retry to send leads
    where count of deliveries less than four. This procedure will repeat every day,
    and you can change period of days in which system will look for leads (days before today)</p>
    <form id="set">
    <input type="number" name="days" value="<?php print $data['days']?>" style="width:4em">
    <input type="submit" value="Save">
</form>
<script>
    $('#set').submit(function(e)
    {
        var data=this.children[0];
        e.preventDefault();
        $.ajax ({
                url: "<?php echo __HOST__ . '/settings/update' ?>",
                method: "POST",
                data:{'days':data.value},
            success:function(){
            console.log(data.value);
                alert('Settings saved successfully!');            }
        });
    });
</script>
