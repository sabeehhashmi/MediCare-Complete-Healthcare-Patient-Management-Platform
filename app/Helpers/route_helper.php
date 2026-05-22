<?php

use App\Models\AccountType;
use App\Models\ActivityType;
use Stripe\FinancialConnections\Account;

// sidebar menu route 
if (!function_exists('get_products_route')) {
    function get_products_route()
    {
        $activity_type_id = auth()->user()->activity_type_id;
        $user_tye_id = auth()->user()->user_type_id;
        $route = '';// route('vendor.products');

        if ($user_tye_id == AccountType::COMMERCIAL_CENTER) {
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT || $activity_type_id == ActivityType::CAFE) {
                $route = route('vendor.food.products');
            } else {
                $route = route('vendor.products');
            }
        } elseif ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $route = route('vendor.reservation.gym.packages');
            } else {
                $route = route('vendor.reservation.products');
            }
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $route = route('vendor.wholesale.products');
        }elseif ($user_tye_id == AccountType::INDIVIDUALS) {
            $route = '';
        }

        return $route;
    }
}

if (!function_exists('get_products_title')) {
    function get_products_title()
    {
        $activity_type_id = auth()->user()->activity_type_id;
        $user_tye_id = auth()->user()->user_type_id;
        $name = "Products";

        if ($user_tye_id == AccountType::COMMERCIAL_CENTER) {
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT || $activity_type_id == ActivityType::CAFE) {
                $name = "Food Products";
            } else {
                $name = "Products";
            }
        } elseif ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $name = 'Gym Packages';
            }else if ($activity_type_id == ActivityType::HOTEL) {
                $name = 'Suites';
            }else if ($activity_type_id == ActivityType::CHALET) {
                $name = 'Chalets';
            }else if ($activity_type_id == ActivityType::PLAYGROUND) {
                $name = 'Playgrounds';
            } else {
                $name = 'Products';
            }
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $name = 'Products';
        }

        return $name;
    }
}

if (!function_exists('get_orders_route')) {
    function get_orders_route($status = null)
    {
        $activity_type_id = auth()->user()->activity_type_id;
        $user_tye_id = auth()->user()->user_type_id;
        $route = route('vendor.orders');

        if ($user_tye_id == AccountType::COMMERCIAL_CENTER) {
            if ($activity_type_id == ActivityType::RESTAURANTS || $activity_type_id == ActivityType::RESTAURANT || $activity_type_id == ActivityType::CAFE) {
                $route = route('vendor.food.orders');
            } else {
                $route = route('vendor.orders');
            }
        } elseif ($user_tye_id == AccountType::RESERVATIONS) {
            if ($activity_type_id == ActivityType::GYM) {
                $route = route('vendor.gym.orders');
            } else {
                $route = route('vendor.reservation.orders');
            }
        } elseif ($user_tye_id == AccountType::SERVICE_PROVIDERS) {
            $route = route('vendor.service.orders');
        } elseif ($user_tye_id == AccountType::WHOLE_SELLERS) {
            $route = route('vendor.wholesale.orders');
        }

        if (!is_null($status)) {
            $route .= '?statusType=' . $status;
        }

        return $route;
    }
}

// food product routes
if (!function_exists('food_products_index_route')) {
    function food_products_index_route()
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/food_products');
        } else {
            $route = route('vendor.food.products');
        }

        return $route;
    }
}

if (!function_exists('food_products_create_route')) {
    function food_products_create_route($store_id = null)
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/food_product/create') . '?store_id=' . $store_id;
        } else {
            $route = route('vendor.food_products.create');
        }
        return $route;
    }
}

if (!function_exists('food_products_store_route')) {
    function food_products_store_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.food_product.add_product');
        } else {
            $route = route('vendor.food_product.add_product');
        }
        return $route;
    }
}

if (!function_exists('food_products_edit_route')) {
    function food_products_edit_route($id)
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/food_products/edit/' . $id);
        } else {
            $route = route('vendor.food_products.edit', $id);
        }
        return $route;
    }
}

if (!function_exists('food_products_delete_route')) {
    function food_products_delete_route($id)
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/food_products/delete/' . $id);
        } else {
            $route = route('vendor.food_products.delete', $id);
        }
        return $route;
    }
}

if (!function_exists('food_products_change_status_route')) {
    function food_products_change_status_route()
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/food_products/change_status');
        } else {
            $route = route('vendor.food_products.change_status');
        }
        return $route;
    }
}

if (!function_exists('food_product_heading_store_route')) {
    function food_product_heading_store_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.food_product.heading.store');
        } else {
            $route = route('vendor.food_product.heading.store');
        }
        return $route;
    }
}

if (!function_exists('food_product_items_store_route')) {
    function food_product_items_store_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.food_product.items.store');
        } else {
            $route = route('vendor.food_product.items.store');
        }
        return $route;
    }
}

if (!function_exists('food_product_combo_row_route')) {
    function food_product_combo_row_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.food_product.combo.row');
        } else {
            $route = route('vendor.food_product.combo.row');
        }
        return $route;
    }
}

if (!function_exists('food_product_item_row_route')) {
    function food_product_item_row_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.food_product.item.row');
        } else {
            $route = route('vendor.food_product.item.row');
        }
        return $route;
    }
}

