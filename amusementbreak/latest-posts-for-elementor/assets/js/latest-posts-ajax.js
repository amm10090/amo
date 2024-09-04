(function ($) {
    'use strict';

    $(document).on('click', '.ajax-page-link, .elementor-load-more-button', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $widget = $this.closest('.latest-news-widget');
        var page = $this.data('page');
        loadPosts($widget, page);
    });

    function loadPosts($widget, page) {
        var settings = $widget.data('settings');
        $.ajax({
            url: latest_posts_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'latest_posts_load_more',
                nonce: latest_posts_ajax.nonce,
                page: page,
                settings: settings
            },
            beforeSend: function () {
                $widget.addClass('loading');
            },
            success: function (response) {
                if (response.success) {
                    $widget.find('.news-container').html(response.data.html);
                    $widget.find('.elementor-pagination').replaceWith(response.data.pagination);
                    $widget.data('current-page', response.data.page);

                    // 更新URL，但不刷新页面
                    if (history.pushState) {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?paged=' + response.data.page;
                        window.history.pushState({ path: newurl }, '', newurl);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown);
            },
            complete: function () {
                $widget.removeClass('loading');
            }
        });
    }
})(jQuery);