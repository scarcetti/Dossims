@extends('superadmin.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Register New Branch</h2>
        </div>

        <div class="w-md-50 p-md-3 m-auto">
            <form action="{{ route('createBranch') }}" method="post">
                <h4>Branch Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" placeholder="" required>
                            <label>Branch name<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="contact_no" placeholder="" required>
                            <label>Contact Number<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">

                                <select class="form-control" id="type" name="type" required>
                                    <option>Type 1 </option>
                                    <option>Type 2 </option>
                                </select>
                            </div>
                            <label>Type<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="address" placeholder="" required>
                            <label>Address<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="city" placeholder="" required>
                            <label>City<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="province" placeholder="" required>
                            <label>Province<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="zipcode" placeholder="" required>
                            <label>Zip<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 my-3 d-flex flex-row-reverse">
                    <button class="btn btn-success w-auto ms-auto" type="submit">Register Branch</button>
                </div>
            </form>
        </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
<script>
    function createBranch(){
       /*  axios.post(`branches/create`).then(response => {
            const angTubag = response
            console.log(response)
        }); */
        console.log('TEST')
    }
</script>
