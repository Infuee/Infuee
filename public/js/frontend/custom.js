Dropzone.autoDiscover = false;
$(document).ready(function() {

    $('select').select2();

    // Transition effect for navbar 
    $(window).scroll(function() {
        // checks if window is scrolled more than 500px, adds/removes solid class
        if ($(this).scrollTop() > 10) {
            $('.navbar').addClass('solid');
        } else {
            $('.navbar').removeClass('solid');
        }
    });

    $('.slick').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,

        prevArrow: $('.left-arrow'),
        nextArrow: $('.right-arrow'),
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    }); 


    $('.music-slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,

        prevArrow: $('.prev-arrow'),
        nextArrow: $('.next-arrow'),
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: true,
                    dots: true
                }
            }, {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    // Video Slick slider

    $('.video-slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,

        prevArrow: $('.prev-arrow1'),
        nextArrow: $('.next-arrow1'),
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            }, {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });


    // $('.single-slick').slick();

    $('.single-slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.prev-arrow'),
        nextArrow: $('.next-arrow')

    });


    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $('#date-of-birth').datepicker({
        endDate: new Date(),
        format: 'yyyy-mm-dd'
    });

    if (typeof minPriceInRupees !== 'undefined') {
        $("#price-range").slider({
            range: true,
            min: minPriceInRupees,
            max: maxPriceInRupees,
            values: [currentMinValue, currentMaxValue],
            slide: function(event, ui) {
                currentMinValue = ui.values[0];
                currentMaxValue = ui.values[1];
                $('#min-price').text(currentMinValue);
                $('#max-price').text(currentMaxValue);
                price_min = currentMinValue;
                price_max = currentMaxValue;
            },
            stop: function(event, ui) {
                currentMinValue = ui.values[0];
                currentMaxValue = ui.values[1];
                $('#min-price').text(currentMinValue);
                $('#max-price').text(currentMaxValue);
                price_min = currentMinValue;
                price_max = currentMaxValue;
                page = 1 ;
                searchInfluencer();
            }
        });

        $("#location-radious-range").slider({
            range: true,
            max: maxKMDistance,
            values: [0, maxKMDistance],
            slide: function(event, ui) {
                currentMinValue = ui.values[0];
                currentMaxValue = ui.values[1];
                $('#radious').text(currentMaxValue);
            },
            stop: function(event, ui) {
                currentMinValue = ui.values[0];
                currentMaxValue = ui.values[1];
                $('#radious').text(currentMaxValue);
                radious = currentMaxValue;
                page = 1 ;
                searchInfluencer();
            }
        });

    }

    $('.platform_checkbox').change(function() {

        if ($(this).is(':checked')) {
            platforms += ($.trim(platforms) != '' ? ',' : '') + $(this).val();
        } else {
            platforms = platforms.replace($(this).val(), '');
        }
        page = 1 ;
        searchInfluencer();
    })

    $(document).ready(function(){
        var url = window.location.href.slice(window.location.href.indexOf('?') + 1);
        var urlparam = url.split('='); 
        if(urlparam[1] === undefined){
            $('input:radio[name="starrating"]').filter('[value="5"]').attr('checked', false);
        } else {
            var raiting_filter = "5"; 
            /*$('input:radio[name="starrating"]').filter('[value="5"]').attr('checked', true).trigger("click");
            $("input:radio:first").prop("checked", true).trigger("click");*/
            $('#5-stars').on('click', function () {
            }).trigger('click');
        }
        $("input:checkbox[class=platform_checkbox]").each(function () {
            if(urlparam[1] == $(this).attr("id")){
                var ids = $(this).attr("id");
                $('#'+ids).prop('checked', true);
                $('#'+ids).trigger("change");

            }
             
        });
    })



    $('.price_order').change(function() {
        price_order = $('.price_order:checked').val()
        console.log('price_order', price_order);
        page = 1 ;
        searchInfluencer();
    })

    /**********Reating Filter****************/
    $('.raiting_filter').change(function() {
        raiting_filter = $('.raiting_filter:checked').val();
        console.log('raiting_filter', raiting_filter);
        page = 1 ;
        searchInfluencer();
    })

    $('.category_checkbox').change(function() {

        if ($(this).is(':checked')) {
            category_checkbox += ($.trim(category_checkbox) != '' ? ',' : '') + $(this).val();
        } else {
            category_checkbox = category_checkbox.replace($(this).val(), '');
        }
        page = 1 ;
        searchInfluencer();
    })
    $('.race_checkbox').change(function() {

        if ($(this).is(':checked')) {
            race_checkbox += ($.trim(race_checkbox) != '' ? ',' : '') + $(this).val();
        } else {
            race_checkbox = race_checkbox.replace($(this).val(), '');
        }
        page = 1 ;
        searchInfluencer();
    })

    $('#influencer_age').change(function() {
        age = $(this).val();
        page = 1 ;
        searchInfluencer();
    })

    $('#address').change(function() {
        if ($(this).val() == '') {
            lat = '';
            lng = '';
            page = 1 ;
            searchInfluencer();
        }
    })
    var delayTimer;
    $('#search_keyword').on('keyup', function() {
        search = $(this).val();

        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            page = 1 ;
            searchInfluencer();
        }, 1000);

    })

    $(document).on('click', '.load_next_page', function() {
        page = $(this).attr('nextpage');
        $(this).hide();
        // page = 1 ;
        searchInfluencer();
    })

    $('.verify-media_box').on('click', function() {

        $('.category-select').show();

    });

    $('.card-radio').on('change', function() {
        console.log('clickeddd')
        if ($(this).val() == 'new') {
            $('.card-detail').show();
            $('.savedcard-detail').hide();
            $('#cardName').val('');
        } else {
            $('.card-detail').hide();
            $('#savedcardNumber span').text($(this).attr('cardnumber'))
            $('#savedcardexpiry span').text($(this).attr('expiry'))
            $('#savedcardexpiry span').text($(this).attr('expiry'));
            $('#cardName').val($(this).attr('cardholdername'));
            $('.savedcard-detail').show();
        }

    })
    if ($('#mydocumentdropzone').length) {

        var attachment = [];
        var myDropzone = [];;
        $("#mydocumentdropzone").dropzone({
            url: HOST_URL + "/upload/job/attachments",
            addRemoveLinks: true,
            acceptedFiles: "image/*,.mp4,.mov,.avi,.mpeg, .flv, .mkv, mpg, .webm, .xls,.xlsx,.csv,.doc,.docx,.txt,application/doc,application/pdf,application/txt,'text/csv,application/vnd.ms-excel',application/ppt,application/pptx,application/docx",
            dictRemoveFile: 'Remove',
            params: {
                _token: $('input[name="_token"]').val(),
                ticket_id: $('#ticket_id').val(),
                type: $('#ticket_type').val()
            },
            maxFilesize: 5,
            init: function() {
                myDropzone = this;
            },
            success: function(file, done) {
                attachment.push(done.success.original);

                $('#proposal_attachments').val(attachment);
                var mockup = { name: done.success.original, size: done.success.size };
                var filename = (file.name); // Get extension
                var newimage = "";

                var filename = filename.toLowerCase();
                var ext = filename.split('.').pop();
                var thumbImg = '';
                if (ext == "pdf") {
                    thumbImg = HOST_URL + '/images/pdf.png';
                } else if (ext == 'mp4' || ext == 'mov' || ext == 'avi') {
                    thumbImg = HOST_URL + "/images/video.png"; // default image path
                } else if (ext != 'png' && ext != 'jpg' && ext != 'jpeg') {
                    thumbImg = HOST_URL + "/images/contract.png"; // default image path
                } else {
                    thumbImg = HOST_URL + "/images/image.png"; // default image path
                }
                this.emit('thumbnail', mockup, thumbImg);
                this.emit('complete', mockup)
            },

            removedfile: function(file) {
                var _ref;
                var token = $('meta[name="csrf-token"]').attr('content');
                if (file.previewElement) {
                    if ((_ref = file.previewElement) != null) {
                        _ref.parentNode.removeChild(file.previewElement);
                    }
                }
                var filename = (file.name).substring((file.name).lastIndexOf('/') + 1);
                var myString = $(document).find('#proposal_attachments').val().split(',');
                for (var i = 0; i < myString.length; i++) {
                    if (myString[i] === filename) {
                        myString.splice(i, 1);
                    }
                }
                for (var i = 0; i < attachment.length; i++) {
                    if (attachment[i] === filename) {
                        attachment.splice(i, 1);
                    }
                }
                $(document).find('#proposal_attachments').val(myString.toString());
                var document_id = ($('input[name="document_id"]').val() !== undefined) ? $('input[name="document_id"]').val() : '';
                if (document_id == '') {
                    document_id = $('input[name="_id"]').val();
                }
                $.ajax({
                    type: 'POST',
                    url: HOST_URL + '/remove/job/attachments',
                    dataType: "json",
                    data: { FileName: filename, _token: token, document_id: document_id },
                    success: function(result) {
                        console.log(result);
                    }
                });
            }
        });


        var existing = $('#existing_roposal_attachments').val();
        var el = JSON.parse(existing);

        if (el.length) {
            $(el).each(function(key, value) {
                var file = { name: value.original, size: value.size };
                myDropzone.options.addedfile.call(myDropzone, file);



                var filename = (file.name); // Get extension
                var newimage = "";

                var filename = filename.toLowerCase();
                var ext = filename.split('.').pop();
                console.log(ext)
                    // Check extension
                var thumbImg = '';
                if (ext == "pdf") {
                    thumbImg = HOST_URL + '/images/pdf.png';
                } else if (ext == 'mp4' || ext == 'mov' || ext == 'avi') {
                    thumbImg = HOST_URL + "/images/video.png"; // default image path
                } else if (ext != 'png' && ext != 'jpg' && ext != 'jpeg' && ext != 'svg') {
                    thumbImg = HOST_URL + "/images/contract.png"; // default image path
                } else {
                    thumbImg = value.original; // default image path
                }

                myDropzone.options.thumbnail.call(myDropzone, file, thumbImg);

                myDropzone.emit("complete", file);
            });
        }

    }
});


