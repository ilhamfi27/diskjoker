$(document).ready(function () {
    toastrOptions();

    function toastrOptions() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "300",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }

    function getCsrfToken() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    $('#songRequestForm').submit(function (e){
        e.preventDefault();
        getCsrfToken();
        $.ajax({
            url: $('#songRequestForm').attr('action'),
            method: 'post',
            data: {
                room_id: $('#room_id').val(),
                url: $('#url').val()
            },
            success: function (result){
                $('#url').val("");
                if (result.error == true) {
                    toastr.error(result.message);
                } else if (result.warning == true) {
                    toastr.warning(result.message);
                } else {
                    $('#song-request-list').html("");
                    result.song_request_data.forEach(data => {
                        $('#song-request-list').append(`
                            <div class="card col mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">${data.url}</h5>
                                    <p class="card-text">Song List Description If Necessary.</p>
                                </div>
                            </div>
                        `);
                    });
                    toastr.success(result.message);
                }
            },
            error: function(e){
                toastr.error("Unknown Error. Error Code " + e.status);
            }
        });
    });
})