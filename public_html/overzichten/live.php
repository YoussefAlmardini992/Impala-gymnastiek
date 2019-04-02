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

                <a href='?overzicht=start'  onclick="" >start</a>
            </div>
            <div class="inputItem_Submit refresh">
                <input type="submit" name="submit" onclick="refresh()" value="vernieuwen">
            </div>
        </div>
    </div>

    <div class="live_status" id="live_status">
        <h2>live status</h2>
    </div>
</div>

<script>
    const socket = io.connect('http://145.120.207.219:3000');
    //const socket = io.connect('http://localhost:3000');

    const users = [];

    function load(box_name,label) {
          $("#"+box_name).animate({width: "400px"},1000,function(){
          $("#"+label).text("connected...");
        });
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

    socket.on('get_user',function (res) {

    if(!firsTime){

      let founded = false;

      users.forEach(function (item) {

        if(item.user === res.user){
          users.remove(item);
          founded = true;
          removeStatusElement(item);
        }

      });

      !founded && users.push(res);

    }else{
      res.status === 'connected' && users.push(res);
      firsTime = false;
    }

      createConnectionStatus();
      console.log(users);
    });

    function removeStatusElement(item) {
      document.getElementById('live_status').removeChild(document.getElementById(item.user + item.user + item.user));
    }

    function createConnectionStatus() {

      const status =  document.getElementById('live_status');

      while (status.firstChild) {
          status.removeChild(status.firstChild);
      }

      users.forEach(function (item) {
        const statusContainer = document.createElement('div');
        statusContainer.id = item.user + item.user + item.user;
        const status_target = document.createElement('div');
        status_target.innerText = item.user;
        statusContainer.classList.add("status_container");
        status_target.classList.add("status_container");
        status_target.classList.add("side");
        status_target.classList.add("option");
        statusContainer.appendChild(status_target);
        const status_loading = document.createElement('div');
        status_loading.classList.add("option");
        status_loading.classList.add("status_loading");
        const progress = document.createElement('div');
        progress.classList.add('progress');
        const box = document.createElement('div');
        box.classList.add('box');
        progress.appendChild(box);
        status_loading.appendChild(progress);
        statusContainer.appendChild(status_loading);
        const status_loading2 = document.createElement('div');
        const connect_label = document.createElement('div');
        status_loading2.classList.add('option');
        status_loading2.classList.add('status_loading');
        status_loading2.classList.add('side');
        status_loading2.appendChild(connect_label);
        statusContainer.appendChild(status_loading2);
        box.id = item.user;
        let Label_ID = item.user + item.user;
        connect_label.innerText = item.user;
        connect_label.id = Label_ID;

        document.getElementById('live_status').appendChild(statusContainer);
        load(item.user,Label_ID);
      })
    }







    socket.on('Login_value',function (result) {
        console.log(result);
        return result;
    });

    console.log( socket.emit('request');
                 io.emit('broadcast', 'Login_value');
    )
</script>

</body>
</html>