$('#search_influencers').on('keyup', function() {
    var searchKey = $(this).val();

    $('#influencer-container').load(HOST_URL + '/influencers?search=' + searchKey + ' #influencer-container', function() {
        console.log('loaded');
    })
});

$('#manage_profile_form .edit_icon img').on('click', function() {

    $(this).closest('.form-group').find('input').prop('readonly', false);
    $(this).closest('.form-group').find('.edit_icon').hide();
    $(this).closest('.form-group').find('.update_icon').show();
});


if ($('#editor').length) {
     config.extraPlugins = 'html5video,widget,widgetselection,clipboard,lineutils';
    ClassicEditor
        .create(document.querySelector('#editor'), {
            removePlugins: ['Blockquote', 'Image', 'List'],
            toolbar: { items: ["heading", "|", "bold", "italic", "bulletedList", "numberedList", "|", "blockQuote", "insertTable", "undo", "redo"] },
        })
        .catch(error => {
            //console.error( error );
        });
}

$('#profileImage').on('change', function() {
    var input = this;
    var url = $(this).val();
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#blah').attr('src', 'media/users/blank.png');
    }
})

$('#delete_bank_account').on('click', function() {
    event.preventDefault();
    var account = $('input[name="bank_account"]').val();
    var token = $('meta[name="csrf-token"]').attr('content');
    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete your bank account ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Delete",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            $('.loader').show();
            $.ajax({
                url: HOST_URL + '/deletebankaccount',
                data: { account: account, _token: token },
                type: 'post',
                success: function(result) {
                    $('.loader').hide();
                    swal.fire({
                        text: result.message,
                        icon: result.success ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        }
    });

});


$('.remove_coupon').on('click', function() {
    var coupon = $(this).data('couponid');

    var token = $('meta[name="csrf-token"]').attr('content');

    var this_ = this;

    var data = {
        _token: token,
        coupon: coupon,
    };

    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove coupon ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Remove",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            $('.loader').show();
            $.ajax({
                url: HOST_URL + '/removecoupon',
                data: data,
                type: 'post',
                success: function(result) {
                    $('.loader').hide();
                    swal.fire({
                        text: result.message,
                        icon: result.success ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        }
    });
})

$('.update_icon img').on('click', function() {

    var this_ = this;

    var data = {
        _token: token,
        coupon: coupon,
    };

    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove coupon ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Remove",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            $('.loader').show();
            $.ajax({
                url: HOST_URL + '/removecoupon',
                data: data,
                type: 'post',
                success: function(result) {
                    $('.loader').hide();
                    swal.fire({
                        text: result.message,
                        icon: result.success ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        }
    });
})

$('.update_icon img').on('click', function() {

    var field = $(this).closest('.form-group').find('input').attr('name');
    var val = $(this).closest('.form-group').find('input').val();

    var token = $('meta[name="csrf-token"]').attr('content');

    var this_ = this;

    var data = {
        _token: token,
        field: field,
        val: val
    }
    $('.loader').show();
    $.post({
        url: HOST_URL + '/update_profile_details',
        data: data,
        success: function(res) {
            $('.loader').hide();
            $(this_).closest('.form-group').find('input').prop('readonly', true);
            $(this_).closest('.form-group').find('.edit_icon').show();
            $(this_).closest('.form-group').find('.update_icon').hide();

            $.notify({
                message: res.message
            }, {
                element: 'body',
                position: null,
                type: res.status,
                allow_dismiss: true,
                newest_on_top: false,
                placement: {
                    from: "top",
                    align: "right"
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss"></button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
            });
        }
    })

});


$('.remove_card').on('click', function() {

    var card_id = $(this).data('id');

    var token = $('meta[name="csrf-token"]').attr('content');

    var this_ = this;

    var data = {
        _token: token,
        card_id: card_id,
    };

    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to accept remove this card ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Remove",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            $('.loader').show();
            $.ajax({
                url: HOST_URL + '/removecard',
                data: data,
                type: 'post',
                success: function(result) {
                    $('.loader').hide();
                    swal.fire({
                        text: result.message,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });
        }
    });


});

$('.accept_order').on('click', function() {

    var item = $(this).data('item');

    var token = $('meta[name="csrf-token"]').attr('content');

    var this_ = this;

    var data = {
        _token: token,
        item: item,
    };

    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to accept this order ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Accept",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            //$('.loader').show();
             $('.loader-wrapper').toggleClass('d-flex');
            $.ajax({
                url: HOST_URL + '/acceptorder',
                data: data,
                type: 'post',
                success: function(result) {
                    //$('.loader').hide();
                    $('.loader-wrapper').toggleClass('d-flex');
                    swal.fire({
                        text: result.message,
                        icon: result.success ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        if (result.success) {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });


});

$('.buy_again').on('click', function() {
    event.preventDefault();
    event.stopPropagation();
    var this_ = this;
    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to purchage this offer again ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Buy",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location = $(this_).attr('href');
        }
    });
});

$('.order-details').on('click', function() {
    $('#order-details-instructions').html($(this).data('details'));
    $('#order-details').modal('show');
})

$(document).ready(function() {
    $('#contact-us-form,#user_login_page,#user-registration-from,#change-password-form,#manage_profile_form,#plan-setting-form').on("submit", function() {
        $('.loader').show();
    });
    setTimeout(function() {
        $('.flash-message').hide();
    }, 3000)
    $('.content_layout').css('min-height', ($(window).height() - $('html').height() + 80) + 'px');
});
// $(document).load(function(){
//     console.log('sdgsdgsdgsdsdgsdg',$('.content_layout').css('min-height', $(window).height() - $('html').height() +'px'));
// });

$(document).on('keyup', '#email', function() {
    $('#email-error').hide();
});

$(document).on('change', '.signup_form select[name ="month"]', function() {
    var val = $('.signup_form input[name ="day"]').val('');
});

$(document).on('change', '.signup_form select[name ="year"]', function() {
    var val = $('.signup_form input[name ="day"]').val('');
});

$(document).on('keyup', '.signup_form input[name ="day"]', function(event) {
    var val = $(this).val();
    var month = $('.signup_form select[name ="month"]').val();
    if (month == '') {
        $('.signup_form input[name ="day"]').val(days);
    } else {
        if (month == 2) {
            var year = $('.signup_form select[name ="year"]').val();
            if (year % 4 == 0) {
                var days = 29;
            } else {
                var days = 28;
            }
        } else {
            if (month % 2 == 0) {
                var days = 30;
            } else {
                var days = 31;
            }
        }
        if (val > days) {
            $('.signup_form input[name ="day"]').val(days);
            // event.preventDefault();
        }
    }
});

$('#low_price').on('keyup', function() {
    var maxPrice = $(this).data('maxprice');
    maxprice = parseInt(maxPrice);
    var value = $(this).val();
    var _value = $(this).val();
    var range = document.getElementById('min_range');
    range.value = parseInt((100 * value) / maxprice);


    var value = (100 / (parseInt(range.max) - parseInt(range.min))) * parseInt(range.value) - (100 / (parseInt(range.max) - parseInt(range.min))) * parseInt(range.min);
    var children = range.parentNode.childNodes[1].childNodes;
    children[1].style.width = value + '%';
    children[5].style.left = value + '%';
    children[7].style.left = value + '%';
    children[11].style.left = value + '%';
    var calPrice = parseInt((parseInt(range.dataset.maxprice) / 100) * value);
    children[11].childNodes[1].innerHTML = '$' + _value;
});


$('#high_price').on('keyup', function() {
    var maxPrice = $(this).data('maxprice');
    maxprice = parseInt(maxPrice);
    var value = $(this).val();
    var _value = $(this).val();
    var range = document.getElementById('max_range');
    range.value = parseInt((100 * value) / maxprice);


    var value = (100 / (parseInt(range.max) - parseInt(range.min))) * parseInt(range.value) - (100 / (parseInt(range.max) - parseInt(range.min))) * parseInt(range.min);
    var children = range.parentNode.childNodes[1].childNodes;
    children[3].style.width = (100 - value) + '%';
    children[5].style.right = (100 - value) + '%';
    children[9].style.left = value + '%';
    children[13].style.left = value + '%';
    var calPrice = parseInt((parseInt(range.dataset.maxprice) / 100) * value);
    children[13].childNodes[1].innerHTML = '$' + _value;
});


$('#apply-coupon').on('click', function() {
    $('.code-error-msg').text('');
    var code = $('.coupon_code_input input').val();
    console.log(code);
    if ($.trim(code) == '') {
        $('.code-error-msg').text('Please enter coupon code.');
        return;
    }

    var token = $('meta[name="csrf-token"]').attr('content');

    var this_ = this;

    var data = {
        _token: token,
        code: code
    };

    $.ajax({
        url: HOST_URL + '/applycoupon',
        data: data,
        type: 'post',
        success: function(result) {
            $('.loader').hide();
            swal.fire({
                text: result.message,
                icon: result.success ? 'success' : 'error',
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then(function() {
                if (result.success) {
                    window.location.reload();
                }
            });
        }
    });



});

$(function() {
    $('#coupon_code').on('keypress', function(e) {
        if (e.which == 32){
            // console.log('Space Detected');
            return false;
        }
    });
});

var page = 1;
var response_recieved = true;
$(document).on('click', '.load-more-influencers', function() {
    var _this = this;
    if (!response_recieved) {
        return;
    }
    page = page + 1;
    var search = $('#search_influencers').val();
    var order = $('#order').val();
    var get_category = $('#get_category').val();
    $.ajax({
        url: HOST_URL + '/influencers?page=' + page + '&search=' + search + '&order=' + order + '&category=' + get_category,
        beforeSend: function(xhr) {
            $('.loader').show();
        },
        success: function(result) {
            if (!result.is_more) {
                $(_this).hide();
            }
            $('#influencer-container').append(result.html)
        },
        complete: function() {
            $('.loader').hide();
        }
    });
})

// if($('#address').length){
//     initiateAddress();
// }

function intiallizeAutocomplete() {
    if ($('#address').length) {
        initiateAddress();
    }

}

function initiateAddress() {

    var autocomplete
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('address')), {
        types: ['geocode']
    });
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        $('#address').parent().find('.fv-plugins-message-container').hide();
        var place = autocomplete.getPlace();
        var address1 = place.formatted_address;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        var latlng = new google.maps.LatLng(latitude, longitude);
        var geocoder = geocoder = new google.maps.Geocoder();
        console.log(place.address_components);

        if (place.address_components.length > 4) {
            var city = place.address_components[place.address_components.length - 4];
            var state = place.address_components[place.address_components.length - 3];
            var country = place.address_components[place.address_components.length - 2];
        } else {
            var city = place.address_components[place.address_components.length - 3];
            var state = place.address_components[place.address_components.length - 2];
            var country = place.address_components[place.address_components.length - 1];
        }
        $('#city').val(city.long_name);
        $('#state').val(state.short_name);
        $('#country').val(country.long_name);
        if ($('#lat').length) {
            $('#lat').val(latitude);
        }
        if ($('#lng').length) {
            $('#lng').val(longitude);
        }

        if ($('#address').hasClass('influencer_search')) {
            lat = latitude;
            lng = longitude;
            page = 1 ;
            searchInfluencer();
        }

    });

}

$('.insta-login').click(function() {
    $('#be-influencer-form').submit();
});

$(document).on("input", ".numeric", function() {
    var maxlength = $(this).attr('data-maxlength');
    var minlength = $(this).attr('data-minlength');
    maxlength = parseInt(maxlength);
    minlength = parseInt(minlength);
    this.value = parseInt(this.value);
    if (this.value > maxlength) {
        $(this).val(maxlength);
        return false;
    } else if (this.value < minlength && this.value != '') {
        $(this).val(minlength);
        return false;
    }
    this.value = this.value.replace(/\D/g, '');
});

$('.fa-star-add').click(function() {
    var start_rating = 0;
    var id = $(this).attr('data-id');
    if ($(this).hasClass('checked')) {
        $(this).removeClass('checked');
        var i = 1;
        $('.fa-star').each(function() {
            if (i >= id) {
                $(this).removeClass('checked');
            }
            i++;
        });
    } else {
        var i = 1;
        $('.fa-star').each(function() {
            if (i <= id) {
                $(this).addClass('checked');
            }
            i++;
        });
        $(this).addClass('checked');
    }

    $('.fa-star').each(function() {
        if ($(this).hasClass('checked')) {
            start_rating++;
        }
    });
    // console.log(start_rating);
    $("#start_rating").val(start_rating);
});

$('.add_rating').click(function() {
    var order_id = $(this).attr('data-id');
    $('#ratings_form').find('#order_id').val(order_id);
});

$('#ratings_form_submit').click(function() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var order_id = $('#order_id').val();
    var rating = $('input[name="rating"]:checked').val();
    var review = $('#review').val();
    if (rating == '' || rating == 0 || rating == undefined) {
        $('.error_rating').show();
    } else {
        $('.loader').show();
        $.ajax({
            url: HOST_URL + '/add-ratings',
            data: { order_id: order_id, review: review, rating: rating, _token: token },
            type: 'post',
            success: function(result) {
                $('.loader').hide();
                swal.fire({
                    text: 'Rating added successfully',
                    icon: 'success',
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    $('#ratingModal').modal('hide');
                    $('.error_rating').hide();
                    $('.fa-star').removeClass('checked');
                    $('input[name="rating"]').attr("checked", false);
                    $('#review').val('');
                });
            }
        });
    }
});

$(".mark_done").click(function() {
    var order_id = $(this).attr('data-id');
    var token = $('meta[name="csrf-token"]').attr('content');
    if ($(this).is(':checked')) {
        var mark_done = 1;
        var text = 'Mark as done successfully';
    } else {
        var mark_done = 0;
        var text = 'Removed from mark as done';
    }
    $.ajax({
        url: HOST_URL + '/mark-done',
        data: { order_id: order_id, mark_done: mark_done, _token: token },
        type: 'post',
        success: function(result) {
            $('.loader').hide();
            swal.fire({
                text: text,
                icon: 'success',
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then(function() {

            });
        }
    });
});

$(".hirebtn1").click(function(){
    event.preventDefault();
    var jobId = $(this).attr('data-jobid');
    var jobhiretatus = $(this).attr('data-jobhiretatus');
    var jobhireinfluencers= $(this).attr('data-influencers');
    var nameinfluencers= $(this).attr('data-name');
    var checkValidCost = $(this).attr('data-cost');
    var jobCost = $(this).attr('data-jobcost');
    var datawalletamount = $(this).attr('data-wallet-amount');
    var joburl = $(this).attr('data-url');
    

    if(checkValidCost == '0'){
            swalWithBootstrapButtons.fire({
                title: 'Sorry',
                text: 'Your wallet amount is not enough to ' + nameinfluencers + '!!',
                icon: 'error',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(e) {
                location.href = "/fund-wallet" ;
            });
           return false;

    }
    if(parseFloat(datawalletamount) < parseFloat(jobCost)){
       
            swalWithBootstrapButtons.fire({
                title: 'Sorry',
                text: 'You dont have sufficient balance for this job.Please update your wallet.',
                icon: 'error',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(e) {
                location.href = "/fund-wallet";
            });
           return false;

    }
    swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: " " + nameinfluencers + "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, I want!! ',
            cancelButtonText: 'Cancel',
            reverseButtons: true
    }).then(function(e) {

        if (e.value == true) {
            $('.loader-wrapper').toggleClass('d-flex');
             $.ajax({
                   type:'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                   url: HOST_URL + '/approve-job-post',
                   data:{jobId:jobId,jobhiretatus:jobhiretatus,joburl:joburl,nameinfluencers:nameinfluencers,jobhireinfluencers:jobhireinfluencers,jobCost:jobCost},
                   beforeSend: function() {
                      $("#loading-image").show();
                   },
                   success:function(data){
                        $('.loader-wrapper').toggleClass('d-flex');
                        
                        if(data.success){

                            swal.fire({
                                icon: 'success',
                                //title: 'Hired Successful',
                                text: "Post are apporved and funds are released",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                                }
                            }).then(function() {
                                location.reload();

                            });
                        }else{
                            swal.fire('Error', data.message, 'error')
                        }

                    }
                   })

             }  else if (

                    e.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your Request is Cancelled',
                        'error'
                    );
                } 

    });


});

$('#review').keyup(function(){
    var reviews = $('#review').val().split(' ');
    var getLength = reviews.length;
    if(getLength > 100 ){
        $('#error').text("Please Can not add more then 70 Words for this testimonail..");
        $('#job-submit-proposal-submit').attr('disabled','disabled');
    } else {
        $('#error').text("");
        $('#job-submit-proposal-submit').removeAttr('disabled');
    }
})

/**Job Done**/
$(".job_mark_done").click(function() {
    
    event.preventDefault();
    var job_id = $(this).attr('data-id');
    var job_status = $(this).attr('data-jobstatus');
    var job_job = $(this).attr('data-job');
    var token = $('meta[name="csrf-token"]').attr('content');

    swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This Job " + job_job + " Done!!" ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Job Done!!',
            cancelButtonText: 'Not Done',
            reverseButtons: true
    }).then(function(e) {
        
            if (e.value == true) {
                
                        /*if ($(this).is(':checked')) {
                            var mark_job_done = 4;
                            var messages = 'Job done successfully.';
                        } else {
                            var mark_job_done = 3;
                            var messages = 'Job Not Done.';
                        }*/
                        if(job_status == 4 ){
                            var messages = 'Job done successfully.';
                        }else{
                            var messages = 'Job Not Done.';
                        }
                        $('.loader-wrapper').toggleClass('d-flex');
                        $.ajax({
                            url: HOST_URL + '/job/jobcomplete',
                            data: { job_id: job_id, _token: token,job_status:job_status },
                            type: 'post',
                            success: function(result) {
                                $('.loader-wrapper').toggleClass('d-flex');
                                swal.fire({
                                    text: messages,
                                    icon: 'success',
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });


                    
            }  else if (

                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    );
                }


    });


});



const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
})


$("#hirejobs2").click(function(){
     event.preventDefault();
    var jobId = $(this).attr('data-jobid1');
    var jobhiretatus = $(this).attr('data-jobhiretatus1');
    var jobhireinfluencers= $(this).attr('data-influencers1');
    swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "want to hire this influencer for your job.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes! Hire Him',
            cancelButtonText: "No! Don't Hire",
            reverseButtons: true
    }).then(function(e) {
        
            if (e.value == true) {
                 $.ajax({
                       type:'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                       url: HOST_URL + '/hire-jobs',
                       
                       data:{jobId:jobId,jobhiretatus:jobhiretatus,jobhireinfluencers:jobhireinfluencers},
                       success:function(data){
                            
                              swal.fire({
                                    icon: 'success',
                                    //title: 'Hired Successful',
                                    text: "Post are apporved and funds are released",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    location.reload();

                                });
                          
                        }
                       })

            }  else if (

                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    );
                }

    });
});





