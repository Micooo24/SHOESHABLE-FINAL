// $(document).ready(function () {
//     var csrfToken = $('meta[name="csrf-token"]').attr('content');
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': csrfToken
//         }
//     });

//     $('#userTable').DataTable({
//         "lengthChange": false,
//         ajax: {
//             url: "/api/users",
//             dataSrc: ""
//         },
//         columns: [
//             { data: 'id', title: 'ID'},
//             { data: 'name', title: 'Name' },
//             { data: 'email', title: 'Email' },
//             {
//                 data: 'role',
//                 title: 'Role',
//                 render: function (data, type, row) {
//                     return `
//                     <select class='roleDropdown' data-id='${row.id}'>
//                          <option value='admin' ${data === 'admin' ? 'selected' : ''}>Admin</option>
//                          <option value='customer' ${data === 'customer' ? 'selected' : ''}>Customer</option>
//                     </select>
//                     `;
//                 }
//             },
//             {
//                 data: 'status',
//                 title: 'Status',
//                 render: function (data, type, row) {
//                     return `<span class="badge ${data.toLowerCase() === 'active' ? 'badge-success' : 'badge-danger'}">${data}</span>`;
//                 }
//             },
//             {
//                 data: null,
//                 title: 'Actions',
//                 render: function (data, type, row) {
//                     let statusBtn = data.status.toLowerCase() === 'active'
//                         ? `<button class='btn btn-warning btn-sm deactivateBtn' data-id='${data.id}'>Deactivate</button>`
//                         : `<button class='btn btn-success btn-sm activateBtn' data-id='${data.id}'>Activate</button>`;
//                     return `${statusBtn}`;
//                 }
//             }
//         ],
//     });

//     $('#userTable').on('change', '.roleDropdown', function () {
//         let userId = $(this).data('id');
//         let newRole = $(this).val();
//         $.ajax({
//             url: `/api/users/${userId}/role`,
//             type: 'PUT',
//             data: { role: newRole },
//             success: function (response) {
//                 showFlashMessage('User role updated successfully.');
//             },
//             error: function (xhr) {
//                 console.error(xhr);
//                 showFlashMessage('Failed to update user role.');
//             }
//         });
//     });

//     $('#userTable').on('click', '.activateBtn', function () {
//         let userId = $(this).data('id');
//         $.ajax({
//             url: `/api/users/${userId}/activate`,
//             type: 'PUT',
//             success: function (response) {
//                 $('#userTable').DataTable().ajax.reload();
//                 showFlashMessage('User activated successfully.', 'success');
//             },
//             error: function (xhr) {
//                 console.error(xhr);
//                 showFlashMessage('Failed to activate user.', 'danger');
//             }
//         });
//     });

//     $('#userTable').on('click', '.deactivateBtn', function () {
//         let userId = $(this).data('id');
//         $.ajax({
//             url: `/api/users/${userId}/deactivate`,
//             type: 'PUT',
//             success: function (response) {
//                 $('#userTable').DataTable().ajax.reload();
//                 showFlashMessage('User deactivated successfully.', 'success');
//             },
//             error: function (xhr) {
//                 console.error(xhr);
//                 showFlashMessage('Failed to deactivate user.', 'danger');
//             }
//         });
//     });

//     function showFlashMessage(message, type) {
//         var flashMessage = $('#flash-message');
//         flashMessage.removeClass().addClass('alert alert-' + type).html(message).fadeIn();
//         setTimeout(function() {
//             flashMessage.fadeOut();
//         }, 5000);
//     }
// });


$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $('#userTable').DataTable({
        "lengthChange": false,
        ajax: {
            url: "/api/users",
            dataSrc: ""
        },
        columns: [
            { data: 'id', title: 'ID'},
            { data: 'name', title: 'Name' },
            { data: 'email', title: 'Email' },
            {
                data: 'role',
                title: 'Role',
                render: function (data, type, row) {
                    return `
                    <select class='roleDropdown' data-id='${row.id}'>
                        <option value='admin' ${data === 'admin' ? 'selected' : ''}>Admin</option>
                        <option value='customer' ${data === 'customer' ? 'selected' : ''}>Customer</option>
                    </select>
                    `;
                }
            },
            {
                data: 'status',
                title: 'Status',
                render: function (data, type, row) {
                    return `<span class="badge ${data.toLowerCase() === 'active' ? 'badge-success' : data.toLowerCase() === 'inactive' ? 'badge-warning' : 'badge-danger'}">${data}</span>`;
                }
            },
            {
                data: null,
                title: 'Actions',
                render: function (data, type, row) {
                    let actionButtons = '';

                    if (data.status.toLowerCase() === 'inactive') {
                        actionButtons += `<button class='btn btn-info btn-sm' disabled>Cannot Change</button>`;
                    } else if (data.status.toLowerCase() === 'active') {
                        actionButtons += `<button class='btn btn-warning btn-sm deactivateBtn' data-id='${data.id}'>Deactivate</button>`;
                    } else if (data.status.toLowerCase() === 'deactivated') {
                        actionButtons += `<button class='btn btn-success btn-sm activateBtn' data-id='${data.id}'>Activate</button>`;
                    }

                    return actionButtons;
                }
            }
        ],
    });

    $('#userTable').on('change', '.roleDropdown', function () {
        let userId = $(this).data('id');
        let newRole = $(this).val();
        $.ajax({
            url: `/api/users/${userId}/role`,
            type: 'PUT',
            data: { role: newRole },
            success: function (response) {
                showFlashMessage('User role updated successfully.');
            },
            error: function (xhr) {
                console.error(xhr);
                showFlashMessage('Failed to update user role.');
            }
        });
    });

    $('#userTable').on('click', '.activateBtn', function () {
        let userId = $(this).data('id');
        $.ajax({
            url: `/api/users/${userId}/activate`,
            type: 'PUT',
            success: function (response) {
                $('#userTable').DataTable().ajax.reload();
                showFlashMessage('User activated successfully.', 'success');
            },
            error: function (xhr) {
                console.error(xhr);
                showFlashMessage(xhr.responseJSON.error || 'Failed to activate user.', 'danger');
            }
        });
    });

    $('#userTable').on('click', '.deactivateBtn', function () {
        let userId = $(this).data('id');
        $.ajax({
            url: `/api/users/${userId}/deactivate`,
            type: 'PUT',
            success: function (response) {
                $('#userTable').DataTable().ajax.reload();
                showFlashMessage('User deactivated successfully.', 'success');
            },
            error: function (xhr) {
                console.error(xhr);
                showFlashMessage(xhr.responseJSON.error || 'Failed to deactivate user.', 'danger');
            }
        });
    });

    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.removeClass().addClass('alert alert-' + type).html(message).fadeIn();
        setTimeout(function() {
            flashMessage.fadeOut();
        }, 5000);
    }
});
