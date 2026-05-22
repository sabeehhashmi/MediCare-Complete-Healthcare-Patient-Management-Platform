@extends('front.template.layout')
@section('content')
<div class="package-grid-page shop-page pt-100 mb-100">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 order-lg-1 order-2 wow animate fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="shop-sidebar">
                    <!-- Search Widget -->
                    <div class="single-widgets widget_search mb-30">
                        <form action="{{ route('front.pharmacy-list') }}" method="GET">
                            <div class="wp-block-search__inside-wrapper">
                                <button type="submit" class="wp-block-search__button">
                                    <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.2746 9.04904C11.1219 7.89293 11.5013 6.45957 11.3371 5.0357C11.1729 3.61183 10.4771 2.30246 9.38898 1.36957C8.30083 0.436668 6.90056 -0.050966 5.46831 0.00422091C4.03607 0.0594079 2.67747 0.653346 1.66433 1.66721C0.651194 2.68107 0.0582276 4.04009 0.00406556 5.47238C-0.0500965 6.90466 0.43854 8.30458 1.37222 9.39207C2.30589 10.4795 3.61575 11.1744 5.03974 11.3376C6.46372 11.5008 7.89682 11.1203 9.05232 10.2722H9.05145C9.07769 10.3072 9.10569 10.3405 9.13719 10.3729L12.5058 13.7415C12.6699 13.9057 12.8924 13.9979 13.1245 13.998C13.3566 13.9981 13.5793 13.906 13.7435 13.7419C13.9076 13.5779 13.9999 13.3553 14 13.1232C14.0001 12.8911 13.908 12.6685 13.7439 12.5043L10.3753 9.13566C10.344 9.104 10.3104 9.07475 10.2746 9.04817V9.04904ZM10.5004 5.68567C10.5004 6.31763 10.3759 6.9434 10.1341 7.52726C9.89223 8.11112 9.53776 8.64162 9.0909 9.08849C8.64403 9.53535 8.11352 9.88983 7.52967 10.1317C6.94581 10.3735 6.32003 10.498 5.68807 10.498C5.05611 10.498 4.43034 10.3735 3.84648 10.1317C3.26262 9.88983 2.73211 9.53535 2.28525 9.08849C1.83838 8.64162 1.48391 8.11112 1.24207 7.52726C1.00023 6.9434 0.875753 6.31763 0.875753 5.68567C0.875753 4.40936 1.38276 3.18533 2.28525 2.28284C3.18773 1.38036 4.41177 0.873346 5.68807 0.873346C6.96438 0.873346 8.18841 1.38036 9.0909 2.28284C9.99338 3.18533 10.5004 4.40936 10.5004 5.68567Z"/>
                                    </svg>
                                </button>
                                <input type="search" class="wp-block-search__input" name="search" placeholder="Search Product" value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                    <!-- Cart Widget - Dynamically Loaded -->
                    <div class="single-widgets mb-30"> 
                        <div class="widget-title">
                            <h5>Cart</h5>
                        </div>
                        <div class="cart-menu" id="sidebar-cart">
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Widget -->
                    <div class="single-widgets mb-30">
                        <div class="widget-title">
                            <h5>Category</h5>
                        </div>
                        <div class="checkbox-container">
                            <ul>
                                @foreach($categories as $category)
                                <li>
                                    <label class="containerss">
                                        <input type="checkbox" 
                                            class="category-checkbox" 
                                            value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span>{{ $category->title }} ({{ $category->medicines_count ?? 0 }})</span>
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="single-widgets">
                        <div class="widget-title">
                            <h5>Tag:</h5>
                        </div>
                        <ul class="tag-list">
                            @foreach($tags as $tag)
                            <li>
                                <a href="{{ route('front.pharmacy-list', ['tag' => $tag->id]) }}" 
                                    style="{{ request('tag') == $tag->id ? 'color: ' . $tag->color : '' }}">
                                    {{ $tag->name_en }},
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 order-lg-2 order-1">
                <!-- Sort Bar -->
                <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <p class="mb-0">Showing {{ $medicines->firstItem() ?? 0 }} - {{ $medicines->lastItem() ?? 0 }} of {{ $medicines->total() }} results</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="me-2">Sort :</label>
                        <select class="form-select" style="width: auto;" id="sort-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Recently Added</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                        </select>
                    </div>
                </div> -->

                <!-- Products -->
                <div id="products-container"  class="row gy-5 mb-60">
                    @forelse($medicines as $medicine)
                    <div class="col-md-4 col-sm-6 wow animate fadeInDown" data-wow-delay="200ms" data-wow-duration="1500ms">
                        <div class="product-card">
                            <div class="product-card-img-wrap">
                                <a href="{{ route('front.pharmacy-detail', $medicine->slug) }}" class="product-card-img">
                                    <img src="{{ $medicine->image_url }}" alt="{{ $medicine->title_en }}">
                                </a>
                                <button type="button" class="cart-btn add-to-cart" 
                                    data-id="{{ $medicine->id }}"
                                    data-name="{{ $medicine->title_en }}"
                                    data-price="{{ $medicine->discount_price ?? $medicine->price }}"
                                    data-image="{{ $medicine->image_url }}"
                                    {{ in_array($medicine->id, $cart_items) ? 'disabled' : '' }}>
                                    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.9016 15H4.10156C2.45156 15 1.10156 13.65 1.10156 12V11.9L1.40156 3.9C1.45156 2.25 2.80156 1 4.40156 1H11.6016C13.2016 1 14.5516 2.25 14.6016 3.9L14.9016 11.9C14.9516 12.7 14.6516 13.45 14.1016 14.05C13.5516 14.65 12.8016 15 12.0016 15H11.9016ZM4.40156 2C3.30156 2 2.45156 2.85 2.40156 3.9L2.10156 12C2.10156 13.1 3.00156 14 4.10156 14H12.0016C12.5516 14 13.0516 13.75 13.4016 13.35C13.7516 12.95 13.9516 12.45 13.9516 11.9L13.6516 3.9C13.6016 2.8 12.7516 2 11.6516 2H4.40156Z"/>
                                        <path d="M8 7C6.05 7 4.5 5.45 4.5 3.5C4.5 3.2 4.7 3 5 3C5.3 3 5.5 3.2 5.5 3.5C5.5 4.9 6.6 6 8 6C9.4 6 10.5 4.9 10.5 3.5C10.5 3.2 10.7 3 11 3C11.3 3 11.5 3.2 11.5 3.5C11.5 5.45 9.95 7 8 7Z"/>
                                    </svg>
                                    <span class="btn-text">
                                        {{ in_array($medicine->id, $cart_items) ? 'Added' : 'Add to Cart' }}
                                    </span>
                                </button>
                            </div>
                            <div class="product-card-content">
                                <h6><a href="{{ route('front.pharmacy-detail', $medicine->slug) }}">{{ $medicine->title_en }}</a></h6>
                                @if($medicine->discount_price)
                                <span><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}"> {{ number_format($medicine->discount_price, 2) }} <del> {{ number_format($medicine->price, 2) }}</del></span>
                                @else
                                <span><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($medicine->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img style="max-width: 240px; margin: 0 auto;" src="{{ asset('assets/img/search-no-data.png') }}" alt="MedNero">
                        <h4>No products found</h4>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pagination-area wow animate fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
                    {{ $medicines->appends(request()->query())->links('front.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    // Load cart sidebar
    loadCartSidebar();

    // Category checkbox filter
    $('.category-checkbox').on('change', function() {
        let selectedCategory = $('.category-checkbox:checked').val();
        let url = new URL(window.location.href);
        
        if (selectedCategory) {
            url.searchParams.set('category', selectedCategory);
        } else {
            url.searchParams.delete('category');
        }
        
        window.location.href = url.toString();
    });

    // Sort select
    $('#sort-select').on('change', function() {
        let url = new URL(window.location.href);
        url.searchParams.set('sort', $(this).val());
        window.location.href = url.toString();
    });

    // Add to cart
    $('.add-to-cart').on('click', function() {
        let btn = $(this);
        let medicineId = btn.data('id');
        
        $.ajax({
            url: '{{ route("front.cart.add") }}',
            type: 'POST',
            data: {
                medicine_id: medicineId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status == '1') {
                    btn.prop('disabled', true);
                    btn.find('.btn-text').text('Added');
                    updateCartCount();
                    loadCartSidebar();
                    toastr.success(response.message);
                } else {
                    if (response.message.includes('login')) {
                        window.location.href = '{{ route("login") }}';
                    } else {
                        toastr.error(response.message);
                    }
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                     toastr.warning('Please login to continue');
                    setTimeout(function() {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    toastr.error('Failed to add item to cart');
                }
            }
        });
    });
});

function loadCartSidebar() {
    $.get('{{ route("front.cart.summary") }}', function(data) {
        let html = '';
        if (data.items.length > 0) {
            html += '<ul class="product-list">';
            data.items.forEach(function(item) {
                html += `
                    <li class="single-product">
                        <div class="product-img">
                            <img src="${item.medicine.image_url}" alt="${item.medicine.title_en}">
                            <button type="button" class="close-btn" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="content">
                            <h6><a href="{{ route('front.pharmacy-detail', '') }}/${item.medicine.slug}">${item.medicine.title_en}</a></h6>
                            <span>${item.quantity} x <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">${item.price}</span>
                        </div>
                    </li>
                `;
            });
            html += '</ul>';
            html += `
                <div class="total-price">
                    <span>Subtotal</span>
                    <strong><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">${data.subtotal}</strong>
                </div>
                <div class="btn-area">
                    <a href="{{ route('front.cart') }}" class="primary-btn1 mb-15">
                         <span>
                            View Cart
                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                            </svg>
                        </span>
                        <span>
                            View Cart
                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="{{ route('front.checkout') }}" class="primary-btn1 black-bg">
                         <span>
                            Checkout
                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                            </svg>
                        </span>
                        <span>
                            Checkout
                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438C7.73487 7.5758 7.67054 5.98959 7.75097 4.58203C7.77875 4.09598 7.82525 3.62422 7.87988 3.17969L1.53027 9.53027C1.23738 9.82317 0.762615 9.82317 0.469722 9.53027C0.176829 9.23738 0.176829 8.76262 0.469722 8.46973L6.83593 2.10254C6.3319 2.16472 5.79596 2.21841 5.25 2.24902C3.8302 2.32862 2.2474 2.26906 0.958003 1.79102L0.704097 1.68945L0.635738 1.65527C0.303274 1.47099 0.157578 1.06102 0.310542 0.704102C0.463655 0.347333 0.860941 0.170391 1.22363 0.28418L1.29589 0.310547L1.48828 0.387695C2.47399 0.751207 3.79966 0.827571 5.16601 0.750977C6.60111 0.670504 7.97842 0.428235 8.86132 0.262695L9.95312 0.0585938L9.73535 1.14746Z"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            `;
        } else {
            html = '<p class="text-center py-3">Your cart is empty</p>';
        }
        $('#sidebar-cart').html(html);
    });
}

// function removeFromCart(cartId) {
//     if (!confirm('Remove item from cart?')) {
//         return;
//     }
    
//     $.ajax({
//         url: '{{ route("front.cart.remove", "") }}/' + cartId,
//         type: 'DELETE',
//         data: {
//             _token: '{{ csrf_token() }}'
//         },
//         success: function(response) {
//             if (response.status == '1') {
//                 loadCartSidebar();
//                 updateCartCount();
//                 location.reload();
//             } else {
//                 toastr.error(response.message);
//             }
//         }
//     });
// }

function removeFromCart(cartId) {
    // Show toastr confirmation
    toastr.warning('', 'Remove item from cart?', {
        timeOut: 0,
        extendedTimeOut: 0,
        closeButton: true,
        tapToDismiss: false,
        positionClass: 'toast-top-center',
        onShown: function() {
            $(this).find('.toast-close-button').after(
                '<div class="mt-2 text-center">' +
                '<button class="btn btn-sm btn-danger me-2" onclick="confirmRemove(' + cartId + ')">Yes, Remove</button>' +
                '<button class="btn btn-sm btn-secondary" onclick="toastr.clear()">Cancel</button>' +
                '</div>'
            );
        }
    });
}

function confirmRemove(cartId) {
    toastr.clear();
    
    $.ajax({
        url: '{{ route("front.cart.remove", "") }}/' + cartId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status == '1') {
                loadCartSidebar();
                updateCartCount();
                location.reload();
                toastr.success('Item removed from cart');
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Failed to remove item');
        }
    });
}

function updateCartCount() {
    $.get('{{ route("front.cart.count") }}', function(data) {
        $('.cart-count').text(data.count);
    });
}
$(document).ready(function() {
    const $searchInput = $('input[name="search"]');
    const $productsContainer = $('#products-container');

    let typingTimer;
    const typingDelay = 500; // 0.5s delay

    $searchInput.on('keyup', function() {
        clearTimeout(typingTimer);
        const query = $(this).val().trim();

        typingTimer = setTimeout(function() {
            fetchProducts(query);
        }, typingDelay);
    });

    function fetchProducts(search) {
        const params = new URLSearchParams(window.location.search);
        if(search) {
            params.set('search', search);
        } else {
            params.delete('search');
        }

        $.ajax({
            url: '{{ route("front.pharmacy-list") }}',
            data: params.toString(),
            type: 'GET',
            success: function(data) {
                // Parse returned HTML and extract the products grid
                const newProducts = $(data).find('#products-container').html();
                $productsContainer.html(newProducts);
            },
            error: function() {
                console.error('Failed to fetch products');
            }
        });
    }
});
</script>
@endsection