// Job Done By Users
$("#jobuserdone").click(function(){
    event.preventDefault();
    var jobId = $(this).attr('data-id');
    var userdone = $(this).attr('data-userdone');
    var jobname = $(this).attr('data-jobname');

    swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This " + jobname + " Job Done!!",   
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Done!!  ',
            cancelButtonText: ' Cancel',

            reverseButtons: true
    }).then(function(e) {
             if (e.value == true) {
                if(userdone == 1 ){
                    var messages = 'Jobs successfully Confirm!!';
                }else{
                    var messages = 'Job Not Done.';
                }
                $('.loader-wrapper').toggleClass('d-flex');
                 $.ajax({
                            type:'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: HOST_URL + '/job/jobdonebyuser',
                            data:{jobId:jobId,userdone:userdone},
                            success:function(data){
                                $('.loader-wrapper').toggleClass('d-flex');
                              swal.fire({
                                    text: messages,
                                    icon: 'success',
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                    confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    
                                   location.reload();
                                });
                          
                        }
                       })

            }  else if (

                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    );
                }     


    });
});

$('.change-status').on('change, click', function() {

    var url = $(this).attr('url');
    var item = $(this).attr('item');
    var status = $(this).attr('status');
    var operation = $(this).attr('operation');
    var this_ = this;

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "want to " + operation + " this " + item,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + operation + ' it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('.loader-wrapper').toggleClass('d-flex');
            $.ajax({
                url: url,
                type: 'get',
                success: function(result) {
                    $('.loader-wrapper').toggleClass('d-flex');
                    swalWithBootstrapButtons.fire({
                        text: result.message,
                        icon: result.status ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            if ($(this_).is(':checked')) {
                $(this_).prop('checked', false);
            } else {
                $(this_).prop('checked', true);
            }

            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your ' + item + ' is safe :)',
                'error'
            )
        }
    })

})

