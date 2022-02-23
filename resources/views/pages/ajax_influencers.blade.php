@foreach($influencers as $influencer)
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="influencer_block">
			<div class="Pos-R">
				<a href="{{url('influencer')}}/{{$influencer['username']}}/profile">@if(is_file(public_path("/media/users").'/'.@$influencer['image']))
                    <img src="{{asset('media/users/'.$influencer['image'])}}">
                @else
                    <img src="media/users/blank.png">
                @endif</a>
				<div class="price">
					<span>${{@$influencer->getPrice($influencer['id'])}}</span>
				</div>
			</div>
			<div class="influencer_block_content">
				<a href="{{url('influencer')}}/{{$influencer['username']}}/profile"> <h2>{{'@'.@$influencer['username']}}</h2></a>
				<p>{{@$influencer->getCategory()}}</p>
				<a href="{{url('influencer')}}/{{$influencer['username']}}/profile"><h4>{{@$influencer->getFollowers()}} Followers</h4></a>
			</div>
		</div>
	</div>
@endforeach