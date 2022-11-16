@extends('admin.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Register New User</h2>
            <!--   <div class="d-flex gap-3 ms-auto">
                                    <a href="" class="btn btn-outline-danger h-100 ms-auto">Back</a>
                                    <a href="" class="btn btn-outline-success h-100">Add Student</a>
                                </div> -->
        </div>

        <div class="w-md-50 p-md-3 m-auto">
            <form method="post">
                <h4>User's Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">
                                <select class="form-control" id="employeeID" name="employeeID" required>
                                    <?php for($x=1;$x<=12;$x++){?>
                                    <option value="employeeID{{ $x }}">Employee {{ $x }}</option>
                                    <?php }?>
                                </select>
                            </div>
                            <label>Employee<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="name" class="form-control" name="name" placeholder="Elon Moist" disabled required>
                            <label>Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="branch" class="form-control" name="branch" placeholder="Branch" disabled required>
                            <label>Branch <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" placeholder="" required>
                            <label>Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">
                                <select class="form-control" id="sex" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="frontdesk">Front Desk</option>
                                </select>
                            </div>
                            <label>Role <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 my-3 d-flex flex-row-reverse">
                    <button class="btn btn-success w-auto ms-auto" type="submit">Register User</button>
                </div>
            </form>
        </div>
@endsection
