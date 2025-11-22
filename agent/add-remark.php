<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une remarque</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>
  <div class="form-layout">
    <form class="card form-card">
      <h1>Ajouter une remarque interne</h1>
      <p class="form-subtitle">Ces remarques ne sont visibles que par les agents et l&apos;administrateur.</p>

      <div class="form-group">
        <label for="statut">Statut interne</label>
        <select id="statut" name="statut">
          <option>Client contacté</option>
          <option>En attente de documents</option>
          <option>Traitement en cours</option>
        </select>
      </div>

      <div class="form-group">
        <label for="remarque">Remarque</label>
        <textarea id="remarque" name="remarque" rows="4"></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-full">Enregistrer la remarque</button>
    </form>
  </div>
</body>
</html>
