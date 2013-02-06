$(document).ready(function() {
var detected = false;
function success(position) {
	if (detected) return;
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	detected = true;
	
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': latlng}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0] && results[0].address_components) {
				var res = {};
				for (i=0; i<results[0].address_components.length; i++) {
					part = results[0].address_components[i];
					if (part.types && part.short_name) {
						res[part.types[0]] = part
					}
				}
				var country_id = $("select[name='country_id']").find('option[data-iso2="' + res.country.short_name + '"]').val();
				$('select[name=\'country_id\']').val(country_id);
				$('select[name=\'country_id\']').trigger('change');
				$(document).bind('ajaxComplete', function(event, xhr, settings) {
					if (settings.url.indexOf('/country&country_id=' + country_id) != -1) {
						var zone_id = $('select[name=\'zone_id\']').find('option:contains("' + res.administrative_area_level_1.long_name + '")').val();
						$('select[name=\'zone_id\']').val(zone_id);
						$('.wait').remove();
					}
				});

				$("input[name='city']").val(res.locality.short_name);
				$("input[name='postcode']").val(res.postal_code.short_name);
			}
		} else {
			error('geocoder failed due to: ' + status);
		}
	});
}

function error(msg) {
	console.log('Error: ' + msg);
	$('.wait').remove();
}

if (navigator.geolocation) {
	$("input[name='city']").after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
	$("input[name='postcode']").after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
	$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
	$('select[name=\'zone_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
	navigator.geolocation.getCurrentPosition(success, error,
		{ enableHighAccuracy: false });
} else {
	$('.wait').remove();
	console.log('Error. Geolocation not supported');
}
})