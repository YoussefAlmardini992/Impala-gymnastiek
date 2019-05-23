<div class="selectLine">
    <div class="heading">Wedstrijd:</div>
    <div class="Selector">
        <select onchange="onWedstrijdSelect(this)" id="WedstrijdSelect" class="Select">
            <option selected="default" class="Option">Kiezen</option>
            <!-- SQL query die alle wedstrijden ophaalt en in OPTIONs zet -->
            <?php
            $wedstrijden = [];
            $sql_alleWedstrijden = "SELECT DISTINCT wedstrijddatum FROM `wedstrijden`";

            $result = $conn->query($sql_alleWedstrijden);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($wedstrijden, $row);
                }
            }

            foreach ($wedstrijden as $wedstrijd) {

                echo "<option value=" . $wedstrijd['wedstrijddatum'] . ">" . $wedstrijd['wedstrijddatum'] . "</option>";
            }

            ?>
        </select>
    </div>
</div>
<div class="selectLine">
    <div class="heading">Deelnemer:</div>
    <div class="Selector">
        <select id='deelnemers' class="Select">
            <option selected="default">Kiezen</option>
        </select>
    </div>
</div>