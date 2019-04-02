<?php
//include("../uti/connection.php");
include("../../../connection.php")
?>
<html>
<head>
    <link rel="stylesheet" href="../styles/liveOverzicht.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
</head>
<body>

<div class="live_container">
    <div class="live_header">
        <p>Wedstrijd beginnen? u kunt een greop kiesn daarnaa op start clicken.</p>
        <div class="groep_select_box">
            <div class="header_item heading">Groep</div>
            <div class="header_item selector">
                <select onchange="onGroepSelect(this)">
                    <option selected="default"></option>
                    <!-- SQL query die alle groepen ophaalt en in OPTIONs zet -->
                    <?php
                    $groepenNaam = [];
                    $sql = "SELECT naam, ID FROM `groepen` ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            array_push($groepenNaam, $row);
                        }
                    }
                    foreach($groepenNaam as $valuekey) {
                        echo "<option value=".$valuekey['ID'].">".$valuekey['naam']."</option>";
                    }
                    ?>
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
    const socket = io.connect('http://145.120.207.219:3000');

    const users = [];

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
        socket.emit('select_group',selectedOption);
    }

    socket.on('selected_group',function (result) {
        console.log(result);
        return result;
    });



    Array.prototype.remove = function() {
      let what, a = arguments, L = a.length, ax;
      while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
          this.splice(ax, 1);
        }
      }
      return this;
    };


    let firsTime = true;
    socket.on('get_jury',function (res) {

    if(!firsTime){

      let founded = false;

      users.forEach(function (item) {

        if(item.user === res.user){
          users.remove(item);
          founded = true;
        }

      });

      !founded && users.push(res);

    }else{
      res.status === 'connected' && users.push(res);
      firsTime = false;
    }

      console.log(users);
    });









</script>

</body>
</html>
