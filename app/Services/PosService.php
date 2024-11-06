<?php

namespace App\Services;


class PosService
{

    public function setUser($user, $set)
    {
        $set('pos-customer_name', $user->name);
        $set('pos-customer_ndocument', $user->ndocument);
        session(['pos-customer_id' => $user->id]);
    }

    public function resetCustomer($set, $form)
    {
        $form->fill();
        $set('pos-customer_name', '');
        $set('pos-customer_ndocument', '');
    }

    public function resetSale()
    {
        session()->forget('pos-sale');
        session()->forget('pos-sale_details');
    }
}