if (!function_exists('food_products_path')) {
    function food_products_path()
    {
        if (auth()->user()->role == '1') {
            $route = '/admin/food_products';
        } else {
            $route = '/vendor/food/products';
        }
        return $route;
    }
}

if (!function_exists('food_products_removeProductImage_url')) {
    function food_products_removeProductImage_url()
    {
        if (auth()->user()->role == '1') {
            $route = url("admin/food_products/removeProductImage");
        } else {
            $route = url("vendor/food_products/removeProductImage");
        }
        return $route;
    }
}

//reservation routes
if (!function_exists('reservation_products_create_route')) {
    function reservation_products_create_route($vendor_id = null)
    {
        if (auth()->user()->role == '1') {
            $route = $vendor_id ? route('admin.reservations.product.create', ['vendor_id' => $vendor_id]) : route('admin.reservations.product.create');
        } else {
            $route = route('vendor.reservations.product.create');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_store_route')) {
    function reservation_products_store_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservations.product.store');
        } else {
            $route = route('vendor.reservations.product.store');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_edit_route')) {
    function reservation_products_edit_route($id)
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservations.product.edit', ['id' => $id]);
        } else {
            $route = route('vendor.reservations.product.edit', ['id' => $id]);
        }
        return $route;
    }
}

if (!function_exists('reservation_products_delete_route')) {
    function reservation_products_delete_route($id)
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservations.product.delete', ['id' => $id]);
        } else {
            $route = route('vendor.reservations.product.delete', ['id' => $id]);
        }
        return $route;
    }
}

if (!function_exists('reservation_products_status_route')) {
    function reservation_products_status_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservations.product.status');
        } else {
            $route = route('vendor.reservations.product.status');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_update_slots_route')) {
    function reservation_products_update_slots_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservation.product.update_slots');
        } else {
            $route = route('vendor.reservation.product.update_slots');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_slots_route')) {
    function reservation_products_slots_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservation.product.slots');
        } else {
            $route = route('vendor.reservation.product.slots');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_list_route')) {
    function reservation_products_list_route($vendor_id = null)
    {
        if (auth()->user()->role == '1') {
            $route = $vendor_id ? route('admin.reservation.products', ['vendor_id' => $vendor_id]) : route('admin.reservation.products');
        } else {
            $route = $vendor_id ? route('vendor.reservation.products', ['vendor_id' => $vendor_id]) : route('vendor.reservation.products');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_get_activities_route')) {
    function reservation_products_get_activities_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.get_activities');
        } else {
            $route = route('vendor.get_activities');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_get_activity_fields_route')) {
    function reservation_products_get_activity_fields_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.get_activity.fields');
        } else {
            $route = route('vendor.get_activity.fields');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_update_route')) {
    function reservation_products_update_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservations.product.update');
        } else {
            $route = route('vendor.reservations.product.update');
        }
        return $route;
    }
}

if (!function_exists('reservation_products_remove_content_route')) {
    function reservation_products_remove_content_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservation.product.remove.content');
        } else {
            $route = route('vendor.reservation.product.remove.content');
        }
        return $route;
    }
}

//gym package routes
if (!function_exists('reservation_gym_packages_index_route')) {
    function reservation_gym_packages_index_route($vendor_id = null)
    {
        if (auth()->user()->role == '1') {
            $route = $vendor_id ? route('admin.reservation.gym.packages', ['vendor_id' => $vendor_id]) : route('admin.reservation.gym.packages');
        } else {
            $route = route('vendor.reservation.gym.packages');
        }
        return $route;
    }
}

if (!function_exists('reservation_gym_packages_create_route')) {
    function reservation_gym_packages_create_route($id = null)
    {
        if (auth()->user()->role == '1') {
            $route = $id ? route('admin.reservation.gym.packages.create', $id) : route('admin.reservation.gym.packages.create');
        } else {
            $route = $id ? route('vendor.reservation.gym.packages.create', $id) : route('vendor.reservation.gym.packages.create');
        }
        return $route;
    }
}

if (!function_exists('reservation_gym_packages_store_route')) {
    function reservation_gym_packages_store_route()
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservation.gym.packages.store');
        } else {
            $route = route('vendor.reservation.gym.packages.store');
        }
        return $route;
    }
}

if (!function_exists('reservation_gym_packages_delete_route')) {
    function reservation_gym_packages_delete_route($id)
    {
        if (auth()->user()->role == '1') {
            $route = route('admin.reservation.gym.packages.delete', $id);
        } else {
            $route = route('vendor.reservation.gym.packages.delete', $id);
        }
        return $route;
    }
}

if (!function_exists('reservation_edit_profile_route')) {
    function reservation_edit_profile_route()
    {
        if (auth()->user()->role == '1') {
            $route = url('admin/reservations');
        } else {
            $route = route('vendor.edit_profile');
        }
        return $route;
    }
}

if (!function_exists('canViewProducts')) {
    function canViewProducts()
    {
        $user_tye_id = auth()->user()->user_type_id;

        return (in_array($user_tye_id, [AccountType::COMMERCIAL_CENTER, AccountType::RESERVATIONS, AccountType::WHOLE_SELLERS, AccountType::INDIVIDUALS]));
    }
}
