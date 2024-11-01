var takeoffActiveInstalls = 0;
jQuery(document).on("wp-plugin-installing", function(response) {
    takeoffActiveInstalls++;
});
jQuery(document).on("wp-plugin-install-success", function(response) {
    takeoffActiveInstalls--;
    wp_takeoff_update_buttons();
});
jQuery(document).on("wp-plugin-install-error", function(response) {
    takeoffActiveInstalls--;
    wp_takeoff_update_buttons();
});

function wp_check_active_plugins() {
    if (takeoffActiveInstalls > 0) {
	return confirm('It looks like there are still some plugins installing. Are you sure you want to leave this page?\r\rIf this takes too long, you can just leave this page.');
    } else {
	return true;
    }
}

function wp_takeoff_update_buttons() {
    $ = jQuery;
    $('.button.activate-now').parent().html('<span class="wp-installed"><span class="dashicons dashicons-yes"></span> Installed!</span>');
    $('.button.button-disabled').parent().html('<span class="wp-installed"><span class="dashicons dashicons-yes"></span> Active!</span>');
}

function checkStep1Button() {
    $ = jQuery;

    if ($('.check-role:checked').length != 0) {
	$('#action .btn').removeClass('disabled');
    } else {
        $('#action .btn').addClass('disabled');
    }
}
function goToStep2() {
    $ = jQuery;
    if (!$('#action .btn').hasClass('disabled')) {
        var scenarios = jQuery.map($(':checkbox[name=scenario\\[\\]]:checked'), function (n, i) {
            return n.value;
        }).join(',');
	document.location = '?page=wp-takeoff-wizard&step=2&scenarios=' +  scenarios + '&role=' + $('.check-role:checked').val();
    }
    return false;
}
function wpto_submitSuggestion(box) {
    $ = jQuery;
    var plugins = [];
    $.each($('#'+box+' .suggest-input:checked'), function(i, val) {
	plugins.push({
	  id : $(this).val(),
	  title : $(this).data('title'),
	  category : $(this).data('category')
	});
    });
    if (plugins.length == 0) {
	return;
    }
    var data = JSON.stringify({
	plugins: plugins
    });


     var settings = {
	"async": true,
	"crossDomain": true,
	"url": globals.suggestion_url,
	"method": "POST",
	"headers": {
	  "accept": "application/json",
	  "content-type": "application/json",
	  "cache-control": "no-cache",
	},
	"data": data
      }

      $.ajax(settings).done(function (response) {
      });

}