$('.delete-notification').on('change, click', function() {

    var url = $(this).attr('url');
    var item = $(this).attr('item');
    var status = $(this).attr('status');
    var operation = $(this).attr('operation');
    var this_ = this;

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "want to " + operation + " this " + item,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + operation + ' it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('.loader-wrapper').toggleClass('d-flex');
            $.ajax({
                url: url,
                type: 'get',
                success: function(result) {
                    $('.loader-wrapper').toggleClass('d-flex');
                    swalWithBootstrapButtons.fire({
                        text: result.message,
                        icon: result.status ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            if ($(this_).is(':checked')) {
                $(this_).prop('checked', false);
            } else {
                $(this_).prop('checked', true);
            }

            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your ' + item + ' is safe :)',
                'error'
            )
        }
    })

})

$('.clear-notification').on('change, click', function() {

    var url = $(this).attr('url');
    var item = $(this).attr('item');
    var status = $(this).attr('status');
    var operation = $(this).attr('operation');
    var this_ = this;

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "want to " + operation + " all " + item,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + operation + ' it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('.loader-wrapper').toggleClass('d-flex');
            $.ajax({
                url: url,
                type: 'get',
                success: function(result) {
                    $('.loader-wrapper').toggleClass('d-flex');
                    swalWithBootstrapButtons.fire({
                        text: result.message,
                        icon: result.status ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                    });
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            if ($(this_).is(':checked')) {
                $(this_).prop('checked', false);
            } else {
                $(this_).prop('checked', true);
            }

            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your ' + item + ' is safe :)',
                'error'
            )
        }
    })

})

