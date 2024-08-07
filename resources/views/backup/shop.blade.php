{{-- @extends('layouts.master')
@section('content')

<div id="flash-message" class="alert" style="display: none;"></div>

<div class="container-fluid mt-3" style="border: 2px solid black">
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-end mt-3">
            <div class="search-bar-container">
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search Products..." />
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>
    </div>

    <div class="row">
        @auth
        <div class="col-lg-3">
            <div class="card fixed-sidebar mt-3">
                <div class="card-body">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card-body">
                <div class="row" id="products">
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-12">
            <div class="card-body" style="transition: box-shadow 0.3s ease, transform 0.3s ease;">
                <div class="row" id="products">
                </div>
            </div>
        </div>
        @endauth
    </div>

    <div id="loading" class="modal" style="align-items: center; justify-content: center;display: flex;background-color: rgba(0,0,0,0.4);overflow: auto;height: 100%;width: 100%;top: 0;left: 0;z-index: 9999;position: fixed;display: flex;">
        <div class="modal-content" style="text-align: center; background-color: #fff;  padding: 20px;  width: 25%; border: 2px solid black;">
            <div class="loading-text">Loading...</div>
            <div class="spinkit">
                <div class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                </div>
            </div>
            <div class="loading-line" style="animation: loading-line 2s infinite; border-radius: 2px;position: relative;margin: 10px auto; background: rgb(36, 182, 230);height: 7px;width: 60px;"></div>
        </div>
    </div>
</div>
@endsection --}}
<script>
    $(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    let nextPageUrl = '/api/shop?page=1';
    let loading = false;
    let searchQuery = '';

    loadMoreProducts();

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (nextPageUrl && !loading && searchQuery === '') {
                loadMoreProducts();
            }
        }
    });

    function loadMoreProducts() {
        if (loading) return;
        loading = true;
        $('#loading').show();

        $.ajax({
            url: nextPageUrl,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.data.length === 0) {
                    nextPageUrl = ''; // No more pages
                } else {
                    $('#products').append(generateProductsHtml(data.data)); // Append new products
                    nextPageUrl = data.next_page_url; // Update URL for next page
                }
                $('#loading').hide(); // Hide loading indicator
                loading = false; // Reset loading state
            },
            error: function (xhr, status, error) {
                console.error("Error loading more products:", error);
                $('#loading').hide(); // Hide loading indicator on error
                loading = false; // Reset loading state
            }
        });
    }

    function generateProductsHtml(products) {
        let productsHtml = '';
        $.each(products, function (key, value) {
            productsHtml += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-dark product-card" data-id="${value.id}">
                        <div class="card-body gradient-background">
                            <div class="item">
                                <div class="itemDetails">
                                    <div id="carousel-${value.id}" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            ${generateCarouselImages(value.img_path)}
                                        </div>
                                        <a class="carousel-control-prev" href="#carousel-${value.id}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel-${value.id}" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                        <div class="itemText mt-3 text-center">
                                            <p class="product-name">${value.name}</p>
                                            <p class="brand-name">${value.brand_name}</p>
                                            <p class="price-container">Php <span class="price">${value.cost}</span></p>
                                            <p class="itemId" style="display: none;">${value.id}</p>
                                        </div>
                                        <div class="text-center btn-container">
                                            <button type="button" class="btn btn-lightblue add mt-3">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        return productsHtml;
    }

    function generateCarouselImages(imgPath) {
        var carouselHtml = '';
        var images = imgPath.split(',');

        if (images && images.length > 0) {
            for (var i = 0; i < images.length; i++) {
                var activeClass = i === 0 ? 'active' : '';
                carouselHtml += `
                    <div class="carousel-item ${activeClass}">
                        <img src="${images[i].trim()}" class="d-block w-100" style="height: 250px; object-fit: contain;" alt="Product Image">
                    </div>`;
            }
        } else {
            carouselHtml += `
                <div class="carousel-item active">
                    <img src="placeholder.jpg" class="d-block w-100" style="height: 250px; object-fit: contain;" alt="Placeholder Image">
                </div>`;
        }
        return carouselHtml;
    }

    $('#products').on('click', '.product-card', function () {
        var productId = $(this).data('id');
        window.location.href = `/product/${productId}`;
    });

    $('#products').on('click', '.add', function (e) {
        e.stopPropagation();
    });

    // Throttle function to limit the rate at which the search function is called
    function throttle(func, limit) {
        let lastFunc;
        let lastRan;
        return function() {
            const context = this;
            const args = arguments;
            if (!lastRan) {
                func.apply(context, args);
                lastRan = Date.now();
            } else {
                clearTimeout(lastFunc);
                lastFunc = setTimeout(function() {
                    if ((Date.now() - lastRan) >= limit) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    }
                }, limit - (Date.now() - lastRan));
            }
        }
    }

    // Search functionality
    $('#search-input').on('input', throttle(function () {
        searchQuery = $(this).val();
        if (searchQuery.length > 0) {
            searchProducts(searchQuery);
        } else {
            $('#products').empty();
            nextPageUrl = '/api/shop?page=1';
            loadMoreProducts();
        }
    }, 100)); // Throttle with 300ms delay

    function searchProducts(query) {
        $.ajax({
            url: '/api/search',
            type: 'GET',
            data: { query: query },
            dataType: 'json',
            success: function (data) {
                $('#products').empty(); // Clear existing products
                $('#products').append(generateProductsHtml(data.data)); // Display search results
                nextPageUrl = ''; // Stop infinite scroll when searching
            },
            error: function (xhr, status, error) {
                console.error("Error searching products:", error);
            }
        });
    }

    var productId = $('#product-id').val();

    if (productId) {
        fetchProductDetails(productId);
    }

    function fetchProductDetails(productId) {
        $.ajax({
            url: `/api/product/${productId}`,
            method: 'GET',
            success: function(response) {
                const productDetails = response.data;

                $('#product-carousel .carousel-inner').html(generateCarouselImages(productDetails.img_path));
                $('#product-name').text(productDetails.name);
                $('#product-brand').text(productDetails.brand_name);
                $('#product-price').text(productDetails.cost);
                $('#product-rating').html(generateStarRating(productDetails.rating));
                $('#product-description').text(productDetails.description);

                if (productDetails.img_path.length > 0) {
                    productDetails.img_path.forEach((imgPath, index) => {
                        $('#product-carousel .carousel-inner').append(`
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${imgPath}" class="d-block w-100" alt="...">
                            </div>
                        `);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching product details:", error);
            }
        });
    }
});
    </script>
