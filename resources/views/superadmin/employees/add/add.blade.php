@extends('superadmin.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Register New Employee</h2>
            <!--   <div class="d-flex gap-3 ms-auto">
                                        <a href="" class="btn btn-outline-danger h-100 ms-auto">Back</a>
                                        <a href="" class="btn btn-outline-success h-100">Add Student</a>
                                    </div> -->
        </div>

        <div class="w-md-50 p-md-3 m-auto">
            <form method="post">
                <h4>Employee's Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="l_name" placeholder="" required>
                            <label>Last Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="f_name" placeholder="" required>
                            <label>First Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="m_name" placeholder="">
                            <label>Middle Name</label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <label>Gender</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="b_date" placeholder="" required>
                            <label>Birthdate <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="contact" placeholder=""
                                pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" required>
                            <label>Contact Number <span class="text-danger">*</span></label>
                            <small class="text-muted">Format: xxx-xxxx-xxxx</small>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">
                                <select class="form-control" id="branch" name="branch" required>
                                    <?php for($x=1;$x<=12;$x++){?>
                                    <option value="branch{{ $x }}">Branch {{ $x }}</option>
                                    <?php }?>
                                </select>
                            </div>
                            <label>Branch <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="address" placeholder="" required>
                            <label>Address <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="city" placeholder="" required>
                            <label>City <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="province" placeholder="">
                            <label>Province <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 my-3 d-flex flex-row-reverse">
                    <button class="btn btn-success w-auto ms-auto" type="submit">Register Employee</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('input[name="number"]').on("change", function() {
            if (!(/[0-9]{4}-[0-9]{4}/.test(this.value))) {
                $(this).val($(this).val().replace(/(\d{4})\-?(\d{4})/, '$1-$2'))
            }
        })
        $('input[name="contact"], input[name="p_contact"]').on("change", function() {
            if (!(/[0-9]{3}-[0-9]{4}-[0-9]{4}/.test(this.value))) {
                $(this).val($(this).val().replace(/(\d{3})\-?(\d{4})\-?(\d{4})/, '$1-$2-$3'))
            }
        })
    </script>
@endsection
