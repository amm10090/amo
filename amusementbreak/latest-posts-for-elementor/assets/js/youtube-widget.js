(function($) {
    'use strict';

    var LatestPostsAdHandler = function($scope, $) {
        var $adPost = $scope.find('.ad-post');
        if ($adPost.length === 0) return;

        var $youtubeAd = $adPost.find('.youtube-ad');
        if ($youtubeAd.length > 0) {
            var videoId = $youtubeAd.data('video-id');
            var autoplay = $youtubeAd.data('autoplay');
            var showPlayIcon = $youtubeAd.data('show-play-icon');
            var $thumbnail = $youtubeAd.find('.news-thumbnail');

            if (showPlayIcon === 'false') {
                $thumbnail.find('.play-icon').hide();
            }

            var playVideo = function() {
                var embedHtml = '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' + 
                                videoId + '?autoplay=' + (autoplay === 'true' ? '1' : '0') + 
                                '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                $thumbnail.html(embedHtml);
            };

            if (autoplay === 'true') {
                playVideo();
            } else {
                $thumbnail.on('click', function(e) {
                    e.preventDefault();
                    playVideo();
                });
            }
        }

        var $imageAd = $adPost.find('.news-thumbnail');
        if ($imageAd.length > 0) {
            // 这里可以添加图片广告的特殊处理，比如点击跟踪等
        }

        var $htmlAd = $adPost.find('.custom-html-ad');
        if ($htmlAd.length > 0) {
            // 这里可以添加自定义HTML广告的特殊处理
        }
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/latest_posts.default', LatestPostsAdHandler);
    });

})(jQuery);