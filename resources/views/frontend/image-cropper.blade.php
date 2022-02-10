@extends('frontend.layouts.page')
@section('title', 'Image Cropper')

@section('content')
	<style type="text/css">
        #image_cropper {
		  display: block;
		  max-width: 100%;
		}
	</style>
    <div>
  		<img id="image_cropper" src="{{ asset('storage/logo-gmail.png') }}">
	</div>
@endsection

@section('js')
    <script>
        $(function () {
            const image = document.getElementById('image_cropper');
			const cropper = new Cropper(image, {
			  	aspectRatio: 1,
			  	cropend(event) {
				    console.log(cropper.getData());
			  		var data = cropper.getData();
				    console.log(data.x);
				    console.log(data.y);
				    console.log(data.width);
				    console.log(data.height);
				    const formData = new FormData();

				  	formData.append('x', data.x);
				  	formData.append('y', data.y);
				  	formData.append('size', data.width);

				  	$.ajax('/image-crop', {
					    method: 'POST',
					    headers: {
		                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		                },
					    data: formData,
					    processData: false,
					    contentType: false,
					    success() {
					      console.log('Upload success');
					    },
					    error() {
					      console.log('Upload error');
					    },
				  	});
				 },
			});
        });
    </script>

@endsection
