<script type="text/javascript">
    
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 300000000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })

</script>

<script>
        $(document).ready(function() {
            //Mobile Filters Accodian
            if ($(window).width() <= 767) {
                $(".filterMenu").click(function() {
                    $(this).toggleClass("active");
                    $('.filters_outer').toggleClass("toggle");
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            //Mobile Filters Accodian
            if ($(window).width() <= 767) {
                $(".filterMenu").click(function() {
                    $(this).toggleClass("active");
                    $('.chat-profile_sidebar').toggleClass("toggle");
                });
            }
        });
    </script>

@if ($message = Session::get('alert'))
<script type="text/javascript">
    $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        console.log(!sessionStorage.getItem('shown-' + popupId));
        if(!sessionStorage.getItem('shown-' + popupId)) {    
            Toast.fire({
              icon: 'success',
              title: "{{$message}}"
            })
         }
        sessionStorage.setItem('shown-' + popupId, '1');
    });
</script>
@endif

                            

@if ($message = Session::get('success'))
<script type="text/javascript">
    $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {   
            
            Toast.fire({
              icon: 'success',
              title: "{{$message}}"
              })
             }
        sessionStorage.setItem('shown-' + popupId, '1');
    });
</script>
@endif


@if ($message = Session::get('error') )
<script type="text/javascript">
     $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {  
            Toast.fire({
              icon: 'error',
              title: "{{$message}}"
            })
            }
        sessionStorage.setItem('shown-' + popupId, '1');
    });
</script>
@endif


@if ($message = Session::get('warning'))
<script type="text/javascript">
     $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {  
            Toast.fire({
              icon: 'warning',
              title: "{{$message}}"
            })
         }
        sessionStorage.setItem('shown-' + popupId, '1');
    });    
</script>
@endif


@if ($message = Session::get('info'))
<script type="text/javascript">
    $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {  
            Toast.fire({
              icon: 'info',
              title: "{{$message}}"
            })
        }
        sessionStorage.setItem('shown-' + popupId, '1');
    });      
</script>
@endif


@if ($errors->any())
<script type="text/javascript">
     $(document).ready(function() {
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {  
            Toast.fire({
              icon: 'info',
              title: "Please check the fields"
            })
        }
        sessionStorage.setItem('shown-' + popupId, '1');
    });    
</script>
@endif