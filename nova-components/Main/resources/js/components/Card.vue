<template>
  <Card class="flex flex-col items-center justify-center">
     <!---main Page--->
     <div class="wrapper main-wrapper">

<div class="logo-box">
  <a href="#"><img src="\img\nhc-logo.png" alt="logo"></a>
</div>
<div class="main-inner"> 
    <div class="main-video-box">
        <div style="padding:56.25% 0 0 0;position:relative;">
        <iframe src="https://player.vimeo.com/video/919292409?h=1fe1f295ad&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Nosphere Healing Demo - Exponential Healthcare">
        </iframe>
      </div>

    </div>
    <div class="main-content-box">
        <h2>WELCOME TO THE NOOSPHERE HEALING CHAMBER</h2>
        <p>a virtual energetic space designed for wellness coaches to facilitate intentional healing. This experimental tool utilizes the sacred geometry of the star tetrahedron to generate targeted energetic effects aligned with focused intention.</p>

        <p>Within the chamber, coaches can upload client images, voice recordings, and micro- scopic blood visuals, serving as energetic inputs for each personalized session. Drawing inspiration from the teachings of Lynne McTaggart and Nassim Haramein, the chamber integrates concepts similar to Vacuum Engineering on a virtual scale, working in harmony with Collective Consciousness to amplify the power of intentional healing.</p>
        
        <p>From this dashboard, you can easily add new clients, initiate healing sessions, record personalized session notes, and access each client's complete energetic history every time you return.</p>
        <p>Watch the video tutorial to learn how to navigate and use the platform effectively.</p> 
    </div>
</div>
</div>

  </Card>
</template> 

<script>
export default {
  props: [
    'card',

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
    //
  },
}
</script>
