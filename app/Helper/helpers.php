<?php

function getProductColor($id){
    return \App\Models\ProductColor::find($id) ?? null;
}
