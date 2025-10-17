<?php
/**
 * View for rapporteur to create tickets.
 *
 * @var array<int,array<string,mixed>> $clients
 * @var array<int,array<string,mixed>> $projects
 */
$title = "Créer un ticket";
ob_start();
?>

<h2>Créer un ticket d’incident</h2>

<form method="POST" action="/ticket/create">
  <label>Titre :</label><br>
  <input type="text" name="title" required><br>

  <label>Description :</label><br>
  <textarea name="description" required></textarea><br>

  <label>Client :</label><br>
  <select name="client_id" required>
    <option value="">-- Choisir --</option>
    <?php foreach ($clients as $client): ?>
      <option value="<?= $client["id"] ?>"><?= htmlspecialchars(
    $client["name"],
) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Projet :</label><br>
  <select name="project_id" required>
    <option value="">-- Choisir --</option>
    <?php foreach ($projects as $project): ?>
      <option value="<?= $project["id"] ?>"><?= htmlspecialchars(
    $project["name"],
) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Priorité :</label><br>
  <select name="priority">
    <option value="p3">p3</option>
    <option value="p2" selected>p2</option>
    <option value="p1">p1</option>
  </select><br><br>

  <button type="submit">Créer le ticket</button>
</form>
<script>
document.querySelector('select[name="client_id"]').addEventListener('change', async function () {
  const clientId = this.value;
  const projectSelect = document.querySelector('select[name="project_id"]');
  projectSelect.innerHTML = '<option value="">-- Chargement... --</option>';
  if (!clientId) {
    projectSelect.innerHTML = '<option value="">-- Choisir un client --</option>';
    return;
  }

  try {
    const res = await fetch('/projects?client_id=' + clientId);
    const data = await res.json();
    if (data.error) {
      console.error('Erreur:', data.error);
      projectSelect.innerHTML = '<option value="">-- Accès refusé --</option>';
      return;
    }

    projectSelect.innerHTML = '<option value="">-- Choisir --</option>';
    data.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.id;
      opt.textContent = p.name;
      projectSelect.appendChild(opt);
    });
  } catch (err) {
    console.error('Fetch error:', err);
    projectSelect.innerHTML = '<option value="">-- Erreur de chargement --</option>';
  }
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

