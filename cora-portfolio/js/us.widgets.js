// US w-portfolio
(function ($) {
	"use strict";

	$.fn.wPortfolio = function () {

		return this.each(function () {
			var portfolio = $(this),
				items = portfolio.find('.w-portfolio-item'),
				running = false,
				activeIndex;

			items.each(function(itemIndex, item){
				var anchor = $(item).find('.w-portfolio-item-anchor'),
					details = $(item).find('.w-portfolio-item-details'),
					detailsClose = details.find('.w-portfolio-item-details-close'),
					detailsNext = details.find('.w-portfolio-item-details-arrow.to_next'),
					detailsPrev = details.find('.w-portfolio-item-details-arrow.to_prev'),
					nextItem = $(item).next(),
					prevItem = $(item).prev();

				anchor.click(function(){
					if ( ! $(item).hasClass('active') && ! running){
						running = true;

						var activeItem = portfolio.find('.w-portfolio-item.active');

						if (activeItem.length && parseInt($(item).offset().top, 10) === parseInt(activeItem.offset().top, 10)) {
							activeItem.find('.w-portfolio-item-details').fadeOut();
							activeItem.removeClass('active').css('margin-bottom', '');
							details.fadeIn();
							$(item).css('margin-bottom', details.height()+'px');
						} else {
							if (activeItem.length){
								activeItem.find('.w-portfolio-item-details').hide();
								activeItem.removeClass('active').css({'margin-bottom': ''});

							}

							$(item).animate({'margin-bottom': details.height()+'px'}, 300);

							details.slideDown(300, function() {
								$(item).css({'margin-bottom': details.height()+'px'});
							});

						}

						// jQuery("html, body").animate({
						// 	scrollTop: $(item).offset().top+0.7*anchor.height()+1-window.headerHeight+"px"
						// }, {
						// 	duration: 1000,
						// 	easing: "easeInOutQuad"
						// });

						$(item).addClass('active');
						activeIndex = itemIndex;
						running = false;

					}
				});

				detailsClose.off('click').click(function(){
					details.slideUp();
					$(item).removeClass('active').animate({'margin-bottom': 0}, 300);
				});

				if (nextItem.length) {
					detailsNext.off('click').click(function(){
						nextItem.find('.w-portfolio-item-anchor').click();
					});
				} else {
					detailsNext.hide();
				}

				if (prevItem.length) {
					detailsPrev.off('click').click(function(){
						prevItem.find('.w-portfolio-item-anchor').click();
					});
				} else {
					detailsPrev.hide();
				}

			});
		});
	};
})(jQuery);

jQuery(document).ready(function() {
	"use strict";

	jQuery('.w-portfolio').wPortfolio();
});