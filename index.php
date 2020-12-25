<?php
include 'functions.php';
// Your PHP code here.

// Home Page template below.
?>

<?php sessionCheck(); ?>
<?=template_header('Home')?>

<div class="content">
	<h2>Home</h2>
	<p>Welcome to the home page!</p>
	<a href="logout.php">Click here to Log out</a>
</div>

<?=template_footer()?>