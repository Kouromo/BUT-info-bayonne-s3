<?php 
    include('ConnBD.php');
    $conn = ConnBD();
    session_start();
    $query = "SELECT * FROM Billet;";
    $result = mysqli_query($conn, $query);

    
    $billets = mysqli_fetch_array($result, MYSQLI_BOTH);
    //print_r($billets);
?>

<!DOCTYPE html>
<HTML>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="main.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <title>Tickets'Press</title>
    </head>



    <body>
        <header>
            <button>Vendre ses billets</button>
        
            <input id="searchbar" onkeyup="search_ticket()" type="text"
            name="search" placeholder="Search tickets..">
            <i class="fa-solid fa-user"></i>
        </header>
        
        <main>
            <bw-promotion>
                <div class="categories">
                    <div role="tab" class="active" id="#nouveautes">Nouveautés</div>
                    <div role="tab" id="#sport">Sport</div>
                    <div role="tab" id="#festival">Festival</div>
                    <div role="tab" id="#concert">Concert</div>
                </div>
                <?php 
              
                foreach ($billets as $billet){
                    echo "<div class='billet'>";
                    echo "<h2> $billets[0] </h2> ";
                    echo "<p> $billet[7] </p>";
        
                    
                    /*if ($billet[4] == " sport "){
                    echo "<div class='category sport'>Sport</div>";}
                    elseif ($billet[4] == "festival"){ 
                        echo "<div class='category festival'>Festival</div>";}
                    elseif ($billet[4] == "concert"){
                        echo "<div class='category concert'>Concert</div>";}
                    else {
                    echo "<div class='category nouveautes'>Nouveautés</div>";}*/
                    //echo "</div>";
                    }
                ?>
                

                <div role="tab" class="mat-tab-label mat-tab-label-active" id="mat-tab-label-1-0" tabindex="0" aria-posinset="1" aria-setsize="2" aria-controls="mat-tab-content-1-0" aria-selected="true" aria-disabled="false"></div>
            </bw-promotion>
            
            <form>
                <label for="DATE">Date: </label>
                <div><input type="date" id="DATE" name="DATE"
                    value="<?php echo $thedate=date('Y-m-d'); ?>"
                    min="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d', strtotime('+5 year'));?>"></div>
            </form>
            
        </main>

        <footer>
            
        </footer>
    </body>
</HTML>