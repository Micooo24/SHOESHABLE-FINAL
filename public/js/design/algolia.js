(function() {
    var client = algoliasearch('6U15BJ87OK', '97a55b8ebb266b1b57f01f3ed49f9c94');
    var index = client.initIndex('products');
    var enterPressed = false;

    autocomplete('#aa-search-input', { hint: false }, {
        source: autocomplete.sources.hits(index, { hitsPerPage: 10 }),
        displayKey: 'name',
        templates: {
            suggestion: function(suggestion) {
                const markup = `
                    <div class="algolia-result">
                        <span>
                            <img src="${window.location.origin}/public/storage/${suggestion.img_path}" alt="" class="algolia-thumb">
                            ${suggestion._highlightResult.name.value}
                        </span>
                        <span>$${(suggestion.cost / 100).toFixed(2)}</span>
                    </div>
                    <div class="algolia-details">
                        <span>${suggestion._highlightResult.description ? suggestion._highlightResult.description.value : ''}</span>
                    </div>
                `;
                return markup;
            },
            empty: function(result) {
                return 'Sorry, we did not find any results for "' + result.query + '"';
            }
        }
    }).on('autocomplete:selected', function(event, suggestion, dataset) {
        window.location.href = window.location.origin + '/product/' + suggestion.id;
        enterPressed = true;
    }).on('keyup', function(event) {});

    // Listen for input changes to fetch and display products dynamically
    $('#aa-search-input').on('input', function() {
        const query = $(this).val();

        if (query.length > 0) {
            index.search(query, { hitsPerPage: 10 })
                .then(({ hits }) => {
                    $('#search-results').html(generateProductsHtml(hits));
                })
                .catch(err => {
                    console.error(err);
                });
        } else {
            $('#search-results').empty();
            window.location.href = '/shop';
        }
    });

    function generateProductsHtml(products) {
        let productsHtml = '';
        $.each(products, function(key, value) {
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
                                        <p class="product-name">${value._highlightResult.name.value}</p>
                                        <p class="brand-name">${value.description}</p>
                                        <p class="price-container">Php <span class="price">${value.cost}</span></p>
                                        <div class="star-rating" style="justify-content: center;">
                                            ${generateStarRating(generateRandomStars())}
                                        </div>
                                        <p class="itemId" style="display: none;">${value.id}</p>
                                    </div>
                                    <div class="text-center btn-container">
                                        <button type="button" class="btn btn-lightblue add mt-3" data-product-id="${value.id}">Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end" style="border-top: none; background: none;width: 100%;bottom: 0;position: absolute;">
                            <a id="wishlist-btn-${value.id}" class="btn wishlist mr-1" data-product-id="${value.id}" style="background-color: white; color: red; border: 1px solid black; font-size: 10px; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-heart"></i>
                            </a>
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

    function generateStarRating(rating) {
        var starsHtml = '';
        var totalStars = 5;
        for (var i = totalStars; i >= 1; i--) {
            var starClass = i <= rating ? 'checked' : '';
            starsHtml += `<span class="fa fa-star ${starClass}"></span>`;
        }
        return starsHtml;
    }

    function generateRandomStars() {
        return Math.floor(Math.random() * 5) + 1;
    }

    $('#search-results').on('click', '.product-card', function(e) {
        const productId = $(this).data('id');
        window.location.href = '/product/' + productId;
    });

    $('#search-results').on('click', '.add', function(e) {
        e.stopPropagation();
        const productId = $(this).data('product-id');
        addToCart(productId);
    });

    $('#search-results').on('click', '.wishlist', function(e) {
        e.stopPropagation();
        const productId = $(this).data('product-id');
        addToWishlist(productId, this);
    });
})();



    // ETO YUNG AKIN TRY MO KUNG MAG ADD TO CART KAPAG NISEARCH SYA
    // function generateProductsHtml(products) {
    //     let productsHtml = '';
    //     $.each(products, function (key, value) {
    //         productsHtml += `
    //             <div class="col-md-4 mb-4">
    //                 <div class="card h-100 border-dark product-card" data-id="${value.id}">
    //                     <div class="card-body gradient-background">
    //                         <div class="item">
    //                             <div class="itemDetails">
    //                                 <div id="carousel-${value.id}" class="carousel slide" data-ride="carousel">
    //                                     <div class="carousel-inner">
    //                                         ${generateCarouselImages(value.img_path)}
    //                                     </div>
    //                                     <a class="carousel-control-prev" href="#carousel-${value.id}" role="button" data-slide="prev">
    //                                         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    //                                         <span class="sr-only">Previous</span>
    //                                     </a>
    //                                     <a class="carousel-control-next" href="#carousel-${value.id}" role="button" data-slide="next">
    //                                         <span class="carousel-control-next-icon" aria-hidden="true"></span>
    //                                         <span class="sr-only">Next</span>
    //                                     </a>
    //                                 </div>
    //                                 <div class="itemText mt-3 text-center">
    //                                     <p class="product-name">${value.name}</p>
    //                                     <p class="brand-name">${value.brand.brand_name}</p>
    //                                     <p class="price-container">Php <span class="price">${value.cost}</span></p>
    //                                     <div class="star-rating" style="justify-content: center;">
    //                                         ${generateStarRating(generateRandomStars())}
    //                                     </div>
    //                                     <p class="itemId" style="display: none;">${value.id}</p>
    //                                 </div>
    //                                 <div class="text-center btn-container">
    //                                     <button type="button" class="btn btn-lightblue add mt-3">Add to cart</button>
    //                                 </div>
    //                                 <div class="card-footer d-flex justify-content-end" style="border-top: none; background: none;width: 100%;bottom: 0;position: absolute;">
    //                                 <a id="wishlist-btn-${value.id}" class="btn wishlist mr-1" data-product-id="${value.id}" style="background-color: white; color: red; border: 1px solid black; font-size: 10px; padding: 10px; border-radius: 5px;">
    //                                     <i class="fas fa-heart"></i>
    //                                 </a>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>`;
    //     });
    //     return productsHtml;
    // }