<?php 
	include("header.php");
?>
    <div class="container">
        <br>
		<?php	$commando = "pgrep -f 'python36'";
	$output = shell_exec($commando);

	if(empty($output)){?>
		<div class="alert alert-success" style="    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;">Er is nog geen crawler actief!</div>
	<?php
	}
	else { ?>
		<div class="alert alert" style="    color: #191919;
    background-color: #ffc645;
    border-color: #ffc645;">Er is al een crawler actief!</div>
	<?php }
	?>
        <h3>Start a Crawler</h3>
        <br/>
        <p>
            <form class="pure-form pure-form-aligned" method="post" action="index.php">
                <fieldset>
                    <div class="pure-control-group">
                        <label for="url">URL (*):</label>
                        <input id="url" name="url" type="text" placeholder="URL to crawl" required <?php if(isset($output)) { echo "disabled"; }?>>
                        <span class="pure-form-message-inline">Dit is verplicht!</span>
                    </div>

                    <div class="pure-control-group">
                        <label for="username">Gebruikersnaam:</label>
                        <input id="username" type="text" placeholder="Gebruikersnaam" <?php if(isset($output)) { echo "disabled"; }?>>
                        <span class="pure-form-message-inline">Optioneel</span>
                    </div>

                    <div class="pure-control-group">
                        <label for="wac">Wachtwoord:</label>
                        <input id="password" type="password" placeholder="Wachtwoord" <?php if(isset($output)) { echo "disabled"; }?>>
                        <span class="pure-form-message-inline">Optioneel</span>
                    </div>

                    <div class="pure-controls">
                        <button type="submit" class="pure-button pure-button-primary" <?php if(isset($output)) { echo "disabled"; }?>>Start crawler</button>
                    </div>
                </fieldset>
            </form>
        </p>
	<?php
		if(isset($_POST['url'])) {
			$url = $_POST["url"];
			$commando = 'nohup python36 /opt/TorCrawlerProject/webCrawler/main.py '.$url.' > /dev/null &';
			shell_exec($commando);
			print 'Webcrawler is gestart ...! Refresh in 3 seconden. Check statistics!';
			header("Refresh:3");
		}
	?>
	
	<?php
		if(isset($output)) { echo '<a class="pure-button" style="background: rgb(202, 60, 60); color: white; border-radius: 4px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);" href="index.php?killcrawler">Kill clawler</a><br /><br />'; }
		if (isset($_GET["killcrawler"])) { 
			$commando = 'pkill -9 -f main.py';
			shell_exec($commando);
			print 'Crawler gestopt, refresh in 3 seconden.';
			header("Refresh:3");
		}
	?>
    </div>

    </body>

    </html>