{{Form::open(array('route' => array('assigned.folder',$module), 'method' => 'post')) }}
   <div class="modal-body">
        <div class="col-md-12">
            <div class="col-auto accordion accordion-flush setting-accordion" id="">
                @foreach($google_drive_modules as $e_module)
                    @if((module_is_active($e_module->module)) || $e_module->module == 'General')
                        <div class="py-2">
                            <p class="accordion-header" id="headingOne">
                                <a class="" data-bs-toggle="collapse" data-bs-target="{{ '#'.$e_module->name }}" aria-expanded="true" aria-controls="{{ $e_module->name }}" href="#">
                                    {{  $e_module->name}} 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="10" height="10" viewBox="0 0 256 256" xml:space="preserve">
                                        <defs>
                                        </defs>
                                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                            <path d="M 90 24.25 c 0 -0.896 -0.342 -1.792 -1.025 -2.475 c -1.366 -1.367 -3.583 -1.367 -4.949 0 L 45 60.8 L 5.975 21.775 c -1.367 -1.367 -3.583 -1.367 -4.95 0 c -1.366 1.367 -1.366 3.583 0 4.95 l 41.5 41.5 c 1.366 1.367 3.583 1.367 4.949 0 l 41.5 -41.5 C 89.658 26.042 90 25.146 90 24.25 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                        </g>
                                    </svg>
                                </a>
                            </p>
                            <div id="{{ $e_module->name }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="{{ $e_module->name }}">
                                <div class="accordion-body" id="checkboxContainer">
                                    @foreach($google_drive_files as $file)
                                        @if($file['mimeType'] == 'application/vnd.google-apps.folder')
                                            <div class="row px-5">
                                                <span class="col-auto form-check form-switch d-inline-block">
                                                    <input class="form-check-input single-checkbox" id="google_drive" name="parent_id" type="checkbox" value="{{ $file['id'] }}">
                                                </span>
                                                {{  $file['name'] }}
                                            </div>
                                        @endif   
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif   
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" value="{{__('Assign Folder')}}" class="btn  btn-primary">
    </div>

    {{Form::close()}}

    <script>
        const checkboxes = document.querySelectorAll('#checkboxContainer .single-checkbox');
        
        checkboxes.forEach(checkbox => {
          checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
              checkboxes.forEach(otherCheckbox => {
                if (otherCheckbox !== checkbox) {
                  otherCheckbox.checked = false;
                }
              });
            }
          });
        });
    </script>
        
        















