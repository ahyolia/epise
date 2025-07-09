<link rel="stylesheet" href="/css/users/account.css">

<div class="account-hero">
    <div class="account-avatar">
        <img src="/images/user.png" alt="Avatar">
    </div>
    <div class="account-title">Mon compte</div>
</div>

<div class="container">
    <div class="account-section">
        <div class="account-info">
            <label>Numéro étudiant</label>
            <div class="info-value"><?= htmlspecialchars($user['numero_etudiant'] ?? '') ?></div>

            <label>Email</label>
            <div class="info-value"><?= htmlspecialchars($user['email'] ?? '') ?></div>

            <label>Nom Prénom</label>
            <div class="info-value"><?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></div>
        </div>
        <div style="margin-top:2em; text-align:center;">
            <a href="/users/edit" class="btn-compte">Modifier mon profil</a>
            <a href="/users/logout" class="btn-compte" style="margin-left:1em;">Déconnexion</a>
        </div>
    </div>
</div>