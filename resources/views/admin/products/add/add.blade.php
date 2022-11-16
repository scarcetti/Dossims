@extends('admin.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Register New Product</h2>
        </div>

        <div class="w-md-50 p-md-3 m-auto">
            <form method="post">
                <h4>Product Information</h4>
                {{--   <div class="row row-cols-1 row-cols-md-3">
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
                </div> --}}
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="product" placeholder="" required>
                            <label>Product<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <div class="form-group">

                                <select class="form-control" id="category" name="category" required>
                                    <?php for($x=1;$x<=5;$x++){?>
                                    <option>Category {{ $x }}</option>
                                    <?php }?>
                                </select>
                            </div>
                            <label>Category<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="price" placeholder="" required>
                            <label>Price<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="quantity" placeholder="" required>
                            <label>Quantity<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Decription</h4>
                <div class="row row-cols-1 row-cols-md-3">
                    <div class="col">
                        <input type="text" class="form-control" name="label" placeholder="" required>
                        <label>Label</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="length" placeholder="length" required>
                        <label>Dimensions</label>
                    </div>x
                    <div class="col">
                        <input type="text" class="form-control" name="width" placeholder="width" required>
                    </div>
                </div>
                <div class="row mx-0 my-3 d-flex flex-row-reverse">
                    <button class="btn btn-success w-auto ms-auto" type="submit">Register Product</button>
                </div>
            </form>
        </div>
@endsection
