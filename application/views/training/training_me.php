<?php
ob_start();
?>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="row">
  <div class="col-md-6">
    <div class="card card-stats card-success">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-users"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Total Training Tersedia </p>
              <h4 class="card-title">
                <?php echo $getCountTraining; ?>
                <!-- give code for jumlah training -->
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-stats card-primary">
      <div class="card-body ">
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-bar-chart"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Total Materi Tersedia</p>
              <h4 class="card-title"> <?php echo $getCountSubstance; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-md-3">
        <div class="card card-stats card-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="la la-newspaper-o"></i>
                        </div>
                    </div>
                    <div class="col-7 d-flex align-items-center">
                        <div class="numbers">
                            <p class="card-category">Materi Telah Diakses</p>
                            <h4 class="card-title"><?php echo $getCountMyTraining; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats card-danger    ">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="la la-check-circle"></i>
                        </div>
                    </div>
                    <div class="col-7 d-flex align-items-center">
                        <div class="numbers">
                            <p class="card-category">Persentase Materi Belum</p>
                            <h4 class="card-title"><?php echo $getCountMySubstance; ?> %</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card" style="height: 350px;">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <h4 class="card-title">Tren Pengaksesan Materi Baru</h4>
          </div>
        </div>
      </div>
      <div id="chart" style="height: 150px;">
      </div>
    </div>
  </div>
</div>

<div class="row row-card-no-pd">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <p class="fw-bold mt-1">Materi yang sudah saya akses</p>
        <h4><b><?php echo $getCountMyDoneLesson; ?></b></h4>
        <a href="<?php echo base_url('Training') ?>" class="btn btn-primary btn-full text-left mt-3 mb-3"><i class="la la-plus"></i>Belajar Lagi</a>
      </div>
      <div class="card-footer">
        <!-- <ul class="nav">
          <li class="nav-item"><a class="btn btn-default btn-link" href="#"><i class="la la-history"></i> History</a></li>
          <li class="nav-item ml-auto"><a class="btn btn-default btn-link" href="#"><i class="la la-refresh"></i> Refresh</a></li>
        </ul> -->
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card">
      <div class="card-body" style="overflow-y: scroll;">

        <?php foreach ($getCountMyDonePercent as $e) { ?>
          <div class="progress-card">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted"><?php echo $e->TRNHDR_TITLE; ?></span>
              <span class="text-muted fw-bold">
                <?php echo intval($e->percentage); ?>%</span>
            </div>
            <?php
            if (!function_exists('calculateColor')) {
              function calculateColor($percentage)
              {
                $startColor = [216, 19, 37]; // RGB values for #E30000
                $middleColor = [248, 229, 60]; // RGB values for middle color
                $endColor = [55, 227, 49]; // RGB values for #59d05d

                if ($percentage <= 50) {
                  $r = $startColor[0] + ($middleColor[0] - $startColor[0]) * (2 * $percentage / 100);
                  $g = $startColor[1] + ($middleColor[1] - $startColor[1]) * (2 * $percentage / 100);
                  $b = $startColor[2] + ($middleColor[2] - $startColor[2]) * (2 * $percentage / 100);
                } else {
                  $r = $middleColor[0] + ($endColor[0] - $middleColor[0]) * (2 * ($percentage - 50) / 100);
                  $g = $middleColor[1] + ($endColor[1] - $middleColor[1]) * (2 * ($percentage - 50) / 100);
                  $b = $middleColor[2] + ($endColor[2] - $middleColor[2]) * (2 * ($percentage - 50) / 100);
                }

                return sprintf("#%02x%02x%02x", $r, $g, $b);
              }
            }

            // ... (rest of your code)

            $color = calculateColor($e->percentage);
            ?>

            <div class="progress progress-striped active">
              <div role="progressbar" class="progress-bar" style="width:<?php echo intval($e->percentage); ?>%; background-color: <?php echo $color; ?>">
                <!-- Your content goes here -->
              </div>
              <!-- <span>"tambah disini untuk kasih kata" di progress bar</span> -->
            </div>

          </div>
          <!-- </div> -->
        <?php };  ?>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <p class="fw-bold mt-1">Statistic</p>
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center icon-warning">
              <i class="la la-pie-chart text-success"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Materi Saya</p>
              <h4 class="card-title"> <?php echo $getCountMySubstance; ?></h4>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-5">
            <div class="icon-big text-center">
              <i class="la la-heart-o text-primary"></i>
            </div>
          </div>
          <div class="col-7 d-flex align-items-center">
            <div class="numbers">
              <p class="card-category">Training Saya</p>
              <h4 class="card-title"><?php echo $getCountMyTraining; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  var trendAccessData = <?php echo json_encode($getMyTrendAccess); ?>;
  var ctx = document.getElementById('chartContainerPersonal').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: trendAccessData.map(function(item) {
        return item.YearMonth;
      }),
      datasets: [{
        data: trendAccessData.map(function(item) {
          return item.RecordCount;
        }),
        label: "Total Akses",
        borderColor: "rgb(60,186,159)",
        backgroundColor: "rgb(60,186,159,0.1)",
      }]
    },
  });
</script>
<!-- Script to initialize and render the chart -->
<script>
  // Assuming trendAccessData is an array of objects with properties YearMonth and RecordCount
  var trendAccessData = <?php echo json_encode($getMyTrendAccess); ?>;

  var options = {
    series: [{
      name: "Jumlah Akses Materi Baru",
      // Update data property with values from trendAccessData
      data: trendAccessData.map(function(item) {
        return item.RecordCount;
      })
    }],
    chart: {
      height: 280,
      type: 'area'
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth'
    },
    xaxis: {
      type: 'month',
      // Update categories property with values from trendAccessData
      categories: trendAccessData.map(function(item) {
        return item.YearMonth;
      })
    },
    tooltip: {
      x: {
        format: 'dd/MM/yy HH:mm'
      },
    },
    toolbar: {},
  };

  var chart = new ApexCharts(document.getElementById("chart"), options);
  chart.render();
</script>


<?php include __DIR__ . '/../script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>