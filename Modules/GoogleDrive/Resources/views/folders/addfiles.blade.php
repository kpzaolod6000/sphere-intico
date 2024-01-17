<div class="p-5 mx-5" >
    <div class="col-md-12 dropzone browse-file" id="GoogleDrive-upload">
        <div class="dz-message p-5 mx-5" data-dz-message>
            <span>{{__('Drop files here to upload or click to upload')}}</span>
        </div>
    </div>
</div>
<style>
    .dz-preview .dz-image {
    max-height: 200px; /* Set your desired max height */
    max-width: 200px;  /* Set your desired max width */
}
</style>
<script src="{{ asset('assets/js/plugins/dropzone.js') }}"></script>
<script>
    // Dropzone has been added as a global variable.
    Dropzone.autoDiscover = false;
    var dropzone = new Dropzone('#GoogleDrive-upload', {
        maxFiles: 20,
        maxFilesize: 200,
        acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt,.json,.sql,.csv,.zip",
        url: "{{ route('upload.file.store',$module) }}",
        success: function(file, response) {
            if (response.flag == 1)
            {
                toastrs('Success', response.msg, 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('googledrive.module.index',[$module,$folder_id]) }}";
                }, 600);
            }
        }
    });
    
    dropzone.on('sending', function(file, xhr, formData) {
        formData.append('_token', "{{ csrf_token() }}");
    });
</script>