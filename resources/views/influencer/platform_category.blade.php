<select class="" name="category"> 
	@foreach($categories1 as $key => $category)
		<option value="{{ $category->id }}"> {{ $category->name }} </option>
	@endforeach
</select>
       
        
