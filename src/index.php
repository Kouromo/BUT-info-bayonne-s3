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
        <i class="fa-solid fa-user"></i>
        <input id="searchbar" onkeyup="search_ticket()" type="text"
        name="search" placeholder="Search tickets..">
    </header>

    <main>
        <bw-promotion>
            <div class="categories">
                <div role="tab" class="active" id="#nouveautes">Nouveautés</div>
                <div role="tab" id="#sport">Sport</div>
                <div role="tab" id="#festival">Festival</div>
                <div role="tab" id="#concert">Concert</div>
            </div>
            <div role="tab" class="mat-tab-label mat-tab-label-active" id="mat-tab-label-1-0" tabindex="0" aria-posinset="1" aria-setsize="2" aria-controls="mat-tab-content-1-0" aria-selected="true" aria-disabled="false"><div class="mat-tab-label-content"><!---->Vols<!----></div></div>
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