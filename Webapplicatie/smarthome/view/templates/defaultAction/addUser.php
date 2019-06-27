<?php include 'include/header.php'; ?>
    <h2>Voeg leraar toe</h2> 

    <form method="POST"> 
        <div class="row add-bottom">
            <div class="three columns alpha">
                <label for="name">Naam</label>
            </div>

            <div class="nine columns">
                <input id="name" type="text" name="name" required="required" <?=isset($_POST['name']) ? 'value="' . $_POST['name'] . '"' : ''?>>
            </div>

            <div class="three columns alpha">
                <label for="id">ID</label>
            </div>

            <div class="nine columns">
                <input id="id" type="text" name="id" required="required" <?=isset($_POST['id']) ? 'value="' . $_POST['id'] . '"' : ''?>>
            </div>

            <div class="three columns alpha">
                <label for="password">Wachtwoord</label>
            </div>

            <div class="nine columns">
                <input id="password" type="password" name="password" required="required" <?=isset($_POST['password']) ? 'value="' . $_POST['password'] . '"' : ''?>>
            </div>
        </div>

        <input id="button" type="submit" value="Voeg toe" class="btn primary">
        <a href="" class="btn primary">Terug</a>
    </form>
<?php include 'include/footer.php';