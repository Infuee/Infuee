
<?php 
    $rating=$rating['totalRating'];
?>
@if($rating >= 1 )
    <i class="fas fa-star"></i>
@else
    @if($rating > 0 && $rating < 1)
    <i class="fas fa-star-half-alt"></i>
    @else
    <i class="fas fa-star last-star"></i> 
    @endif
@endif

@if($rating >= 2 )
    <i class="fas fa-star"></i>
@else
    @if($rating > 1 && $rating < 2)
    <i class="fas fa-star-half-alt"></i>
    @else
    <i class="fas fa-star last-star"></i> 
    @endif
@endif
@if($rating >= 3 )
    <i class="fas fa-star"></i>
@else
    @if($rating > 2 && $rating < 3)
    <i class="fas fa-star-half-alt"></i>
    @else
    <i class="fas fa-star last-star"></i> 
    @endif
@endif
@if($rating >= 4 )
    <i class="fas fa-star"></i>
@else
    @if($rating > 3 && $rating < 4)
    <i class="fas fa-star-half-alt"></i>
    @else
    <i class="fas fa-star last-star"></i> 
    @endif
@endif
@if($rating >= 5 )
    <i class="fas fa-star"></i>
@else
    @if($rating > 4 && $rating < 5)
    <i class="fas fa-star-half-alt"></i>
    @else
    <i class="fas fa-star last-star"></i> 
    @endif
@endif
