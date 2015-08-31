<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);

include "init.php";

include "api.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>APC Admin</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script src="./js/highcharts.js"></script>
        <script src="./js/highcharts-3d.js"></script>
        <script src="./js/highcharts-more.js"></script>
        <script src="./js/exporting.js"></script>
        <script src="./js/jquery.timeago.js"></script>
        <script src="./js/jquery.tablesorter.min.js"></script>

        <script>
            var req_time = (new Date()).getTime();
            var opcode_progress = Array();
            var ctime = (new Date()).getTime();
            var opcode_chart;
            var cache;

            function refreshData() {
                req_time = (new Date()).getTime();
                $.getJSON("apc.php?status", function (data) {

                    cache = data;

                    var curr_time = (new Date()).getTime();
                    var delay = curr_time - req_time;

                    if (delay > 1000) {
                        refreshData();
                    } else {
                        setTimeout(refreshData, (1000 - (curr_time - req_time)));
                    }

                });
            }
            ;

            function applyData() {
                if (typeof cache != 'undefined') {
                    var curr_time = (new Date()).getTime();


                    console.log(cache);

                    opcode_progress[0].addPoint([curr_time, cache.num_hits], false, true);
                    opcode_progress[1].addPoint([curr_time, cache.num_misses], false, true);

                    opcode_chart.redraw();

                    user_progress[0].addPoint([curr_time, cache.user.num_hits], false, true);
                    user_progress[1].addPoint([curr_time, cache.user.num_misses], false, true);

                    user_chart.redraw();

                }
                setTimeout(applyData, 1000);
            }
            ;

            $(document).ready(function () {
                $(".timeago").timeago();
                $(".sorter").tablesorter();
                refreshData();
                applyData();
            });
        </script>
    </head>
    <body>

        <div class="container">
            <div id="content">

                <ul id="tabs" class="nav nav-tabs" data-tabs="tabs" role="tablist">
                    <li class="active"><a href="#status" role="tab" data-toggle="tab"><b>APC Admin</b></a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Opcode Cache <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#opcode" role="tab" data-toggle="tab">Cache Info</a></li>
                            <li><a href="#per-file" role="tab" data-toggle="tab">Cached Files</a></li>
                            <li><a href="#per-directory" role="tab" data-toggle="tab">Per Directory Entries</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">User Cache <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#user" role="tab" data-toggle="tab">Cache Info</a></li>
                            <li><a href="#per-user" role="tab" data-toggle="tab">Cached User Variables</a></li>
                        </ul>
                    </li>

                    <li><a href="#fragmentation" role="tab" data-toggle="tab">Memory Fragmentation</a></li>
                    <li><a href="#config" role="tab" data-toggle="tab">Configuration</a></li>
                    <li><a href="#about" id="about_link" role="tab" data-toggle="tab">About</a></li>

                    <li role="presentation" class="disabled"><a href="#"><small>Last updated <?php echo date(DATE_ATOM); ?></small></a></li>
                </ul>

                <div class="tab-content" style="padding-top:1em">

                    <div class="tab-pane active" id="status">
                        <?php include "status.php"; ?>
                    </div>

                    <?php include "opcode.php"; ?>

                    <?php include "user.php"; ?>

                    <div class="tab-pane" id="fragmentation">
                        <?php include "fragmentation.php"; ?>
                    </div>

                    <div class="tab-pane" id="config">
                        <?php include "config.php"; ?>
                    </div>

                    <div class="tab-pane" id="about">
                        <?php include "about.php"; ?>
                    </div>

                </div>  
            </div>
        </div>

    </body>
</html>