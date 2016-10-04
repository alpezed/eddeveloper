(function($){

    var edslImgUpload = $('.edsl-upload-img-btn');
    var edslImg = $('.esl-img');
    var edslHidden = $('.esl-hidden input');

    var addNewSlideBtn = $("#add-new-slide");
    var slideTable = $(".ed-slider-table");

    function esl_table_index() {
        $("table.ed-slider-table tr").each(function(){
            $(this).find('td:first span').text($(this).index()+1);
            $(this).attr('id', 'esl-row-' + $(this).index());
        });
    }

    /*Sortable Slide*/
    slideTable.find('tbody').sortable({

        items: '> tr',
        handle: '> td.reorder',
        start: function(e,ui){
            ui.placeholder.height(ui.item.height());
        },
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        },
        update: function() {
            esl_table_index();
        }

    });

    addNewSlideBtn.on('click', function() {

        var lastSlide = $(".ed-slider-table tr:last");

        lastSlide.clone().find(':input').each(function() {

            //change the value of array on name attribute (name[0])
            this.name = this.name.replace(/\[(\d+)\]/,
                function(str,p1){
                    return '[' + (parseInt(p1,10)+1) + ']';
                });

            //empty value
            this.value = '';

            //show upload button area
            $(this).parents('tr').find('.esl-view').removeClass('n-hide');
            //remove existing image
            $(this).parents('tr').find('.eslView img').attr('src', '');

        }).end().appendTo("table.ed-slider-table").hide().fadeIn();

        esl_table_index();

    });

    $('.esl-remove-row').live('click', function () {

        $(this).parents('tr').fadeOut('fast', function() {
            $(this).remove();
            esl_table_index();
        });

        return false;

    });


    var edsl_img_uploader, toogleVisibility;

    edslImgUpload.live( 'click', function() {

        var clicked = $(this);

        toogleVisibility = function( action ) {
            if( action == "REMOVE" ) {
                clicked.parents('td').find('.esl-view').show();
            }
            if( action == "ADD" ) {
                clicked.parents('td').find('.esl-view').hide();
            }
        }

        edsl_img_uploader = wp.media({
            title: 'Select Image',
            button: { text: 'Select' },
            multiple: false
        });

        edsl_img_uploader.on( 'select', function() {

            var attachment = edsl_img_uploader.state().get('selection').first().toJSON();

            clicked.parents('td').find('.esl-hidden input').val( attachment.id  );
            clicked.parents('td').find('.eslView img').attr('src', attachment.sizes.thumbnail.url);
            clicked.parents('td').find('.esl-view').hide();

            toogleVisibility("ADD");

        } );

        edsl_img_uploader.open();

    } );

    //hide image uploader if there's an image
    slideTable.find('input[type="hidden"]').each(function() {
        if( $(this).val() != '' ) {
            $(this).parents('tr').find('.esl-view').addClass('n-hide');
        }
    });

})(jQuery);