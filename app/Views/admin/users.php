<?php
/**
 * @var array<int, array{ id:int, username:string, role:string, created_at:string }> $users
 */
$title = "Admin – Utilisateurs";
ob_start();
?>
<h2>Gestion des utilisateurs</h2>

<button id="openCreateBtn">➕ Ajouter un utilisateur</button>


<table border="1" cellpadding="6" style="margin-top:15px;">
  <tr><th>ID</th><th>Nom</th><th>Rôle</th><th>Créé le</th><th>Action</th></tr>
  <?php foreach ($users as $u): ?>
  <tr>
    <td><?= $u["id"] ?></td>
    <td><?= htmlspecialchars($u["username"]) ?></td>
    <td><?= htmlspecialchars($u["role"]) ?></td>
    <td><?= htmlspecialchars($u["created_at"]) ?></td>
    <td>
      <button class="editBtn"
        data-id="<?= $u["id"] ?>"
        data-username="<?= htmlspecialchars($u["username"]) ?>"
        data-role="<?= $u["role"] ?>">Modifier</button>
      <?php if ($u["id"] != $_SESSION["user"]["id"]): ?>
        | <a href="/admin/users?delete=<?= $u[
            "id"
        ] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">supprimer</a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>



<div id="userModal" style="
      display:none;
      position:fixed;top:0;left:0;width:100%;height:100%;
      background:rgba(0,0,0,0.6);justify-content:center;align-items:center;">
  <div style="background:white;padding:20px;border-radius:8px;min-width:300px;">
    <h3 id="modalTitle">Créer un utilisateur</h3>

    <form method="POST" id="userForm">
      <input type="hidden" name="id" id="userId">

      <label>Nom d'utilisateur :</label><br>
      <input type="text" name="username" id="username" required><br><br>

      <label>Mot de passe :</label><br>
      <input type="password" name="password" id="password" placeholder="••••••"><br><br>

      <label>Rôle :</label><br>
      <select name="role" id="role" required>
        <option value="rapporteur">Rapporteur</option>
        <option value="developpeur">Développeur</option>
        <option value="admin">Admin</option>
      </select><br><br>

      <button type="submit">Enregistrer</button>
      <button type="button" id="cancelBtn">Annuler</button>
    </form>
  </div>
</div>

<script>
const modal = document.getElementById('userModal');
const form  = document.getElementById('userForm');
const modalTitle = document.getElementById('modalTitle');
const userId = document.getElementById('userId');
const username = document.getElementById('username');
const password = document.getElementById('password');
const role = document.getElementById('role');

document.getElementById('openCreateBtn').addEventListener('click', () => {
  modal.style.display = 'flex';
  modalTitle.textContent = 'Créer un utilisateur';
  form.reset();
  userId.value = '';
  password.required = true;
});

document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', () => {
    modal.style.display = 'flex';
    modalTitle.textContent = 'Modifier un utilisateur';
    userId.value = btn.dataset.id;
    username.value = btn.dataset.username;
    role.value = btn.dataset.role;
    password.required = false; // optional in edit
  });
});

document.getElementById('cancelBtn').addEventListener('click', () => {
  modal.style.display = 'none';
});

form.addEventListener('submit', () => {
  modal.style.display = 'none';
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

