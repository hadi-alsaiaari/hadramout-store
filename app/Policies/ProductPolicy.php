<?php

namespace App\Policies;

use App\Models\Product;
use Illuminate\Auth\Access\Response;

class ProductPolicy extends ModelPolicy
{
    /*
    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }
    
    public function viewAny($user): bool
    {
        return $user->hasAbility('products.view');
    }
    
    public function view($user, Product $product): bool
    {
        return $user->hasAbility('products.view') && $product->store_id == $user->store_id;
    }

    public function create($user): bool
    {
        return $user->hasAbility('products.create');
    }

    public function update($user, Product $product): bool
    {
        return $user->hasAbility('products.update') && $product->store_id == $user->store_id;
    }

    public function delete($user, Product $product): bool
    {
        return $user->hasAbility('products.delete') && $product->store_id == $user->store_id;
    }
    
    public function restore($user, Product $product): bool
    {
        return $user->hasAbility('products.restore') && $product->store_id == $user->store_id;
    }
    
    public function forceDelete($user, Product $product): bool
    {
        return $user->hasAbility('products.force-delete') && $product->store_id == $user->store_id;
    }
    */
}
