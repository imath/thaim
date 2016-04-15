<!-- Searchform -->
<form method="get" class="search" action="<?php echo esc_url( home_url() ); ?>" >
	<input id="s" type="text" name="s" onfocus="if(this.value==''){this.value=''};"
	onblur="if(this.value==''){this.value=''};" value="">
	<input class="searchsubmit" type="submit" value="<?php esc_attr_e( 'Search', 'thaim' ); ?>">
</form>
<!-- /Searchform -->
