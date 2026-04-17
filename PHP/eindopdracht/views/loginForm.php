<form id="login-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
    <section class="form-section">
        <label for="login-form-username">username:</label>
        <input type="text" name="username" id="login-form-username" required>
    </section>
    <section class="form-section">
        <label for="login-form-password">password:</label>
        <input type="password" name="password" id="login-form-password" required>
    </section>
    <input type="submit" value="login" id="submit" name="login">
</form>