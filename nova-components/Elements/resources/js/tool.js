import Tool from './pages/Tool'

Nova.booting((app, store) => {
  Nova.inertia('Elements', Tool)
})

// $(".more").click(function() {
//   $(this).parent().addClass('active').siblings().removeClass('active');
//   return false;
// });
