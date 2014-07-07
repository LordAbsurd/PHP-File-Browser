$(document).ready(function() {
    if (phpVars["show_preview"] == 'true') {

        function getImageSize(src, callback) {
            var img = new Image();
            img.onload = function() {
                callback(this.width, this.height);
            };
            img.src = src;
        }

        function mouseIn(event) {
            var imageObj = $(this);
            getImageSize(imageObj.attr('href'), function(width, height) {
                var new_width = phpVars["preview_width"];
                var new_height = phpVars["preview_height"];

                if((width/height) > (new_width/new_height))
                {
                    new_height = new_width * (height / width);
                }
                else
                {
                    new_width = new_height * (width / height);
                }
                if(new_width >= width && new_height >= height)
                {
                    new_width = width;
                    new_height = height;
                }
                $("#thumbnail").css({
                    width: new_width,
                    height: new_height,
                    top: event.pageY - 25,
                    left: event.pageX + 15
                }).attr("src", imageObj.attr('href'));

                $('#thumbnail').stop().fadeIn('fast');
            });
        }

        function mouseMove(event) {
            $("#thumbnail").css({
                top: event.pageY - 25,
                left: event.pageX + 15
            });
        }

        function mouseOut() {
            $('#thumbnail').stop().hide();
        }

        $('[data-extension!=""]').each(function() {
            if (/image/i.test($(this).attr('data-extension'))) {
                $(this).hover(mouseIn, mouseOut);
                $(this).mousemove(mouseMove);
            }
        });
    }
});
