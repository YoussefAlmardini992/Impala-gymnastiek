<html>
<head>
    <link rel="stylesheet" href="../styles/liveOverzicht.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<div class="live_container">
    <div class="live_header">
        <p>Wedstrijd beginnen? u kunt een greop kiesn daarnaa op start clicken.</p>
        <div class="groep_select_box">
            <div class="header_item heading">Groep</div>
            <div class="header_item selector">
                <select onchange="onGroepSelect(this)">
                    <option value="default">kiezen</option>
                    <option value="goep naam">goep naam</option>
                    <option value="goep naam">goep naam</option>
                    <option value="goep naam">goep naam</option>
                    <option value="goep naam">goep naam</option>
                </select>
            </div>
            <div class="inputItem_Submit start">
                <input type="submit" name="submit" onclick="onStart()" value="start">
            </div>
            <div class="inputItem_Submit refresh">
                <input type="submit" name="submit" onclick="refresh()" value="vernieuwen">
            </div>
        </div>
    </div>

    <div class="live_status">
        <h2>live status</h2>
        <div class="status_container">
            <div class="option status_target side">
                secretariaat
            </div>

            <div class="option status_loading">
                <div class="progress">
                    <div id="box_secretariaat" class="box"></div>
                </div>
            </div>

            <div class="option status_loading side">
                <div id="secretariaat">not connected</div>
            </div>
        </div>

        <div class="status_container">
            <div class="option status_target side">
                scoreboard
            </div>

            <div class="option status_loading">
                <div class="progress">
                    <div id="box" class="box"></div>
                </div>
            </div>

            <div class="option status_loading side">
                <div id="connect_label">not connected</div>
            </div>
        </div>
    </div>
</div>

<script>
  const sql = require('mssql');

  const config = {
    user: 'root',
    password: '',
    server: 'localhost',
    database: 'rocle_db10',

    options: {
      encrypt: true
    }
  };

  (async function () {
    try {
      let pool = await sql.connect(config)
      let result1 = await pool.request()
        .input('input_parameter', sql.Int, value)
        .query('select * from deelnemers')

      console.log(result1);
    } catch (err) {

    }
  })();

  sql.on('error', err => {

  });

    function load(box_name,label) {
          $("#"+box_name).animate({width: "400px"},1000,function(){
          $("#"+label).text("connected...");
        });
    }

    function onStart(){
      load("box_secretariaat","secretariaat");
    }
    
    function refresh(){
      load("box_secretariaat","secretariaat");
    }

    function onGroepSelect(select){
        let selectedOption = select.value;
        <?php 
            $query = "SELECT * FROM 'groepen' WHERE 'groep naam' = "?>selectedOption<?php
        ?>
    }

</script>

</body>
</html>
