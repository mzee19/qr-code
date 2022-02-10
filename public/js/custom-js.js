// Nav SideBar
$(document).ready(function () {
    $("#sidebarCollapse").click(function () {
        $("#sidebar").toggleClass("active");
        $("#content").toggleClass("custom-margin");
        $(".content-head").toggleClass("custom-left");
    });

    $('input:radio').change(function () {
        $('.show-radio-input').addClass('hidden');
        var r_id = $(this).attr('data-id');
        $('.inlineRadio' + r_id).removeClass('hidden');
    });
    width = $( window ).width();
    if(width <= 1040){
        $("#sidebar").toggleClass("active");
        $("#content").toggleClass("custom-margin");
        $(".content-head").toggleClass("custom-left");
    }

});


// image upload
function readURL(input) {
    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {
            $('.image-upload-wrap').hide();

            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();

            $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);

    } else {
        removeUpload();
    }
}

// Remove
function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}

$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
});
$('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});
$(document).ready(function() {
    $(document).on('click', 'body', function() {
      $('.custom-navbar-container .collapse').removeClass('show');
    })
  });
