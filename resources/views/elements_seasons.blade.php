<x-app-layout>
    @include('components.sidebar')
    <div class="wrapper bg-image guide-wrapper">
        <div class="inner-wrapper">
            
            <div class="guide-content">
                <div class="content-box-left">
                    <div class="logo">
                        <a href=""><img src="\img\Quantum-evaluation-logo.png" alt="logo-image"/></a>
                    </div>
                    <h2>A GUIDE TO THE SEASONS</h2>
                    <p>The seasons are used as a metaphor to explain the cycle of life and self-actualization. Each season corresponds to a different stage of life and is associated with different elements and vital organs, according to Taoist philosophy.</p>
                </div>
                <div class="content-box-right">
                    <p><b>Spring:</b> This season is associated with the liver and the gallbladder. It symbolizes birth and the beginning of life, much like how spring represents the start of a new year in nature</p>
                    <p><b>Summer:</b> The heart is the organ associated with summer. This season represents maturity and the peak of life, similar to how summer is the time when nature is at its most vibrant. The heart chakra is said to be more open in those who have been nurtured and matured, which is symbolically linked to the warmth and growth of summer.</p>
                    <p><b>Late Summer:</b> This season is associated with the spleen, pancreas, and stomach. It represents a time of nurturing and further maturity. The late summer is seen as a time of decreasing power, reflecting the transition from the peak of life to a more reflective and nurturing stage.</p>
                    <p><b>Fall:</b> The lung and large intestine are the organs associated with fall. This season represents adulthood and the beginning of decline, much like how fall represents the start of decay in nature.</p>
                    <p><b>Winter:</b> The bladder and kidney are associated with winter. This season represents the end of life, similar to how winter signifies the end of the year in nature.</p>
                    <p>It's important to note that these associations are not literal but symbolic, serving to illustrate the cyclical nature of life and the journey of self-actualization. The cycle could represent a day in your life, a year, or your entire lifespan. The aim is to understand these natural laws and align with them for optimal health.</p>
                </div>
            </div>
        </div>
    </div>
    <style>
         .guide-content p,strong  {
            font-size:20px;
        }
        b {
            font-size:18px;
        }
        h2 {
  font-family: Nunito Sans, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
  font-size: 30px;
  font-weight: 900;
  text-transform: uppercase;
  font-weight: 900;
  margin-bottom: 0.5em;
  line-height: normal;
  animation: blink 3s infinite;
}
.guide-content p {
  font-size: 16px;
  line-height: normal;
  margin-bottom: 0.8em;
}
 .content-box-right {
  padding-top: 40%;
}
@media (max-width:767px) {
     .guide-wrapper:after{
              content: "";
              background-color: rgba(0, 0, 0, 0.6);
              position: absolute;
              left: 0;
              right: 0;
              top: 0;
              bottom: 0;
            }
            .guide-wrapper .inner-wrapper {
  position: relative;
  z-index: 1;
}
}
    </style>
</x-app-layout>
