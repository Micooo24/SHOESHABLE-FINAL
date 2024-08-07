$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: '/api/search',
                type: 'GET',
                data: { query: query },
                success: function(data) {
                    $('#search-results').empty();
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            $('#search-results').append('<div><a href="' + item.url + '">' + item.searchable.name + '</a></div>');
                        });
                    } else {
                        $('#search-results').append('<div>No results found</div>');
                    }
                }
            });
        } else {
            $('#search-results').empty();
        }
    });
});
