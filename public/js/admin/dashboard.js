$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // Function to show flash messages
    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.removeClass();
        flashMessage.addClass('alert alert-' + type);
        flashMessage.html(message);
        flashMessage.fadeIn();

        setTimeout(function() {
            flashMessage.fadeOut();
        }, 5000);
    }

    function fetchDashboardData() {
        $.ajax({
            type: "GET",
            url: "/api/dashboard-data",
            dataType: "json",
            success: function (data) {
                console.log(data);
                // Update your UI with the fetched data
                $('#totalCustomers').text(data.totalCustomers);
                $('#earnings').text('₱' + data.earnings.toFixed(2)); // Assuming earnings is a currency value
                $('#totalTransactions').text(data.totalTransactions);
                $('#lowStockCount').text(data.lowStockCount);
                $('#outOfStockCount').text(data.outOfStockCount);

                // Update charts
                updateLowStockChart(data.lowStockCount);
                updateOutOfStockChart(data.outOfStockCount);
            },
            error: function (error) {
                console.log(error);
                showFlashMessage("Failed to fetch dashboard data.", "danger");
            }
        });
    }

    function updateLowStockChart(count) {
        var ctx = document.getElementById('lowStockChart').getContext('2d');
        var lowStockChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Low Stock'],
                datasets: [{
                    label: 'Low Stock',
                    data: [100 - count, count],
                    backgroundColor: [
                        '#36A2EB',
                        '#FFCE56'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Low Stock Status'
                }
            }
        });
    }

    function updateOutOfStockChart(count) {
        var ctx = document.getElementById('outOfStockChart').getContext('2d');
        var outOfStockChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Out of Stock'],
                datasets: [{
                    label: 'Out of Stock',
                    data: [100 - count, count],
                    backgroundColor: [
                        '#4BC0C0',
                        '#FF6384'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Out of Stock Status'
                }
            }
        });
    }
    fetchDashboardData();
});
