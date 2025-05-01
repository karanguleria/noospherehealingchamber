<?php
function is_admin()
{
    if (auth()->user()->type_id == 3) {
        return true;
    } else {
        return false;
    }
}

function is_super_admin()
{
    if (auth()->user()->type_id == 4) {
        return true;
    } else {
        return false;
    }
}
