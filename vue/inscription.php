
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter un Utilisateur</title>
</head>
<body>

<h2>Ajouter un Utilisateur</h2>

<form action="/gestionnaire-inscription" method="post">
  <label for="username">Nom d'utilisateur:</label><br>
  <input type="text" id="username" name="username" required><br>
  
  <label for="password">Mot de passe:</label><br>
  <input type="password" id="password" name="password" required><br>
  
  <label for="isAdmin">Administrateur:</label><br>
  <input type="checkbox" id="isAdmin" name="isAdmin" value="1"><br>
  
  <label for="phoneNumber">Numéro de téléphone:</label><br>
  <input type="tel" id="phoneNumber" name="phoneNumber"><br>
  
  <label for="email">Email:</label><br>
  <input type="email" id="email" name="email" required><br>
  
  <label for="lastName">Nom de famille:</label><br>
  <input type="text" id="lastName" name="lastName" required><br>
  
  <label for="firstName">Prénom:</label><br>
  <input type="text" id="firstName" name="firstName" required><br>
  
  <input type="submit" value="Ajouter l'utilisateur">
</form>

</body>
</html>
