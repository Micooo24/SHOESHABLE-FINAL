// $(document).ready(function () {
//     var csrfToken = $('meta[name="csrf-token"]').attr('content');
//     console.log('CSRF Token:', csrfToken);
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': csrfToken
//         }
//     });

//     // Function to generate random colors
//     function generateRandomColors(count) {
//         var colors = [];
//         for (var i = 0; i < count; i++) {
//             var letters = '0123456789ABCDEF';
//             var color = '#';
//             for (var x = 0; x < 6; x++) {
//                 color += letters[Math.floor(Math.random() * 16)];
//             }
//             colors.push(color);
//         }
//         return colors;
//     }

//     $.ajax({
//         type: "GET",
//         url: "/api/admin/sales-chart",
//         dataType: "json",
//         success: function (data) {
//             console.log(data.labels, data.data);
//             var ctx = $("#salesChart");
//             var myBarChart = new Chart(ctx, {
//                 type: 'bar',
//                 data: {
//                     labels: data.labels,
//                     datasets: [{
//                         label: 'Month Sales Chart',
//                         data: data.data,
//                         backgroundColor: generateRandomColors(data.data.length),
//                         borderColor: generateRandomColors(data.data.length),
//                         borderWidth: 1,
//                     }]
//                 },
//                 options: {
//                     scales: {
//                         y: {
//                             beginAtZero: true
//                         }
//                     },
//                     indexAxis: 'y',
//                 },
//             });

//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });

//     $.ajax({
//         type: "GET",
//         url: "/api/admin/stock-chart",
//         dataType: "json",
//         success: function (data) {
//             console.log(data);
//             var ctx = $("#stockChart");
//             var myBarChart = new Chart(ctx, {
//                 type: 'bar',
//                 data: {
//                     labels: data.labels,
//                     datasets: [{
//                         label: 'Monthly sales',
//                         data: data.data,
//                         backgroundColor: generateRandomColors(data.data.length),
//                         borderColor: generateRandomColors(data.data.length),
//                         borderWidth: 1
//                     }]
//                 },
//                 options: {
//                     scales: {
//                         y: {
//                             beginAtZero: true
//                         }
//                     }
//                 },
//             });

//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });

//     $.ajax({
//         type: "GET",
//         url: "/api/admin/product-chart",
//         dataType: "json",
//         success: function (data) {
//             console.log(data);
//             var ctx = $("#productChart");
//             var myDoughnutChart = new Chart(ctx, {
//                 type: 'doughnut',
//                 data: {
//                     labels: data.labels,
//                     datasets: [{
//                         label: 'Popular Brands',
//                         data: data.data,
//                         backgroundColor: generateRandomColors(data.data.length),
//                         borderColor: generateRandomColors(data.data.length),
//                         borderWidth: 1,
//                         responsive: true,
//                     }]
//                 },
//             });

//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });

//     $.ajax({
//         type: "GET",
//         url: "/api/admin/product-sales",
//         dataType: "json",
//         success: function (data) {
//             console.log(data.labels, data.data);
//             var ctx = $("#ratingsChart");
//             var myBarChart = new Chart(ctx, {
//                 type: 'bar',
//                 data: {
//                     labels: data.labels,
//                     datasets: [{
//                         label: 'Product Ratings',
//                         data: data.data,
//                         backgroundColor: generateRandomColors(data.data.length),
//                         borderColor: generateRandomColors(data.data.length),
//                         borderWidth: 1,
//                     }]
//                 },
//                 options: {
//                     scales: {
//                         y: {
//                             beginAtZero: true
//                         }
//                     },
//                     indexAxis: 'y',
//                 },
//             });

//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// });


$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // Function to generate random colors
    function generateRandomColors(count) {
        var colors = [];
        for (var i = 0; i < count; i++) {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var x = 0; x < 6; x++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            colors.push(color);
        }
        return colors;
    }

    $.ajax({
        type: "GET",
        url: "/api/admin/sales-chart",
        dataType: "json",
        success: function (data) {
            console.log(data.labels, data.data);
            var ctx = $("#salesChart");
            var salesBarChart = new Chart(ctx, { // Renamed variable
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Month Sales Chart',
                        data: data.data,
                        backgroundColor: generateRandomColors(data.data.length),
                        borderColor: generateRandomColors(data.data.length),
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    indexAxis: 'y',
                },
            });

        },
        error: function (error) {
            console.log(error);
        }
    });

    $.ajax({
        type: "GET",
        url: "/api/admin/stock-chart",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var ctx = $("#stockChart");
            var stockBarChart = new Chart(ctx, { // Renamed variable
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Monthly sales',
                        data: data.data,
                        backgroundColor: generateRandomColors(data.data.length),
                        borderColor: generateRandomColors(data.data.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            });

        },
        error: function (error) {
            console.log(error);
        }
    });

    $.ajax({
        type: "GET",
        url: "/api/admin/product-chart",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var ctx = $("#productChart");
            var productDoughnutChart = new Chart(ctx, { // Renamed variable
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Popular Brands',
                        data: data.data,
                        backgroundColor: generateRandomColors(data.data.length),
                        borderColor: generateRandomColors(data.data.length),
                        borderWidth: 1,
                        responsive: true,
                    }]
                },
            });

        },
        error: function (error) {
            console.log(error);
        }
    });

    $.ajax({
        type: "GET",
        url: "/api/admin/product-sales",
        dataType: "json",
        success: function (data) {
            console.log(data.labels, data.data);
            var ctx = $("#ratingsChart");
            var productRatingsBarChart = new Chart(ctx, { // Renamed variable
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Product Ratings',
                        data: data.data,
                        backgroundColor: generateRandomColors(data.data.length),
                        borderColor: generateRandomColors(data.data.length),
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    indexAxis: 'y',
                },
            });

        },
        error: function (error) {
            console.log(error);
        }
    });
});
