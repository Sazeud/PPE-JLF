<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Titre de la page</title>
  <link rel="stylesheet" href="form.css">
  <script src="script.js"></script>
</head>
<body>

	<form action="Billet.php" method="post">

	<div class ="f59">
		<label for = "nom" > Nom : </label>
		<input type="text" id="nom" name="nom">
	</div>

	<div class ="f59">
		<label for = "adresse" > adresse : </label>
		<input type="text" id="adresse" name="adresse">
	</div>

	<div class ="f59">
		<label for = "cp" > Code Postal : </label>
		<input type="text" id="cp" name="cp">
	</div>

	<div class ="f59">
		<label for = "ville" > Ville : </label>
		<input type="text" id="ville" name="ville">
	</div>

	<table>
	<tr>
		<td>
			Catégorie
		</td>
		<td>
			Tarif en €
		</td>
		<td>
			Quantité
		</td>
	</tr>

	<br>

	<tr>
		<td>
			Adulte
		</td>
		<td>
			0
		</td>
		<td>
			<div class ="f60">
				<label for = "adulte" > </label>
				<input type="number"  value="0" min="0" max="64" name="adulte">
			</div>
		</td>
	</tr>

	<br>

	<tr>
		<td>
			Junior 8 à 18 ans 
		</td>
		<td>
			0
		</td>
		<td>
			<div class ="f60">
				<label for = "junior" > </label>
				<input type="number"  value="0" min="0" max="64" name="junior">
			</div>
		</td>
	</tr>

	<br>

	<tr>
		<td>
			Enfant 0 à 7 ans 
		</td>
		<td>
			0
		</td>
		<td>
			<div class ="f60">
				<label for = "enfant" > </label>
				<input type="number"  value="0" min="0" max="64" name="enfant">
			</div>
		</td>
	</tr>

	<br>

	<tr>
		<td>
			Voiture long.inf.4m 
		</td>
		<td>
			0
		</td>
		<td>

			<div class ="f60">
				<label for = "voiLongInf4" > </label>
				<input type="number"  value="0" min="0" max="10" name="voiLongInf4">
			</div>

		</td>
	</tr>

	<br>

	<tr>
		<td>
			Voiture long.inf.5m
		</td>
		<td>
			0
		</td>
		<td>

			<div class ="f60">
				<label for = "voiLongInf5" > </label>
				<input type="number"  value="0" min="0" max="10" name="voiLongInf5">
			</div>

		</td>
	</tr>

	<br>

	<tr>
		<td>
			Fourgon
		</td>
		<td>
			0
		</td>
		<td>

			<div class ="f60">
				<label for = "fourgon" > </label>
				<input type="number"  value="0" min="0" max="10" name="fourgon">
			</div>

		</td>
	</tr>

	<br>

	<tr>
		<td>
			Camping Car
		</td>
		<td>
			0
		</td>
		<td>

			<div class ="f60">
				<label for = "campingCar" > </label>
				<input type="number"  value="0" min="0" max="10" name="campingCar">
			</div>

		</td>
	</tr>

	<br>

	<tr>
		<td>
			Camion
		</td>
		<td>
			0
		</td>
		<td>

			<div class ="f60">
				<label for = "camion" > </label>
				<input type="number"  value="0" min="0" max="10" name="camion">
			</div>

		</td>
	</tr>
	</table>

	<div class ="f59" id="submit">
		<input type="submit" value="Envoyer">
	</div>


</form>
 
</body>
</html>

