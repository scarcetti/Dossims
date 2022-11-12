@extends('superadmin.index')

@section('section')
    <div id="content">
        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="bi bi-list"></i>

        </button>
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Register Employee</h2>
            <!--   <div class="d-flex gap-3 ms-auto">
                            <a href="" class="btn btn-outline-danger h-100 ms-auto">Back</a>
                            <a href="" class="btn btn-outline-success h-100">Add Student</a>
                        </div> -->
        </div>

        <div class="w-md-50 p-md-3 m-auto">
            <form method="post">
                <h4>Student's Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" disabled value="">
                            <label>ID:</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="number" pattern="[0-9]{4}-[0-9]{4}"
                                value="" required>
                            <small class="text-muted">Format: xxxx-xxxx</small>
                            <label>Student Number</label>
                        </div>
                    </div>
                </div>
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
                            <select class="form-select form-select-lg mb-3" name="sex">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
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
                            <input type="text" class="form-control" name="zip" placeholder="">
                            <label>Zip <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Parent's Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="p_name" placeholder="" required>
                            <label>Parent Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="p_rel" placeholder="" required>
                            <label>Parent Relationship<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="p_contact" placeholder=""
                                pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" required>
                            <label>Contact Number <span class="text-danger">*</span></label>
                            <small class="text-muted">Format: xxx-xxxx-xxxx</small>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Academic Information</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="level_id" required>
                                <option value="" selected disabled>Choose Grade Level...</option>
                                <?php
                                /* foreach ($levels as $level) { */
                                ?>
                                <option value="">yet</option>
                                <?php /* } */
                                ?>
                            </select>
                            <label>Grade Level <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="status" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <label>Is active? <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="row mx-0 my-3">
                    <button class="btn btn-success w-auto ms-auto" type="submit">Add Student</button>
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

@section('script')
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
@endsection
