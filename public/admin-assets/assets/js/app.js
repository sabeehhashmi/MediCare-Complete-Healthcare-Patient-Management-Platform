$(document).ready(function() {
  
//   $(window).resize(function() {
      if ($(window).width() <= 1024) {
        // Code to execute when screen width is 767px or less
        console.log("Screen width is 767px or less");
        
          $('#sidebarShow').click(function() {
            $('.vertical-menu').addClass('sidebar-show');
            // document.body.removeAttribute("data-sidebar-size", "sm")
            // console.log('Hide!')
          });
          $('#sidebarHide').click(function() {
            $('.vertical-menu').removeClass('sidebar-show');
            // $('body').addClass('sidebar-enable');
            // document.body.setAttribute("data-sidebar-size", "sm")
            // console.log('Show!')
          });
        
      } else {
        // Code to execute when screen width is greater than 767px
        console.log("Screen width is greater than 767px");
    
          $('#sidebarShow').click(function() {
            $('body').removeClass('sidebar-enable');
            document.body.removeAttribute("data-sidebar-size", "sm")
            // console.log('Hide!')
          });
          $('#sidebarHide').click(function() {
            $('body').addClass('sidebar-enable');
            document.body.setAttribute("data-sidebar-size", "sm")
            // console.log('Show!')
          });
      }
//   });
    // $('select').not('.flatpickr-monthDropdown-months, #dt-length-0').select2({
    //   minimumResultsForSearch: Infinity // Disables search box
    // });
    // $('select.jqv-input, select#country, select#emirate').select2();
    // $('.modal select').select2({
    //   minimumResultsForSearch: Infinity,
    //   dropdownParent: $('.modal')
    // });
    $('select.jqv-input, select#country, select#emirate, select#emirate_id, select#area_id, select[name="country_id"]').select2();
    $("select[name='is_contract_signed'], select[name='active'], select[name='status'], .select2-nosearch").select2({
        minimumResultsForSearch: Infinity,
    });
    // Initialize Select2 on the modal shown event
            $('#neweareamodel').on('shown.bs.modal', function () {
                $('#emirate_area, #neweareamodel .status-selection').select2({
                    dropdownParent: $('#neweareamodel') // Ensures the dropdown is appended to the modal
                });
            });
            $('#newcountrymodel').on('shown.bs.modal', function () {
                $('#newcountrymodel .status-selection').select2({
                    dropdownParent: $('#newcountrymodel') // Ensures the dropdown is appended to the modal
                });
            });
});