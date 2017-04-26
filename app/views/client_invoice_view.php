<?php
print '<form method="POST" action="'. __HOST__ .'/docs/invoices/download.php">';
print '<ul class="list-inline">';
foreach ($data as $item)
{
    if ($item=='.' || $item=='..')continue;
    print '<li><button name="file" class="btn btn-sm btn-link" value="'.$item.'" title="Download Current">'.$item.'</button></li>';

}
print '</form>';
print '</ul>';
