/**
 * global variable
 */
var _lastSongQueue = 0;

///////////////////////////////////////////////////////////////////////////////

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
          _lastSongQueue = result.song_request_data.queue;
          toastr.success(result.message);
          addLastRequest(result.song_request_data)
        }
      },
      error: function(e){
        toastr.error("Unknown Error. Error Code " + e.status);
      }
    });
  });
});

function addLastRequest(data) {
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
  $('#song-request-list .card:last-child').css({
    'background-color': 'rgb(83, 204, 69)',
    'color': 'rgb(255,255,255)'
  });
  $('#song-request-list .card:last-child').animate({
    backgroundColor: 'rgb(255,255,255)',
    color: 'rgb(0,0,0)'
  }, 3000);
}

function checkNewestSongRequest(url) {
  $.ajax({
    url: url,
    method: 'get',
    success: function (result){
      if(_lastSongQueue < result.queue){
        _lastSongQueue = result.queue;
      }
    },
    error: function(e){
      console.log("Unknown Error. Error Code " + e.status);
    },
    async: false
  });
  setInterval(() => {
    $.ajax({
      url: url,
      method: 'get',
      success: function (result){
        if(_lastSongQueue < result.queue){
          _lastSongQueue = result.queue;
          addLastRequest(result);
        }
      },
      error: function(e){
        console.log("Unknown Error. Error Code " + e.status);
      },
      async: false
    });
  }, 2000);
}
