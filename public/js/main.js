/**
 * global variable
 * I declare it with g letter on the beginning of the name of the
 * variable
 */
var gLastSongQueue = 0;
var gUserIsRoomMaster = false;

///////////////////////////////////////////////////////////////////////////////

$(document).ready(function () {
  toastrOptions();
  submitSongRequestForm();
  removeSongOnClick();
  removeSongModalButtonClick();
});

function toastrOptions() {
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "2000",
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

function submitSongRequestForm() {
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
          gLastSongQueue = result.song_request_data.queue;
          toastr.success(result.message);
        }
      },
      error: function(e){
        toastr.error("Unknown Error. Error Code " + e.status);
      }
    });
  });
}

function removeSongModalButtonClick() {
  $('.delete-song-button-modal').click(function(delEvent){
    var songId = $(this).data('id');
    console.log(APP_URL + ":8000/song_request/" + songId);
    
    getCsrfToken();
    $.ajax({
      url: APP_URL + ":8000/song_request/" + songId,
      method: 'delete',
      success: function(result){
        if(result.error == false){
          $('#delete-song-modal').modal('hide');
          toastr.success(result.message);
        } else if(result.error == true){
          $('#delete-song-modal').modal('hide');
          toastr.waring(result.message);
        }
      },
      error: function(error){
        toastr.error("Unknown Error. Error Code " + error.status);
      }
    });
  });
}

function removeSongOnClick() {
  $('#song-request-list').on('click', '.remove-song-button', function(event){
    var songId = $(this).data('id');
    $('.delete-song-button-modal').data('id', songId);
    $('#delete-song-modal').modal('show');
  });
}

function addLastRequest(data) {
  var removeButton = '';
  if (data.status == 'queue') {
    spanStatus = '<span class="badge badge-success">In Queue</span>';
  } else if (data.status == 'played') {
    spanStatus = '<span class="badge badge-warning">Played</span>';
  }

  if(gUserIsRoomMaster){
    removeButton = `
    <button type="button" class="close remove-song-button" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    `;
  }
  
  $('#song-request-list').append(`
    <div class="card col mb-2">
      <div class="card-body px-0">
        <div class="row">
          <div class="col-4">
            <img src="${data.thumbnail_df}" width="150" alt="${data.title} Thumbnail">
          </div>
          <div class="col-8">
            ${removeButton}
            <h5 class="card-title">${data.title}</h5>
            ${spanStatus}
          </div>
        </div>
      </div>
    </div>
  `);
  $('#song-request-list .card:last-child').css({
    'background-color': 'rgb(83, 204, 69)',
    'color': 'rgb(255,255,255)'
  });
  $('#song-request-list .card:last-child').animate({
    backgroundColor: 'rgb(255,255,255)',
    color: 'rgb(0,0,0)'
  }, 3000);
}

function pusherListenToSongTransaction(init) {
  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;
  
  var pusher = new Pusher(init.key, {
      cluster: init.cluster,
      forceTLS: true
  });

  var channel = pusher.subscribe('songRequestTranscaction');

  channel.bind('App\\Events\\SongRequestAdded', function(data) {
    data.songRequest.id !== undefined ? addLastRequest(data.songRequest) : null;
    $('.empty-request-card').css({
      "display": "hidden",
    });
  });

  channel.bind('App\\Events\\SongRequestDeleted', function(data) {
    $('.empty-request-card').css({
      "display": "block",
    });
    $('.card-'+data.id).remove();
  });
}

function setRoomMasterTrue() {
  gUserIsRoomMaster = true;
}
