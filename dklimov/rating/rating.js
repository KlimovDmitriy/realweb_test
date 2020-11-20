
$("."+upvoteClass).click(function () {
    var request = BX.ajax.runComponentAction(
        'dklimov:rating',
        'updateRating',
        {
            mode: 'class',
            data: {
                counter: 'plus',
                id:id,
                iblockId:iblockId,
                ratingCode:ratingCode

            }
        }
    );
    request.then(function (response) {
        $("."+upvoteClass).append("<div>"+response.data+"</div>")
    });
})
$("."+downvoteClass).click(function () {
    var request = BX.ajax.runComponentAction(
        'dklimov:rating',
        'updateRating',
        {
            mode: 'class',
            data: {
                counter: 'minus',
                id:id,
                iblockId:iblockId,
                ratingCode:ratingCode

            }
        }
    );
    request.then(function (response) {
        $("."+downvoteClass).append("<div>"+response.data+"</div>")
    });
})


