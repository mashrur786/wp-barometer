/*

Title:		jQMeter: a jQuery Progress Meter Plugin
Author:		Gerardo Larios
Version:	0.1.2
Website:	http://www.gerardolarios.com/plugins-and-tools/jqmeter
License: 	Dual licensed under the MIT and GPL licenses.

*/

(function($) {

	//Extend the jQuery prototype
    $.fn.extend({
        jQMeter: function(options) {
            if (options && typeof(options) == 'object') {
                options = $.extend( {}, $.jQMeter.defaults, options );
            }
            this.each(function() {
                new $.jQMeter(this, options);
            });
            return;
        }
    });

	$.jQMeter = function(elem, options) {
		//Define plugin options
		console.log(options);
		goal = parseInt((options.goal).replace(/\D/g,''));
		raised = parseInt((options.raised).replace(/\D/g,''));
		width = options.width;
		height = options.height;
		bgColor = options.bgColor;
		barColor = options.barColor;
		meterOrientation = options.meterOrientation;
		animationSpeed = options.animationSpeed;
		counterSpeed = options.counterSpeed;
		displayTotal = options.displayTotal;
		total = (raised / goal) * 100;

		/*
		 * Since the thermometer width/height is set based off of
		 * the total, we force the total to 100% if the goal has
		 * been exceeded.
		 */
		if(total >= 100) {
			total = 100;
		}

		//Create the thermometer layout based on orientation option
		if(meterOrientation == 'vertical') {

			$(elem).html('<div class="therm outer-therm vertical">' +
								'<i class="hovered-arrow"></i>' +
								'<div class="therm inner-therm vertical">' +
									'<span style="display:none;">' + total + '</span>' +
									'<i class="hovered-raised">' + raised +' </i>' +
								'</div>' +
								'<i class="hovered-raised"> £' + goal +'</i>' +
							'</div>');
			$(elem).children('.outer-therm').attr('style','width:' + width + ';height:' + height + ';background-color:' + bgColor);
			$(elem).children('.outer-therm').children('.inner-therm').attr('style','background-color:' + barColor + ';height:0;width:100%');
			$(elem).children('.outer-therm').children('.inner-therm').animate({height : total + '%'},animationSpeed);

		} else {

			$(elem).html('<div class="therm outer-therm">' +
							'<i class="hovered-arrow"></i>' +
							'<div class="therm inner-therm">' +
								'<i class="hovered-arrow"></i>' +
								'<span style="display:none;">' + total + '</span>' +
								'<i class="hovered-raised">' + raised +' </i>' +
							'</div>' +
							'<i class="hovered-raised"> £' + goal +'</i>' +
						'</div>');
			$(elem).children('.outer-therm').attr('style','width:' + width + ';height:' + height + ';background-color:' + bgColor);
			$(elem).children('.outer-therm').children('.inner-therm').attr('style','background-color:' + barColor + ';height:' + height + ';width:0');
			$(elem).children('.outer-therm').children('.inner-therm').animate({width : total + '%'},animationSpeed);

		}

		//If the user wants the total percentage to be displayed in the thermometer
		if(displayTotal) {

			//Accomodate the padding of the thermometer to include the total percentage text
			var formatted_height = parseInt(height);
			var padding = (formatted_height/2) - 13 + 'px 10px';

			if(meterOrientation != 'horizontal'){
			  padding = '10px 0';
			}

			$(elem).children('.outer-therm').children('.inner-therm').children('span').show();
			$(elem).children('.outer-therm').children('.inner-therm').children('span').css('padding', padding);

			//Animate the percentage total. Borrowed from: http://stackoverflow.com/questions/23006516/jquery-animated-number-counter-from-zero-to-value
			$({ Counter: 0 }).animate({ Counter: $(elem).children('.outer-therm').children('.inner-therm').children().text() }, {
  				duration : counterSpeed,
  				easing : 'swing',
  				step : function() {
   					$(elem).children('.outer-therm').children('.inner-therm').children('span').text(Math.ceil(this.Counter) + '%');
  				}
			});
			//animate the raised amount
				$({ Counter: 0 }).animate({ Counter: $(elem).children('.outer-therm').children('.inner-therm').children('i.hovered-raised').text() }, {
  				duration : counterSpeed,
  				easing : 'swing',
  				step : function() {
   					$(elem).children('.outer-therm').children('.inner-therm').children('i.hovered-raised').text( '£' + Math.ceil(this.Counter));
  				}
			});

		}

		//Add CSS
		$(elem).append('<style>.therm{height:30px;border-radius:5px;}.outer-therm{margin:20px 0; position: relative}.inner-therm{ position: relative}.inner-therm span {color: #fff;display: inline-block;float: right;font-family: Trebuchet MS;font-size: 20px;font-weight: bold;} i.hovered-arrow {border-top: 10px solid #8e8383;  border-right: 10px solid transparent;border-left: 10px solid transparent;position: absolute;top: -2rem; right: -1rem ; color: darkgray}  i.hovered-raised { font-family: inherit;position: absolute; top: -5rem; right: -2rem; color: #8e8383}.vertical.inner-therm span{width:100%;text-align:center;}.vertical.outer-therm{position:relative;}.vertical.inner-therm{position:absolute;bottom:0;}</style>');

	};

	//Set plugin defaults
	$.jQMeter.defaults = {

		width : '100%',
		height : '50px',
		bgColor : '#444',
		barColor : '#bfd255',
		meterOrientation : 'horizontal',
		animationSpeed : 2000,
		counterSpeed : 2000,
		displayTotal : true

	};

})(jQuery);