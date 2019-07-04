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
                video_id: $('#video_id').val()
            },
            success: function (result){
                $('#video_id').val("");
                if (result.error == true) {
                    toastr.error(result.message);
                } else if (result.warning == true) {
                    toastr.warning(result.message);
                } else {
                    $('#song-request-list').html("");
                    result.song_request_data.forEach(data => {
                        if (data.status == 'queue') {
                            spanStatus = '<span class="badge badge-success">In Queue</span>';
                        } else if (data.status == 'played') {
                            spanStatus = '<span class="badge badge-warning">Played</span>';
                        }
                        $('#song-request-list').append(`
                            <div class="card col mb-2">
                                <div class="card-body px-0">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="${data.thumbnail_df}" width="150" alt="${data.title} Thumbnail">
                                        </div>
                                        <div class="col-8">
                                            <h5 class="card-title">${data.title}</h5>
                                            ${spanStatus}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                    toastr.success(result.message);
                    $('#song-request-list .card:last-child').css({
                        'background-color': 'rgb(83, 204, 69)',
                        'color': 'rgb(255,255,255)'
                    });
                    $('#song-request-list .card:last-child').animate({
                        backgroundColor: 'rgb(255,255,255)',
                        color: 'rgb(0,0,0)'
                    }, 3000);
                }
            },
            error: function(e){
                toastr.error("Unknown Error. Error Code " + e.status);
            }
        });
    });
    // ytPlayer();
});

function ytPlayer() {
    // console.log('hai it\'s the player');
    // $('#yt_player').text("hahiahih");
    // // 2. This code loads the IFrame Player API code asynchronously.
    // var tag = document.createElement('script');

    // tag.src = "https://www.youtube.com/iframe_api";
    // var firstScriptTag = document.getElementsByTagName('script')[0];
    // firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    
    // // 3. This function creates an <iframe> (and YouTube player)
    // //    after the API code downloads.
    // var player;
    // function onYouTubeIframeAPIReady() {
    //     player = new YT.Player('yt_player', {
    //         height: '390',
    //         width: '640',
    //         videoId: 'M7lc1UVf-VE',
    //         events: {
    //             'onReady': onPlayerReady,
    //             'onStateChange': onPlayerStateChange
    //         }
    //     });
    // }

    // // 4. The API will call this function when the video player is ready.
    // function onPlayerReady(event) {
    //     event.target.playVideo();
    // }

    // // 5. The API calls this function when the player's state changes.
    // //    The function indicates that when playing a video (state=1),
    // //    the player should play for six seconds and then stop.
    // var done = false;
    // function onPlayerStateChange(event) {
    //     if (event.data == YT.PlayerState.PLAYING && !done) {
    //         setTimeout(stopVideo, 6000);
    //         done = true;
    //     }
    // }
    // function stopVideo() {
    //     player.stopVideo();
    // }
}
