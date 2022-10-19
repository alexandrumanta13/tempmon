 <!--  <?php //print_r(getAssignedSensors(2)) ?> -->
                   <!--   <ul class="sensor-list">
                        <? //if(!empty(getAssignedSensors(2))) : ?>
                            <? //for($i = 0; $i < count(getAssignedSensors(2)); $i++) : ?>
                                <li style="margin-bottom: 0; box-shadow: none">
                                    <div class="raport">
                                        <!-- <a class="btn primary" href="http://tempmon.ro/demo/configuration/notifications?sensorid=<?= //getAssignedSensors(1)[$i]['sensorid'] ?>">Raport</a> -->
                                        <!--  <a class="btn primary" href="http://tempmon.ro/demo?sensorid=<?= //getAssignedSensors(2)[$i]['sensorid'] ?>&locationid=<?= //getAssignedSensors(1)[$i]['storeid'] ?>">Raport</a>
                                    </div>
                                    <div class="sensor-list-details">
                                        <div class="sensor-details">
                                            <p><?=//getAssignedSensors(2)[$i]['name']?> (ID<?=//getAssignedSensors(2)[$i]['sensorid']?>:<?=//getAssignedSensors(2)[$i]['storeid']?>)</p>
                                            <? //if(showGatewayStatus(2, 1, "MINUTE", //getAssignedSensors(2)[$i]['sensorid'], //getAssignedSensors(2)[$i]['storeid']) == 'gateway-online') : ?>
                                                <small class="online">Online</small>
                                            <? //else : ?>
                                                <small class="offline">Offline</small>
                                            <?// endif; ?>
                                        </div>
                                        <div class="current-temperature">
                                            <?php
                                            //$status = "online";

                                            // if(getAssignedSensors(2)[$i]['current_temp'] > getAssignedSensors(2)[$i]['interval_max'] || getAssignedSensors(2)[$i]['current_temp'] < getAssignedSensors(2)[$i]['interval_min'] )
                                            //     $status = "offline"
                                            ?>
                                            <span class="<?=$status?>">
                                                <?=//getAssignedSensors(2)[$i]['current_temp']?>
                                            </span>
                                        </div>
                                        <div class="last-incident">
                                            <?php 
                                            // if(count(getLastIncident((int)getAssignedSensors(2)[$i]['sensorid'], 0)) > 0) {
                                            //     for($j = 0; $j < count(getLastIncident((int)getAssignedSensors(2)[$i]['sensorid'], 0)); $j++) {
                                            //         echo getLastIncident((int)getAssignedSensors(2)[$i]['sensorid'], 0)[0]['reported_at'];
                                            //     }
                                            // } else {
                                            //     echo "N/A";
                                            // }
                                            
                                            ?>

                                          
                                    </div>

                           <!--  <div class="list-actions">
                                <a href="http://tempmon.ro/demo/configuration/sensors/edit?id=<?=// getAssignedSensors(1)[$i]['id'] ?>" class="edit">
                                    <i class="icon-edit"></i>
                                    <small>Editeaza</small>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/remove?id=<?= //getAssignedSensors(1)[$i]['id'] ?>" class="delete">
                                    <i class="icon-delete"></i>
                                    <small>Sterge</small>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/assign?id=<?= //getAssignedSensors(1)[$i]['id'] ?>" class="assign">
                                    <i class="icon-assign"></i>
                                    <small>Asigneaza</small>
                                </a>
                            </div> -->
                        <!--  </div>
                    </li>
                <? //endfor; ?>
            <? //endif; ?> 
        </ul>-->