function searchInfluencer() {
    
    if ($('#job_result_conainer').length > 0) {
        var url = HOST_URL + '/jobs'; 
        var id = 'job_result_conainer' ;
    }else{
        var url = HOST_URL + '/influencers'; 
        var id = 'influencer_result_conainer' ;
    }
         
    if ($.trim(price_order) !== '') {
        url = url + '?price_order=' + price_order;
    } else {
        url = url + '?price_order=lowheigh';
    }
    if (raiting_filter) {
        //var raiting_filter = '4';
        url = url + '&raiting_filter=' + raiting_filter;
    } 
    if ($.trim(platforms) !== '') {
        url = url + '&platforms=' + platforms;
    }
    if ($.trim(category_checkbox) !== '') {
        url = url + '&categories=' + category_checkbox;
    }
    if ($.trim(race_checkbox) !== '') {
        url = url + '&race=' + race_checkbox;
    }
    if (price_min) {
        url = url + '&price_min=' + price_min;
    }
    if (price_max) {
        url = url + '&price_max=' + price_max;
    }
    if (lat) {
        url = url + '&lat=' + lat;
    }
    if (lng) {
        url = url + '&lng=' + lng;
    }
    if (radious) {
        url = url + '&radious=' + radious;
    }
    if (age) {
        url = url + '&age=' + age;
    }
    if ($.trim(search) !== "") {
        url = url + '&search=' + search;
    }

    if (page > 1) {
        url = url + '&page=' + page;
        $.ajax({url: url, success: function(result){
        $("#"+id).append(result);
        }}); 
        return 
    }
    $('#'+id).load(url, function() {
        console.log('loaded');
    })


}

