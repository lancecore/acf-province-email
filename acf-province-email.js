jQuery(document).ready(function($) {
	$('select[id^="province-select-"]').on('change', function() {
		const fieldGroup = $(this).attr('id').replace('province-select-', '');
		const selectedProvince = $(this).val();

		// Ensure provinceData object is available and has data for the fieldGroup
		if (window.provinceData && window.provinceData[fieldGroup]) {
			const data = window.provinceData[fieldGroup];
			if (selectedProvince && data[selectedProvince]) {
				const provinceData = data[selectedProvince];
				const emails = provinceData.emails.join(",");
				const subject = encodeURIComponent(provinceData.subject);
				const cc = encodeURIComponent(provinceData.cc_address);
				const body = encodeURIComponent(provinceData.text);

				const mailtoLink = `mailto:${emails}?subject=${subject}&cc=${cc}&body=${body}`;

				$('#send-email-link-' + fieldGroup).attr('href', mailtoLink).show(); // Show the link
			} else {
				$('#send-email-link-' + fieldGroup).removeAttr('href').hide(); // Hide the link if no valid province is selected
			}
		} else {
			$('#send-email-link-' + fieldGroup).removeAttr('href').hide(); // Hide the link if no data available
		}
	});
});
