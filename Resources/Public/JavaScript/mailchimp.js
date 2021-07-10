$(function () {
    let $extMailchimpForms = $('form.mailchimp-form');

    if ($extMailchimpForms.length) {

        $extMailchimpForms.on('submit', function (e) {

            let $this = $(this),
                url = $this.data('url');

            if (url) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $this.serialize(),
                    dataType: 'html',
                    encode: false
                })
                    .done(function (data) {
                        $('.mailchimp-ajax-response', $this).html(data);
                    });
            }
        });
    }
});