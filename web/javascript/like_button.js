$(document).ready(function () {
    $('form.news-item-like-form').on('submit', function () {
        submitButton = this.elements['like-button'];
        $.ajax({
            url: '/like-news-item',
            method: 'post',
            dataType: 'text',
            data: $(this).serialize(),
            success: function (result) {
                submitButton.innerHTML = result;
            },
            error: function () {
                var prev_value = submitButton.value;
                submitButton.innerHTML = 'something went wrong';
                setTimeout(function () { submitButton.value = prev_value }, 2000)
            }
        });
        return false;
    });
    // var like_forms = document.getElementsByClassName('news-item-like-form');
    // for (form of like_forms) {
    //     form.addEventListener('submit', function () {
    // debugger;
    //     submitButton = form.elements['like-button'];
    //     $.ajax({
    //         url: '/like-news-item',
    //         method: 'post',
    //         dataType: 'text',
    //         data: $(this).serialize(),
    //         success: function (result) {
    //             submitButton.value = result;
    //         },
    //         error: function () {
    //             var prev_value = submitButton.value;
    //             submitButton.value = 'something went wrong';
    //             setTimeout(function () { submitButton.value = prev_value }, 2000)
    //         }
    //     });
    //     });
    // }
});