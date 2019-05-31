@extends('layouts.main')

@section('content')
    @include('logo')
    <p class="display-4 text-center">REPORT</p>
    <form class="container">
        @csrf
        <div class="row align-items-center justify-content-center mx-0">
            <label class="col-auto btn btn-info p-3 ml-4" for="images">Upload your images</label>
            <input id="images" name="images[]" type="file" multiple class="d-none">
            <span id="countFiles" class="col-auto ml-2">No files chosen</span>
        </div>
        <div class="row mx-0">
        
        </div>
        <div class="row mx-0 mt-4">
            <div class="col form-group p-4">
                <label for="description" class="ml-4">Description</label>
                <textarea id="description" class="form-control" rows="4" name="description"></textarea>
            </div>
        </div>
    </form>

    <script>
        document.querySelector('#images').addEventListener('change', function() {
            console.log(this.files)
            text = document.querySelector('#countFiles')
            if (this.files && this.files.length) {
                if (this.files.length == 1) {
                    text.innerHTML = '1 file'
                } else {
                    text.innerHTML = `${this.files.length} files`
                }
            } else {
                text.innerHTML = 'No file chosen'
            } 
        })
    </script>
@endsection