</div>

</body>

<script type='text/javascript'>
$(document).ready(function() {
	$(".dropdown").on("show.bs.dropdown", function() {
		$(this).find(".dropdown-menu").first().stop(true, true).slideDown(250);
	});
	$(".dropdown").on("hide.bs.dropdown", function() {
		$(this).find(".dropdown-menu").first().stop(true, true).slideUp(250);
	});
});
</script>

</html>