function pickImage(ele) {
    event.preventDefault();
    $('#profileImage').click();
}

  $(document.body).on('click', '.js-submit-confirm', function (event) {
    event.preventDefault();
    var $form = $(this).closest('form');
    var jobname= $("#jobnames").val();
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Want to mark this job done!!",   
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Done!!  ',
        cancelButtonText: ' Cancel',
        reverseButtons: true
    }).then(function(e) {
        if (e.value == true) {
            $form.submit();
        }  
        else if ( result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
            );
        }


    });


});     


$(document).on('change', '#platforms',function(e) {
    var selected = $(this).val();
    $('.social-platform-followeres').hide();
    selected.forEach(function(value, index){
        $('.display-category-'+value).show();
        console.log($('.display-category-'+value).length, value, index);
    });
});

$(document).on('change', '.select2-hidden-accessible', function(){
    $(this).parent().find('.error').hide();
})


$('.catNameIflu').on('click', function(){
    var catName = $(this).attr('data-catname');


})    

    /*Pusher.logToConsole = true;

    var pusher = new Pusher('85a375b8fd97154b93ee', {
      cluster: 'ap2',
      encrypted: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });*/

    
      Pusher.logToConsole = true;
      var pusher = new Pusher('85a375b8fd97154b93ee', {
          cluster: 'ap2'
        });

      
      var channel = pusher.subscribe('my-channel');
      /*channel.bind('my-event', function(data) {
          alert(JSON.stringify(data));
        });*/

      channel.bind('my-event', function(data) {
        $('#notificationCount_span').text(data.count).show();
      });


