{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')
     <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Manage Testimonials 
                    <!-- <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div> -->
                </h3>
            </div>
            
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <!--begin::Search Form-->
            <div class="mb-7">
                @include('flash-message')
                <form action="">
                
                
                </form>
            </div>
            <!--begin: Datatable-->
            <div class="table-responsive">
                <table class="datatable datatable-bordered datatable-head-custom" id="testimonial_data_listing">
                    <thead>
                    
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--end: Datatable-->
        </div>


<!-- Modal -->
<div id="myModal" class="modal fade modal-center" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Testimonial Description</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body viewdescriptions">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


    </div>
@endsection

{{-- Styles Section --}}
@section('styles')

@endsection
