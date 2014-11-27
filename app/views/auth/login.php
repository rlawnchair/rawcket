<div class="container">
<?php if(isset($flash['lel'])){ ?>
	<div class="alert alert-warning">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <strong>Erreur</strong> <?= $flash['lel']; ?>
	</div>
<?php }?>
	<div class="row">
		<div class="col-sm-offset-4 col-xs-offset-3 col-xs-6 col-sm-4">
			<form action="<?= $app->urlFor('postLogin'); ?>" method="POST" role="form">
					<legend>Connexion</legend>
				
					<div class="form-group">
						<label for="login">Login</label>
						<input type="text" class="form-control" id="login" placeholder="ex. John" name="login">

						<label for="pass">Password</label>
						<input type="password" class="form-control" id="pass" placeholder="ex. Secret Password" name="pass">
					</div>
				
					
				
					<button type="submit" class="btn btn-primary">Submit</button>
			</form>	
		</div>
	</div>
</div>
