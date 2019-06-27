<?php include 'include/header.php'; ?>
    <a href=""><h1>Smarthome</h1></a>
    
    <table class="table">
        <thead>
            <tr>
                <th width="<?=isset($user) ? '30' : '40'?>%">Lokaal</th>
                <th width="20%">Temperatuur</th>
                <th width="20%">Vochtigheid</th>
                <th width="20%">CO2</th>
                <?php if (isset($user)): ?>
                    <th width="10%">Status</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classrooms as $classroom): ?>
            	<tr>
            		<td><span>Lokaal </span><?=$classroom['classroom']?></td>
                    <td><?=$classroom['temperature']?> °C</td>
                    <td><?=$classroom['humidity']?> %</td>
                    <td><?=$classroom['carbondioxide']?> ppm</td>
                    <?php if (isset($user)): ?>
                        <td>
                            <a href="?action=turnStateHot&classroom=<?=rawurlencode($classroom['classroom'])?>" class="alert danger<?=$classroom['status'] == 'hot' ? ' active' : ''?>"><i class="fa fa-fire"></i></a>
                            <a href="?action=turnStateCold&classroom=<?=rawurlencode($classroom['classroom'])?>" class="alert info<?=$classroom['status'] == 'cold' ? ' active' : ''?>"><i class="fa fa-snowflake"></i></a>
                        </td>
                    <?php endif ?>
            	</tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
    <?php if (!isset($user)): ?>
        <div class="twelve columns add-top">
            <h2 class="h4">Inloggen</h2>

            <form method="POST" action="">
                <div class="row add-bottom">
                    <div class="three columns alpha">
                        <label for="username">Gebruikersnaam</label>
                    </div>

                    <div class="nine columns">
                        <input id="username" type="text" name="username" required="required">
                    </div>
                </div>

                <div class="row add-bottom">
                    <div class="three columns alpha">
                        <label for="password">Wachtwoord</label>
                    </div>

                    <div class="nine columns">
                        <input id="password" type="password" name="password" required="required">
                    </div>
                </div>

                <button id="button" type="submit" value="nouse[submit]" class="btn primary">Inloggen</button>
            </form>
        </div>
    <?php else: ?>
        <div class="row add-top">
            <h2 class="h4">Welkom <?=$user['name']?></h2>
            
            <a href="?action=uitloggen" class="btn primary add-right">Uitloggen</a>

            <?php if ($user['id'] == 'admin'): ?>
                <a href="?action=addUser" class="btn primary">Voeg leraar toe</a>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php include 'include/footer.php';
