var clothHelper = {
    currModelState: "normal",
    turnModel: function() {
        var turnedUrlModel = "images/models/body_girl_back.png";
        var urlModel = "images/models/body_girl_front.png";

        if (clothHelper.currModelState == "normal") {
            var new_turn_img = $('<img>');
            $(new_turn_img).load(function(event) {
                $("div.ajax-load2").hide();
                loaded1 = true;
                $('.model').css('background', 'url(' + turnedUrlModel + ') center center no-repeat')
            })
            new_turn_img.prop('src', turnedUrlModel);
            $(new_turn_img).error(function() {
                alert('Error wile loading image.');
            })
            clothHelper.currModelState = "turned"
        }
        else if (clothHelper.currModelState == "turned") {
            var new_turn_img = $('<img>');
            $(new_turn_img).load(function(event) {
                $("div.ajax-load2").hide();
                loaded1 = true;
                $('.model').css('background', 'url(' + urlModel + ') center center no-repeat')
            })
            new_turn_img.prop('src', urlModel);
            $(new_turn_img).error(function() {
                alert('Error wile loading image.');
            })
            clothHelper.currModelState = "normal";
        }
    },
    clearClothes: function() {
        $('.model').html('');
    },
    ////////////////////////////////////////////////////////
    //Remove cloth by dragging
    removeClothOnDrag: function() {
        $(".draggable").draggable({
            start: function(event, ui) {
            },
            stop: function(event, ui) {
                $(event.target).remove();
            }
        });
    }
}


$(window).load(function() {
    //turn model
    $(".turn-model-button").click(function(event) {
        clothHelper.turnModel();
    })
    //Clear model
    $(".cs-new").click(function(event) {
        clothHelper.clearClothes();
    })
})
