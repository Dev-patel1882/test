<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-footer' );
	} else {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<?php wp_footer(); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click',"#test",function(){
			openModal();
		});
	});

	function openModal() {
		$(".modal-backdrop").addClass('d-none');
		$(".modal-backdrop").removeClass('show');
		$('#send-mail-modal').modal('show');
	}
	function closeModal() {
		$('#send-mail-modal').modal('hide');
	}
	function isValidEmail(email) {
		var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	function sendMail() {
		let emailAddress = $("#email-address").val();
		let validEmail = isValidEmail(emailAddress);
		if (emailAddress == '') {
			$("#email-address-error").removeClass('d-none');
			setTimeout(() => {
				$("#email-address-error").addClass('d-none');
			}, 2500);
		} else if (emailAddress != '' && !validEmail) {
			$("#email-invalid-error").removeClass('d-none');
			setTimeout(() => {
				$("#email-invalid-error").addClass('d-none');
			}, 2500)
		} else {
			$.ajax({
				type: 'POST',
				url: '<?php echo $_SERVER['REQUEST_URI'] ?>',
				data: {email: emailAddress, action:'get_membership_id'},
				success: function(response) {
					let cleanedString = response.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "");
					const regex = /<link[^>]+>/g;
					const cleanJsonString = cleanedString.replace(regex, '');

					const jsonObj = JSON.parse(cleanJsonString);
					console.log(jsonObj)
					if (jsonObj.success) {
						$("#email-success").text(jsonObj.msg);
						$("#email-success").removeClass('d-none');
						setTimeout(() => {
							$("#email-address").val('');
							$("#email-success").text('');
							$("#email-success").addClass('d-none');
							closeModal();
						}, 1500);
					} else {
						$("#email-error").text(jsonObj.msg);
						$("#email-error").removeClass('d-none');
						setTimeout(() => {
							$("#email-error").text('');
							$("#email-error").addClass('d-none');
						}, 1500)
					}
				}
			});
		}
	}
</script>
</body>
</html>
