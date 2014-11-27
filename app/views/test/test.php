<div class="container">
	<h1>Ceci est une page de test !</h1>

	<p>Quel est ton joli petit nom ?</p>

	<form action="<?= $app->urlFor('postTest'); ?>" method="POST" role="form">
		<div class="form-group">
			<label for="name">Nom</label>
			<input type="text" class="form-control" id="" placeholder="ex. John" name="nom">
		</div>

		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	<a href="<?= $app->urlFor('root'); ?>">Retour</a>
</div>