// JS for ckeditor
   
    var editor = CKEDITOR.replace( 'ckeditor' );
    CKFinder.setupCKEditor( editor );


  

// js for pagination

$(".load-more").on('click',function(){
    var totalCurrentResult=$(".cat_box").length;
    var catId = $('#catId').val();
    
    $.ajax({
        type:'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: HOST_URL + '/pagination/influencersCat',
        data:{totalCurrentResult:totalCurrentResult,catId:catId},
        success:function(data){ 
        $('#helloo').append(data);          
              //console.log(data);
        }
    })

})



function searchup() {
 
    var keywords = $('#search-input').val();
    console.log(keywords);
    var catId = $('#search-input').attr('data-catId');
            if (keywords.length > 0)
            {
                $.ajax({
                            type:'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: HOST_URL + '/influencers/search',
                            data:{keywords:keywords,catId:catId},
                            success:function(data){
                                console.log(data);
                                // $('#search-results').remove();
                           $('.category-inner').html(data);
                                }
                        })
               
            }
 
}





/*$('#withrawalAmount').on('keyup', function(){
    var currentAmount = $(this).val();
    var totalWalletAmt = $('#totalamount').val();
    var minimumStripAmt = '100';
    if(totalWalletAmt < currentAmount){
        $("#error").text("You can't Withral Amount Greather then Your Total Amount");
        
    } else{
        $("#error").text("");
    }
    if(currentAmount < minimumStripAmt){
        console.log("You have not insuficent Amount");
    }else {
        console.log("You Can Withraw");
    }

    
})*/

