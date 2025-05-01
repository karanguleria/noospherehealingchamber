<x-app-layout>
    @include('components.sidebar')
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <!--------Result Page-------->
    <div class="wrapper result-web-page">
        <div class="result-web-dewnload-sec">
            <div class="row-box">
                <div class="box-inner">
                    <img src="\img\Quantum-evaluation-logo.png" alt="logo">
                    <div class="some-text">
                    <h1>Your Wellness Insights</h1>
                        <p>Your responses are invaluable in crafting a personalized wellness plan tailored uniquely to you. Here are your wellness insights:</p>
                    </div>
                </div>
                <div class="box-inner buttons-box">
                    <a href="<?php echo route('download.result', $result->id); ?>" class="white-btn">Download Results</a>

                        <!-- <a target="_blank" href="https://quantumevaluation.exponentialhealthcare.com/img/Interpreting Your Results.pdf" class="white-btn">Interpret Results</a> -->
                        <a href="{{ route('question.elementsguide') }}" class="skyblue-btn">Elements Guide</a>
                        <a href="{{ route('question.elementsseasons') }}" class="skyblue-btn">Seasons Guide</a>

                   
                </div>
            </div>
        </div>
        <?php  
    foreach($elements as $key_element => $element){        
    $element_detail = App\Models\Element::where('title', $key_element)->first();
    ?>
    
        <div class="sec-page <?php echo strtolower($key_element); ?>-sec ">
            <div class="section-logo">
                <h2><?php echo ucfirst($key_element); ?></h2>
                <img src="/storage/<?php echo $element_detail['result_img']; ?>" alt="<?php echo $key_element; ?>">
            </div>
            <?php foreach($element as $key_bodytype => $bodytype){
            $bodytype_image = App\Models\Bodypart::where('title', $key_bodytype)->value('png_img'); 
            ?>
            <div class="inner-row">
                <div class="img-box">
                    <img src="/storage/<?php echo $bodytype_image; ?>" alt="<?php echo $key_bodytype; ?>">
                </div>
                <div class="info-content">
                    <div class="heading-box">
                    <h4></h4>
                        <ul>
                            <?php foreach($bodytype['type'] as $key => $type){  ?>
                                <li><?php echo ucwords($key_bodytype); ?><span><?php echo (@$type ? "(".( @$type == 'Mental' ? 'Vital/Mental' : $type ).")": "") ; ?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="box-info">
                        <h4 class="tooltip">Excess <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg> 
                        <span class="tooltiptext"><?php echo $element_detail['excess']; ?></span>
                        </h4>
                        <ul>
                            <?php foreach($bodytype['excess'] as $key => $excess){  ?>
                            <li><?php echo $excess; ?>%</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="box-info">
                        <h4 class="tooltip">Balance  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                            <span class="tooltiptext"><?php echo $element_detail['balance']; ?></span>
                        </h4>
                        <ul>
                            <?php foreach($bodytype['balance'] as $key => $balance){  ?>
                            <li><?php echo $balance; ?>%</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="box-info">
                        <h4 class="tooltip">Insufficient <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                            <span class="tooltiptext"><?php echo $element_detail['insufficient']; ?></span>
                        </h4>
                        <ul>
                            <?php foreach($bodytype['insufficiency'] as $key => $insufficiency){  ?>
                            <li><?php echo $insufficiency; ?>%</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>


    </div>
    <div class="result-total">
    <h2>TOTAL</h2>
        <div>
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <div class="thankyou-bottom center">
        <p>Thank you for taking Nosphere Healing, Your provider will be able to answer any questions you may have
            about your results.</p>
    </div>
    <style>
         h2{
        font-weight:900;
        font-size:22px;
        line-height:33px;
       }
        .result-web-page {
           
            background-color: #0a1944;
            color: #fff;
           
        }

        
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
 
    <style>
.container {
width: 40%;
margin: 5px auto;
}
  </style>
    <script>
      var data = [{
  data: [<?php echo $result->excess; ?>, <?php echo $result->balance; ?>, <?php echo $result->insufficiency; ?>],
  backgroundColor: [
    '#E84C3D',
                    '#2A80B9',
                    '#F1C40F'
  ],
  borderColor: "#fff"
}];

var options = {
  tooltips: {
    enabled: false
  },
  plugins: {
    datalabels: {
      formatter: (value, ctx) => {
        const datapoints = ctx.chart.data.datasets[0].data
         const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
        const percentage = value / total * 100
        return percentage.toFixed(2) + "%";
      },
      color: '#fff',
    }
  }
};

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
  labels: ['Excess',
                'Balance',
                'Insufficiency'],
    datasets: data
  },
  options: options,
  plugins: [ChartDataLabels],
});


        // const ctx = document.getElementById('myChart');
        // const data = {
        //     labels: [
        //         'Excess',
        //         'Balance',
        //         'Insufficiency'
        //     ],
        //     datasets: [{
        //         label: ' Result',
        //         data: [<?php echo $result->excess; ?>, <?php echo $result->balance; ?>, <?php echo $result->insufficiency; ?>],
        //         backgroundColor: [
        //             '#E84C3D',
        //             '#2A80B9',
        //             '#F1C40F'
        //         ],
        //         hoverOffset: 4
        //     }]
        // };
        // new Chart(ctx, {
        //     type: 'pie',
        //     data: data,
        //     options: {
        //         // scales: {
        //         //   y: {
        //         //     beginAtZero: true
        //         //   }
        //         // }
        //     }
        // });
    </script>
</x-app-layout>
