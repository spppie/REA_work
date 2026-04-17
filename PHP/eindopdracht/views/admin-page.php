<div class="include-flex">
    <?php
        if (isset($_POST['create-user'])) {
            $xml = new SimpleXMLElement('xml/userdata.xml', 0, true);

            $entry = count($xml);
            $xml->addChild('user');
            $xml->user[$entry]->addAttribute('id', uniqid());
            $xml->user[$entry]->addChild('username', $_POST['create-username']);
            $xml->user[$entry]->addChild('password', $_POST['create-password']);
            $xml->user[$entry]->addChild('email', $_POST['create-email']);
            $xml->user[$entry]->addChild('img', '');

            $xml->asXML('xml/userdata.xml');
        }
    ?>
    <p>create user:</p>
    <form id="user-create-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
        <label for="create-username">username:</label>
        <input type="text" name="create-username" id="create-username" required>

        <label for="create-password">password:</label>
        <input type="text" name="create-password" id="create-password" required>

        <label for="create-email">email:</label>
        <input type="text" name="create-email" id="create-email" required>

        <input type="submit" value="create-user" id="create-user" name="create-user">
    </form>
</div>