$('#withrawalAmount').on('keyup', function(){

    var currentAmount = parseInt($(this).val());
    var totalWalletAmt = parseInt($('#totalamount').val());
    var minimumStripAmt = parseInt($('#minimumStripAmount').val());
    if(totalWalletAmt < currentAmount){
        $("#error").text("You can't Withral Amount Greather then Your Total Amount");
        $('input[type="submit"]').attr('disabled','disabled');
    } else if(currentAmount < minimumStripAmt){
        $("#error").text("You have not insuficent Amount");
        $('input[type="submit"]').attr('disabled','disabled');
    } else {
        $("#error").text("");
        $('input[type="submit"]').removeAttr('disabled');

        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: HOST_URL + '/withrawal-amount-request',
            data:{currentAmount:currentAmount},
            success:function(data){
            console.log(data);
            // $('#search-results').remove();
            $('.category-inner').html(data);
            }      
        })

    }
   
})

$('.getPlatformId').on('click', function(){
    var platId = $(this).attr('data-id');
    $.ajax({
                type:'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: HOST_URL + '/platform/categories',
                data:{platId:platId},
                success:function(data){
                    console.log(data);
               $('.platformsCat').html(data);
                    }
            })
})


$(document).on('keydown, keypress', 'input[type="number"]', function(e){

    var key = e.charCode || e.keyCode || 0;
    return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57));
})


/*$('.jobCostAmount').on('keyup', function(){
    var getAmount = parseInt($(this).val());
   
    var jobcost = parseInt($('#jobCosts').val());
   
    if(jobcost < getAmount){
        $('#error').text('You can enter greater than amount from job cost');
         $('#job-submit-proposal-submit').attr('disabled','disabled');
        
    } else {
        $('#error').text("");
        $('#job-submit-proposal-submit').removeAttr('disabled');
    }
})*/



$(document).on('click','.apply-btn .apply', function(){
    var jobId = $(this).data('jobid');
    var token = $('meta[name="csrf-token"]').attr('content');
    var this_ = this ;
    $('.loader').show();
    $.ajax({
        type: 'POST',
        url: HOST_URL + '/checkjob/applications',
        dataType: "json",
        data: { id: jobId, _token: token },
        success: function(result) {
            $('.loader').hide();
            if(result.success){
                location.href = $(this_).data('href');
                return ;
            }
            swal.fire({
                text: result.message,
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then(function() {
               
            });
        }
    });

});

 $(document.body).on('click', '.apply-btn .userapply', function (event) {
    event.preventDefault();
    var $form = $(this).closest('form');
    // var jobname= $("#jobnames").val();
    swalWithBootstrapButtons.fire({
        // html:true,
        html: 'You must verify your social medias on the <a href="be-influencer" target="_blank">become an influencer </a>tab!!',   
        icon: 'warning',
        reverseButtons: true
    }).then(function(e) {
        if (e.value == true) {
            $form.submit();
        }  



    });


}); 

// $(document).on('click','.userapply-btn .apply', function(){
//     alert('ok');
//     var jobId = $(this).data('jobid');
//     var token = $('meta[name="csrf-token"]').attr('content');
//     var this_ = this ;
//     $('.loader').show();
//     $.ajax({
//         type: 'POST',
//         url: HOST_URL + '/checkjob/applications',
//         dataType: "json",
//         data: { id: jobId, _token: token },
//         success: function(result) {
//             $('.loader').hide();
//             if(result.success){
//                 location.href = $(this_).data('href');
//                 return ;
//             }
//             swal.fire({
//                 text: result.message,
//                 icon: 'warning',
//                 buttonsStyling: false,
//                 confirmButtonText: "Ok",
//                 customClass: {
//                     confirmButton: "btn font-weight-bold btn-light-primary"
//                 }
//             }).then(function() {
               
//             });
//         }
//     });

// });